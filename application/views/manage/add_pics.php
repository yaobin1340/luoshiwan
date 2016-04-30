<div id="uploader">
    <p>Your browser doesn't have Flash, Silverlight or HTML5 support.</p>
</div>

<form onsubmit="callBackToNavtab();" id="form">
<input type="hidden" id="time" value="<?php echo $time;?>">
<input type="hidden" id="save_url" value="<?php echo site_url('manage/save_pics/'.$time);?>">
        <div class="formBar">
    		<ul>
    			<li><div class="buttonActive"><div class="buttonContent"><button type="submit" class="icon-save">确认</button></div></div></li>
    			<li><div class="button"><div class="buttonContent"><button type="button" class="close icon-close">取消</button></div></div></li>
    		</ul>
        </div>
</form>
<script type="text/javascript">
// Convert divs to queue widgets when the DOM is ready
$(document).ready(function() {
	$("#uploader",$.pdialog.getCurrent()).plupload({
		runtimes : 'html5,flash,silverlight',
		url : "<?php echo site_url('manage/save_pics/'.$time);?>",
        max_file_size : '50mb',
        
        //chunk_size: '10mb',
 
        // Resize images on clientside if we can
        resize : {
//            width : 200,
//            height : 200,
//            quality : 90,
            crop: true // crop to exact dimensions
        },
        file_data_name: 'userfile',
        // Specify what files to browse for
        filters : [
        ],

        filters: {
        	  mime_types : [ //只允许上传图片和zip文件
        	  	{title : "Image files", extensions : "jpg,gif,png"}
        	  ],
        	  max_file_size : '1024kb', //最大只能上传400kb的文件
        	  prevent_duplicates : true //不允许选取重复文件
        },
 
        // Rename files by clicking on their titles
        rename: true,
         
        // Sort files
        sortable: true,
 
        // Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
        dragdrop: true,
 
        // Views to activate
        views: {
            list: true,
            thumbs: true, // Show thumbs
            active: 'thumbs'
        },
 

		// Flash settings
		flash_swf_url : '<?php echo base_url();?>plupload/js/Moxie.swf',

		// Silverlight settings
		silverlight_xap_url : '<?php echo base_url();?>plupload/js/Moxie.xap'

	});
});



function callBackToNavtab(){ 
	if ($('#uploader').plupload('getFiles').length > 0) {
		var time = $("#time",$.pdialog.getCurrent()).val();
		$.pdialog.close('add_pics');
		callbacktime(time,1);
	} else {
		alertMsg.warn("请至少选择一个图片上传");
	}
}  



</script>

