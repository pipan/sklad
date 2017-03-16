<h2><?php echo $page_title;?></h2>
<div>
	<?php 
	if (($error = validation_errors()) != ""){
		echo "<div class='form-error'>".$error."</div>";
	}
	echo form_open("cms/user/password/".$user['id']);
	?>
		<div class="form-line">
			<label for="new_password">Nové heslo</label>
			<input id="new_password" name="new_password" type="password" value="">
		</div>
		<div class="form-line">
			<label for="repeat_password">Zopakovať heslo</label>
			<input id="repeat_password" name="repeat_password" type="password" value="">
		</div>
		<div class="form-line">
			<input class="save" name="login" type="submit" value="Uložiť">
		</div>
		<div class="form-line">
			<a class="button" href="<?php echo base_url()."cms/user/edit/".$user['id'];?>">upraviť profil</a>
		</div>
	</form>
</div>