<h2><?php echo $page_title;?></h2>
<div>
	<?php 
	if (($error = validation_errors()) != ""){
		echo "<div class='form-error'>".$error."</div>";
	}
	echo form_open("cms/inventory/category_add/".$category['id']);
	?>
		<div class="form-line">
			<label for="category_name">Názov</label>
			<input id="category_name" name="category_name" maxlength="50" value="<?php echo set_value("category_name", $category['category_name']);?>">
		</div>
		<div class="form-line">
			<input class="save" name="save" type="submit" value="Uložiť">
		</div>
	</form>
</div>