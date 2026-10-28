<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AcademicYear extends Model {
    protected $fillable=['label','starts_at','ends_at','is_current'];
    protected $casts=['starts_at'=>'date','ends_at'=>'date','is_current'=>'boolean'];
    public function semesters(){ return $this->hasMany(Semester::class); }
    public function classes(){ return $this->hasMany(ClassRoom::class); }
}
