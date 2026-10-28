<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TeachingAssignment extends Model {
    protected $fillable=['teacher_id','course_id','class_id','semester_id','is_primary'];
    protected $casts=['is_primary'=>'boolean'];
    public function teacher(){ return $this->belongsTo(Teacher::class); }
    public function course(){ return $this->belongsTo(Course::class); }
    public function schoolClass(){ return $this->belongsTo(ClassRoom::class,'class_id'); }
    public function semester(){ return $this->belongsTo(Semester::class); }
    public function schedules(){ return $this->hasMany(Schedule::class); }
    public function lessons(){ return $this->hasMany(Lesson::class); }
    public function assessments(){ return $this->hasMany(Assessment::class); }
}
