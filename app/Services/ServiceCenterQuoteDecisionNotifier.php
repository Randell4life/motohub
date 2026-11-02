<?php

namespace App\Services;

use App\Models\ServiceCenterActivityLog;
use App\Models\WorkOrder;
use App\Notifications\ServiceCenterCustomerUpdate;

class ServiceCenterQuoteDecisionNotifier
{
    public function send(WorkOrder $workOrder, string $decision): void
    {
        $workOrder->loadMissing(['serviceCenter.owner']);
        $owner = $workOrder->serviceCenter?->owner;
        $approved = $decision === 'approved';
        $title = $approved ? 'კლიენტმა ხარჯთაღრიცხვა დაადასტურა' : 'კლიენტმა ხარჯთაღრიცხვა უარყო';
        $message = $approved
            ? 'კლიენტმა დაადასტურა შეთავაზება. შეგიძლიათ გააგრძელოთ სამუშაო შეკვეთა.'
            : 'კლიენტმა უარყო შეთავაზება. გადაამოწმეთ ხარჯები და საჭიროების შემთხვევაში გაგზავნეთ ახალი ვერსია.';
        $url = route('my-service-center.work-orders.show', $workOrder);

        if ($owner) {
            $owner->notify(new ServiceCenterCustomerUpdate(
                $title,
                $message,
                $url,
                [
                    'work_order_id' => $workOrder->id,
                    'order_number' => $workOrder->order_number,
                    'decision' => $decision,
                ],
            ));
        }

        ServiceCenterActivityLog::create([
            'service_center_id' => $workOrder->service_center_id,
            'work_order_id' => $workOrder->id,
            'channel' => $owner ? 'in_app' : 'service_center_portal',
            'event' => 'customer_quote_' . $decision,
            'recipient' => $owner?->email,
            'message' => $message,
            'status' => $owner ? 'sent' : 'logged',
            'sent_at' => $owner ? now() : null,
        ]);
    }
}
