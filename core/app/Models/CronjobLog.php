<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronjobLog extends Model
{
    use HasFactory;

    protected $table = 'cronjob_logs';
    protected $fillable = ['cronjob_id','type','title','running_qty','others','status'];
}
