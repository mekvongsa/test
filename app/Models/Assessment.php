<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Assessment extends Model {
    protected $fillable=['teaching_assignment_id','title_lo','type','weight','max_score','due_date'];
    protected $casts=['due_date'=>'date','weight'=>'decimal:2','max_score'=>'decimal:2'];
    public function assignment(){ return $this->belongsTo(TeachingAssignment::class,'teaching_assignment_id'); }
    public function grades(){ return $this->hasMany(Grade::class); }
}
