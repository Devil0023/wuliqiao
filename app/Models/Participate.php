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

    public function chkInfo($aid, $uid, $field = "participate"){

        $field = in_array($field, array("participate", "sign"))? $field: "participate";

        $info = self::where(array(
            "aid" => $aid,
            "uid" => $uid,
        ))->first();

        return intval($info->$field);
    }
}
