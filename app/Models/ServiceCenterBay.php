<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ServiceCenterBay extends Model {
 protected $fillable=['service_center_id','name','description','is_active'];
 protected $casts=['is_active'=>'boolean'];
 public function serviceCenter(): BelongsTo { return $this->belongsTo(ServiceCenter::class); }
}