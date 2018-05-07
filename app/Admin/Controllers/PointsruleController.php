<?php

namespace App\Admin\Controllers;

use App\Models\Pointsrule;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class PointsruleController extends Controller
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

            $content->header('积分规则');
            $content->description('积分规则查看及管理');

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

            $content->header('积分规则');
            $content->description('积分规则查看及管理');

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

            $content->header('积分规则');
            $content->description('积分规则查看及管理');

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
        return Admin::grid(Pointsrule::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->rule("规则");
            $grid->type("类型")->display(function ($type){
                $string = "";
                switch($type){
                    case "0": $string = "普通积分"; break;
                    case "1": $string = "党员积分"; break;
                    case "2": $string = "志愿者积分"; break;
                }

                return $string;
            });

            $grid->delta("积分变量");

            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });

            $grid->actions(function ($actions) {
                $actions->disableDelete();
            });

            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Pointsrule::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->text("rule", "规则");
            $form->radio("type", "类型")->options(array(
                0 => "普通积分", 1 => "党员积分", 2 => "志愿者积分",
            ))->default(0);

            $form->number("delta", "积分变量");
            $form->text("intro", "介绍");

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
