<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Schedule extends Model {
    protected $fillable=['teaching_assignment_id','day_of_week','start_time','end_time','room_lo'];
    public function assignment(){ return $this->belongsTo(TeachingAssignment::class,'teaching_assignment_id'); }
}
