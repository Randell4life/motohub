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
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'subscription_price' => 'decimal:2',
        'subscription_started_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];

    public static function subscriptionPlans(): array
    {
        return [
            'start' => [
                'name' => 'START',
                'price' => 0,
                'description' => 'დასაწყებად საჭირო ძირითადი შესაძლებლობები.',
                'features' => ['სახელოსნოს პროფილი და კატალოგი', 'მომსახურებების დამატება', 'ონლაინ ჯავშნების მიღება'],
            ],
            'pro' => [
                'name' => 'PRO',
                'price' => 49,
                'description' => 'ყოველდღიური სამუშაო პროცესის სამართავად.',
                'features' => ['START-ის ყველა ფუნქცია', 'სამუშაო შეკვეთები', 'ხელოსნები და სამუშაო ბოქსები', 'კლიენტებისა და გადახდების აღრიცხვა'],
            ],
            'business' => [
                'name' => 'BUSINESS',
                'price' => 99,
                'description' => 'სრული სახელოსნოს ოპერაციებისა და საწყობისთვის.',
                'features' => ['PRO-ის ყველა ფუნქცია', 'საწყობისა და ნაწილების ჩამოწერა', 'მარაგის კონტროლი', 'ფინანსური მიმოხილვა'],
            ],
            'premium' => [
                'name' => 'PREMIUM',
                'price' => 149,
                'description' => 'მზარდი და მრავალფილიალიანი ბიზნესისთვის.',
                'features' => ['BUSINESS-ის ყველა ფუნქცია', 'რამდენიმე ფილიალის მართვა', 'გაფართოებული ანგარიშები', 'პრიორიტეტული მხარდაჭერა'],
            ],
        ];
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
        return $query->where('is_active', true)
            ->where('approval_status', 'approved');
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
