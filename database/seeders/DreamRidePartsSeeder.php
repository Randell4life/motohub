<?php

namespace Database\Seeders;

use App\Models\PartListing;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DreamRidePartsSeeder extends Seeder
{
    /**
     * Imports the first 25 catalogue items supplied by DreamRide.
     * Re-runnable: stable slugs are updated rather than duplicated.
     */
    public function run(): void
    {
        $seller = User::firstOrCreate(
            ['email' => 'Dreamride.ge@gmail.com'],
            [
                'name' => 'DreamRide',
                'phone' => '+995 568 15 55 25',
                'password' => Hash::make(Str::random(64)),
            ]
        );

        if ($seller->name !== 'DreamRide' || $seller->phone !== '+995 568 15 55 25') {
            $seller->forceFill([
                'name' => 'DreamRide',
                'phone' => '+995 568 15 55 25',
            ])->save();
        }

        $sourceCategory = 'https://dreamride.ge/product-category/motorcycle-parts-2/';

        $products = [
            ['bando-belt-860-19-7-28-sym-jetx', 'BANDO ღვედი 860-19.7-28 SYM JETX', 95, 'ღვედი', 'SYM JETX'],
            ['brakestar-tvs-ntorq-bs4-throttle-cable', 'BRAKESTAR TVS NTORQ BS4 გაზის გვარლი', 40, 'ტროსები', 'TVS NTORQ BS4'],
            ['brakestar-tvs-ntorq-bs6-throttle-cable', 'BRAKESTAR TVS NTORQ BS6 გაზის გვარლი', 40, 'ტროსები', 'TVS NTORQ BS6'],
            ['brakestar-yamaha-ray-zr-speedometer-cable', 'BRAKESTAR სპიდომეტრის გვარლი YAMAHA RAY ZR 125', 35, 'ტროსები', 'Yamaha RAY ZR 125'],
            ['brakestar-piaggio-front-brake-pads', 'BRAKESTAR წინა ხუნდები PIAGGIO', 45, 'სამუხრუჭე სისტემა', 'Piaggio'],
            ['brakestar-sym-jet-x-brake-pads', 'BRAKESTAR ხუნდები SYM JET X', 45, 'სამუხრუჭე სისტემა', 'SYM JET X'],
            ['brakestar-yamaha-ray-zr-air-filter', 'BRAKESTAR ჰაერის ფილტრი YAMAHA RAY ZR', 35, 'ჰაერის ფილტრები', 'Yamaha RAY ZR'],
            ['denel-gel-moped-battery-4ah-12v', 'DENEL მოპედის აკუმულატორი GEL 4AH-12VOLT', 75, 'აკუმულატორები', 'მოპედი'],
            ['denel-gel-moped-battery-7ah-12v', 'DENEL მოპედის აკუმულატორი GEL 7AH-12VOLT', 100, 'აკუმულატორები', 'მოპედი'],
            ['eivissa-125-150cc-rear-disc', 'EIVISSA 125/150cc უკანა დისკი', 180, 'დისკები', 'EIVISSA 125/150cc'],
            ['honda-dio-smart-125-side-cover-grey-left', 'HONDA DIO SMART 125 კაპოტის გვერდითა პლასტმასი ნაცრისფერი მარცხენა', 105, 'პლასტმასები', 'Honda Dio Smart 125'],
            ['honda-dio-smart-125-side-cover-grey-right', 'HONDA DIO SMART 125 კაპოტის გვერდითა პლასტმასი ნაცრისფერი მარჯვენა', 105, 'პლასტმასები', 'Honda Dio Smart 125'],

            ['suzuki-avenis-125-black-front-fender-side-left', 'SUZUKI AVENIS 125CC BLACK წინა კრილოს გვერდითა პლასტმასი შავი მარცხენა', 110, 'პლასტმასები', 'Suzuki Avenis 125 Black'],
            ['suzuki-avenis-125-black-front-fender-side-right', 'SUZUKI AVENIS 125CC BLACK წინა კრილოს გვერდითა პლასტმასი შავი მარჯვენა', 110, 'პლასტმასები', 'Suzuki Avenis 125 Black'],
            ['suzuki-avenis-125-black-front-handlebar-cover', 'SUZUKI AVENIS 125cc BLACK წინა საჭის პლასტმასი', 115, 'პლასტმასები', 'Suzuki Avenis 125 Black'],
            ['suzuki-avenis-125-green-floor-side-cover-left', 'SUZUKI AVENIS 125cc GREEN გვერდითა ძირის გრძელი პლასტმასი ნაცრისფერი მარცხენა', 90, 'პლასტმასები', 'Suzuki Avenis 125 Green'],
            ['suzuki-avenis-125-green-floor-side-cover-right', 'SUZUKI AVENIS 125cc GREEN გვერდითა ძირის გრძელი პლასტმასი ნაცრისფერი მარჯვენა', 90, 'პლასტმასები', 'Suzuki Avenis 125 Green'],
            ['suzuki-avenis-125-green-rear-fender', 'SUZUKI AVENIS 125CC GREEN საბურავის ფრთა ნაცრისფერი', 135, 'პლასტმასები', 'Suzuki Avenis 125 Green'],
            ['suzuki-avenis-125-green-front-fender-side-left', 'SUZUKI AVENIS 125CC GREEN წინა კრილოს გვერდითა პლასტმასი ნაცრისფერი მარცხენა', 110, 'პლასტმასები', 'Suzuki Avenis 125 Green'],
            ['suzuki-avenis-125-green-front-fender-side-right', 'SUZUKI AVENIS 125CC GREEN წინა კრილოს გვერდითა პლასტმასი ნაცრისფერი მარჯვენა', 110, 'პლასტმასები', 'Suzuki Avenis 125 Green'],
            ['suzuki-avenis-125-rollers', 'SUZUKI AVENIS 125CC ბურთულები', 35, 'ვარიატორის ნაწილები', 'Suzuki Avenis 125'],
            ['sym-crox-125-air-filter', 'SYM CROX 125cc ჰაერის ფილტრი', 35, 'ჰაერის ფილტრები', 'SYM CROX 125'],
            ['teamworld-sym-crox-orbit-xpro-key-set', "TEAMWORLD გასაღების კომპლექტი SYM CROX 50, ORBIT, X’pro", 180, 'გასაღებები', 'SYM CROX 50 / ORBIT / X’pro'],
            ['teamworld-sym-variator-roller-10-1g', 'TEAMWORLD ვარიატორის ბურთულა 10.1გრ SYM', 40, 'ვარიატორის ნაწილები', 'SYM'],
            ['tvs-ntorq-125-oem-mirrors', 'TVS NTORQ 125cc ორიგინალი სარკეები', 70, 'სარკეები', 'TVS NTORQ 125'],
        ];

        foreach ($products as [$slug, $title, $price, $category, $fitment]) {
            PartListing::updateOrCreate(
                ['slug' => 'dreamride-' . $slug],
                [
                    'user_id' => $seller->id,
                    'title' => $title,
                    'brand' => Str::before($title, ' '),
                    'category' => $category,
                    'condition' => 'new',
                    'part_type' => null,
                    'compatible_motorcycle' => $fitment,
                    'motorcycle_brand' => null,
                    'motorcycle_model' => null,
                    'description' => 'პროდუქტი DreamRide-ის კატალოგიდან. თავსებადობა გადაამოწმეთ შეკვეთამდე. მიმდინარე ფასი და ხელმისაწვდომობა დააზუსტეთ უშუალოდ DreamRide-თან. კატალოგი: ' . $sourceCategory,
                    'price' => $price,
                    'image_path' => null,
                    'status' => 'published',
                    'is_demo' => false,
                ]
            );
        }
    }
}
