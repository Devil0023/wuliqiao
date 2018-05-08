<?php

namespace App\Admin\Controllers;

use App\Models\Vpointslog;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;

class VpointslogController extends Controller
{
    use ModelForm;

    private $uid = 0;

    public function __construct(Request $request){
        $this->uid = $request->uid;
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('志愿者积分日志');
            $content->description('查看志愿者积分详情');

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

            $content->header('志愿者积分日志');
            $content->description('查看志愿者积分详情');

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

            $content->header('志愿者积分日志');
            $content->description('查看志愿者积分详情');

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
        return Admin::grid(Vpointslog::class, function (Grid $grid) {

            $grid->model()->where("uid", $this->uid);
            $grid->model()->orderBy("id", "desc");

            $grid->id('ID')->sortable();
            $grid->desc("说明");
            $grid->delta("积分变动");

            $grid->created_at();
            $grid->updated_at();

            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();
            });

            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
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
        return Admin::form(Vpointslog::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
