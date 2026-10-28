<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Document extends Model {
    use SoftDeletes;
    protected $fillable=['owner_type','owner_id','category','disk','path','original_name','mime_type','size','uploaded_by'];
    public function owner(){ return $this->morphTo(); }
    public function uploader(){ return $this->belongsTo(User::class,'uploaded_by'); }
}
