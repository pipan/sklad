
<h2><?php echo $page_title;?></h2>
<div>
	<div class="form-line">
		<label>excel</label>
		<a href="<?php echo base_url()."cms/order/download/".$order['id'];?>" target="_blank"><?php echo $order['order_number'].".xlsx";?></a>
	</div>
	<?php 
	if ($order['order_accepted'] == 0){
		if (($error = validation_errors()) != ""){
			echo "<div class='form-error'>".$error."</div>";
		}
		echo form_open("cms/order/view/".$order['id']);
		if (isset($iio)){
			foreach($iio as $o){
				?>
				<div class="form-line">
					<label for="<?php echo "inv_".$o['id'];?>"><?php echo $o['inventory_name'];?></label>
					<input id="<?php echo "inv_".$o['id'];?>" type="text" name="<?php echo "inv_".$o['id'];?>" value="<?php echo set_value('inv_'.$o['id'], $o['order_amount_orig']);?>">
				</div>
				<?php
			}
		}
		?>
			<div class="form-line">
				<label for="order_accepted">prijatá</label>
				<input id="order_accepted" type="checkbox" name="order_accepted" value="1" <?php echo set_checkbox('order_accepted', 1, $order['order_accepted'] == 1);?>>
			</div>
			<div class="form-line">
				<input type="submit" name="edit" value="edit">
			</div>
		</form>
		<?php 
	}
	?>
</div>