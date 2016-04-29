<style type="text/css">
.file-box{ position:relative;width:300px}
.btn{ background-color:#FFF; border:1px solid #CDCDCD;height:21px; width:70px;}
.file{ position:absolute; top:0; right:80px; height:24px; filter:alpha(opacity:0);opacity: 0;width:270px }


/* 颜色输入框
------------------------------ */
.input_cxcolor{
	width:18px !important;
	height:18px !important;
	padding:0 !important;
	border:none !important;
	font-size:0 !important;
	line-height:0 !important;
	vertical-align:middle !important;
	cursor:pointer !important;
}

/* 颜色选择器
------------------------------ */
.cxcolor{display:none;position:absolute;z-index:10000;top:0px !important;}
.cxcolor .panel_hd{position:relative;width:220px;padding:0 5px;border:1px solid #000;border-bottom:none;background:#fff;font:normal 12px/20px "\5B8B\4F53";}
.cxcolor .panel_hd a{color:#999;text-decoration:none;}
.cxcolor .panel_hd a:hover{color:#333;}
.cxcolor .clear{position:absolute;top:0;right:5px;}
.cxcolor table{border-collapse:collapse;table-layout:fixed;empty-cells:show;}
.cxcolor td{position:static;width:10px;height:10px;padding:0;border:1px solid #000;font-size:0;line-height:0;cursor:pointer;}

.cxcolor_lock{display:none;position:absolute;top:0;left:0;background:#fff;z-index:9999;filter:alpha(opacity=0);opacity:0;}

</style>
<div class="pageContent">
    <form method="post" enctype="multipart/form-data" action="<?php echo site_url('manage/save_product');?>" class="pageForm required-validate" onsubmit="return iframeCallback(this, navTabAjaxDone);">
        <div class="pageFormContent" layoutH="55">
        	<fieldset style="width: 95%">
        	    <dl>
        			<dt>型号：</dt>
        			<dd>
						<?php if(!empty($id)):?>
							<input type="hidden" name="id" value="<?php if(!empty($id)) echo $id;?>">
							<?php echo $num;?>
						<?php else:?>
							<input name="num" type="text" class="required" value="<?php if(!empty($num)) echo $num;?>" />
						<?php endif;?>
        			</dd>
        		</dl>
        		
        		<dl>
        			<dt>颜色：</dt>
        			<dd>
        				<input name="color" type="text" class="required" value="<?php if(!empty($color)) echo $color;else echo '#000000'?>" />
						<input id="color_b" type="text" class="input_cxcolor">
						<input type="hidden" name="rgb" value="<?php if(!empty($rgb)) echo $rgb;else echo '#000000'?>">
        			</dd>
        		</dl>
        		<dl>
        			<dt>照片：</dt>
        			<dd>
        			<div class="file-box">
        			<input type="hidden" name="old_img" value="<?php if(!empty($pic)) echo $pic;?>" />
    				<input type='text' id='textfield' class='txt' value="<?php if(!empty($pic)) echo $pic;?>" />  
			 		<input type='button' class='btn' value='浏览...' />
					<input type="file" name="userfile" class="file" id="fileField"  onchange="document.getElementById('textfield').value=this.value" />
					</div>
        			</dd>
        		</dl>
				
        	    <dl class="nowrap">
        			<dt>图片预览：</dt>
        			<dd id="img"><?php if(!empty($pic)):?><img height="100px" src="<?php echo base_url().'uploadfiles/product/'.$pic;?>" /><?php endif;?></dd>
        		</dl>

        	</fieldset>

			<fieldset>
				<table class="list nowrap itemDetail" addButton="添加尺码" width="100%" >
					<thead>
					<tr>
						<th type="text" width="80" name="size[]"  fieldClass="required" size="30">尺码</th>
						<th type="file_class" name="h_stock[]" fieldClass="required" size="10" width="120">产线库存</th>
						<th type="file_class" name="stock[]" fieldClass="required" size="10" >实际库存</th>
						<th type="del" width="30">操作</th>
					</tr>
					</thead>
					<tbody class="tbody" id="file_list">
					<?php if(!empty($list)):?>
						<?php foreach($list as $k=>$v):?>
							<tr class="unitBox" id="<?php echo "olda".$v->id;?>">
								<td><input type="text" class="required" size='30' name="size[]" value="<?php echo $v->size?>"></td>
								<td><input type="text" class="required" size='10' name="h_stock[]" value="<?php echo $v->h_stock?>"></td>
								<td><input type="text" class="required" size='10' name="stock[]" value="<?php echo $v->stock?>"></td>
								<td><a class="btnDel" href="javascript:$('#olda<?php echo $v->id;?>').remove();void(0);"><span>删除</span></a></td>
							</tr>
						<?php endforeach;?>
					<?php else:?>
						<tr class="unitBox">
							<td><input type="text" class="required" size='30' name="size[]" value="34"></td>
							<td><input type="text" class="required" size='10' name="h_stock[]" value="0"></td>
							<td><input type="text" class="required" size='10' name="stock[]" value="0"></td>
							<td><a class="btnDel" href="javascript:void(0)"><span>删除</span></a></td>
						</tr>
						<tr class="unitBox">
							<td><input type="text" class="required" size='30' name="size[]" value="35"></td>
							<td><input type="text" class="required" size='10' name="h_stock[]" value="0"></td>
							<td><input type="text" class="required" size='10' name="stock[]" value="0"></td>
							<td><a class="btnDel" href="javascript:void(0)"><span>删除</span></a></td>
						</tr>
						<tr class="unitBox">
							<td><input type="text" class="required" size='30' name="size[]" value="36"></td>
							<td><input type="text" class="required" size='10' name="h_stock[]" value="0"></td>
							<td><input type="text" class="required" size='10' name="stock[]" value="0"></td>
							<td><a class="btnDel" href="javascript:void(0)"><span>删除</span></a></td>
						</tr>
						<tr class="unitBox">
							<td><input type="text" class="required" size='30' name="size[]" value="37"></td>
							<td><input type="text" class="required" size='10' name="h_stock[]" value="0"></td>
							<td><input type="text" class="required" size='10' name="stock[]" value="0"></td>
							<td><a class="btnDel" href="javascript:void(0)"><span>删除</span></a></td>
						</tr>
						<tr class="unitBox">
							<td><input type="text" class="required" size='30' name="size[]" value="38"></td>
							<td><input type="text" class="required" size='10' name="h_stock[]" value="0"></td>
							<td><input type="text" class="required" size='10' name="stock[]" value="0"></td>
							<td><a class="btnDel" href="javascript:void(0)"><span>删除</span></a></td>
						</tr>
						<tr class="unitBox">
							<td><input type="text" class="required" size='30' name="size[]" value="39"></td>
							<td><input type="text" class="required" size='10' name="h_stock[]" value="0"></td>
							<td><input type="text" class="required" size='10' name="stock[]" value="0"></td>
							<td><a class="btnDel" href="javascript:void(0)"><span>删除</span></a></td>
						</tr>
					<?php endif;?>
					</tbody>
				</table>
			</fieldset>

        </div>
        <div class="formBar">
    		<ul>
    			<li><div class="buttonActive"><div class="buttonContent"><button type="submit" class="icon-save">保存</button></div></div></li>
    			<li><div class="button"><div class="buttonContent"><button type="button" class="close icon-close">取消</button></div></div></li>
    		</ul>
        </div>
	</form>
</div>
<script>
	<?php if(!empty($rgb)):?>
	$('#color_b').cxColor({
		color:'<?php echo $rgb?>'
	});
	<?php else:?>
	$('#color_b').cxColor();
	<?php endif;?>

	(function(){
		var color = $('#color_b');
		color.bind('change', function(){
			$('[name="rgb"]').val(this.value)
		});
	})();
</script>
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

</script>

