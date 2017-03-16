<?php
if (isset($search_count) && isset($search) && $search != ""){
	?>
	<div class="title">
		 <?php echo "Výsledky pre '".$search."'";?>
	</div>
	<?php
}
if (isset($inventories)){
	$even = -1;
	foreach ($inventories as $i){
		?>
		<div id="<?php echo "inv-".$i['id'];?>" class="body-item">
			<div class="body-item-title"><?php echo $i['inventory_name'];?></div>
			<div class="body-item-amount"><?php echo $i['amount'];?></div>
			<div class="body-item-location"><?php echo $i['location'];?></div>
			<div class="body-item-description"><?php echo $i['description'];?></div>
		</div>
		<?php
	}
}
?>