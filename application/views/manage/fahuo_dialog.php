<div class="pageContent">
    
    <?php 
    $attributes = array('class' => 'pageForm', 'onsubmit' => 'return validateCallback(this, dialogAjaxDone)'); 
    echo form_open('manage_login/check_login', $attributes);
    ?>
        <div class="pageFormContent" layoutH="58">
            <div class="unit">
                <label>选择快递：</label>
                <select class="combox" name="express">
                    <?php foreach($express as $v):?>
                        <option value="<?php echo $v->express?>"><?php echo $v->name?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="unit">
                <label>快递编号：</label>
                <input type="text" name="express" size="30" class="required"/>
            </div>
        </div>
        <div class="formBar">
            <ul>
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">提交</button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
            </ul>
        </div>
    <?php echo form_close();?>
    
</div>