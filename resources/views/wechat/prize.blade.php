<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title></title>
</head>

<body>

@foreach($list["data"] as $key => $val)

    <div id="read{{$val["id"]}}"><a>{{$val["title"]}}</a></div>

@endforeach

</body>
</html>