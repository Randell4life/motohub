<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ServiceCenterWorkOrderPart extends Model {
 protected $fillable=['work_order_id','inventory_item_id','quantity','unit_price','total'];
 protected $casts=['quantity'=>'integer','unit_price'=>'decimal:2','total'=>'decimal:2'];
 public function inventoryItem(): BelongsTo { return $this->belongsTo(ServiceCenterInventoryItem::class,'inventory_item_id'); }
}