<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class GradeHistory extends Model {
    protected $fillable=['grade_id','old_score','new_score','edited_by','reason'];
    protected $casts=['old_score'=>'decimal:2','new_score'=>'decimal:2'];
    public function grade(){ return $this->belongsTo(Grade::class); }
    public function editor(){ return $this->belongsTo(User::class,'edited_by'); }
}
