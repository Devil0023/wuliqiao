<!DOCTYPE html>
<html lang="en">
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
    <div class="main-wrapper daily-detail">
        <div class="white-wrapper">
            <div class="daily-info">
                <p class="daily-intro">每日任务规则说明</br><span>阅读并转发任意一篇文章加1积分，每日上限5分</span></p>
                <div class="row-flexwrapper daily-progress">
                    <p>今日进度</p>
                    <p id="daily-progress-bar" data-complete="{{$mission['complete']}}" class="row-flexwrapper {{array('zero','one','two','three','four','five')[$mission['complete']]}}">
                        <span></span><span></span>
                        <span></span><span></span>
                        <span></span>
                    </p>
                </div>
            </div>
            <ul class="daily-list">
                @foreach($list["data"] as $key => $val)
                    <li data-readid="{{$val["id"]}}" data-url="{{$val["url"]}}">
                        <p class="title">{{$val["title"]}}</p>
                        <p class="intro">{{$val["intro"]}}</p>
                    </li>
                @endforeach
            </ul>
            <p id="more" style="text-align: center;">下拉加载更多</p>
        </div>
    </div>
        <script id="list" type="text/html">
            <% for(var i = 0; i < data.length; i++){ %>
            <li data-readid="<%= data[i]["id"] %>" data-url="<%= data[i]["url"] %>">
                <p class="title"><%= data[i]["title"] %></p>
                <p class="intro"><%= data[i]["intro"] %></p>
            </li>
            <% } %>
        </script>
        <script type="text/javascript" src="https://skin.kankanews.com/v6/2016zt/hz/js/jquery.js"></script>
        <script type="text/javascript" src="//wlq.kankannews.com/wechat/scripts/public.js"></script>
        <script type="text/javascript" src="//wlq.kankannews.com/wechat/scripts/general.js"></script>
        <script type="text/javascript" src="//wlq.kankannews.com/wechat/scripts/template-web.js"></script>
        <script type="text/javascript">
            var config={
                url:"/wechat/news?page=",
                listid:"list",
                wrapper:$(".daily-list")
            }
            loadData(config);
        </script>

    
    <script type="text/javascript">
        var dataTimes=['zero','one','two','three','four','five'],
        dailyProgressBar=$("#daily-progress-bar");
        $(".daily-list").on("click",function(e){
            var target=$(e.target);
            if(typeof target.data("readid")==="undefined"){
                target=target.parent("li");
            }
            console.log(target.data("readid"),target.data("url"))
            $(".loadingMask").addClass("submiting").removeClass("hide");
            $.ajax({
                cache: true,
                type: "POST",
                url:   "/wechat/news/read",
                data:  "newsid="+target.data("readid"),
                async: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                error: function(data) {
                    console.log(data);
                },
                success: function(data) {
                    if(data.error_message==="success"){
                        var times=parseInt(dailyProgressBar.data("complete"));
                        dailyProgressBar.data("complete",times+1);
                        dailyProgressBar.addClass(dataTimes[times+1]);
                        $(".loadingMask").addClass("hide");
                        window.location.href=target.data("url");
                    }else{
                        $(".loadingMask").addClass("hide");
                        alert(data.error_message);
                    }
                }
            });
        });
    </script>
</body>
</html>
