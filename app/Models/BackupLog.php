<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BackupLog extends Model {
    protected $fillable=['type','path','size','checksum','encrypted','status','run_by','verified_at','restored_at'];
    protected $casts=['encrypted'=>'boolean','verified_at'=>'datetime','restored_at'=>'datetime'];
}
