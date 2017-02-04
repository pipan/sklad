<h2><?php echo $page_title;?></h2>
<div>
	<?php 
	if (($error = validation_errors()) != ""){
		echo "<div class='form-error'>".$error."</div>";
	}
	echo form_open("cms/privelage/add/".$privelage['id']);
	?>
		<div class="form-line">
			<label for="privelage_name">Názov</label>
			<input id="privelage_name" name="privelage_name" value="<?php echo set_value("privelage_name_", $privelage['privelage_name']);?>">
		</div>
		<div class="form-line">
			<label for="manage_user">spravovať používatelov</label>
			<input id="manage_user" name="manage_user" type="checkbox" value="1" <?php echo set_checkbox("manage_user", $privelage['manage_user'], $privelage['manage_user'] == 1);?>>
		</div>
		<div class="form-line">
			<label for="manage_privelage">spravovať oprávnenia</label>
			<input id="manage_privelage" name="manage_privelage" type="checkbox" value="1" <?php echo set_checkbox("manage_privelage", $privelage['manage_privelage'], $privelage['manage_privelage'] == 1);?>>
		</div>
		<div class="form-line">
			<label for="manage_inventory">spravovať inventár</label>
			<input id="manage_inventory" name="manage_inventory" type="checkbox" value="1" <?php echo set_checkbox("manage_inventory", $privelage['manage_inventory'], $privelage['manage_inventory'] == 1);?>>
		</div>
		<div class="form-line">
			<label for="manage_supplier">spravovať dodávateľov</label>
			<input id="manage_supplier" name="manage_supplier" type="checkbox" value="1" <?php echo set_checkbox("manage_supplier", $privelage['manage_supplier'], $privelage['manage_supplier'] == 1);?>>
		</div>
		<div class="form-line">
			<label for="manage_order">spravovať objednávky</label>
			<input id="manage_order" name="manage_order" type="checkbox" value="1" <?php echo set_checkbox("manage_order", $privelage['manage_order'], $privelage['manage_order'] == 1);?>>
		</div>
		<div class="form-line">
			<input class="save" name="save" type="submit" value="Uložiť">
		</div>
	</form>
</div>