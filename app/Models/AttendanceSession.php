<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AttendanceSession extends Model {
    protected $fillable=['lesson_id','teaching_assignment_id','opens_at','closes_at','qr_token_hash','qr_expires_at','created_by'];
    protected $casts=['opens_at'=>'datetime','closes_at'=>'datetime','qr_expires_at'=>'datetime'];
    public function lesson(){ return $this->belongsTo(Lesson::class); }
    public function assignment(){ return $this->belongsTo(TeachingAssignment::class,'teaching_assignment_id'); }
    public function records(){ return $this->hasMany(AttendanceRecord::class); }
    public function isOpen(): bool {
        $now = now();
        if ($this->opens_at && $now->lt($this->opens_at)) return false;
        if ($this->closes_at && $now->gt($this->closes_at)) return false;
        return true;
    }
}
