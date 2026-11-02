<?php

namespace App\Services;

use App\Models\ServiceCenterActivityLog;
use App\Models\WorkOrder;
use App\Notifications\ServiceCenterCustomerUpdate;

class ServiceCenterCustomerNotifier
{
    public function send(WorkOrder $workOrder, string $event, string $title, string $message): void
    {
        $workOrder->loadMissing(['serviceBooking.user', 'serviceCenter']);
        $booking = $workOrder->serviceBooking;
        $user = $booking?->user;
        $url = route('service-centers.work-orders.status', $workOrder->public_token);

        if ($user) {
            $user->notify(new ServiceCenterCustomerUpdate(
                $title,
                $message,
                $url,
                [
                    'work_order_id' => $workOrder->id,
                    'order_number' => $workOrder->order_number,
                    'service_center' => $workOrder->serviceCenter?->name,
                ],
            ));
        }

        ServiceCenterActivityLog::create([
            'service_center_id' => $workOrder->service_center_id,
            'work_order_id' => $workOrder->id,
            'channel' => $user ? 'in_app' : 'customer_portal',
            'event' => $event,
            'recipient' => $user?->email ?? $booking?->customer_phone,
            'message' => $message,
            'status' => $user ? 'sent' : 'logged',
            'sent_at' => $user ? now() : null,
        ]);
    }

    public function statusMessage(string $status, string $orderNumber): array
    {
        $messages = [
            'received' => ['მოტოციკლი მიღებულია', "მოტოციკლი მიღებულია სერვისცენტრში. შეკვეთა: {$orderNumber}"],
            'diagnostics' => ['დიაგნოსტიკა დაიწყო', "თქვენი მოტოციკლის დიაგნოსტიკა დაიწყო. შეკვეთა: {$orderNumber}"],
            'waiting_for_parts' => ['ნაწილებს ველოდებით', "შეკეთება დროებით შეჩერებულია საჭირო ნაწილების მოლოდინში. შეკვეთა: {$orderNumber}"],
            'in_repair' => ['შეკეთება მიმდინარეობს', "თქვენი მოტოციკლის შეკეთება მიმდინარეობს. შეკვეთა: {$orderNumber}"],
            'completed' => ['შეკეთება დასრულდა', "თქვენი მოტოციკლის შეკეთება დასრულებულია. შეკვეთა: {$orderNumber}"],
            'handed_over' => ['მოტოციკლი გადაცემულია', "მოტოციკლი თქვენ გადმოგეცათ. შეკვეთა: {$orderNumber}"],
            'cancelled' => ['შეკვეთა გაუქმდა', "სამუშაო შეკვეთა გაუქმდა. შეკვეთა: {$orderNumber}"],
        ];

        return $messages[$status] ?? ['შეკვეთის განახლება', "შეკვეთის სტატუსი განახლდა: {$orderNumber}"];
    }
}
