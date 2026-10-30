<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MotoAssistantController extends Controller
{
    public function index(): View
    {
        return view('moto-assistant.index');
    }

    public function diagnose(Request $request): View
    {
        $data = $request->validate([
            'motorcycle' => ['required', 'string', 'max:160'],
            'symptom' => ['required', 'string', 'max:3000'],
            'when' => ['nullable', 'in:cold,warm,always,under_load'],
            'warning_signs' => ['nullable', 'array', 'max:5'],
            'warning_signs.*' => ['string', 'in:oil_pressure,overheating,brake_issue,fuel_leak,smoke,none'],
        ]);

        $text = mb_strtolower($data['symptom']);
        $results = [];
        $urgent = false;

        if (str_contains($text, 'brake') || str_contains($text, 'მუხრუჭ') || in_array('brake_issue', $data['warning_signs'] ?? [], true)) {
            $results[] = ['title' => 'მუხრუჭის სისტემა', 'detail' => 'შესაძლოა პრობლემა იყოს ხუნდებში, სითხის დონეში, ჰაერში ან გაჟონვაში. მუხრუჭის ეფექტიანობის ეჭვისას მოტოციკლი არ მართო და სერვისამდე გადაიყვანე ტრანსპორტირებით.'];
            $urgent = true;
        }
        if (str_contains($text, 'oil') || str_contains($text, 'ზეთი') || in_array('oil_pressure', $data['warning_signs'] ?? [], true)) {
            $results[] = ['title' => 'ზეთის დონე ან წნევა', 'detail' => 'შეამოწმე ზეთის დონე მხოლოდ მწარმოებლის პროცედურით. ზეთის წნევის გამაფრთხილებელი ნათურის ან უჩვეულო მეტალის ხმისას ძრავა გამორთე და აღარ ამუშაო შემოწმებამდე.'];
            $urgent = true;
        }
        if (str_contains($text, 'heat') || str_contains($text, 'overheat') || str_contains($text, 'ტემპერატ') || str_contains($text, 'ცხელ') || in_array('overheating', $data['warning_signs'] ?? [], true)) {
            $results[] = ['title' => 'გაგრილების სისტემა', 'detail' => 'შესაძლო მიზეზებია დაბალი გამაგრილებელი სითხე, ვენტილატორი, თერმოსტატი ან გაჟონვა. ცხელ რადიატორის თავსახურს არ შეეხო; გადახურებისას გააჩერე ძრავა და აცადე გაგრილება.'];
            $urgent = true;
        }
        if (str_contains($text, 'fuel') || str_contains($text, 'gas') || str_contains($text, 'ბენზინ') || str_contains($text, 'საწვავ') || in_array('fuel_leak', $data['warning_signs'] ?? [], true)) {
            $results[] = ['title' => 'საწვავის სისტემა', 'detail' => 'სუნი ან სველი კვალი შეიძლება უკავშირდებოდეს შლანგს, შეერთებას ან ინჟექტორს. საწვავის გაჟონვისას არ დაქოქო, მოარიდე ცეცხლს და მიმართე სერვისს.'];
            $urgent = true;
        }
        if (str_contains($text, 'start') || str_contains($text, 'ქოქ') || str_contains($text, 'ბატარე') || str_contains($text, 'battery')) {
            $results[] = ['title' => 'დაქოქვის პრობლემა', 'detail' => 'შესამოწმებელია აკუმულატორის ძაბვა და კონტაქტები, დამუხტვის სისტემა, ფიუზები და სტარტერის წრე. მხოლოდ ხმაზე დაყრდნობით კონკრეტული დეტალის დაზიანება ვერ დადასტურდება.'];
        }
        if (str_contains($text, 'chain') || str_contains($text, 'ჯაჭვ') || str_contains($text, 'კლაჩ') || str_contains($text, 'clutch') || str_contains($text, 'ვიბრ') || str_contains($text, 'ხმაურ') || str_contains($text, 'noise')) {
            $results[] = ['title' => 'გადაცემათა ნაწილი / ხმაური', 'detail' => 'შესაძლოა საჭირო იყოს ჯაჭვის დაჭიმულობისა და შეზეთვის, კლაჩის თავისუფალი სვლის, სამაგრებისა და ხმაურის წყაროს შემოწმება. ძლიერი ან უეცარი ხმაურისას შეწყვიტე მოძრაობა.'];
        }
        if (!$results) {
            $results[] = ['title' => 'საჭიროა დამატებითი დიაგნოსტიკა', 'detail' => 'აღწერე როდის ჩნდება სიმპტომი, ცივ თუ ცხელ ძრავაზე, რა სიჩქარეზე/ბრუნზე და ანთია თუ არა გამაფრთხილებელი ნათურა. საბოლოო დიაგნოზი ადგილზე შემოწმებას საჭიროებს.'];
        }

        if (in_array('smoke', $data['warning_signs'] ?? [], true)) {
            $urgent = true;
            $results[] = ['title' => 'კვამლი', 'detail' => 'გააჩერე უსაფრთხო ადგილას და გამორთე ძრავა. კვამლის წყარო დაუდგენლად მოძრაობა არ გააგრძელო.'];
        }

        return view('moto-assistant.result', [
            'data' => $data,
            'results' => $results,
            'urgent' => $urgent,
        ]);
    }
}
