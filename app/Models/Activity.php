<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    protected $table = 'activity';

    protected $fillable = [
        "title", "titlepic", "stime", "etime", "checked", "type",
        "address", "activitytime", "editor", "newstext", "limitation", "limitation_left",
    ];
}
