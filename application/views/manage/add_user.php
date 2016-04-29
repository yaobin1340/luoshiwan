
<div class="pageContent">
    <form method="post" action="<?php echo site_url('manage/save_user');?>" class="pageForm required-validate" onsubmit="return iframeCallback(this, navTabAjaxDone);">
        <div class="pageFormContent" layoutH="55">
        	<fieldset style="width: 95%">
        	    <dl>
        			<dt>用户名：</dt>
        			<dd>
						<input type="text" class="required" name="username">
        			</dd>
        		</dl>

				<dl>
					<dt>真实姓名：</dt>
					<dd>
						<input type="text" class="required" name="rel_name">
					</dd>
				</dl>

				<dl>
					<dt>用户权限：</dt>
					<dd>
						<select class="combox" name="admin_group">
							<option value="2">库存管理</option>
							<option value="3">客服</option>
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


