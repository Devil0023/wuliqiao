<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title></title>
</head>

<body>

{{$info["nickname"]}}
{{$info["openid"]}}
{{$info["headimgurl"]}}
{{$info["points"]}}
{{$info["volunteer"]}}
{{$info["volunteer_points"]}}
{{$info["partymember"]}}
{{$info["partymember_points"]}}

<input type="button" class="sBtn" value="签到" id="sign" />

<script src="http://skin.kankanews.com/v6/js/libs/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
    $("#sign").on("click",function(){

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

                console.log(data);
                //var dataObj = JSON.parse(data);

//                if(data.error_code != "0"){
//                    $(".mask").show();
//                    $(".mask").find("p").html(data.error_message);
//                }else{
//                    $(".first").hide();
//                    $(".second").show();
//                };

            }
        });

    });
</script>

</body>