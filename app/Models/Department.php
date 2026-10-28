<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Department extends Model {
    use SoftDeletes;
    protected $fillable=['code','name_lo','phone','email','is_active'];
    protected $casts=['is_active'=>'boolean'];
    public function programs(){ return $this->hasMany(Program::class); }
    public function teachers(){ return $this->hasMany(Teacher::class); }
}
