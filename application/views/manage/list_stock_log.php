<form id="pagerForm" method="post" action="<?php echo site_url('manage/list_production')?>">
	<input type="hidden" name="pageNum" value="<?php echo $pageNum;?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $numPerPage;?>" />
	<input type="hidden" name="num" value="<?php echo $num;?>" />
	<input type="hidden" name="orderField" value="<?php echo $this->input->post('orderField');?>" />
	<input type="hidden" name="orderDirection" value="<?php echo $this->input->post('orderDirection');?>" />
</form>

<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="<?php site_url('manage/list_production')?>" method="post">
		<div class="searchBar">
			<table class="searchContent" id="search_purchase_order">
				<tr>
					<td><label>型号：</label><input type="text" size="16" name="num" value="<?php echo $num;?>" /></td>
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
			<li><a class="add" href="<?php echo site_url('manage/add_stock_in')?>" target="navTab" rel="add_production" title="新建"><span>新建</span></a></li>
		</ul>
	</div>

	<div layoutH="116" id="list_warehouse_in_print">
	<table class="list" width="100%" targetType="navTab" asc="asc" desc="desc">
		<thead>
			<tr>
				<th>型号</th>
				<th width="90">颜色</th>
				<th width="90">尺码</th>
				<th width="100">入库数量</th>
				<th width="120">入库时间</th>
			</tr>
		</thead>
		<tbody>
            <?php          
                if (!empty($res_list)):
            	    foreach ($res_list as $row):		               
            ?>		            
            			<tr>
            				<td><?php echo $row->num;?></td>
            				<td><font color="<?php echo $row->rgb;?>"><?php echo $row->color;?></font></td>
            				<td><?php echo $row->size;?></td>
            				<td><?php echo $row->qty;?></td>
            				<td><?php echo $row->cdate?></td>
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