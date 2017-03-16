<h2><?php echo $page_title;?></h2>
<table cellspacing="0">
	<tr class="table-header">
		<td>Názov</td>
		<td></td>
	</tr>
	<?php 
	if (isset($categories)){
		foreach ($categories as $c){
			?>
			<tr class="hover">
				<td><a href="<?php echo base_url()."cms/inventory/category_add/".$c['id'];?>" title="Editovať"><?php echo $c['category_name'];?></a></td>
				<td class="right">
					<a class="delete" href="<?php echo base_url()."cms/inventory/category_remove/".$c['id'];?>" title="Odstrániť"><img class="action-image" src="<?php echo assets_url()."images/delete.png";?>"/></a>
					<a href="<?php echo base_url()."cms/inventory/category_add/".$c['id'];?>" title="Editovať"><img class="action-image" src="<?php echo assets_url()."images/settings.png";?>"/></a>
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