<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pointslog extends Model
{
    use SoftDeletes;

    protected $table = 'pointslog';

    protected $fillable = [
        "uid", "openid", "delta", "desc"
    ];
}
