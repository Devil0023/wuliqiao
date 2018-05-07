<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ppointslog extends Model
{
    use SoftDeletes;

    protected $table = 'ppointslog';

    protected $fillable = [
        "uid", "openid", "delta", "desc"
    ];
}
