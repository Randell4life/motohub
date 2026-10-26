<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Motorcycle;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WorkshopMotorcycleController extends Controller
{
    private function getWorkshop(Request $request): Partner
    {
        return Partner::query()
            ->where('user_id', $request->user()->id)
            ->where('type', 'workshop')
            ->where('application_status', 'approved')
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function attach(
        Request $request,
        int $customer
    ): RedirectResponse {
        $partner = $this->getWorkshop($request);

        $validated = $request->validate([
            'motorcycle_id' => ['required', 'integer', 'min:1'],
        ]);

        $customer = $partner->customers()->findOrFail($customer);

        // მოტოციკლი უნდა ეკუთვნოდეს ამ სახელოსნოს.
$motorcycle = Motorcycle::query()
    ->where('partner_id', $partner->id)
    ->whereNull('customer_id')
    ->whereKey($validated['motorcycle_id'])
    ->firstOrFail();

        $motorcycle->customer_id = $customer->id;
        $motorcycle->save();

        return redirect()
            ->route('workshop.customers.index')
            ->with('success', 'მოტოციკლი კლიენტს წარმატებით მიება.');
    }

    public function detach(
        Request $request,
        int $customer,
        int $motorcycle
    ): RedirectResponse {
        $partner = $this->getWorkshop($request);

        $customer = $partner->customers()->findOrFail($customer);

        $motorcycle = Motorcycle::query()
            ->where('partner_id', $partner->id)
            ->where('customer_id', $customer->id)
            ->whereKey($motorcycle)
            ->firstOrFail();

        $motorcycle->customer_id = null;
        $motorcycle->save();

        return redirect()
            ->route('workshop.customers.index')
            ->with('success', 'მოტოციკლი კლიენტს მოეხსნა.');
    }
}
