<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ServiceCenterInventoryItem extends Model {
 protected $table='service_center_inventory';
 protected $fillable=['service_center_id','sku','name','brand','category','fitment','quantity','min_quantity','cost_price','sale_price','location','is_active'];
 protected $casts=['quantity'=>'integer','min_quantity'=>'integer','cost_price'=>'decimal:2','sale_price'=>'decimal:2','is_active'=>'boolean'];
 public function serviceCenter(): BelongsTo { return $this->belongsTo(ServiceCenter::class); }
}