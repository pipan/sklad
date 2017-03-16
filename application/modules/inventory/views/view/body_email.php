<?php
if (isset($warnings)){
	foreach ($taken as $t){
		?>
		<div>
			<strong><?php echo $t['inventory_name']."[".$t['amount']."/".$t['min_amount']."]";?></strong>
		</div>
		<?php
	} 
	if (sizeof($taken) > 0){
		?><br/><?php
	}
	foreach ($warnings as $w){
		if (!in_array($w['id'], $notify)){
		?>
			<div>
				<?php echo $w['inventory_name']."[".$w['amount']."/".$w['min_amount']."]";?>?>
			</div>
		<?php
		}
	}
}	
?>