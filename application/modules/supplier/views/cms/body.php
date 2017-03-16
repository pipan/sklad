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
	<form action="<?php echo cms_url()."supplier";?>" method="post">
		<div class="form-line">
			<label for="filter_remove">include removed</label>
			<input id="filter_remove" type="checkbox" name="filter_remove" value="1" <?php echo  set_checkbox('filter_remove', $filter['remove'], $filter['remove'] == 1);?>>
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
		<td></td>
	</tr>
	<?php 
	if (isset($suppliers)){
		foreach ($suppliers as $s){
			?>
			<tr class="hover">
				<td><a href="<?php echo base_url()."cms/supplier/add/".$s['id'];?>" title="Editovať"><?php echo $s['supplier_name'];?></a></td>
				<td class="center">
					<?php 
					if ($s['supplier_active'] == 1){
						?>
						<a class="delete" href="<?php echo base_url()."cms/supplier/remove/".$s['id'];?>" title="Odstrániť"><img class="action-image" src="<?php echo assets_url()."images/delete.png";?>"/></a>
						<?php 
					}
					else{
						?>
						<a href="<?php echo base_url()."cms/supplier/restore/".$s['id'];?>" title="Obnoviť"><img class="action-image" src="<?php echo assets_url()."images/restore.png";?>"/></a>
						<?php
					}
					?>
					<a href="<?php echo base_url()."cms/supplier/add/".$s['id'];?>" title="Editovať"><img class="action-image" src="<?php echo assets_url()."images/settings.png";?>"/></a>
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