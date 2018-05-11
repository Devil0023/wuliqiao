<?php

namespace App\Admin\Controllers;

use App\Models\Exchange;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ExchangeController extends Controller
{
    use ModelForm;

    public $pid;

    public function __construct(Request $request){
        $this->pid = $request->pid;
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('奖品兑换');
            $content->description('查看详情');

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

            $content->header('奖品兑换');
            $content->description('查看详情');

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

            $content->header('奖品兑换');
            $content->description('查看详情');

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
        return Admin::grid(Exchange::class, function (Grid $grid) {

            $grid->model()->where("pid", $this->pid)->orderBy("id", "desc");

            $grid->id('ID')->sortable();
            $grid->column("用户")->display(function (){

                $user = Wxuser::find($this->uid);
                if(is_null($user)){
                    return "";
                }else{
                    return $user->nickname;
                }

            });

            $grid->created_at();
            $grid->updated_at();

            $grid->disableCreateButton();

            $grid->actions(function ($actions) {
                $actions->disableDelete();
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
        return Admin::form(Exchange::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
