<form id="pagerForm" method="post" action="<?php echo site_url('manage/list_order')?>">
	<input type="hidden" name="pageNum" value="<?php echo $pageNum;?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $numPerPage;?>" />
	<input type="hidden" name="num" value="<?php echo $num;?>" />
	<input type="hidden" name="status" value="<?php echo $status;?>" />
	<input type="hidden" name="orderField" value="<?php echo $this->input->post('orderField');?>" />
	<input type="hidden" name="orderDirection" value="<?php echo $this->input->post('orderDirection');?>" />
</form>

<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="<?php site_url('manage/list_order')?>" method="post">
		<div class="searchBar">
			<table class="searchContent" id="search_purchase_order">
				<tr>
					<td><label>订单编号：</label><input type="text" size="16" name="num" value="<?php echo $num;?>" /></td>
					<td><label>状态：</label>
					<select class="combox" name="status">
						<option value="">请选择状态</option>
						<option value="1" <?php if($status == 1) echo 'selected="selected"'?>>待付款</option>
						<option value="2" <?php if($status == 2) echo 'selected="selected"'?>>待发货</option>
						<option value="3" <?php if($status == 3) echo 'selected="selected"'?>>待收货</option>
						<option value="4" <?php if($status == 4) echo 'selected="selected"'?>>待评价</option>
						<option value="5" <?php if($status == 5) echo 'selected="selected"'?>>退款中</option>
						<option value="6" <?php if($status == 6) echo 'selected="selected"'?>>已退款</option>
						<option value="7" <?php if($status == 7) echo 'selected="selected"'?>>已评价</option>
					</select>
					</td>
				</tr>
			</table>
			<div class="subBar">
				<ul>
					<li><div class="button"><div class="buttonContent"><button id="clear_search">清除查询</button></div></div></li>
					<li><div class="buttonActive"><div class="buttonContent"><button type="submit">执行查询</button></div></div></li>
				</ul>
			</div>
		</div>
	</form>
</div>

<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="edit" href="<?php echo site_url('manage/edit_order/{id}')?>" target="navTab" rel="edit_order" warn="请选择一条记录" title="查看"><span>查看</span></a></li>
		</ul>
	</div>

	<div layoutH="116" id="list_warehouse_in_print">
	<table class="list" width="100%" targetType="navTab" asc="asc" desc="desc">
		<thead>
			<tr>
				<th>订单编号</th>
				<th width="160">付款交易号</th>
				<th width="90">状态</th>
				<th width="120">创建时间</th>
			</tr>
		</thead>
		<tbody>
            <?php          
                if (!empty($res_list)):
            	    foreach ($res_list as $row):		               
            ?>		            
            			<tr target="id" rel=<?php echo $row->id; ?>>
            				<td><?php echo $row->num;?></td>
            				<td><?php echo $row->order_num;?></td>
            				<td><?php
								if($row->status == 1) echo '<font color="red">待付款</font>';
								if($row->status == 2) echo '<font color="#ff4500">待发货</font>';
								if($row->status == 3) echo '<font color="#db7093">待收货</font>';
								if($row->status == 4) echo '<font color="#adff2f">待评价</font>';
								if($row->status == 5) echo '<font color="red">退款中</font>';
								if($row->status == 6) echo '<font color="green">已退款</font>';
								if($row->status == 7) echo '<font color="green">已评价</font>';
								?>
							</td>
            				<td><?php echo $row->cdate;?></td>
            			</tr>
            <?php 
            		endforeach;
            	endif;
            ?>
		</tbody>
	</table>
	</div>
	<div class="panelBar" >
		<div class="pages">
			<span>显示</span>
			<select name="numPerPage" class="combox" onchange="navTabPageBreak({numPerPage:this.value})">
				<option value="20" <?php echo $this->input->post('numPerPage')==20?'selected':''?>>20</option>
				<option value="50" <?php echo  $this->input->post('numPerPage')==50?'selected':''?>>50</option>
				<option value="100" <?php echo $this->input->post('numPerPage')==100?'selected':''?>>100</option>
				<option value="200" <?php echo $this->input->post('numPerPage')==200?'selected':''?>>200</option>
			</select>
			<span>条，共<?php  echo $countPage;?>条</span>
		</div>		
		<div class="pagination" targetType="navTab" totalCount="<?php echo $countPage;?>" numPerPage="<?php echo $numPerPage;?>" pageNumShown="10" currentPage="<?php echo $pageNum;?>"></div>
	</div>
</div>

<script>
	//清除查询
	$('#clear_search',navTab.getCurrentPanel()).click(function(){
		$(".searchBar",navTab.getCurrentPanel()).find("input").each(function(){
			$(this).val("");
		});
		$(".searchBar",navTab.getCurrentPanel()).find("select option").each(function(index){
			if($(this).val() == "")
			{
				$(this).attr("selected","selected");
			}
		});
	});
</script>