<div id="content-submenu" class="tab-pane">
	<h2><?php echo $content_menu_title;?></h2>
	<div class="tab-menu">
		<div class="tab-menu-item tab-menu-item-active">all</div>
		<div class="tab-menu-item">warnings</div>
	</div>
	<div class="tab-body tab-body-active">
		<?php 
		if (isset($inventories)){
			foreach ($inventories as $i){
				?>
				<div id="<?php echo "inv-".$i['id'];?>" class="content-submenu-item" onClick="order.addItem(this);"><spam class="name"><?php echo $i['inventory_name'];?></spam></div>
				<?php
			}
		}
		?>
	</div>
	<div class="tab-body">
		<?php 
		if (isset($warnings)){
			foreach ($warnings as $w){
				?>
				<div id="<?php echo "warning-inv-".$w['id'];?>" class="content-submenu-item" onClick="order.addItem(this);"><spam class="name"><?php echo $w['inventory_name'];?></spam></div>
				<?php
			}
		}
		?>
	</div>
</div>
<div>
	<h2><?php echo $page_title;?></h2>
	<div>
		<?php 
		if (($error = validation_errors()) != ""){
			echo "<div class='form-error'>".$error."</div>";
		}
		echo form_open("cms/order/add");
		?>
			<div id="form-body" class="form-line">
			
			</div>
			<div class="form-line">
				<label for="order_supplier">dodávateľ</label>
				<select id="order_supplier" name="order_supplier">
					<?php 
					if (isset($suppliers)){
						foreach ($suppliers as $s){
							?>
							<option value="<?php echo $s['id'];?>" <?php echo set_select('order_supplier', $s['id'], false);?>><?php echo $s['supplier_name'];?></option>
							<?php
						}
					}
					?>
				</select>
			</div>
			<div class="form-line">
				<input type="submit" name="generate" value="generate">
			</div>
		</form>
	</div>
</div>