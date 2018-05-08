<?php

namespace App\Admin\Controllers;

use App\Models\Pointslog;
use App\Models\Ppointslog;
use App\Models\Vpointslog;
use App\Models\Wxuser;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use DB;
use Illuminate\Http\Request;

class WxuserController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('微信用户');
            $content->description('微信用户信息管理');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('微信用户');
            $content->description('微信用户信息管理');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('微信用户');
            $content->description('微信用户信息管理');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Wxuser::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->nickname("昵称");
            $grid->headimgurl("头像")->display(function ($img){
                return "<img src=\"$img\" style=\"width:25px;\">";
            });

            $grid->points("积分");
            $grid->volunteer_points("志愿者积分");
            $grid->partymember_points("党性积分");



            $grid->created_at("创建时间");

            $grid->model()->orderBy("id", "desc");


            $grid->tools(function ($tools){
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });

            $grid->actions(function ($actions){
                $actions->disableDelete();

                $actions->append('<a title="积分日志" href="'.url("admin/points/".$actions->getKey()."/pointslog").'"><i class="fa fa-eye"></i></a>&nbsp;');
                $actions->append('<a title="志愿者积分日志" href="'.url("admin/points/".$actions->getKey()."/vpointslog").'"><i class="fa fa-eye"></i></a>&nbsp;');
                $actions->append('<a title="党员积分日志" href="'.url("admin/points/".$actions->getKey()."/ppointslog").'"><i class="fa fa-eye"></i></a>&nbsp;');
            });

            $grid->filter(function ($filter){
                $filter->like("nickname", "昵称");
            });


            $grid->disableCreateButton();
            $grid->disableExport();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Wxuser::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display("nickname", "昵称");

            $form->display("sex", "性别")->with(function ($sex){
                switch($sex){
                    case 1; $value = "男"; break;
                    case 2: $value = "女"; break;
                    default: $value = "不明";
                }
                return $value;
            });

            $form->display("language", "语言");
            $form->display("province", "省份");
            $form->display("city", "城市");
            $form->display("country", "国家");
            $form->display("headimgurl", "头像")->with(function ($img){
                return "<img src=\"$img\" style=\"width: 132px;\">";
            });

            $form->hidden("id");
            $form->text("truename", "真实姓名");
            $form->text("mobile", "手机");
            $form->text("address", "地址");

            $form->radio("volunteer", "是否为志愿者")->options([0 => "否", 1 => "是"])->default(0);
            $form->radio("partymember", "是否为党员")->options([0 => "否", 1 => "是"])->default(0);

            $form->number("points", "积分");
            $form->number("volunteer_points", "志愿者积分");
            $form->number("partymember_points", "党性积分");

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }

    public function update(Request $request){ //update重写
        $id   = $request->id;
        $user = Wxuser::find($id);

        $old["points"]               = $user->points;
        $old["volunteer_points"]   = $user->volunteer_points;
        $old["partymember_points"] = $user->partymember_points;


        DB::beginTransaction();
        try{

            $request->points               = $request <= 0? 0: $request->points;
            $request->volunteer_points    = $request <= 0? 0: $request->volunteer_points;
            $request->partymember_points = $request <= 0? 0: $request->partymember_points;


            $user->update(array(

                "truename"     => $request->truename,
                "mobile"       => $request->mobile,
                "address"      => $request->address,
                "volunteer"   => $request->volunteer,
                "partymember" => $request->partymember,

                "points"               => $request->points,
                "volunteer_points"   => $request->volunteer_points,
                "partymember_points" => $request->partymember_points,

            ));

            if($old["points"] != $request->points){

                Pointslog::create(array(
                    "uid"     => $user->id,
                    "openid" => $user->openid,
                    "delta"  => $request->points - $old["points"],
                    "desc"   => "后台调整",

                ));
            }

            if($old["partymember_points"] != $request->partymember_points){

                Ppointslog::create(array(
                    "uid"     => $user->id,
                    "openid" => $user->openid,
                    "delta"  => $request->partymember_points - $old["partymember_points"],
                    "desc"   => "后台调整",

                ));
            }

            if($old["volunteer_points"] != $request->volunteer_points){

                Vpointslog::create(array(
                    "uid"     => $user->id,
                    "openid" => $user->openid,
                    "delta"  => $request->volunteer_points - $old["volunteer_points"],
                    "desc"   => "后台调整",

                ));
            }



            DB::commit();
            return redirect(url("admin/wxuser"));

        }catch(Exception $e){

            DB::rollBack();
            return array("status" => false, "message" => "Update failed!");

        }
    }
}
