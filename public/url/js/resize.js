// JavaScript Document

//当屏幕大小小于1280时进行响应式

function screenWidth(){
	var w1 = document.documentElement.clientWidth || document.body.clientWidth;
	if(w1<1255)
	{
		$(".inner").addClass("inner1000");	
	}else{
		$(".inner").removeClass("inner1000");
	}	
}
//右侧工具栏响应式
function screenWidth2(){
	var w2 = document.documentElement.clientWidth || document.body.clientWidth;
	if(w2<1024)
	{
		$(".toolbar>.tool-inner").animate({right:-40});	
	}else{
		$(".toolbar>.tool-inner").animate({right:0});	
	}	
}
$(function(){
	//初始化浏览器窗口宽度
	//screenWidth();
	screenWidth2();
	$(window).resize(screenWidth);
	$(window).resize(screenWidth2);	
	
	//当屏幕大小小于1280时进行响应式
	$(".toolbar1>ul>li").hover(function(){
		$(this).addClass("hover");
		$(this).find(".txtitem").show().animate({right:35,opacity:1},300);	
	},function(){
		$(this).removeClass("hover");
		$(this).find(".txtitem").animate({right:50,opacity:0},300,function(){
			$(this).hide();
		});
	})
	
	
})