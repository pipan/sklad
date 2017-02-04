<h2><?php echo $page_title;?></h2>
<div>
	<?php 
	if (($error = validation_errors()) != ""){
		echo "<div class='form-error'>".$error."</div>";
	}
	echo form_open("cms/inventory/export");
	?>
		<div class="form-line">
			<label for="category_id">Kategória</label>
			<select id="category_id" name="category_id">
				<?php 
				if (isset($categories)){
					foreach ($categories as $c){
						?>
						<option value="<?php echo $c['id']?>" <?php echo set_select('category_id', $c['id'], false);?>><?php echo $c['category_name'];?></option>
						<?php
					}
				}
				?>
			</select>
		</div>
		<div class="form-line">
			<input class="save" name="save" type="submit" value="Exportuj">
		</div>
	</form>
</div>