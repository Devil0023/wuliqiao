/*scroll*/
function loadData(config){
	var isScroll = false;
	var complete = false;
	var page=2;
	var lasttime=config.wrapper.find("li").last().data("time");
	var scrollEvent = "onscroll" in document.documentElement ? "scroll" : "touchmove";
	var loadText = '加载中...';
	var finishText= '下拉加载更多';
	var endText= '没有更多了';

	function viewData() {
	    var e = 0,
	        l = 0,
	        i = 0,
	        g = 0,
	        f = 0,
	        m = 0;
	    var j = window,
	        h = document,
	        k = h.documentElement;
	    e = k.clientWidth || h.body.clientWidth || 0;
	    l = j.innerHeight || k.clientHeight || h.body.clientHeight || 0;
	    g = h.body.scrollTop || k.scrollTop || j.pageYOffset || 0;
	    i = h.body.scrollLeft || k.scrollLeft || j.pageXOffset || 0;
	    f = Math.max(h.body.scrollWidth, k.scrollWidth || 0);
	    m = Math.max(h.body.scrollHeight, k.scrollHeight || 0, l);
	    return {
	        scrollTop: g,
	        scrollLeft: i,
	        documentWidth: f,
	        documentHeight: m,
	        viewWidth: e,
	        viewHeight: l
	    };
	}

	function render() {
	    if (complete) return
	    $("#more").html(loadText);
	   	lasttime=config.wrapper.find("li").last().data("time");
	    //alert(config.url+lasttime);
	    $.getJSON(config.url+page).then(
	        function(data) {
	        	//alert(JSON.stringify(data));
	        	try{

	        		config.wrapper.append(template(config.listid, data));
	        		if (data.data.length==0) {
		                complete = true;
		                $("#more").html(endText);
		            } else {
		               complete = false;
		                $("#more").html(finishText);
		            }  

	        	}catch(e){
		           	$("#more").html(finishText);
	           	}
	           	isScroll=false;
	           	page = page+1;
	           	console.log(page);
	        }, function(e) {
	        	//alert(config.url);
	        	//alert(e.readyState+'|'+e.responseText);
	        	$("#more").html(finishText);
	        	isScroll=false;
	        	console.log(page,e.readyState+'|'+e.responseText);
	            //console.log( "$.get failed!" );
	        }
	    );
	}

	$(window).on(scrollEvent, function() {
	    var vd = viewData();
	    var ald = 0;
	    if (vd.viewHeight + vd.scrollTop + ald >= vd.documentHeight) {
	        if (!isScroll) {
	        	isScroll=true;
	            render();
	        }
	    }
	});
}