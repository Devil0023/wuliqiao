<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
	<div class="main-wrapper point-detail">
		<div class="white-wrapper">
			<div class="row-flexwrapper user-info no-border">	
				<div class="row-flexwrapper">
					<div class="protrait">
						<img src="{{$uinfo["headimgurl"]}}"/>
					</div>
					<p>{{$uinfo["nickname"]}}<br/><span><em>{{@$uinfo["partymember_points"]}}</em>分</span></p>
				</div>
			</div>
			<div class="line-title"><span>积分明细</span></div>
			<ul class="point-list">
				@foreach($uinfo["list"] as $key => $val)
				<li class="row-flexwrapper">
					<div class="date">{{$val["created_at"]}}</div>
					<div class="action">{{$val["desc"]}}</div>
					<div class="point"><span>{{$val["delta"]}}</span>积分</div>
				</li>
				@endforeach
			</ul>
		</div>
	</div>
	<script type="text/javascript" src="https://skin.kankanews.com/v6/2016zt/hz/js/jquery.js"></script>
	<script type="text/javascript" src="//wlq.kankannews.com/wechat/scripts/public.js"></script>
</body>
</html>