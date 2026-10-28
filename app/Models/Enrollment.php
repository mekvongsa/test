<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/** ການສັກສອນ/ລົງທະບຽນເຂົ້າຫ້ອງ (reserved for per-course enrollment in future) */
class Enrollment extends Model {
    protected $table='enrollments';
    protected $guarded=[];
}
