<style type="text/css">
.file-box{ position:relative;width:300px}
.btn{ background-color:#FFF; border:1px solid #CDCDCD;height:21px; width:70px;}
.file{ position:absolute; top:0; right:80px; height:24px; filter:alpha(opacity:0);opacity: 0;width:270px }
</style>
<div class="pageContent">
    <form method="post" enctype="multipart/form-data" action="<?php echo site_url('manage/save_production');?>" class="pageForm required-validate" onsubmit="return iframeCallback(this, navTabAjaxDone);">
        <div class="pageFormContent" layoutH="55">
        	<fieldset style="width: 95%">
        	    <dl>
        			<dt>型号：</dt>
        			<dd>
						<input name="num" type="text" class="required" readonly value="<?php if(!empty($num)) echo $num;?>" /><span id="color"><?php if(!empty($rgb)) echo '<font color="'.$rgb.'">'.$color.'</font>'?></span>
						<?php if(empty($num)):?><a lookupgroup="" href="<?php echo site_url('manage/list_product_dialog');?>" class="btnLook">查找带回</a><?php endif;?>
        			</dd>
        		</dl>

        	</fieldset>

			<fieldset>
				<table class="list nowrap" width="100%" >
					<thead>
					<tr>
						<th width="80">尺码</th>
						<th width="120">现产线库存</th>
						<th width="120">现实际库存</th>
						<th width="120">本次下单</th>
						<?php if(!empty($list)):?>
							<th width="120">已生产</th>
						<?php endif;?>
						<th></th>
					</tr>
					</thead>
					<tbody class="tbody" id="size">
						<?php if(!empty($list)):?>
							<?php foreach($list as $k=>$v):?>
								<tr>
									<td><?php echo $v['size']?></td>
									<td><?php echo $v['s_h_stock']?></td>
									<td><?php echo $v['s_stock']?></td>
									<td><?php echo $v['h_stock']?></td>
									<td><?php echo $v['stock']?></td>
								</tr>
							<?php endforeach;?>
						<?php endif;?>
					</tbody>
				</table>
			</fieldset>

        </div>
        <div class="formBar">
    		<ul>
				<?php if(empty($list)):?>
    			<li><div class="buttonActive"><div class="buttonContent"><button type="submit" class="icon-save">保存</button></div></div></li>
				<?php endif;?>
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

function list_size(data,num,color,rgb){
	html=''
	data.forEach(function(item){
		html += '<tr><td>'+ item.size +'<input type="hidden" name="size[]" value="'+ item.size +'"></td><td>'+ item.h_stock +'</td><td>'+ item.stock +'</td><td><input type="text" class="required" size="30" name="h_stock[]" value="0"></td></tr>'
	});
	$("#size").html(html)
	$("[name='num']").val(num)
	color = '<font color="'+ rgb +'">' + color + '</font>'
	$("#color").html(color)

}
</script>

