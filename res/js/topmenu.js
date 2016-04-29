$(window).load(function(){
	var ht=$(".header").height();
	$(".top_menu").css("top",ht);
	$(".show_title").css("line-height",ht+"px");
	$(".main").css("margin-top",ht+10);
});
$(window).scroll(function(){
   var wht=$(".header").height();
   var sht=$(this).scrollTop();
   if(sht>=wht){ $(".top_menu").css({ "position":"fixed","top":"0","opacity":"1"}) }
   else {$(".top_menu").css({ "position":"absolute","top":"50px","opacity":"1"})}
	})
$(function(){
    $(".search_box_bottom li").each(function(index){
    	$(this).on('tap',function(){
    		$(this).addClass("on_order").siblings().removeClass("on_order");
    		$(this).find(".line").addClass("on");
    		$(this).siblings().find(".line").removeClass("on");
    		$(".order_info_list .list_show:eq("+index+")").addClass("on");
    		$(".order_info_list .list_show:eq("+index+")").siblings().removeClass("on");
    	});
    })
});