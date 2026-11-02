<?php

namespace App\Services;

use App\Models\ServiceCenter;
use Illuminate\Support\Facades\DB;

class ServiceCenterFinancialSnapshotService
{
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
            ->selectRaw('COALESCE(SUM(CASE WHEN invoices.total_amount > COALESCE(payments.paid, 0) THEN invoices.total_amount - COALESCE(payments.paid, 0) ELSE 0 END), 0) as total')
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

        $invoiceDebt = (float) $invoiceBalances;
        $directDebt = max(0, $directPurchases - $directPayments);

        $invoiceDebtBySupplier = DB::table('service_center_supplier_invoices as invoices')
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
            ->select('invoices.supplier_id')
            ->selectRaw('SUM(CASE WHEN invoices.total_amount > COALESCE(payments.paid, 0) THEN invoices.total_amount - COALESCE(payments.paid, 0) ELSE 0 END) as debt')
            ->selectRaw('COUNT(invoices.id) as invoice_count')
            ->groupBy('invoices.supplier_id')
            ->get()
            ->keyBy('supplier_id');

        $directPurchasesBySupplier = DB::table('service_center_inventory_movements')
            ->where('service_center_id', $center->id)
            ->where('type', 'in')
            ->whereNotNull('supplier_id')
            ->whereNull('supplier_invoice_item_id')
            ->select('supplier_id')
            ->selectRaw('COALESCE(SUM(quantity * unit_cost), 0) as total')
            ->groupBy('supplier_id')
            ->pluck('total', 'supplier_id');

        $directPaymentsBySupplier = DB::table('service_center_supplier_payments')
            ->where('service_center_id', $center->id)
            ->whereNull('invoice_id')
            ->select('supplier_id')
            ->selectRaw('COALESCE(SUM(amount), 0) as total')
            ->groupBy('supplier_id')
            ->pluck('total', 'supplier_id');

        $supplierBalances = DB::table('service_center_suppliers')
            ->where('service_center_id', $center->id)
            ->orderBy('name')
            ->get(['id', 'name', 'phone', 'email'])
            ->map(function ($supplier) use ($invoiceDebtBySupplier, $directPurchasesBySupplier, $directPaymentsBySupplier) {
                $invoiceData = $invoiceDebtBySupplier->get($supplier->id);
                $invoiceBalance = (float) ($invoiceData->debt ?? 0);
                $directBalance = max(
                    0,
                    (float) ($directPurchasesBySupplier[$supplier->id] ?? 0)
                    - (float) ($directPaymentsBySupplier[$supplier->id] ?? 0)
                );

                return [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'phone' => $supplier->phone,
                    'email' => $supplier->email,
                    'invoice_count' => (int) ($invoiceData->invoice_count ?? 0),
                    'invoice_debt' => round($invoiceBalance, 2),
                    'direct_debt' => round($directBalance, 2),
                    'total_debt' => round($invoiceBalance + $directBalance, 2),
                ];
            })
            ->filter(fn (array $supplier) => $supplier['invoice_count'] > 0 || $supplier['direct_debt'] > 0)
            ->values();

        // Keep supplier-level balances isolated: an overpayment to one supplier
        // must not offset another supplier's outstanding balance.
        $directDebt = (float) $supplierBalances->sum('direct_debt');
        $invoiceDebt = (float) $supplierBalances->sum('invoice_debt');

        return [
            'inventory_value' => round($inventoryValue, 2),
            'supplier_invoice_debt' => round($invoiceDebt, 2),
            'supplier_direct_debt' => round($directDebt, 2),
            'supplier_debt' => round($invoiceDebt + $directDebt, 2),
            'supplier_balances' => $supplierBalances,
        ];
    }
}
