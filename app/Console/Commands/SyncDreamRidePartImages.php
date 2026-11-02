<?php

namespace App\Console\Commands;

use App\Models\PartListing;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DOMDocument;
use DOMXPath;
use Throwable;

class SyncDreamRidePartImages extends Command
{
    protected $signature = 'dreamride:sync-part-images {--limit=25 : Maximum number of DreamRide listings to update}';
    protected $description = 'Download matching product photos from the authorised DreamRide parts catalogue';

    public function handle(): int
    {
        $limit = max(1, (int) $this->option('limit'));
        $base = 'https://dreamride.ge/product-category/motorcycle-parts-2/';
        $pages = [
            $base,
            $base . 'page/2/',
            $base . 'page/3/',
        ];

        $catalogue = [];
        foreach ($pages as $pageUrl) {
            try {
                $response = Http::timeout(25)
                    ->withHeaders(['User-Agent' => 'MotoHub catalogue image sync/1.0'])
                    ->get($pageUrl);

                if (!$response->successful()) {
                    $this->warn("ვერ ჩაიტვირთა კატალოგის გვერდი: {$pageUrl}");
                    continue;
                }

                $html = $response->body();
                $previous = libxml_use_internal_errors(true);
                $dom = new DOMDocument();
                $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
                libxml_clear_errors();
                libxml_use_internal_errors($previous);
                $xpath = new DOMXPath($dom);

                // Find product titles first, then walk up to the nearest card
                // containing an image. This works with WooCommerce themes that
                // do not put the generic "product" class on the card element.
                $titleNodes = $xpath->query(
                    "//*[contains(concat(' ', normalize-space(@class), ' '), ' woocommerce-loop-product__title ')"
                    . " or contains(concat(' ', normalize-space(@class), ' '), ' product-title ')"
                    . " or contains(concat(' ', normalize-space(@class), ' '), ' wd-entities-title ')]"
                );

                foreach ($titleNodes as $titleNode) {
                    $title = trim(preg_replace('/\\s+/u', ' ', $titleNode->textContent));
                    $card = $xpath->query(
                        "ancestor::*[.//img and (contains(@class, 'product') or contains(@class, 'wd-'))][1]",
                        $titleNode
                    )->item(0) ?: $xpath->query('ancestor::*[.//img][1]', $titleNode)->item(0);
                    if (!$card) {
                        continue;
                    }

                    $imageNode = $xpath->query('.//img[1]', $card)->item(0);
                    if (!$imageNode) {
                        continue;
                    }

                    $imageUrl = $imageNode->getAttribute('data-src')
                        ?: $imageNode->getAttribute('data-lazy-src')
                        ?: $imageNode->getAttribute('data-original')
                        ?: $imageNode->getAttribute('src');

                    if (!$imageUrl || str_starts_with($imageUrl, 'data:')) {
                        $srcset = $imageNode->getAttribute('data-srcset')
                            ?: $imageNode->getAttribute('data-lazy-srcset')
                            ?: $imageNode->getAttribute('srcset');
                        if ($srcset) {
                            $candidate = trim(explode(' ', trim(explode(',', $srcset)[0]))[0]);
                            $imageUrl = $candidate;
                        }
                    }

                    if (!$imageUrl) {
                        continue;
                    }

                    $imageUrl = html_entity_decode($imageUrl, ENT_QUOTES | ENT_HTML5);
                    if (str_starts_with($imageUrl, '//')) {
                        $imageUrl = 'https:' . $imageUrl;
                    } elseif (str_starts_with($imageUrl, '/')) {
                        $imageUrl = 'https://dreamride.ge' . $imageUrl;
                    }

                    $host = parse_url($imageUrl, PHP_URL_HOST);
                    if (!in_array($host, ['dreamride.ge', 'www.dreamride.ge'], true)) {
                        continue;
                    }

                    $catalogue[$this->normalise($title)] = $imageUrl;
                }
            } catch (Throwable $exception) {
                $this->warn('კატალოგის გვერდის დამუშავება ვერ მოხერხდა: ' . $exception->getMessage());
            }
        }

        if (!$catalogue) {
            $this->error('DreamRide-ის კატალოგიდან პროდუქტების ფოტოები ვერ წამოვიღე.');
            return self::FAILURE;
        }

        $listings = PartListing::query()
            ->where('slug', 'like', 'dreamride-%')
            ->where('status', 'published')
            ->orderBy('id')
            ->limit($limit)
            ->get();

        $updated = 0;
        $missing = [];

        foreach ($listings as $listing) {
            $imageUrl = $catalogue[$this->normalise($listing->title)] ?? null;

            if (!$imageUrl) {
                $missing[] = $listing->title;
                continue;
            }

            try {
                $imageResponse = Http::timeout(30)
                    ->withHeaders(['User-Agent' => 'MotoHub catalogue image sync/1.0'])
                    ->get($imageUrl);

                if (!$imageResponse->successful()) {
                    $missing[] = $listing->title;
                    continue;
                }

                $mime = strtolower((string) $imageResponse->header('Content-Type'));
                $extension = match (true) {
                    str_contains($mime, 'image/jpeg') => 'jpg',
                    str_contains($mime, 'image/png') => 'png',
                    str_contains($mime, 'image/webp') => 'webp',
                    str_contains($mime, 'image/gif') => 'gif',
                    default => null,
                };

                if (!$extension) {
                    $missing[] = $listing->title;
                    continue;
                }

                $path = 'parts/dreamride/' . Str::slug($listing->slug) . '.' . $extension;
                Storage::disk('public')->put($path, $imageResponse->body());

                $listing->forceFill(['image_path' => $path])->save();
                $updated++;
                $this->info('ფოტო დაემატა: ' . $listing->title);
            } catch (Throwable $exception) {
                $missing[] = $listing->title;
                $this->warn('ფოტოს ჩამოტვირთვა ვერ მოხერხდა: ' . $listing->title);
            }
        }

        $this->newLine();
        $this->info("დამატებულია {$updated} ფოტო.");
        if ($missing) {
            $this->warn('ფოტო ვერ მოიძებნა/ჩამოიტვირთა ' . count($missing) . ' პროდუქტისთვის:');
            foreach ($missing as $title) {
                $this->line('- ' . $title);
            }
        }

        return $updated > 0 ? self::SUCCESS : self::FAILURE;
    }

    private function normalise(string $value): string
    {
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5);
        $value = mb_strtolower($value);
        return preg_replace('/[^\\p{L}\\p{N}]+/u', '', $value) ?? '';
    }
}
