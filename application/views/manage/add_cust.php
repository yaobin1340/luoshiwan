
<div class="pageContent">
    <form method="post" action="<?php echo site_url('manage/save_cust');?>" class="pageForm required-validate" onsubmit="return iframeCallback(this, navTabAjaxDone);">
        <div class="pageFormContent" layoutH="55">
        	<fieldset style="width: 95%">
        	    <dl>
        			<dt>姓名：</dt>
        			<dd>
						<input type="text" class="required" name="name">
        			</dd>
        		</dl>

				<dl>
					<dt>手机：</dt>
					<dd>
						<input type="text" class="required" name="phone">
					</dd>
				</dl>

				<dl class="nowrap">
					<dt>备注：</dt>
					<dd>
						<textarea name="remark"></textarea>
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


