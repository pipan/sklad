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
	<form action="<?php echo cms_url()."user";?>" method="post">
		<div class="form-line">
			<label for="filter_remove">include removed</label>
			<input id="filter_remove" type="checkbox" name="filter_remove" value="1" <?php echo  set_checkbox('filter_remove', 1, $filter['remove'] == 1);?>>
		</div>
		<div class="form-line">
			<input type="submit" name="filter" value="filter">
			<input type="submit" name="filter" value="reset">
		</div>
	</form>
</div>
<table cellspacing="0">
	<tr class="table-header">
		<td>UID</td>
		<td>Meno Priezvisko</td>
		<td>Oprávnenie</td>
		<td></td>
	</tr>
	<?php 
	if (isset($users)){
		foreach ($users as $u){
			?>
			<tr class="hover">
				<td><?php echo $u['uid'];?></td>
				<td><a href="<?php echo base_url()."cms/user/edit/".$u['id'];?>" title="Editovať"><?php echo $u['admin_name']." ".$u['admin_surname'];?></a></td>
				<td><?php echo $u['privelage_name'];?></td>
				<td class="center">
					<?php 
					if ($u['active'] == 1){
						?>
						<a class="delete" href="<?php echo base_url()."cms/user/remove/".$u['id'];?>" title="Odstrániť"><img class="action-image" src="<?php echo assets_url()."images/delete.png";?>"/></a>
						<?php 
					}
					else{
						?>
						<a href="<?php echo base_url()."cms/user/restore/".$u['id'];?>" title="Obnov"><img class="action-image" src="<?php echo assets_url()."images/restore.png";?>"/></a>
						<?php
					}
					?>
					<a href="<?php echo base_url()."cms/user/edit/".$u['id'];?>" title="Editovať"><img class="action-image" src="<?php echo assets_url()."images/settings.png";?>"/></a>
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