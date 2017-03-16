<h2><?php echo $page_title;?></h2>
<div>
	<?php 
	if (($error = validation_errors()) != ""){
		echo "<div class='form-error'>".$error."</div>";
	}
	echo form_open("cms/supplier/add/".$supplier['id']);
	?>
		<div class="form-line">
			<label for="supplier_name">Názov</label>
			<input id="supplier_name" name="supplier_name" maxlength="30" value="<?php echo set_value("supplier_name_", $supplier['supplier_name']);?>">
		</div>
		<div class="form-line">
			<label for="supplier_email">Email</label>
			<input id="supplier_email" name="supplier_email" maxlength="100" type="input" value="<?php echo set_value("supplier_email", $supplier['supplier_email']);?>">
		</div>
		<div class="form-line">
			<label for="supplier_phone">Telefón</label>
			<input id="supplier_phone" name="supplier_phone" maxlength="20" type="input" value="<?php echo set_value("supplier_phone", $supplier['supplier_phone']);?>">
		</div>
		<div class="form-line">
			<label for="supplier_web">Web</label>
			<input id="supplier_web" name="supplier_web" maxlength="100" type="input" value="<?php echo set_value("supplier_web", $supplier['supplier_web']);?>">
		</div>
		<div class="form-line">
			<label for="supplier_contact_person">Kontaktná osoba</label>
			<input id="supplier_contact_person" name="supplier_contact_person" maxlength="100" type="input" value="<?php echo set_value("supplier_contact_person", $supplier['supplier_contact_person']);?>">
		</div>
		<div class="form-line">
			<label for="supplier_priority">Priorita</label>
			<input id="supplier_priority" name="supplier_priority" maxlength="100" type="input" value="<?php echo set_value("supplier_priority", $supplier['supplier_priority']);?>">
		</div>
		<div class="form-line">
			<input class="save" name="save" type="submit" value="Uložiť">
		</div>
	</form>
</div>