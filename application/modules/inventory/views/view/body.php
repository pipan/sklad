<div id="body-loading"><img src="<?php echo base_url()."assets/images/loading.gif";?>"></div>
<div id="body" class="body">
<?php 
if (isset($inventories)){
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
</div>
<form id="items-form" action="<?php echo base_url()."inventory/view/edit"?>" method="post">
	<div id="download-body" class="body">
		<div class="title">Beriem si zo skladu</div>
		<div id="download-items"></div>
		<div class="button">
			<input type="button" value="potvrdiť" onClick="$('#login-pop-up').show();$('#uid').focus();">
		</div>
		<div class="button">
			<input type="button" value="späť" onClick="$('#notifications .notification.active').trigger('click');">
		</div>
	</div>
</form>
<div id="warning-body" class="body">
<div class="title">Treba doplnit</div>
<?php 
if (isset($warnings)){
	$even = -1;
	foreach ($warnings as $w){
		$class = "body-item";
		if ($even == 1){
			$class .= " even-line";
		}
		?>
		<div id="<?php echo "warning-inv-".$w['id'];?>" class="body-item">
			<div class="body-item-title"><?php echo $w['inventory_name'];?></div>
			<div class="body-item-location"><?php echo $w['location'];?></div>
			<div class="body-item-amount"><?php echo $w['amount']."/".$w['min_amount'];?></div>
			<div class="body-item-description"><?php echo $w['description'];?></div>
		</div>
		<?php
		$even *= -1;
	}
}
?>
</div>