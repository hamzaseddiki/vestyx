<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;
    protected $table = 'contact_messages';
    protected $fillable = ['form_builder_id','fields','attachment','status'];

    public function form()
    {
        return $this->belongsTo(FormBuilder::class,'form_builder_id','id');
    }
}
