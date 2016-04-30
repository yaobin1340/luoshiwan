<style type="text/css">
.file-box{ position:relative;width:300px}
.btn{ background-color:#FFF; border:1px solid #CDCDCD;height:21px; width:70px;}
.file{ position:absolute; top:0; right:80px; height:24px; filter:alpha(opacity:0);opacity: 0;width:270px }

</style>
<div class="pageContent">
    <form method="post" enctype="multipart/form-data" action="<?php echo site_url('manage/save_product');?>" class="pageForm required-validate" onsubmit="return iframeCallback(this, navTabAjaxDone);">
        <div class="pageFormContent" layoutH="55">
        	<fieldset style="width: 95%">
        	    <dl>
        			<dt>产品名称：</dt>
        			<dd>
						<input type="hidden" name="id" value="<?php if(!empty($id)) echo $id;?>">
						<input name="name" type="text" class="required" value="<?php if(!empty($name)) echo $name;?>" />
        			</dd>

        		</dl>

				<dl>
					<dt>类别：</dt>
					<dd><select class="combox" name="type">
							<?php foreach($list_type as $v):?>
								<option value="<?php echo $v->id?>" <?php if(!empty($type)){if($v->id==$type) {echo "selected";}} ?>><?php echo $v->name?></option>
							<?php endforeach;?>
						</select></dd>
				</dl>

				<dl>
					<dt>产品状态：</dt>
					<dd>
						<select name="status" class="combox">
							<option value="1" <?php if(!empty($status) && $status == 1) echo 'selected="selected"';?>>销售中</option>
							<option value="-1" <?php if(!empty($status) && $status == -1) echo 'selected="selected"';?>>下架</option>
						</select>
					</dd>
				</dl>

				<dl>
					<dt>推荐首页：</dt>
					<dd>
						<select name="recommend" class="combox">
							<option value="-1" <?php if(!empty($recommend) && $recommend == -1) echo 'selected="selected"';?>>否</option>
							<option value="1" <?php if(!empty($recommend) && $recommend == 1) echo 'selected="selected"';?>>是</option>
						</select>
					</dd>
				</dl>

        	</fieldset>

			<fieldset>
				<legend>产品图片</legend>
				<dl class="nowrap">
					<dt>
						<?php if(empty($folder)):?>
							<a class="tpsc" href="<?php echo site_url('manage/add_pics/'.date('YmdHis').'/1')?>" target="dialog" rel="add_pics" title="图片选择" width="800" height="370" mask=true>图片上传</a>
						<?php else: ?>
							<a class="tpsc" href="<?php echo site_url('manage/add_pics/'.$folder)?>" target="dialog" rel="add_pics" title="图片选择" width="800" height="370" mask=true>图片上传</a>
						<?php endif; ?>
						<input type="hidden" name="folder" value="<?php if(!empty($folder)) echo $folder;?>" id="folder">
						<input type="hidden" name="is_bg" value="<?php if(!empty($bg_pic)) echo $bg_pic;?>" id="is_bg">
					</dt>
				</dl>
				<dl class="nowrap" id="imageSection">
<!--					--><?php
//					if(!empty($house_img)):
//                        foreach ($house_img as $img):
//                            $pic = "/uploadfiles/pics/" . $folder . "/1/" .$img['pic_short'];
//                            $pic_short = $img['pic_short'];
//                ?>
<!--                <dt style="width: 250px; position:relative; margin-top:20px">-->
<!--                    <div style="position:absolute;filter:alpha(opacity=50);-moz-opacity:0.5;-khtml-opacity:0.5;opacity:0.5; top:95px; width:200px; height:24px; line-height:24px; left:6px; background:#000; font-size:12px; font-family:宋体; font-weight:lighter; text-align:center; ">-->
<!--                        <a href="javascript:void(0);" onclick="del_pic(this,1);" style="text-decoration:none; color:#fff">删除</a>&nbsp;&nbsp;&nbsp;&nbsp;-->
<!--                        <a href="javascript:void(0);" onclick="set_bg(this,1);" style="text-decoration:none; color:#fff">设为封面</a>-->
<!--                    </div>-->
<!--                    <div class="fengmian"></div>-->
<!--                    <img height="118" width="200" src="--><?php //echo $pic; ?><!--" style="border:1px solid #666;">-->
<!--                    <input type="hidden" size="22" name="pic_short[]" class="pic_short" value="--><?php //echo $pic_short; ?><!--">-->
<!--                </dt>-->
<!--                --><?php
//                        endforeach;
//                    endif;
//					?>
				</dl>

			</fieldset>

			<fieldset>
				<table class="list nowrap itemDetail" addButton="添加价格" width="100%" >
					<thead>
					<tr>
						<th type="text" width="80" name="size[]"  fieldClass="required" size="30">规格</th>
						<th type="file_class" name="price[]" fieldClass="required" size="10" width="120">价格</th>
						<th type="file_class" name="s_price[]" fieldClass="required" size="10" >运费</th>
						<th type="del" width="30">操作</th>
					</tr>
					</thead>
					<tbody class="tbody" id="file_list">
					<?php if(!empty($list)):?>
						<?php foreach($list as $k=>$v):?>
							<tr class="unitBox" id="<?php echo "olda".$v->id;?>">
								<td><input type="text" class="required" size='30' name="size[]" value="<?php echo $v->size?>"></td>
								<td><input type="text" class="required" size='10' name="price[]" value="<?php echo $v->price?>"></td>
								<td><input type="text" class="required" size='10' name="s_price[]" value="<?php echo $v->s_price?>"></td>
								<td><a class="btnDel" href="javascript:$('#olda<?php echo $v->id;?>').remove();void(0);"><span>删除</span></a></td>
							</tr>
						<?php endforeach;?>

					<?php endif;?>
					</tbody>
				</table>
			</fieldset>

			<fieldset>
				<legend>产品详情</legend>
				<dl class="nowrap">
					<dd><textarea class="editor" name="desc" rows="22" cols="100" upImgUrl="<?php echo site_url('manage/upload_pic')?>" upImgExt="jpg,jpeg,gif,png"  ><?php if(!empty($desc)) echo $desc;?></textarea></dd>
				</dl>
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
$("#fileField").change(function(){
	var objUrl = getObjectURL(this.files[0]);
	if (objUrl) {
		html = '<img height="100px" src="'+objUrl+'" />';
		$("#img").html(html) ;
	}
}) ;

$(function() {
	folder = $("#folder",navTab.getCurrentPanel()).val();
	if(folder != ''){
		callbacktime(folder, -1);
	}
	$(".tpsc",navTab.getCurrentPanel())
		.button()
		.click(function( event ) {
			event.preventDefault();
		});

	var a = $('[name="is_bg"]').val();
	var b = a.split("/");
	$('.pic_short').each(function(){
		alert($(this).val())
		if($(this).val() == b[2]){
			html_img = '<img src="<?php echo base_url().'res/images/fengmian.png';?>" style=" position:absolute; top:0px;">';
			$(this).parent().find('.fengmian').html(html_img);
		}
	});
});

function callbacktime(time,is_back){
	id = $("[name='id']",navTab.getCurrentPanel()).val();
	if (!id){
		$("#folder",navTab.getCurrentPanel()).val(time);
	}
	$.getJSON("<?php echo site_url('manage/get_pics')?>"+"/"+time + "?_=" +Math.random(),function(data){
		html = '';
		now_pic = [];
		$('.pic_short').each(function(index){
			now_pic[index] = $(this).val();
		});
		$.each(data.img,function(index,item){
			path = "<?php echo base_url().'uploadfiles/pics/';?>"+data.time +"/"+item;
			if($.inArray(item, now_pic) < 0){
				var a = $('[name="is_bg"]').val();
				var b = a.split("/");
				html+='<dt style="width: 250px; position:relative; margin-top:20px">';
				html+='<div style="position:absolute;filter:alpha(opacity=50);-moz-opacity:0.5;-khtml-opacity:0.5;opacity:0.5; top:95px; width:200px; height:24px; line-height:24px; left:6px; background:#000; font-size:12px; font-family:宋体; font-weight:lighter; text-align:center; ">';
				html+='<a href="javascript:void(0);" onclick="del_pic(this);" style="text-decoration:none; color:#fff">删除</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="set_bg(this, 1);" style="text-decoration:none; color:#fff">设为封面</a></div>';
				if(item == b[1]){
					html_img = '<img src="<?php echo base_url().'res/images/fengmian.png';?>" style=" position:absolute; top:0px;">';
					html+='<div class="fengmian">'+html_img+'</div>';
				}else{
					html+='<div class="fengmian"></div>';
				}

				html+='<img height="118" width="200" src="'+path +'" style="border:1px solid #666;">';
				html+='<input type="hidden" size="22" class="pic_short" value="'+item+'"></dt>';
			}
		});
		$("#imageSection",navTab.getCurrentPanel()).append(html);
	});

	//兼容chrome
	var isChrome = navigator.userAgent.toLowerCase().match(/chrome/) != null;
	if (isChrome)
		event.returnValue=false;
}

function set_bg(obj){
	pic = $("#folder",navTab.getCurrentPanel()).val() + '/' + $(obj).parent().parent().find('.pic_short').val();
	$(".fengmian",navTab.getCurrentPanel()).html('');
	$("[name='is_bg']").val(pic);
	html_img = '<img src="<?php echo base_url().'res/images/fengmian.png';?>" style=" position:absolute; top:0px;">';
	$(obj).parent().parent().find('.fengmian').html(html_img);
}

function del_pic(obj){
	folder = $("[name='folder']",navTab.getCurrentPanel()).val();
	current_pic = $(obj).parent().parent().find('input[name="pic_short[]"]').val();
	$.getJSON("<?php echo site_url('manage/del_pic')?>"+"/"+ folder + "/" + current_pic,function(data){
		if(data.flag == 1){
			$("#imageSection",navTab.getCurrentPanel()).find('input[name="pic_short[]"]').each(function(){
				if($(this).val() == data.pic){
					$(this).parent().remove();
				}
			});
		}else{
			alertMsg.warn("删除图片失败，请清理图片缓存并刷新标签页");
		}
	});
}

</script>

