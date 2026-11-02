<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ServiceCenterTechnician extends Model {
 protected $fillable=['service_center_id','user_id','name','phone','specialty','pay_type','pay_rate','is_active'];
 protected $casts=['is_active'=>'boolean','pay_rate'=>'decimal:2'];
 public function serviceCenter(): BelongsTo { return $this->belongsTo(ServiceCenter::class); }
 public function user(): BelongsTo { return $this->belongsTo(User::class); }
 public function payroll(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(ServiceCenterTechnicianPayroll::class); }
}