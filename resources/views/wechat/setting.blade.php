<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>更新</title>
    <link rel="stylesheet" type="text/css" href="//wlq.kankannews.com/wechat/styles/public.css">
</head>

<body>
<div class="loadingMask">
    <div class="loadWrapper text-center">
        <p>loading...</p>
    </div>
</div>
<div class="main-wrapper">
    <div class="white-wrapper">
        <div class="row-flexwrapper user-info">
            <div class="row-flexwrapper">
                <div class="protrait">
                    <img src="{{@$uinfo["headimgurl"]}}"/>
                </div>
                <p>{{@$uinfo["nickname"]}}</p>
            </div>
            <div class="row-flexwrapper user-tag">
                @if($info["volunteer"])
                    <p><i class="icon volunteer-icon"></i></br><span>志愿者</span></p>
                @endif
                @if($info["partymember"])
                    <p><i class="icon partymember-icon"></i></br><span>党员</span></p>
                @endif
            </div>
        </div>
        <div class="edit-wrapper">
            <form id="register">
                <div class="edit-title">基本信息</div>
                <div class="input-wrapper row-flexwrapper">
                    <span><i>*</i>姓名</span>
                    <input type="text" name="truename" value="{{$info["truename"]}}">
                </div>
                
                <div class="input-wrapper row-flexwrapper">
                    <span><i>*</i>手机</span>
                    <input type="text" name="mobile" value="{{$info["mobile"]}}">
                </div>
                <div class="input-wrapper row-flexwrapper">
                    <span><i>*</i>验证码</span>
                    <input type="text" name="code">
                    <span class="btn"  id="smschk">发送验证码</span>
                </div>
                <div class="input-wrapper row-flexwrapper">
                    <span><i></i>地址</span>
                    <input type="text" name="address" value="{{$info["address"]}}">
                </div>
                @if($info["volunteer"]==0)
                <div class="yellow-board row-flexwrapper volunteer">
                    <p>申请成为志愿者</p>
                    <p class="row-flexwrapper">
                        <span class="btn green">申请</span>
                        <span class="btn">不申请</span>
                    </p>
                </div>
                @endif
                @if($info["partymember"]==0)
                <div class="yellow-board row-flexwrapper partymember">
                    <p>是否为党员</p>
                    <p class="row-flexwrapper">
                        <span class="btn green">是</span>
                        <span class="btn">否</span>
                    </p>
                </div>
                @endif
                <input type="hidden" name="volunteer" value="{{$info["volunteer"]}}">
                <input type="hidden" name="partymember" value="{{$info["partymember"]}}">
            </form>
        </div>
    </div>
    <div class="edit-btn-submit btn" id="submit">修改</div>
</div>

<script type="text/javascript" src="https://skin.kankanews.com/v6/2016zt/hz/js/jquery.js"></script>
<script type="text/javascript" src="//wlq.kankannews.com/wechat/scripts/public.js"></script>
<script type="text/javascript">
   function timeCountDown(btn, time, txt) {
       if (time == 0) {
           btn.html(txt);
           btn.removeClass("gray");
           return;
       }
       btn.html(time + "S");
       setTimeout(function () {
           timeCountDown(btn, --time, txt)
       }, 1000)
   }
    $("#smschk").on("click",function(){
        if( $("input[name='mobile']").val()===""){
            alert("请输入手机号");
            return;
        }
        if($(this).hasClass("gray")){
            return;
        }
        $(this).addClass("gray");
        timeCountDown($("#smschk"), 10, "发送验证码")
        $.ajax({
            cache: true,
            type: "POST",
            url:   "/wechat/usercenter/smscheck",
            data:  "mobile=" + $("input[name='mobile']").val(),
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function(data) {
                alert(data.error_message);
            },
            success: function(data) {
                if(parseInt(data.error_code)===0){
                    alert("修改成功");
                }else{
                    alert(data.error_message);
                }
            }
        });

    });

    $("#submit").on("click",function(){
        $.ajax({
            cache: true,
            type: "POST",
            url:   "/wechat/usercenter/updateinfo",
            data:  $("#register").serialize(),
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function(data) {
                alert(data.error_message);
            },
            success: function(data) {
                if(parseInt(data.error_code)===0){
                    alert("修改成功");
                };
            }
        });

    });
</script>
