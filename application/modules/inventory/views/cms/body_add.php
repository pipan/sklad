<h2><?php echo $page_title;?></h2>
<div>
	<?php 
	if (($error = validation_errors()) != ""){
		echo "<div class='form-error'>".$error."</div>";
	}
	echo form_open("cms/inventory/add/".$inventory['id']);
	?>
		<div class="form-line">
			<label for="inventory_name">Názov</label>
			<input id="inventory_name" name="inventory_name" maxlength="100" value="<?php echo set_value("inventory_name", $inventory['inventory_name']);?>">
		</div>
		<div class="form-line">
			<label for="category_id">Kategória</label>
			<select id="category_id" name="category_id">
				<option value="null">none</option>
				<?php 
				if (isset($categories)){
					foreach ($categories as $c){
						?>
						<option value="<?php echo $c['id'];?>" <?php echo set_select('category_id', $c['id'], $c['id'] == $inventory['category_id']);?>><?php echo $c['category_name'];?></option>
						<?php
					}
				}
				?>
			</select>
		</div>
		<div class="form-line">
			<label for="min_amount">Minimálne množstvo</label>
			<input id="min_amount" name="min_amount" type="input" value="<?php echo set_value("min_amount", $inventory['min_amount']);?>">
		</div>
		<div class="form-line">
			<label for="location">Miesto</label>
			<textarea id="location" name="location" maxlength="100"><?php echo set_value("location", $inventory['location']);?></textarea>
		</div>
		<div class="form-line">
			<label for="description">Popis</label>
			<textarea id="description" name="description" maxlength="255"><?php echo set_value("description", $inventory['description']);?></textarea>
		</div>
		<div class="form-line">
			<label for="description">Dodávatelia</label>
			<div class="tab-pane">
			<?php 
			if (isset($suppliers)){
				?>
				<div class="tab-menu">
				<?php
				$first = true;
				foreach ($suppliers as $s){
					$class= "";
					if ($first == true){
						$class = " tab-menu-item-active";
					}
					?>
					<div class="<?php echo "tab-menu-item".$class;?>">
						<?php echo $s["supplier_name"];?>
					</div>
					<?php
					$first = false;
				}
				?>
				</div>
				<?php
				$first = true;
				foreach ($suppliers as $s){
					$class= "";
					if ($first == true){
						$class = " tab-body-active";
					}
					?>
					<div class="<?php echo "tab-body".$class;?>">
						<div class="form-line">
							<label for="<?php echo "sup_".$s['id']."_code";?>"><?php echo "Kód produktu";?></label>
							<input id="<?php echo "sup_".$s['id']."_code";?>" name="<?php echo "sup_".$s['id']."_code";?>" type="text" maxlength="255" value="<?php echo set_value("sup_".$s['id']."_code", $iis[$s['id']]['code']);?>">
						</div>
						<div class="form-line">
							<label for="<?php echo "sup_".$s['id']."_info";?>">info</label>
							<input id="<?php echo "sup_".$s['id']."_info";?>" name="<?php echo "sup_".$s['id']."_info";?>" type="text" maxlength="100" value="<?php echo set_value("sup_".$s['id']."_info", $iis[$s['id']]['info']);?>">
						</div>
						<div class="form-line">
							<label for="<?php echo "sup_".$s['id']."_price";?>">cena</label>
							<input id="<?php echo "sup_".$s['id']."_price";?>" name="<?php echo "sup_".$s['id']."_price";?>" type="text" value="<?php echo set_value("sup_".$s['id']."_price", convert_to_float($iis[$s['id']]['price']));?>">
						</div>
					</div>
					<?php
					$first = false;
				}
			}
			?>
			</div>
		</div>
		<div class="form-line">
			<input class="save" name="save" type="submit" value="Uložiť">
		</div>
	</form>
</div>