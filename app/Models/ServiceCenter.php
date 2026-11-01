<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCenter extends Model
{
    protected $fillable = [
        'owner_id', 'name', 'slug', 'description', 'phone', 'email',
        'city', 'address', 'is_verified', 'is_active', 'approval_status',
        'subscription_plan', 'subscription_status', 'subscription_price', 'subscription_started_at', 'subscription_ends_at',
        'subscription_requested_plan', 'subscription_request_note', 'subscription_requested_at',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'subscription_price' => 'decimal:2',
        'subscription_started_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'subscription_requested_at' => 'datetime',
    ];

    public static function subscriptionPlans(): array
    {
        return [
            'start' => [
                'name' => 'START',
                'price' => 0,
                'description' => 'დასაწყებად საჭირო ძირითადი შესაძლებლობები.',
                'features' => ['ბიზნესპროფილი და მომსახურებების კატალოგი', 'სამუშაო საათები', 'ონლაინ ჯავშნების მიღება'],
            ],
            'pro' => [
                'name' => 'PRO',
                'price' => 49,
                'description' => 'ყოველდღიური სამუშაო პროცესის სამართავად.',
                'features' => ['START-ის ყველა ფუნქცია', 'სამუშაო შეკვეთები', '5-მდე ხელოსანი და 3 ბოქსი', 'კლიენტებისა და გადახდების აღრიცხვა'],
            ],
            'business' => [
                'name' => 'BUSINESS',
                'price' => 99,
                'description' => 'სრული სახელოსნოს ოპერაციებისა და საწყობისთვის.',
                'features' => ['PRO-ის ყველა ფუნქცია', 'საწყობისა და ნაწილების ჩამოწერა', 'მარაგის კონტროლი', 'ფინანსური მიმოხილვა', '15-მდე ხელოსანი და 10 ბოქსი'],
            ],
            'premium' => [
                'name' => 'PREMIUM',
                'price' => 149,
                'description' => 'მაღალი დატვირთვის მქონე სერვისცენტრებისთვის.',
                'features' => ['BUSINESS-ის ყველა ფუნქცია', 'გუნდისა და ბოქსების შეუზღუდავი რაოდენობა', 'გაფართოებული ანგარიშები', 'პრიორიტეტული მხარდაჭერა'],
            ],
        ];
    }


    public function subscriptionIsActive(): bool
    {
        return ($this->subscription_status ?? 'active') === 'active' && (! $this->subscription_ends_at || $this->subscription_ends_at->isFuture());
    }
    public function effectiveSubscriptionStatus(): string
    {
        return ($this->subscription_status ?? 'active') === 'active' && $this->subscription_ends_at && $this->subscription_ends_at->isPast() ? 'expired' : ($this->subscription_status ?? 'active');
    }
    public function canUseFeature(string $feature): bool
    {
        if (! $this->subscriptionIsActive()) return false;
        $rank = ['start'=>0,'pro'=>1,'business'=>2,'premium'=>3];
        $required = ['services'=>'start','bookings'=>'start','work_orders'=>'pro','team'=>'pro','finance'=>'pro','inventory'=>'business','inventory_usage'=>'business','financial_reports'=>'business','advanced_reports'=>'premium','priority_support'=>'premium'][$feature] ?? 'premium';
        return ($rank[$this->subscription_plan ?? 'start'] ?? 0) >= $rank[$required];
    }

    public function technicianLimit(): ?int
    {
        return match ($this->subscription_plan ?? 'start') {
            'pro' => 5, 'business' => 15, default => null,
        };
    }

    public function bayLimit(): ?int
    {
        return match ($this->subscription_plan ?? 'start') {
            'pro' => 3, 'business' => 10, default => null,
        };
    }

    public function featureAccess(): array
    {
        return collect(['services','bookings','work_orders','team','finance','inventory','inventory_usage','financial_reports','advanced_reports','priority_support'])->mapWithKeys(fn (string $feature) => [$feature=>$this->canUseFeature($feature)])->all();
    }
    public function featureLockedMessage(string $feature): string
    {
        $required = match ($feature) {'services','bookings'=>'START','work_orders','team','finance'=>'PRO','inventory','inventory_usage','financial_reports'=>'BUSINESS',default=>'PREMIUM'};
        return ! $this->subscriptionIsActive() ? 'გამოწერა აქტიური არ არის. ფასიანი ფუნქციების გასააქტიურებლად განაახლე გამოწერა.' : $required.' პაკეტი საჭიროა ამ ფუნქციის გამოსაყენებლად.';
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(ServiceCenterService::class);
    }

    public function hours(): HasMany
    {
        return $this->hasMany(ServiceCenterHour::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(ServiceBooking::class);
    }

    public function workOrders(): HasMany
{
    return $this->hasMany(WorkOrder::class);
}

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('approval_status', 'approved')->where('subscription_status', 'active')->where(fn ($sub) => $sub->whereNull('subscription_ends_at')->orWhere('subscription_ends_at', '>', now()));
    }
    public function technicians(): HasMany
    {
        return $this->hasMany(ServiceCenterTechnician::class);
    }

    public function bays(): HasMany
    {
        return $this->hasMany(ServiceCenterBay::class);
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(ServiceCenterInventoryItem::class, 'service_center_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(ServiceCenterPayment::class);
    }

}
