
<style type="text/css">
    .file-box{ position:relative;width:340px}
    .btn{ background-color:#FFF; border:1px solid #CDCDCD;height:21px; width:70px;}
    .file{ position:absolute; top:0; right:80px; height:24px; filter:alpha(opacity:0);opacity: 0;width:300px }
</style>
<div class="pageContent">
    <form method="post" enctype="multipart/form-data" action="<?php echo site_url('manage/save_fahuo');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
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
                <input type="text" name="express_num" size="30" class="required"/>
                <input type="hidden" name="id" value="<?php echo $id?>"/>
            </div>
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

</script>