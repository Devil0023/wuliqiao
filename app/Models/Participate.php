<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participate extends Model
{
    use SoftDeletes;

    protected $table = 'participate';

    public function wxuser(){
        return $this->belongsTo(Wxuser::class, "uid", "id");
    }
}
