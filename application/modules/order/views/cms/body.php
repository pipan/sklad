<div id="filter-logo">
	<?php 
	if ($filter['is_set']){
		?>
		<img class="action-image-small" src="<?php echo assets_url()."images/filter_used.png"?>" onClick="$('#filter').toggle();">
		<?php 
	}
	else{
		?>
		<img class="action-image-small" src="<?php echo assets_url()."images/filter.png"?>" onClick="$('#filter').toggle();">
		<?php 
	}
	?>
</div>
<h2><?php echo $page_title;?></h2>
<div id="filter" style="display:none;">
	<form action="<?php echo cms_url()."order";?>" method="post">
		<div class="form-line">
			<label for="filter_accepted">select</label>
			<select id="filter_accepted" name="filter_accepted">
				<option value="0" <?php echo  set_select('filter_accepted', 0, $filter['accepted'] == 0);?>>all</option>
				<option value="1" <?php echo  set_select('filter_accepted', 1, $filter['accepted'] == 1);?>>accepted</option>
				<option value="2" <?php echo  set_select('filter_accepted', 2, $filter['accepted'] == 2);?>>not accepted yet</option>
			</select>
		</div>
		<div class="form-line">
			<input type="submit" name="filter" value="filter">
			<input type="submit" name="filter" value="reset">
		</div>
	</form>
</div>
<table cellspacing="0">
	<tr class="table-header">
		<td>Číslo objednávky</td>
		<td>Dátum</td>
		<td></td>
	</tr>
	<?php 
	if (isset($orders)){
		foreach ($orders as $o){
			if ($o['order_accepted'] == 0){
				$tr_class = " unaccepted";
			}
			else{
				$tr_class = " accepted";
			}
			?>
			<tr class="<?php echo "hover".$tr_class;?>">
				<td><a href="<?php echo base_url()."cms/order/view/".$o['id'];?>" title="Editovať"><?php echo $o['order_number'];?></a></td>
				<td><?php echo $o['order_date'];?></td>
				<td class="center">
					<a class="delete" href="<?php echo base_url()."cms/order/remove/".$o['id'];?>" title="Odstrániť"><img class="action-image" src="<?php echo assets_url()."images/delete.png";?>"/></a>
					<a href="<?php echo base_url()."cms/order/view/".$o['id'];?>" title="Editovať"><img class="action-image" src="<?php echo assets_url()."images/view.png";?>"/></a>
				</td>
			</tr>
			<?php
		}
	}
	?>
</table>
<?php 
page_div($page, $page_offset, $page_last, $page_link, true);
?>