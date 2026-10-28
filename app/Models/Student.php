<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Student extends Model {
    use SoftDeletes;
    protected $fillable=['user_id','class_id','program_id','student_code','first_name_lo','last_name_lo','gender','birth_date','phone','email','address_lo','photo_path','status','history_notes'];
    protected $casts=['birth_date'=>'date'];
    public function user(){ return $this->belongsTo(User::class); }
    public function schoolClass(){ return $this->belongsTo(ClassRoom::class,'class_id'); }
    public function program(){ return $this->belongsTo(Program::class); }
    public function grades(){ return $this->hasMany(Grade::class); }
    public function attendanceRecords(){ return $this->hasMany(AttendanceRecord::class); }
    public function leaveRequests(){ return $this->hasMany(LeaveRequest::class); }
    public function getNameLoAttribute(){ return trim($this->first_name_lo.' '.$this->last_name_lo); }
}
