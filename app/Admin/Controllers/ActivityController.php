<?php

namespace App\Admin\Controllers;

use App\Models\Activity;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    use ModelForm;

    public $type = 1;
    public $header = "活动管理";

    public function __construct(Request $request){
        switch($request->type){
            case "community":
                $this->type   = 1;
                $this->header = "社区活动";
                break;

            case "publicservice":
                $this->type   = 2;
                $this->header = "公益活动";
                break;
        }

    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header($this->header);
            $content->description('查看及修改活动信息');

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
        $id = $request->info;

        return Admin::content(function (Content $content) use ($id) {

            $content->header($this->header);
            $content->description('查看及修改活动信息');

            $content->body($this->form()->edit($id));
        });
    }

    public function update(Request $request){

        if($request->titlepic){
            var_dump($request->file("titlepic"));  die;
        }

        $result = Activity::find($request->info)->update(array(
            "title"         => $request->title,
            "titlepic"      => $request->titlepic,
            "stime"         => $request->stime,
            "etime"         => $request->etime,
            "checked"       => $request->checked,
            "address"       => $request->address,
            "activitytime" => $request->activitytime,
            "editor"        => $request->editor,
            "newstext"      => $request->newstext,
        ));

        if($result){

            $url = "/admin/activity/community/info";
            switch($request->type){
                case 1: $url = "/admin/activity/community/info"; break;
                case 2: $url = "/admin/activity/publicservice/info"; break;
            }

            return redirect($url);

        }else{
            return array("status" => false, "message" => "Update failed!");
        }
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header($this->header);
            $content->description('查看及修改活动信息');

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
        return Admin::grid(Activity::class, function (Grid $grid) {

            $grid->model()->where("type", $this->type);
            $grid->model()->orderBy("activitytime", "desc");

            $grid->id('ID')->sortable();

            $grid->title("活动");

            $grid->activitytime("活动时间");

            $grid->column("报名起止")->display(function (){
                return $this->stime."-".$this->etime;
            });

            $grid->checked("审核")->display(function ($checked){
                return $checked ? '是' : '否';
            });

            $grid->editor("编辑");

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
        return Admin::form(Activity::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->text("title", "活动");
            $form->image('titlepic', "活动图片")->rules("required")->uniqueName()->move('images/'.date("Y-m-d"));

            $form->text("address", "地点");

            $form->datetime("activitytime", "活动时间");

            $form->datetimeRange("stime", "etime", "报名起止");

            $form->radio("checked", "审核")->options([0 => "否", 1 => "是"])->default(0);

            $form->text("editor", "编辑");
            $form->textarea("newstext", "活动正文");

            $form->hidden("type")->value($this->type);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
