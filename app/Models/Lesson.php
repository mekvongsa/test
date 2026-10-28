<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Lesson extends Model {
    protected $fillable=['teaching_assignment_id','date','title_lo','plan_lo','content_lo','notes','status','taught','taught_at'];
    protected $casts=['date'=>'date','taught'=>'boolean','taught_at'=>'datetime'];
    public function assignment(){ return $this->belongsTo(TeachingAssignment::class,'teaching_assignment_id'); }
    public function attendanceSession(){ return $this->hasOne(AttendanceSession::class); }
}
