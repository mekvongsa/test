<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Semester extends Model {
    protected $fillable=['academic_year_id','number','name_lo','starts_at','ends_at','is_current'];
    protected $casts=['starts_at'=>'date','ends_at'=>'date','is_current'=>'boolean'];
    public function academicYear(){ return $this->belongsTo(AcademicYear::class); }
    public function assignments(){ return $this->hasMany(TeachingAssignment::class); }
}
