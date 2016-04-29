<style type="text/css">
.file-box{ position:relative;width:300px}
.btn{ background-color:#FFF; border:1px solid #CDCDCD;height:21px; width:70px;}
.file{ position:absolute; top:0; right:80px; height:24px; filter:alpha(opacity:0);opacity: 0;width:270px }
</style>
<div class="pageContent">
    <form method="post" enctype="multipart/form-data" action="<?php echo site_url('manage/stock_in');?>" class="pageForm required-validate" onsubmit="return iframeCallback(this, navTabAjaxDone);">
        <div class="pageFormContent" layoutH="55">
        	<fieldset style="width: 95%">
        	    <dl>
        			<dt>型号：</dt>
        			<dd>
						<input name="num" type="text" class="required" readonly value="<?php if(!empty($num)) echo $num;?>" /><span id="color"><?php if(!empty($rgb)) echo '<font color="'.$rgb.'">'.$color.'</font>'?></span>
						<a lookupgroup="" href="<?php echo site_url('manage/list_production_dialog');?>" class="btnLook">查找带回</a>
        			</dd>
        		</dl>

        	</fieldset>

			<fieldset>
				<table class="list nowrap" width="100%" >
					<thead>
					<tr>
						<th width="80">尺码</th>
						<th width="120">订单数</th>
						<th width="120">已入库数</th>
						<th width="50">可入库数</th>
						<th width="120">本次入库数</th>
						<th></th>
					</tr>
					</thead>
					<tbody class="tbody" id="size">

					</tbody>
				</table>
			</fieldset>

        </div>
        <div class="formBar">
    		<ul>
    			<li><div class="buttonActive"><div class="buttonContent"><button type="submit" class="icon-save" onclick="return check_input();">保存</button></div></div></li>
    			<li><div class="button"><div class="buttonContent"><button type="button" class="close icon-close">取消</button></div></div></li>
    		</ul>
        </div>
	</form>
</div>
<script>
$("#fileField").change(function(){
	var objUrl = getObjectURL(this.files[0]);
	if (objUrl) {
		html = '<img height="100px" src="'+objUrl+'" />';
		$("#img").html(html) ;
	}
}) ;
//建立一個可存取到該file的url
function getObjectURL(file) {
	var url = null ; 
	if (window.createObjectURL!=undefined) { // basic
		url = window.createObjectURL(file) ;
	} else if (window.URL!=undefined) { // mozilla(firefox)
		url = window.URL.createObjectURL(file) ;
	} else if (window.webkitURL!=undefined) { // webkit or chrome
		url = window.webkitURL.createObjectURL(file) ;
	}
	return url ;
}

function list_production(data,num,color,rgb){
	html=''
	data.forEach(function(item){
		html += '<tr><td>'+ item.size +'<input type="hidden" name="size[]" value="'+ item.size +'"><input type="hidden" name="h_id[]" value="'+ item.h_id +'"></td><td>'+ item.h_stock +'</td><td>'+ item.stock +'</td><td onclick="input_in(this)"><a herf="javascript:;">'+ (item.h_stock - item.stock) +'</a></td><td><input type="text" class="required" size="30" name="qty[]" value="0"></td></tr>'
	});
	$("#size").html(html)
	$("[name='num']").val(num)
	color = '<font color="'+ rgb +'">' + color + '</font>'
	$("#color").html(color)

}
function input_in(obj){
	$(obj).next().find('input').val($(obj).find('a').html());
}

function check_input(){
	all_qty = 0
	$("#size").find('tr').each(function(){
		qty = $(this).find('[name="qty[]"]').val()
		all_qty = parseInt(all_qty) + parseInt(qty);
		limit_qty = $(this).find('a').html()
		if(qty < 0){
			alertMsg.warn('请填写正确的数字');
			return false
		}
		if(limit_qty < qty){
			alertMsg.warn('入库数量不能大于可入库数量');
			return false
		}
	})
	if(all_qty <= 0){
		alertMsg.warn('总入库数量不能等于0 ');
		return false
	}
}
</script>

