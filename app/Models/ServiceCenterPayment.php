<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ServiceCenterPayment extends Model {
 protected $fillable=['service_center_id','work_order_id','amount','method','reference','note','received_by','paid_at'];
 protected $casts=['amount'=>'decimal:2','paid_at'=>'datetime'];
 public function workOrder(): BelongsTo { return $this->belongsTo(WorkOrder::class); }
 public function serviceCenter(): BelongsTo { return $this->belongsTo(ServiceCenter::class); }
}