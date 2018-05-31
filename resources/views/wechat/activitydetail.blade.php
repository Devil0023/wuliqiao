<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{$info["title"]}}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="format-detection" content="telephone=no">
        <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
        <link rel="stylesheet" type="text/css" href="//wlq.kankannews.com/wechat/styles/public.css">
    </head>
    <body>
        <div class="loadingMask">
            <div class="loadWrapper text-center">
                <p>loading...</p>
            </div>
        </div>
        <div class="main-wrapper activity-detail">
            <div class="white-wrapper">
                <header>
                    <img src="/uploads/{{$info["titlepic"]}}" alt="">
                    @if ($info["participate"]==0)
                    <div class="baoming activitybaoming">立即报名</div>
                    @endif
                    <!-- <a href="/wechat/activity/sign/{!! $info["id"] !!}">签到</a><br/> -->
                </header>
                <article>
                    <div class="tit">{{$info["title"]}}</div>
                    <div class="info"><span data-time=""></span><span>{{$info["timeinfo"]}} - {{$info["editor"]}}</span></div>
                    <div class="careinfo">                    
                        <p class="stime"><i></i>活动时间：{{$info["activitytime"]}}</p>
                        <p class="place"><i></i>活动地点：上海中心22楼</p>                
                    </div>
                    <div class="content">
                        {{$info["newstext"]}}
                    </div>
                </article>
                @if ($info["participate"]==0)
                <div class="baoming activitybaoming">立即报名</div>
                @endif
            </div>
        </div>
    <script type="text/javascript" src="https://skin.kankanews.com/v6/2016zt/hz/js/jquery.js"></script>
    <script type="text/javascript" src="//wlq.kankannews.com/wechat/scripts/public.js"></script>
    <script type="text/javascript">
    $(".activitybaoming").on("click",function(){
        $.ajax({
            cache: true,
            type: "POST",
            url:   "/wechat/activity/participate",
            data:  "id={!! $info["id"] !!}",
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function(data) {
                console.log(data);
            },
            success: function(data) {
                if(data.error_code===0&&data.error_message==="success"){
                     $(".activitybaoming").remove();
                }else{
                    alert(data.error_message);
                }
            }
        });

    });
</script>
</body>
</html>