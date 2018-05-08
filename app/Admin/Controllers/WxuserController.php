<?php

namespace App\Admin\Controllers;

use App\Models\Wxuser;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

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

                $actions->append('<div title = "积分日志"><a href="'.url("admin/points/".$actions->getKey()."/pointslog").'"><i class="fa fa-eye"></i></a></div>');
                $actions->append('<div title = "志愿者积分日志"><a href="'.url("admin/points/".$actions->getKey()."/pointslog").'"><i class="fa fa-eye"></i></a></div>');
                $actions->append('<div title = "党员积分日志"><a href="'.url("admin/points/".$actions->getKey()."/pointslog").'"><i class="fa fa-eye"></i></a></div>');
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
}
