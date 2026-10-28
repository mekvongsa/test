<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Teacher extends Model {
    use SoftDeletes;
    protected $fillable=['user_id','department_id','staff_code','first_name_lo','last_name_lo','position_lo','gender','birth_date','phone','email','photo_path','employment_status','hired_at','teaching_experience'];
    protected $casts=['birth_date'=>'date','hired_at'=>'date'];
    public function user(){ return $this->belongsTo(User::class); }
    public function department(){ return $this->belongsTo(Department::class); }
    public function assignments(){ return $this->hasMany(TeachingAssignment::class); }
    public function getNameLoAttribute(){ return trim($this->first_name_lo.' '.$this->last_name_lo); }
}
