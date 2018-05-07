<?php

namespace App\Models;

use App\Admin\Controllers\PointsruleController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pointsrule extends Model
{
    use SoftDeletes;

    protected $table = 'pointsrule';


    static public function addPointsByRule($ruleid, $uid, $openid){

        $rule = self::find($ruleid);

        if(is_null($rule)){
            return false;
        }

        return $rule->rule;

        DB::beginTransaction();
        try{


            DB::commit();
            return true;

        }catch(Exception $e){

            DB::rollBack();
            return false;

        }
    }
}
