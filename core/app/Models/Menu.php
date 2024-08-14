<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tzsk\Payu\Models\HasTransactions;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'menus';
    protected $fillable = ['title','content','status'];

}
