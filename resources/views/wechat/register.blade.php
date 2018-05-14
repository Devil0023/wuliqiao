<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title></title>
</head>

<body>
<form id="register">
    truename:<input name="truename" type="text" value=""><br/>
    mobile:<input name="mobile" type="text" value="" id="mobile"><br/>
    address:<input name="address" type="text" value=""><br/>
    volunteer:<input name="volunteer" type="text" value=""><br/>
    partymember:<input name="partymember" type="text" value=""><br/>
    code:<input name="code" type="text" value=""><input name="smschk" value="smschk" id="smschk" type="button"><br/>
    <input name="submit" type="button" value="submit" id="submit"><br/>
</form>

<script src="http://skin.kankanews.com/v6/js/libs/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
    $("#smschk").on("click",function(){

        $.ajax({
            cache: true,
            type: "POST",
            url:   "/wechat/usercenter/smscheck",
            data:  "mobile=" + document.getElementById("mobile").value,
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
