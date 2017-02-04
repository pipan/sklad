<div id="login">
	<a href="<?php echo $login["link"];?>"><?php echo $login["text"];?></a>
</div>
<?php
if (isset($menu)){
	?>
	<div class="list-pane">
	<?php
	foreach($menu as $m){
		?>
		<div class="list-over">
			<a class="solid" href="<?php echo base_url().$m['link'];?>">
				<div class="list-name">
					<?php echo $m['text'];?>
				</div>
			</a>
			<?php 
			if (isset($m['submenu'])){
				?>
				<div class="list-body">
				<?php
				foreach ($m['submenu'] as $sub){
					?>
					<a class="solid" href="<?php echo base_url().$sub['link'];?>">
						<div class="list-body-item">
							<?php echo $sub['text'];?>
						</div>
					</a>
					<?php
				}
				?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}
	?>
	</div>
	<?php
}