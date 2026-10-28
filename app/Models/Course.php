<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Course extends Model {
    use SoftDeletes;
    protected $fillable=['program_id','code','name_lo','description','credits','pass_mark','is_active'];
    protected $casts=['is_active'=>'boolean','pass_mark'=>'decimal:2'];
    public function program(){ return $this->belongsTo(Program::class); }
    public function assignments(){ return $this->hasMany(TeachingAssignment::class); }
}
