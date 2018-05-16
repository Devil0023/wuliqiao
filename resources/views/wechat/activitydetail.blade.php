<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title></title>
</head>
<body>
{{$info["id"]}}
{{$info["title"]}}
{{$info["titlepic"]}}
{{$info["timeinfo"]}} - {{$info["editor"]}}
{{$info["activitytime"]}}
{{$info["newstext"]}}



</body>
</html>