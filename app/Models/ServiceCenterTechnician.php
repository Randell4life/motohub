<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ServiceCenterTechnician extends Model {
 protected $fillable=['service_center_id','user_id','name','phone','specialty','is_active'];
 protected $casts=['is_active'=>'boolean'];
 public function serviceCenter(): BelongsTo { return $this->belongsTo(ServiceCenter::class); }
 public function user(): BelongsTo { return $this->belongsTo(User::class); }
}