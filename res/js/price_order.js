(function() {
	$(".cart_list li").each(function(index) {
		var price = $(this).find(".price_prt").text();
		$(this).find(".del_btn").click(function(){
			$(this).siblings(".edit_div").addClass("on");
		});
		$(this).find(".over").click(function(){
			$(this).parent().parent().removeClass("on");
		});
		$(this).find(".click_subtract").click(function() {
			var qty = $(this).siblings(".qty").val();
			if (qty != '1') {
				qty_new = parseInt(qty) - 1;
				$(this).siblings(".qty").val(qty_new);
			};
			$(this).parent().parent().siblings(".cart_price").find(".prt_num").text(qty_new);
            if($(this).parent().parent().siblings(".input_cart").find("[name='checkbox']").prop("checked")){
            	cart_sum();
            };           
		});
		$(this).find(".click_add").click(function() {
			var qty = $(this).siblings(".qty").val();
			if (qty != '99') {
				qty_new = parseInt(qty) + 1;
				$(this).siblings(".qty").val(qty_new);
			};
			$(this).parent().parent().siblings(".cart_price").find(".prt_num").text(qty_new);
            if($(this).parent().parent().siblings(".input_cart").find("[name='checkbox']").prop("checked")){
            	cart_sum();
            };			
		});
	});

	$(".btn_add").find(".click_subtract").click(function() {
		var qty = $(this).siblings(".qty").val();
		if (qty != '1') {
			qty_new = parseInt(qty) - 1;
			$(this).siblings(".qty").val(qty_new);
		};

	});
	$(".btn_add").find(".click_add").click(function() {
		var qty = $(this).siblings(".qty").val();
		if (qty != '99') {
			qty_new = parseInt(qty) + 1;
			$(this).siblings(".qty").val(qty_new);
		};
	});
	$(".cart_list li").find("[name='checkbox']").each(function(index) {
		$(this).click(function() {
			cart_sum();
			check_num();
			$(".input_cart_all input").prop("checked",false);
		})
	});

}());

function cart_sum() {
	var cart_sum = new Array();
	$(".cart_list li").find("[name='checkbox']").each(function(index) {
		var price_num = Number($(this).parent(".input_cart").siblings(".cart_price").find(".price_num").text());
		var prt_num = Number($(this).parent(".input_cart").siblings(".cart_price").find(".prt_num").text());
		if ($(this).prop("checked")) {
			cart_sum[index] = price_num * prt_num;
		} else {
			cart_sum[index] = 0
		}
	});
	var sum_length = cart_sum.length;
	for (var sum_num = i = 0; i < sum_length; i++) {
		sum_num += Number(cart_sum[i]);
	};
	$(".price_sum_num").text(sum_num.toFixed(1));
};
function check_num(){
	var c_num=$(".cart_list li").find("[name='checkbox']");
    var ch_num=0;
		for(var i=0;i<c_num.length;i++){
			if(c_num.eq(i).prop("checked")){
				ch_num++;
			};
		};
   $(".sl_ck").text(ch_num);
};