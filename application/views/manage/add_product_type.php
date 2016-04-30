<div class="pageContent">
    <form method="post" action="<?php echo site_url('manage/save_product_type');?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone);">
        <div class="pageFormContent" layoutH="65">
        	<fieldset>
        		<legend>产品类别</legend>
        	    <dl style="width: 250px">
        			<dt style="width: 80px">中文标题：</dt>
        			<dd style="width: 100px"><input type="hidden" name="id" value="<?php if(!empty($id)) echo $id;?>"><input name="name" type="text" class="required" value="<?php if(!empty($name)) echo $name;?>" /></dd>
        		</dl>
        		<dl style="width: 250px">
        			<dt style="width: 80px">上级目录：</dt>
        			<dd style="width: 100px">
        				<select class="combox" name="parent_id">
        				<option value="">无上级目录</option>
        				<?php foreach($type_list as $k=>$v):?>
        				<option value="<?php echo $v['id'];?>" <?php if(!empty($parent_id) && $parent_id == $v['id']) echo 'selected="selected"'?>><?php echo $v['name']?></option>
        				<?php endforeach;?>
        				</select>
        			</dd>
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


