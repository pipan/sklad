<h2><?php echo $page_title;?></h2>
<div>
	<?php 
	if (($error = validation_errors()) != ""){
		echo "<div class='form-error'>".$error."</div>";
	}
	echo form_open("cms/admin/profile");
	?>
		<div class="form-line">
			<label for="nickname">Prihlasovacie meno</label>
			<input id="nickname" name="nickname" type="text" value="<?php echo set_value('nickname', $profile['admin_nickname']);?>">
		</div>
		<div class="form-line">
			<label for="name">Meno</label>
			<input id="name" name="name" type="text" value="<?php echo set_value('name', $profile['admin_name']);?>">
		</div>
		<div class="form-line">
			<label for="surname">Priezvisko</label>
			<input id="surname" name="surname" type="text" value="<?php echo set_value('surname', $profile['admin_surname']);?>">
		</div>
		<div class="form-line">
			<label for="email">E-mail</label>
			<input id="email" name="email" type="text" value="<?php echo set_value('email', $profile['email']);?>">
		</div>
		<div>
			<input class="save" name="login" type="submit" value="Uložiť">
		</div>
	</form>
</div>