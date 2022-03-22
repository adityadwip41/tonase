<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kapal extends Model
{
    protected $table = 'kapal';
    protected $primaryKey = 'id';
    protected $guarded = [''];
}
