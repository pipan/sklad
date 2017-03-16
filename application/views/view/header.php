<div style="overflow: visible;">
	<div id="notifications">
		<div class="notification empty">
			<img class="notification-logo" src="<?php echo base_url()."assets/images/download.png";?>" title="vybraté veci">
			<div id="download-counter" class="notification-counter" style="display: none;"></div>
		</div>
		<?php
		if (isset($warnings_count) && $warnings_count > 0){
			$style = '';
			$class = "";
		}
		else{
			$style = 'style="display: none;"';
			$class = "empty";
			$warnings_count = 0;
		}
		?>
		<div class="notification <?php echo $class;?>">
			<img class="notification-logo" src="<?php echo base_url()."assets/images/warning.png";?>" title="upozornenia">
			
			<div id="warnig-counter" class="notification-counter" <?php echo $style;?>>
				<?php echo $warnings_count;?>
			</div>
		</div>
	</div>
	<div id="search-bar">
		<div>
			<input type="text" name="search" placeholder="Hľadať" onKeyup="search();" onCut="search();" onPaste="search();">
		</div>
		<div>
			<?php 
			if (isset($header_categories)){
				foreach ($header_categories as $c){
					?>
					<div class="search-bar-category">
						<input id="<?php echo "category_name_".$c['id'];?>" type="checkbox" name="category[]" value="<?php echo $c['id'];?>" onChange="search();"><label for="<?php echo "category_name_".$c['id'];?>"><?php echo $c['category_name'];?></label>
					</div>
					<?php
				}
			}
			?>
		</div>
	</div>
</div>
<div id="storage_change" class="hide" style="display: none;"></div>
