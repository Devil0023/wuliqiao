<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>活动通知</title>
		<meta name="format-detection" content="telephone=no">
    	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    	<meta name="csrf-token" content="{{ csrf_token() }}" />
		<link rel="stylesheet" type="text/css" href="//wlq.kankannews.com/wechat/styles/public.css">
	</head>
	<body>
		<div class="loadingMask">
			<div class="loadWrapper text-center">
				<p>loading...</p>
			</div>
		</div>
		<div class="main-wrapper activity-list">
			<div class="white-wrapper">
				<header class="row-flexwrapper active-tag-{{$type}}">
					<p><a href="//wlq.kankannews.com/wechat/activity/community">社区活动</a></p>
					<p><a href="//wlq.kankannews.com/wechat/activity/publicservice">公益活动</a></p>
				</header>
				<ul>
					@foreach($list["data"] as $key => $val)
					<li>
						<a href="/wechat/activity/detail/{{$val["id"]}}">
							<img src="/uploads/{{$val["titlepic"]}}"/>
						</a>
						<a href="/wechat/activity/detail/{{$val["id"]}}">
							<div>
								<p>{{$val["title"]}}</p>
								@if ($val["participate"]==1)
								<img src="http://www.lujiazuifc.com/skin/v1/images/baoming1.jpg"/>
								@else
								<img src="http://www.lujiazuifc.com/skin/v1/images/baoming.jpg"/>
								@endif
								<p>{{$val["activitytime"]}}</p>
							</div>
						</a>
					</li>
					@endforeach
				</ul>
				<p id="more" style="text-align: center; padding-top: 20px;">下拉加载更多</p>
			</div>
		</div>
		<script id="list" type="text/html">
	        <% for(var i = 0; i < data.length; i++){ %>
	        <li data-readid="<%= data[i]["id"] %>" data-url="<%= data[i]["url"] %>">
	            <p class="title"><%= data[i]["title"] %></p>
	            <p class="intro"><%= data[i]["intro"] %></p>
	        </li>
	        <li>
				<a href="/wechat/activity/detail/<%= data[i]["id"] %>">
					<img src="/uploads/<%= data[i]["titlepic"] %>"/>
				</a>
				<a href="/wechat/activity/detail/<%= data[i]["id"] %>">
					<div>
						<p><%= data[i]["title"] %></p>
						<% if(data[i]["participate"]===1 ){ %>
						<img src="http://www.lujiazuifc.com/skin/v1/images/baoming1.jpg"/>
						<% }else{ %>
						<img src="http://www.lujiazuifc.com/skin/v1/images/baoming.jpg"/>
						<% } %>
						<p><%= data[i]["activitytime"] %></p>
					</div>
				</a>
			</li>
	        <% } %>
        </script>
        <script type="text/javascript" src="https://skin.kankanews.com/v6/2016zt/hz/js/jquery.js"></script>
        <script type="text/javascript" src="//wlq.kankannews.com/wechat/scripts/public.js"></script>
        <script type="text/javascript" src="//wlq.kankannews.com/wechat/scripts/general.js"></script>
        <script type="text/javascript" src="//wlq.kankannews.com/wechat/scripts/template-web.js"></script>
        <script type="text/javascript">
            var config={
                url:window.location.origin+window.location.pathname+"?page=",
                listid:"list",
                wrapper:$(".white-wrapper ul")
            }
            loadData(config);
        </script>
</body>
</html>