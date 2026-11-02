<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceCenterExpense extends Model
{
    public const CATEGORIES = [
        'technician' => 'ხელოსნის ანაზღაურება',
        'external_parts' => 'საწყობის გარეთ შეძენილი ნაწილები',
        'rent' => 'ქირა',
        'utilities' => 'კომუნალური',
        'tools' => 'ინსტრუმენტები და ინვენტარი',
        'transport' => 'ტრანსპორტი',
        'marketing' => 'მარკეტინგი',
        'tax' => 'გადასახადები და საკომისიო',
        'other' => 'სხვა ხარჯი',
    ];

    protected $fillable = [
        'service_center_id', 'work_order_id', 'created_by',
        'category', 'description', 'amount', 'expense_date', 'reference',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function serviceCenter(): BelongsTo
    {
        return $this->belongsTo(ServiceCenter::class);
    }
}
