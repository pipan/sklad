<script>
function load(){
	<?php 
	if (!empty($this->link)){
		foreach ($this->link as $list){
			?>
			editor.components.link.loadList('<?php echo $list['text'];?>', '<?php echo $list['link']?>');
			<?php
		}
	}
	$i = 1;
	if (!empty($this->image)){
		foreach ($this->image as $list){
			if ($blog['thumbnail'] != null){
				if ($list['link'] == $blog['thumbnail']){
					$this->thumbnail = $i;
				}
			}
			$i++;
			?>
			editor.components.image.loadList('<?php echo $list['text'];?>', '<?php echo $list['link']?>', '<?php echo $list['width']?>', '<?php echo $list['alignment']?>');
			<?php
		}
	}
	if (!empty($this->video)){
		foreach ($this->video as $list){
			?>
			editor.components.video.loadList('<?php echo $list['text'];?>', '<?php echo $list['link']?>', '<?php echo $list['width']?>', '<?php echo $list['alignment']?>', '<?php echo $list['code']?>');
			<?php
		}
	}
	if (!empty($this->tag)){
		foreach ($this->tag as $list){
			?>
			editor.components.tag.loadList('<?php echo $list['tag_name'];?>');
			<?php
		}
	}
	?>
	editor.components.link.render();
	editor.components.image.render();
	<?php 
	if (!empty($this->thumbnail)){
		?>
		editor.components.tag.thumbnail = <?php echo $this->thumbnail;?>;
		<?php	
	} 
	if (!empty($this->series)){
		?>
		editor.components.tag.series = <?php echo $this->series;?>;
		<?php	
	}
	?>
	editor.components.image.renderThumbnailSelect(0);
	editor.components.video.render();
	editor.components.tag.render();
	<?php 
	if (!empty($this->id)){
		?>
		editor.id = <?php echo $this->id;?>;
		<?php
	}
	if (!empty($this->lang)){
		?>
		editor.lang = '<?php echo $this->lang['lang_shortcut'];?>';
		<?php	
	}
	if (!empty($this->url_save)){
		?>
		editor.urlSave = "<?php echo $this->url_save;?>";
		<?php
	}
	if (!empty($this->url)){
		?>
		editor.url = "<?php echo $this->url;?>";
		<?php
	}
	?>
}
</script>