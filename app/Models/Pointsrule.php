<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use DB;

class Pointsrule extends Model
{
    use SoftDeletes;

    protected $table = 'pointsrule';


    static public function addPointsByRule($ruleid, $uid){

        $rule = self::find($ruleid);
        $user = Wxuser::find($uid);

        if(is_null($rule)){
            return false;
        }

        switch($rule->type){
            case 0: $model = Pointslog::class ; $fields = "points";  break;
            case 1: $model = Ppointslog::class; $fields = "partymember_points"; break;
            case 2: $model = Vpointslog::class; $fields = "volunteer_points"; break;
            default: return false;
        }

        echo $fields; die;

        DB::beginTransaction();
        try{

            if($user->$fields + $rule->delta < 0){

                $user->update(array(
                    $fields => 0
                ));

                $model::create(array(
                    "uid"     => $user->id,
                    "openid" => $user->openid,
                    "delta" => (0 - $user->$fields),
                    "desc"   => $rule->rule,
                ));

            }else{

                $user->increment($fields, $rule->delta);

                $model::create(array(
                    "uid"     => $user->id,
                    "openid" => $user->openid,
                    "delta" => $rule->delta,
                    "desc"   => $rule->rule,
                ));

            }

            DB::commit();
            return true;

        }catch(Exception $e){

            DB::rollBack();
            return false;

        }
    }
}
