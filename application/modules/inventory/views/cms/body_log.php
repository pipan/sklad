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
	<form action="<?php echo cms_url()."inventory/log";?>" method="post">
		<div class="form-line">
			<label for="filter_inventory">select</label>
			<select id="filter_inventory"name="filter_inventory">
				<option value="0">all</option>
				<?php
				if (isset($filter['inventories'])){ 
					foreach ($filter['inventories'] as $i){
						?>
						<option value="<?php echo $i['id'];?>" <?php echo set_select('filter_inventory', $i['id'], $i['id'] == $filter['inventory_id']);?>><?php echo $i['inventory_name']?></option>
						<?php
					}
				}
				?>
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
		<td>Meno</td>
		<td>Vec</td>
		<td>Množstvo</td>
		<td>Poznámka</td>
		<td>Dátum</td>
	</tr>
	<?php 
	if (isset($log)){
		foreach ($log as $l){
			if($l['log_amount'] > 0){
				$class = "green";
			}
			else{
				$class = "red";
			}
			?>
			<tr class="hover">
				<td><?php echo $l['admin_name']." ".$l['admin_surname'];?></td>
				<td><?php echo $l['inventory_name'];?></td>
				<td class="<?php echo $class;?>"><?php echo $l['log_amount'];?></td>
				<td><?php echo $l['log_note'];?></td>
				<td><?php echo date("d.m.Y H:i:s", strtotime($l['log_date']));?></td>
			</tr>
			<?php
		}
	}
	?>
</table>
<?php 
page_div($page, $page_offset, $page_last, $page_link, true);
?>