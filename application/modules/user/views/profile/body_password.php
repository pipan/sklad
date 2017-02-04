<h2><?php echo $page_title;?></h2>
<div>
	<?php 
	if (($error = validation_errors()) != ""){
		echo "<div class='form-error'>".$error."</div>";
	}
	echo form_open("cms/admin/profile/password");
	?>
		<div class="form-line">
			<label for="old_password">Staré heslo</label>
			<input id="old_password" type="password" name="old_password"  />
		</div>
		<div class="form-line">
			<label for="new_password">Nové heslo</label>
			<input id="new_password" type="password" name="new_password"  />
		</div>
		<div class="form-line">
			<label for="repeat_password">Zopakuj nové heslo</label>
			<input id="repeat_password" type="password" name="repeat_password"  />
		</div>
		<div>
			<input class="save" type="submit" name="changePassword" value="Uložiť"  />
		</div>
	</form>
</div>