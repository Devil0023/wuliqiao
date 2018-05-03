<?php

namespace App\Admin\Controllers;

use App\Models\Article;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ArticleController extends Controller
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

            $content->header('文章管理');
            $content->description('查看及修改文章信息');

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

            $content->header('文章管理');
            $content->description('查看及修改文章信息');

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

            $content->header('文章管理');
            $content->description('查看及修改文章信息');

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
        return Admin::grid(Article::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->column("标题")->display(function (){
                return "<a href='".$this->url."' target='_blank'>".($this->top? "[推".$this->top."]": "").$this->title."</a>";
            });

            $grid->checked("审核")->display(function ($checked){
                return $checked? "是": "否";
            });

            $grid->newstime("新闻时间");

            $grid->model()->orderBy("newstime", "desc");

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
        return Admin::form(Article::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->text("title", "标题");
            $form->url("url", "链接地址");
            $form->radio("checked", "审核")->options([0 => "否", 1 => "是"])->default(0);
            $form->datetime("newstime", "新闻时间")->format('YYYY-MM-DD HH:mm:ss');
            $form->text("intro", "文章简介");

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
