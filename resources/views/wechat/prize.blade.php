<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title></title>
    <link rel="stylesheet" type="text/css" href="//wlq.kankannews.com/wechat/styles/public.css">
    <style type="text/css">
        /*兑换*/
        .changeWrapper>.flexBox,.changeProfileWrapper>.flexBox{ height: 100%; }
        .changeWrapper,.changeProfileWrapper{ position: fixed; top:0px; left: 0px; width: 100%; height: 100%; background:rgba(0,0,0,0.5);}
        .changeWrapper>.flexBox>div,.changeProfileWrapper>.flexBox>div{ width: 50%; background: #000; color: #fff; padding: 1em; border-radius: 1em; }
        .changeWrapper>.flexBox>div p,.changeProfileWrapper>.flexBox>div p{  font-size: .8em; margin-bottom: 1em; }
        .changeWrapper>.flexBox>div p i{ color: #dd5525; font-size: 1.25em; }
        .changeWrapper>.flexBox>div div,.changeProfileWrapper>.flexBox>div div{ margin-top: 2em; }
        .changeProfileWrapper>.flexBox>div .smallBtnRed{ width: auto; margin:0 auto;    padding: 0 1em; }
        .caution-text{ background: #ffffb2;font-size: 0.9em;line-height: 1.5;padding: .5em 1em;margin-right: 1.5em;border-bottom: 1px solid #f8b551;}
    </style>
</head>

<body>
    <div class="loadingMask">
        <div class="loadWrapper text-center">
            <p>loading...</p>
        </div>
    </div>
    <div class="main-wrapper point-detail">
        <div class="white-wrapper">
            <div class="row-flexwrapper user-info no-border">   
                <div class="row-flexwrapper">
                    <div class="protrait">
                        <img src="aaa"/>
                    </div>
                    <p>{{@$uinfo["nickname"]}}<br/><span><em class="mallPoint">{{@$uinfo["points"]}}</em>分</span></p>
                </div>
            </div>
            <div class="line-title"><span>积分兑换</span></div>
            <ul class="prize-list">
                @foreach($list as $key => $val)
                <li>
                    <a href="productDetail.html">
                    <div class="prize-img"><img src="{{$val["img"]}}"><span class="caution">剩余<i>{{$val["num"]}}</i>份</span></div>
                    </a>
                    <div class="prize-detail">
                        <p class="prize-title">{{$val["prize"]}}</p>
                        <p class="prize-subtitle">积分兑换</p>
                        <div class="row-flexwrapper">
                            <div class="point"><i><b>{{@$val["cost"]}}</b></i>积分</div>
                            <div class="prize-state {{@$val["num"]>0?"":"soldout"}}">
                                <span class="btn gray">{{@$val["state"]}}</span>
                                <span class="prize-change btn" data-point="{{@$val["cost"]}}" data-pid="{{$val["id"]}}">兑换</span>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
        <div class="changeWrapper hide">
        <div class="flexBox">
            <div>
                <p>是否确认兑换此礼物？</p>
                <p>将消耗 <i class="changePoint"></i> 积分</p>
                <div class="row-flexwrapper text-center">
                    <span class="smallBtnRed changeForSure">兑换</span>
                    <span class="smallBtnGray cancelChange">取消</span>
                </div>
            </div>
        </div>
    </div>
    <div class="changeProfileWrapper hide">
        <div class="flexBox">
            <div>
                <p>请先完善您的个人资料</p>
                <p>我们才能准确配送礼物</p>
                <div class="row-flexwrapper text-center">
                    <span class="smallBtnRed"><a href="/wechat/usercenter/profile">完善个人资料</a></span>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript" src="https://skin.kankanews.com/v6/2016zt/hz/js/jquery.js"></script>
<script type="text/javascript" src="//wlq.kankannews.com/wechat/scripts/public.js"></script>
<script type="text/javascript">
    var loading=$(".loadingMask");
    $(".prize-change").bind("click",function() {
        var _=$(this);
        var pid=_.data("pid"),
            point=parseInt(_.data("point")),
            cautionNum=_.parent().parent().parent().parent().find(".caution i");

        $(".changeWrapper>.flexBox>div p i").html(point);
        $(".changeWrapper").removeClass("hide");
        $(".changeForSure").bind("click",function() {
            loading.addClass("submiting").removeClass("hide")
            var totalpointsDiv=$(".mallPoint");
            var totalpoints=parseInt(totalpointsDiv.html());
            $.ajax({
                cache: true,
                type: "POST",
                url:   "/wechat/prize/exchange",
                data:  "id=1",
                async: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                error: function(data) {
                     alert("兑换失败");
                     loading.addClass("hide");
                },
                success: function(res) {
                    loading.addClass("hide");
                    var error_code=parseInt(res.error_code)
                    if(error_code===0){
                        alert("兑换成功")
                        totalpointsDiv.html(res.points)
                        //alert("v1left"+res.left)
                        if(res.left===0){
                            _.parent(".prize-state").addClass("soldout")
                        }
                        cautionNum.html(res.left)

                        $(this).unbind("click")
                        $(".changeForSure").unbind("click");
                        $(".changeWrapper").addClass("hide");
                    }else if(error_code===400011){
                        $(this).unbind("click")
                        $(".changeProfileWrapper").removeClass("hide");
                    }else{
                        //alert(res.error_message)
                        alert("兑换失败")
                    }
                }
            });
            
        })
    })
    $(".cancelChange").bind("click",function() {
        $(".changeForSure").unbind("click");
        $(".changeWrapper").addClass("hide");
    })
</script>

</body>
</html>