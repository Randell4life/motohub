<?php

namespace App\Services;

use App\Models\ServiceCenter;
use Illuminate\Support\Facades\DB;

class ServiceCenterFinancialSnapshotService
{
    /**
     * Current balance-sheet style figures, independent of the selected report period.
     */
    public function forCenter(ServiceCenter $center): array
    {
        $inventoryValue = (float) DB::table('service_center_inventory')
            ->where('service_center_id', $center->id)
            ->selectRaw('COALESCE(SUM(quantity * cost_price), 0) as total')
            ->value('total');

        $invoiceBalances = DB::table('service_center_supplier_invoices as invoices')
            ->leftJoinSub(
                DB::table('service_center_supplier_payments')
                    ->select('invoice_id')
                    ->selectRaw('SUM(amount) as paid')
                    ->whereNotNull('invoice_id')
                    ->groupBy('invoice_id'),
                'payments',
                'payments.invoice_id',
                '=',
                'invoices.id'
            )
            ->where('invoices.service_center_id', $center->id)
            ->selectRaw('COALESCE(SUM(GREATEST(invoices.total_amount - COALESCE(payments.paid, 0), 0)), 0) as total')
            ->value('total');

        $directPurchases = (float) DB::table('service_center_inventory_movements')
            ->where('service_center_id', $center->id)
            ->where('type', 'in')
            ->whereNotNull('supplier_id')
            ->whereNull('supplier_invoice_item_id')
            ->selectRaw('COALESCE(SUM(quantity * unit_cost), 0) as total')
            ->value('total');

        $directPayments = (float) DB::table('service_center_supplier_payments')
            ->where('service_center_id', $center->id)
            ->whereNull('invoice_id')
            ->sum('amount');

        return [
            'inventory_value' => round($inventoryValue, 2),
            'supplier_invoice_debt' => round((float) $invoiceBalances, 2),
            'supplier_direct_debt' => round(max(0, $directPurchases - $directPayments), 2),
            'supplier_debt' => round((float) $invoiceBalances + max(0, $directPurchases - $directPayments), 2),
        ];
    }
}
