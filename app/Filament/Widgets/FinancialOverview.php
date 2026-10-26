<?php

namespace App\Filament\Widgets;

use App\Models\Part;
use App\Models\PartSale;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class FinancialOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $now = now();

        $today = $this->getTotals(
            $now->copy()->startOfDay(),
            $now->copy()->endOfDay()
        );

        $month = $this->getTotals(
            $now->copy()->startOfMonth(),
            $now->copy()->endOfMonth()
        );

        $year = $this->getTotals(
            $now->copy()->startOfYear(),
            $now->copy()->endOfYear()
        );

        $outOfStock = Part::query()
            ->where('is_active', true)
            ->where('stock', 0)
            ->count();

        $lowStock = Part::query()
            ->where('is_active', true)
            ->whereBetween('stock', [1, 3])
            ->count();

        return [
            Stat::make('დღის შემოსავალი', $this->money($today['revenue']))
                ->description('დღეს გაყიდული ნაწილები')
                ->color('primary'),

            Stat::make('დღის მოგება', $this->money($today['profit']))
                ->description('შემოსავალი მინუს თვითღირებულება')
                ->color('success'),

            Stat::make('თვის შემოსავალი', $this->money($month['revenue']))
                ->description('მიმდინარე თვე')
                ->color('primary'),

            Stat::make('თვის მოგება', $this->money($month['profit']))
                ->description('მიმდინარე თვე')
                ->color('success'),

            Stat::make('წლის შემოსავალი', $this->money($year['revenue']))
                ->description('მიმდინარე წელი')
                ->color('primary'),

            Stat::make('წლის მოგება', $this->money($year['profit']))
                ->description('მიმდინარე წელი')
                ->color('success'),

            Stat::make('მარაგი ამოწურულია', $outOfStock)
                ->description('აქტიური ნაწილი, რაოდენობა 0')
                ->color($outOfStock > 0 ? 'danger' : 'success'),

            Stat::make('დაბალი მარაგი', $lowStock)
                ->description('აქტიური ნაწილი, დარჩენილია 1–3 ცალი')
                ->color($lowStock > 0 ? 'warning' : 'success'),
        ];
    }

    private function getTotals(Carbon $start, Carbon $end): array
    {
        $totals = PartSale::query()
            ->whereBetween('sold_at', [$start, $end])
            ->selectRaw('
                COALESCE(SUM(total_amount), 0) as revenue,
                COALESCE(SUM(total_cost), 0) as cost
            ')
            ->first();

        $revenue = (float) $totals->revenue;
        $cost = (float) $totals->cost;

        return [
            'revenue' => $revenue,
            'profit' => $revenue - $cost,
        ];
    }

    private function money(float $amount): string
    {
        return number_format($amount, 2, '.', ',') . ' ₾';
    }
}
