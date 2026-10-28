<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LeaveRequest extends Model {
    protected $fillable=['student_id','attendance_session_id','date','reason_lo','status','approved_by','decided_at'];
    protected $casts=['date'=>'date','decided_at'=>'datetime'];
    public function student(){ return $this->belongsTo(Student::class); }
    public function approver(){ return $this->belongsTo(User::class,'approved_by'); }
}
