<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vpointslog extends Model
{
    use SoftDeletes;

    protected $table = 'vpointslog';
}
