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
	<form action="<?php echo cms_url()."inventory";?>" method="post">
		<div class="form-line">
			<label for="filter_search">search</label>
			<input id="filter_search" type="text" name="filter_search" value="<?php echo set_value('filter_search', $filter['search']);?>">
		</div>
		<div class="form-line">
			<label for="filter_remove">include removed</label>
			<input id="filter_remove" type="checkbox" name="filter_remove" value="1" <?php echo  set_checkbox('filter_remove', $filter['remove'], $filter['remove'] == 1);?>>
		</div>
		<div>
			<label for="filter_category_id">category</label>
			<select id="filter_category_id" name="filter_category_id">
				<option value="">--select--</option>
				<?php 
				if (isset($filter['categories'])){
					foreach ($filter['categories'] as $c){
						?>
						<option value="<?php echo $c['id']?>" <?php echo set_select('filter_category_id', $c['id'], $filter['category_id'] == $c['id']);?>><?php echo $c['category_name'];?></option>
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
		<td>Názov</td>
		<td>Množstvo</td>
		<td>Min množstvo</td>
		<td></td>
	</tr>
	<?php 
	if (isset($inventories)){
		foreach ($inventories as $i){
			?>
			<tr class="hover">
				<td><a href="<?php echo base_url()."cms/inventory/add/".$i['id'];?>" title="Editovať"><?php echo $i['inventory_name'];?></a></td>
				<td><?php echo $i['amount'];?></td>
				<td><?php echo $i['min_amount'];?></td>
				<td class="right">
					<?php 
					if ($i['inventory_active'] == 1){
						?>
						<a class="delete" href="<?php echo base_url()."cms/inventory/remove/".$i['id'];?>" title="Odstrániť"><img class="action-image" src="<?php echo assets_url()."images/delete.png";?>"/></a>
						<?php 
					}
					else{
						?>
						<a href="<?php echo base_url()."cms/inventory/restore/".$i['id'];?>" title="Obnoviť"><img class="action-image" src="<?php echo assets_url()."images/restore.png";?>"/></a>
						<?php
					}
					?>
					<a href="<?php echo base_url()."cms/inventory/add/".$i['id'];?>" title="Editovať"><img class="action-image" src="<?php echo assets_url()."images/settings.png";?>"/></a>
					<a href="<?php echo base_url()."cms/inventory/edit/".$i['id'];?>" title="Editovať množstvo"><img class="action-image" src="<?php echo assets_url()."images/edit.png";?>"/></a>
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