<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participate extends Model
{
    use SoftDeletes;

    protected $table = 'participate';
    protected $fillable = [
        "uid", "openid", "aid", "participate", "sign", "participatetime", "signtime",
    ];

    public function wxuser(){
        return $this->belongsTo(Wxuser::class, "uid", "id");
    }

    public static function chkInfo($aid, $uid, $field = "participate"){

        $field = in_array($field, array("participate", "sign"))? $field: "participate";

        $info = self::where(array(
            "aid" => $aid,
            "uid" => $uid,
        ))->first();

        return is_null($info)? 0: intval($info->$field);
    }
}
