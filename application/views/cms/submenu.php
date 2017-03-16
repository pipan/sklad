<?php
if (isset($submenu)){
	?>
	<ul>
	<?php
	foreach($submenu as $m){
		?>
		<li>
			<a href="<?php echo base_url().$m['link'];?>"><?php echo $m['text'];?></a>
		</li>
		<?php
	}
	?>
	</ul>
	<?php
}