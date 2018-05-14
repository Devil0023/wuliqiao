<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title></title>
</head>

<body>

任务：{{$mission["complete"]}}/{{$mission["daily"]}}
@foreach($list["data"] as $key => $val)

    <a id="read{{$val["id"]}}">{{$val["title"]}}</a><br/>

@endforeach

<script src="http://skin.kankanews.com/v6/js/libs/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
    $("#read1").on("click",function(){

        $.ajax({
            cache: true,
            type: "POST",
            url:   "/wechat/news/read",
            data:  "newsid=3",
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