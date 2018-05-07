<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Pointslog;
use App\Models\Ppointslog;
use App\Models\Vpointslog;

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
            case 0: $model = "Pointslog" ; $fields = "points";  break;
            case 1: $model = "Ppointslog"; $fields = "partymember_points"; break;
            case 2: $model = "Vpointslog"; $fields = "volunteer_points"; break;
            default: return false;
        }

        DB::beginTransaction();
        try{

            if($user->$fields + $rule->delta < 0){

                $user->update(array(
                    $fields => 0
                ));

                $model::create(array(
                    "uid"     => $user->id,
                    "openid" => $user->openid,
                    "points" => (0 - $user->$fields),
                    "desc"   => $rule->rule,
                ));

            }else{

                $user->increment($fields, $rule->delta);

                $model::create(array(
                    "uid"     => $user->id,
                    "openid" => $user->openid,
                    "points" => $rule->delta,
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
