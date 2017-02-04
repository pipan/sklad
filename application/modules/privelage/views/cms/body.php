<h2><?php echo $page_title;?></h2>
<table cellspacing="0">
	<tr class="table-header">
		<td>Názov</td>
		<td></td>
	</tr>
	<?php 
	if (isset($privelages)){
		foreach ($privelages as $p){
			?>
			<tr class="hover">
				<td><a href="<?php echo base_url()."cms/privelage/add/".$p['id'];?>" title="Editovať"><?php echo $p['privelage_name'];?></a></td>
				<td class="center">
					<a class="delete" href="<?php echo base_url()."cms/privelage/remove/".$p['id'];?>" title="Odstrániť"><img class="action-image" src="<?php echo assets_url()."images/delete.png";?>"/></a>
					<a href="<?php echo base_url()."cms/privelage/add/".$p['id'];?>" title="Editovať"><img class="action-image" src="<?php echo assets_url()."images/settings.png";?>"/></a>
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