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

        $activity = Activity::find($request->info);

        if(!is_null($request->file("titlepic"))){
            $request->titlepic = $request->file("titlepic")->store('images/'.date("Y-m-d"));
        }else{
            $request->titlepic = $activity->titlepic;
        }

        $result = $activity->update(array(
            "title"         => $request->title,
            "titlepic"      => $request->titlepic,
            "stime"         => $request->stime,
            "etime"         => $request->etime,
            "checked"       => $request->checked,
            "address"       => $request->address,
            "activitytime" => $request->activitytime,
            "editor"        => $request->editor,
            "newstext"      => $request->newstext,
            "limitation"   => intval($request->limitation) >= 0 ? intval($request->limitation): 0,
            "limitation_left" => intval($request->limitation_left) >= 0 ? intval($request->limitation_left): 0,
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

    public function destroy(Request $request){

        if(Activity::destroy(explode(",", $request->info))){
            $result["status"]  = true;
            $result["message"] = "Delete succeeded !";
        }else{
            $result["status"]  = false;
            $result["message"] = "Delete failed !";
        }

        return $result;
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
            $grid->model()->orderBy("id", "desc");

            $grid->id('ID')->sortable();

            $grid->title("活动");

            $grid->limitation("人数限制")->display(function ($limitation){
                return (intval($limitation) === 0)? "无限制": $limitation;
            });

            $grid->limitation_left("人数剩余")->display(function (){
                return (intval($this->limitation) === 0)? "无限制": $this->limitation_left;
            });

            $grid->activitytime("活动时间");

            $grid->column("报名起止")->display(function (){
                return $this->stime."-".$this->etime;
            });

            $grid->checked("审核")->display(function ($checked){
                return $checked ? '是' : '否';
            });

            $grid->editor("编辑");


            $grid->actions(function ($actions){

                $actions->append('<a title="查看参与详情" href="'.url("admin/activity/".$actions->getKey()."/participate").'"><i class="fa fa-eye"></i></a>&nbsp;');

                $actions->append('<a title="签到二维码" href="#" onclick="window.open(\''.url("admin/activity/qrcode/".$actions->getKey()).'\', \'QRcode\', \'width=600,height=360,scrollbars=yes,resizable=yes\'）"><i class="fa fa-qrcode"></i></a>&nbsp;');

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
        return Admin::form(Activity::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->text("title", "活动");
            $form->image('titlepic', "活动图片")->rules("required")->uniqueName()->move('images/'.date("Y-m-d"));

            $form->text("address", "地点");

            $form->datetime("activitytime", "活动时间");

            $form->datetimeRange("stime", "etime", "报名起止");

            $form->number("limitation", "人数限制");
            $form->number("limitation_left", "人数剩余");

            $form->radio("checked", "审核")->options([0 => "否", 1 => "是"])->default(0);

            $form->text("editor", "编辑");
            $form->textarea("newstext", "活动正文");

            $form->hidden("type")->value($this->type);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
