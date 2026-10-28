<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ActivityLog extends Model {
    protected $fillable=['user_id','action','module','record_id','description','meta','ip_address','user_agent','status'];
    protected $casts=['meta'=>'array'];
    public function user(){ return $this->belongsTo(User::class); }
}
