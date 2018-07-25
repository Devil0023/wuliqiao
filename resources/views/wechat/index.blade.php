<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>个人中心</title>
    <link rel="stylesheet" type="text/css" href="//wlq.kankannews.com/wechat/styles/public.css">
</head>
<body>
    <div class="loadingMask">
        <div class="loadWrapper text-center">
            <p>loading...</p>
        </div>
    </div>
    <div class="main-wrapper user-center">
        <div class="white-wrapper">
            <div class="row-flexwrapper user-info"> 
                <div class="row-flexwrapper">
                    <div class="protrait">
                        <img src="{{$uinfo["headimgurl"]}}"/>
                    </div>
                    <p>{{$uinfo["nickname"]}}</p>
                </div>
                <div class="btn sign-in {{$sign==1?"gray":""}}"> {{$sign==1?"已":""}}签到</div>
            </div>
            <ul class="item-list">
                <li class="row-flexwrapper everyday more" data-url="/wechat/news">
                    <span>每日任务</span><span>{{$mission["complete"]}}/{{$mission["daily"]}}</span>
                </li>
                <li class="row-flexwrapper myprofile more" data-url="/wechat/news">
                    <span>我的资料</span>
                    <span></span>
                </li>
                <li class="row-flexwrapper mypoint more" data-url="/wechat/usercenter/pointslog">
                    <span>我的积分</span>
                    <span>{{$uinfo["points"]}}<i>分</i></span>
                </li>
                @if ($uinfo["volunteer"])
                <li class="row-flexwrapper volunteerpoint" data-url="
                /wechat/usercenter/vpointslog">
                    <span>志愿者积分</span>
                    <span>{{$uinfo["volunteer_points"]}}<i>分</i></span>
                </li>
                @endif
                @if ($uinfo["partymember"])
                <li class="row-flexwrapper partypoint" data-url="/wechat/usercenter/ppointslog">
                    <span>党性积分</span>
                    <span>{{$uinfo["partymember_points"]}}<i>分</i></span>
                </li>
                @endif
            </ul>
        </div>
    </div>
    <script type="text/javascript" src="https://skin.kankanews.com/v6/2016zt/hz/js/jquery.js"></script>
    <script type="text/javascript" src="./scripts/public.js"></script>
<script type="text/javascript">
    $(".item-list li").on("click",function(){
        var url=$(this).data("url");
        window.location.href=url;
    })
    $(".sign-in").on("click",function(){
        if($(this).hasClass("gray")){
            alert("已签到");
            return;
        }
        $.ajax({
            cache: true,
            type: "POST",
            url:   "/wechat/usercenter/sign",
            data:  "",
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            error: function(data) {
                console.log(data);
            },

            success: function(data) {
                if(data.error_code==="0"||data.error_code==="400008"){
                   $(".sign-in").addClass("gray");
                }
                alert(data.error_message)
            }
        });

    });
</script>
</body>
</html>