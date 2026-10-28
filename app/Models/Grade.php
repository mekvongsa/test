<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Grade extends Model {
    protected $fillable=['assessment_id','student_id','score','comment','confirmed','updated_by'];
    protected $casts=['score'=>'decimal:2','confirmed'=>'boolean'];
    public function assessment(){ return $this->belongsTo(Assessment::class); }
    public function student(){ return $this->belongsTo(Student::class); }
    public function histories(){ return $this->hasMany(GradeHistory::class); }
}
