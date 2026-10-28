<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/** ຫ້ອງຮຽນ (table: classes) */
class ClassRoom extends Model {
    use SoftDeletes;
    protected $table='classes';
    protected $fillable=['program_id','academic_year_id','name_lo','section_lo','room_lo','capacity','is_active'];
    protected $casts=['is_active'=>'boolean'];
    public function program(){ return $this->belongsTo(Program::class); }
    public function academicYear(){ return $this->belongsTo(AcademicYear::class); }
    public function students(){ return $this->hasMany(Student::class); }
    public function assignments(){ return $this->hasMany(TeachingAssignment::class); }
}
