<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AttendanceRecord extends Model {
    protected $fillable=['attendance_session_id','student_id','status','method','device_info','checked_by','checked_at','note'];
    protected $casts=['checked_at'=>'datetime'];
    public function session(){ return $this->belongsTo(AttendanceSession::class,'attendance_session_id'); }
    public function student(){ return $this->belongsTo(Student::class); }
}
