
<div class="pageContent">
    <form method="post" enctype="multipart/form-data" action="<?php echo site_url('manage/save_product');?>" class="pageForm required-validate" onsubmit="return iframeCallback(this, navTabAjaxDone);">
        <div class="pageFormContent" layoutH="55">
        	<fieldset style="width: 95%">
        	    <dl>
        			<dt>订单编号：</dt>
        			<dd>
						<input type="hidden" name="id" value="<?php if(!empty($id)) echo $id;?>">
						<?php echo $head->num;?>
        			</dd>
        		</dl>
				<dl>
					<dt>付款交易号：</dt>
					<dd>
						<?php echo $head->order_num;?>
					</dd>
				</dl>

				<dl>
					<dt>创建时间：</dt>
					<dd>
						<?php echo $head->cdate;?>
					</dd>
				</dl>

				<dl>
					<dt>付款时间：</dt>
					<dd>
						<?php echo $head->pdate;?>
					</dd>
				</dl>

				<dl>
					<dt>付款交易号：</dt>
					<dd>
						<?php echo $head->order_num;?>
					</dd>
				</dl>

				<dl>
					<dt>状态：</dt>
					<dd>
						<?php
						if($head->status == 1) echo '<font color="red">待付款</font>';
						if($head->status == 2) echo '<font color="#ff4500">待发货</font>';
						if($head->status == 3) echo '<font color="#db7093">待收货</font>';
						if($head->status == 4) echo '<font color="#adff2f">待评价</font>';
						if($head->status == 5) echo '<font color="red">退款中</font>';
						if($head->status == 6) echo '<font color="green">已退款</font>';
						if($head->status == 7) echo '<font color="green">已评价</font>';
						?>
					</dd>
				</dl>

				<dl class="nowrap">
					<dt>送货地址：</dt>
					<dd>
						<?php echo $head->province_name.$head->city_name.$head->area_name.$head->address.'&nbsp;'.$head->name.'&nbsp;'.$head->phone.'&nbsp;'.$head->zip;?>
					</dd>
				</dl>


        	</fieldset>


			<fieldset>
				<table class="list nowrap"  width="100%" >
					<thead>
					<tr>
						<th width="80" >产品名称</th>
						<th width="80" >规格</th>
						<th width="120">价格</th>
						<th width="120">数量</th>
						<th width="80" >运费</th>
						<th width="80" >小计</th>
					</tr>
					</thead>
					<tbody class="tbody">
					<?php if(!empty($list)):?>
						<?php foreach($list as $k=>$v):?>
							<tr class="unitBox">
								<td><?php echo $v->product_name?></td>
								<td><?php echo $v->size?></td>
								<td><?php echo $v->price?></td>
								<td><?php echo $v->qty?></td>
								<td><?php echo $v->s_price?></td>
								<td class="xiaoji"><?php echo $v->price * $v->qty + $v->s_price?></td>
							</tr>
						<?php endforeach;?>
						<tr>
							<td colspan="5">总计</td>
							<td id="total">总计</td>
						</tr>

					<?php endif;?>
					</tbody>
				</table>
			</fieldset>
			<?php if($head->status == 2):?>
			<ul>
				<a class="fahuo" href="<?php echo site_url('manage/fahuo/'.$head->id)?>" target="dialog" rel="add_pics" title="发货" width="800" height="370" mask=true>发货</a>
			</ul>
			<?php endif;?>

			<?php if($head->status > 2):?>
				<fieldset>
					<legend>物流信息</legend>
					<dl>
						<dt>发货时间：</dt>
						<dd>
							<?php echo $head->pdate;?>
						</dd>
					</dl>
					<dl>
						<dt>快递物流：</dt>
						<dd>
							<?php echo $head->express_name;?>
						</dd>
					</dl>
					<dl>
						<dt>运单号：</dt>
						<dd>
							<?php echo $head->express_num;?>
						</dd>
					</dl>

					<table class="list nowrap"  width="100%" >
						<thead>
						<tr>
							<th width="60" >时间</th>
							<th width="80" >描述</th>
						</tr>
						</thead>
						<tbody class="tbody">
						<?php if(!empty($express)):?>
							<?php foreach($express as $k=>$v):?>
								<tr class="unitBox">
									<td><?php echo $v['time']?></td>
									<td><?php echo $v['context']?></td>
								</tr>
							<?php endforeach;?>
						<?php endif;?>
						</tbody>
					</table>
				</fieldset>
			<?php endif;?>

        </div>
        <div class="formBar">
    		<ul>
    			<li><div class="button"><div class="buttonContent"><button type="button" class="close icon-close">取消</button></div></div></li>
    		</ul>
        </div>
	</form>
</div>
<script>
	var total = 0
	$('.xiaoji').each(function(){
		total += parseInt($(this).html());
	})
	$("#total").html(total)
	$(".fahuo",navTab.getCurrentPanel())
		.button()
		.click(function( event ) {
			event.preventDefault();
		});
</script>


