<?php

namespace App\Admin\Controllers;

use App\Models\Prize;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class PrizeController extends Controller
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

            $content->header('奖品管理');
            $content->description('设置奖品及相关信息');

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

            $content->header('奖品管理');
            $content->description('设置奖品及相关信息');

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

            $content->header('奖品管理');
            $content->description('设置奖品及相关信息');

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
        return Admin::grid(Prize::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->prize("奖品名称");
            $grid->img("奖品图片")->display(function ($img){
                return "<img src=\"/uploads/{$img}\" style=\"width:25px;\"/>";
            });

            $grid->cost("兑换积分");
            $grid->stime("开始时间");
            $grid->etime("结束时间");

            $grid->checked("发布")->display(function ($checked) {
                return $checked ? '是' : '否';
            });

            $grid->num("奖品数量");

            $grid->created_at("创建时间");
            $grid->updated_at("更新时间");


            $grid->model()->orderBy('id', 'desc');
            $grid->paginate(30);
            $grid->disableExport();
            $grid->perPages([30, 40, 50]);

            $grid->tools(function ($tools){
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });

            $grid->actions(function ($actions){
                $actions->disableDelete();
                $actions->append('<a href="'.url("admin/exchange?pid=".$actions->getKey()).'"><i class="fa fa-eye"></i></a>');
            });

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Prize::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('prize', "奖品名称");
            $form->image('img', "奖品图片")->rules("required")->uniqueName();
            $form->datetimeRange("stime", "etime", "兑换时间");
            $form->number("num", "奖品数量");
            $form->number("cost", "兑换积分");
            $form->radio("checked", "发布")->options([0 => "否", 1 => "是"])->default(0);
            $form->textarea("intro", "奖品简介");

            $form->display('created_at', '创建时间');
            $form->display('updated_at', '更新时间');
        });
    }
}
