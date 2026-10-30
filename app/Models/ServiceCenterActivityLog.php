<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ServiceCenterActivityLog extends Model {
 protected $fillable=['service_center_id','work_order_id','channel','event','recipient','message','status','sent_at'];
 protected $casts=['sent_at'=>'datetime'];
}