<?php

namespace App\Http\Controllers;

use App\Models\PartRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartRequestController extends Controller
{
    public function create(): View
    {
        return view('part-requests.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'part_name' => ['required', 'string', 'max:255'],
            'motorcycle_brand' => ['nullable', 'string', 'max:255'],
            'motorcycle_model' => ['nullable', 'string', 'max:255'],
            'motorcycle_year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'details' => ['nullable', 'string', 'max:5000'],
        ], [
            'part_name.required' => 'გთხოვ, მიუთითე სასურველი ნაწილის დასახელება.',
            'customer_name.required' => 'გთხოვ, მიუთითე შენი სახელი.',
            'phone.required' => 'გთხოვ, მიუთითე საკონტაქტო ტელეფონი.',
            'email.email' => 'ელფოსტის მისამართი არასწორია.',
            'motorcycle_year.integer' => 'მოტოციკლის გამოშვების წელი უნდა იყოს რიცხვი.',
            'motorcycle_year.min' => 'გამოშვების წელი არ უნდა იყოს 1900-ზე ნაკლები.',
            'motorcycle_year.max' => 'გამოშვების წელი არ უნდა აღემატებოდეს 2100-ს.',
        ]);

        PartRequest::create($validated);

        return redirect()
            ->route('part-requests.create')
            ->with('success', 'მოთხოვნა წარმატებით გაიგზავნა! MotoHub-ის გუნდი დაგიკავშირდება.');
    }
    
public function storeApi(Request $request)
{
    $validated = $request->validate([
        'part_name' => ['required', 'string', 'max:255'],
        'motorcycle_brand' => ['nullable', 'string', 'max:255'],
        'motorcycle_model' => ['nullable', 'string', 'max:255'],
        'motorcycle_year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
        'customer_name' => ['required', 'string', 'max:255'],
        'phone' => ['required', 'string', 'max:50'],
        'email' => ['nullable', 'email', 'max:255'],
        'details' => ['nullable', 'string', 'max:5000'],
    ]);

    $partRequest = \App\Models\PartRequest::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'მოთხოვნა წარმატებით გაიგზავნა!',
        'id' => $partRequest->id,
    ], 201);
}

}
