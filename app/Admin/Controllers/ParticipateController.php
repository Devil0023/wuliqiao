<?php

namespace App\Admin\Controllers;

use App\Models\Participate;
use Illuminate\Http\Request;
use App\Models\Wxuser;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ParticipateController extends Controller
{
    use ModelForm;

    private $aid = 0;
    public function __construct(Request $request){
        $this->aid = $request->aid;
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('活动参与');
            $content->description('查看参与情况');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    //public function edit($id)
    public function edit(Request $request)
    {
        $id = $request->participate;

        return Admin::content(function (Content $content) use ($id) {

            $content->header('活动参与');
            $content->description('查看参与情况');

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

            $content->header('活动参与');
            $content->description('查看参与情况');

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
        return Admin::grid(Participate::class, function (Grid $grid) {

            $grid->model()->where("aid", $this->aid);
            $grid->model()->orderBy("id", "desc");

            $grid->id('ID')->sortable();
            $grid->column("用户")->display(function (){

                $user = Wxuser::find($this->uid);
                if(is_null($user)){
                    return "";
                }else{
                    return $user->nickname;
                }

            });

            $grid->column("报名时间")->display(function (){
                return ($this->participate)? $this->participatetime: "";
            });

            $grid->column("签到时间")->display(function (){
                return ($this->sign)? $this->signtime: "";
            });


            $grid->disableCreateButton();

            $grid->actions(function ($actions){
                $actions->disableDelete();
            });

            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
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
        return Admin::form(Participate::class, function (Form $form) {

            $form->display('id', 'ID');


            $form->display("wxuser.nickname", "昵称");
            $form->display("wxuser.sex", "性别")->with(function ($sex){
                switch($sex){
                    case 1; $value = "男"; break;
                    case 2: $value = "女"; break;
                    default: $value = "不明";
                }
                return $value;
            });

            $form->display("wxuser.language", "语言");
            $form->display("wxuser.province", "省份");
            $form->display("wxuser.city", "城市");
            $form->display("wxuser.country", "国家");
            $form->display("wxuser.headimgurl", "头像")->with(function ($img){
                return "<img src=\"$img\" style=\"width: 132px;\">";
            });

            $form->display("wxuser.mobile", "手机");
            $form->display("wxuser.address", "地址");



            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $form->disableSubmit();
            $form->disableReset();

            $form->tools(function (Form\Tools $tools){
                $tools->disableListButton();
            });
        });
    }
}
