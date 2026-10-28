<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Program extends Model {
    protected $fillable=['department_id','code','name_lo','level_lo','duration_years','is_active'];
    protected $casts=['is_active'=>'boolean'];
    public function department(){ return $this->belongsTo(Department::class); }
    public function courses(){ return $this->hasMany(Course::class); }
    public function classes(){ return $this->hasMany(ClassRoom::class); }
    public function students(){ return $this->hasMany(Student::class); }
}
