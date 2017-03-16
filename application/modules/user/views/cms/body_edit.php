<h2><?php echo $page_title;?></h2>
<div>
	<?php 
	if (($error = validation_errors()) != ""){
		echo "<div class='form-error'>".$error."</div>";
	}
	echo form_open("cms/user/edit/".$user['id']);
	?>
		<div class="form-line">
			<label for="name">Meno</label>
			<input id="name" name="name" type="text" value="<?php echo set_value('name', $user['admin_name']);?>">
		</div>
		<div class="form-line">
			<label for="surname">Priezvisko</label>
			<input id="surname" name="surname" type="text" value="<?php echo set_value('surname', $user['admin_surname']);?>">
		</div>
		<div class="form-line">
			<label for="nickname">Login</label>
			<input id="nickname" name="nickname" type="text" value="<?php echo set_value('nickname', $user['admin_nickname']);?>">
		</div>
		<div class="form-line">
			<label for="email">E-mail</label>
			<input id="email" name="email" type="text" value="<?php echo set_value('email', $user['email']);?>">
		</div>
		<div class="form-line">
			<label for="email_notification">Upozornenia na e-mail</label>
			<input id="email_notification" name="email_notification" type="checkbox" value="1" <?php echo set_checkbox('email_notification', $user['email_notification'], $user['email_notification'] == 1);?>>
		</div>
		<div class="form-line">
			<label for="privelage">Oprávnenie</label>
			<select name="privelage">
				<?php 
				if (isset($privelages)){
					foreach ($privelages as $p){
						?>
						<option value="<?php echo $p['id']?>" <?php echo set_select('privelage', $p['id'], $user['privelage_id'] == $p['id']);?>><?php echo $p['privelage_name'];?></option>
						<?php
					}
				}
				?>
			</select>
		</div>
		<div class="form-line">
			<input class="save" name="login" type="submit" value="Uložiť">
		</div>
		<div class="form-line">
			<a class="button" href="<?php echo base_url()."cms/user/password/".$user['id'];?>">zmeniť heslo</a>
		</div>
	</form>
</div>