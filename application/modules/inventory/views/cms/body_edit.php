<h2><?php echo $page_title;?></h2>
<div>
	<?php 
	if (($error = validation_errors()) != ""){
		echo "<div class='form-error'>".$error."</div>";
	}
	echo form_open("cms/inventory/edit/".$inventory['id']);
	?>
		<div class="form-line">
			<label for="amount">Upraviť množstvo o</label>
			<input id="amount" name="amount" value="<?php echo set_value("amount", 0);?>">
		</div>
		<div class="form-line">
			<label for="note">Poznámka</label>
			<textarea id="note" name="note" maxlength="255"><?php echo set_value("note", "");?></textarea>
		</div>
		<div class="form-line">
			<input class="save" name="save" type="submit" value="Uložiť">
		</div>
	</form>
</div>