<?php
if (!function_exists("page_div")){
	function page_div($page, $offset, $max, $link, $base = false){
		$from = $page - $offset;
		if ($from < 1){
			$from = 1;
		}
		$to = $page + $offset;
		if ($to > $max){
			$to = $max;
		}
		if ($from < $to){
			?>
			<div id="pages">
				<?php 
				if ($from > 1){
					if ($base){
						$link_complete = str_replace("%p", "1", $link);
						?>
						<div class="page-number">
							<a class="exception" href="<?php echo $link_complete;?>">1</a>
						</div>
						<?php
						if ($from > 2){
							?>
							<div class="page-number">
								...
							</div>
							<?php
						}
					}
					else{
						?>
						<div class="page-number">
							...
						</div>
						<?php
					}
				}
				for ($i = $from; $i <= $to; $i++){
					$link_complete = str_replace("%p", $i, $link);
					?>
					<div class="page-number">
						<?php
						if ($i == $page){
							?>
							<a href="<?php echo $link_complete;?>"><?php echo $i;?></a>
							<?php 
						}
						else{
							?>
							<a class="exception" href="<?php echo $link_complete;?>"><?php echo $i;?></a>
							<?php
						}
						?>
					</div>
					<?php	
				}
				if ($to < $max){
					if ($base){
						$link_complete = str_replace("%p", $max, $link);
						if ($to < ($max - 1)){
							?>
							<div class="page-number">
								...
							</div>
							<?php
						}
						?>
						<div class="page-number">
							<a class="exception" href="<?php echo $link_complete;?>"><?php echo $max;?></a>
						</div>
						<?php
					}
					else{
						?>
						<div class="page-number">
							...
						</div>
						<?php	
					}
				}
				?>
			</div>
			<?php
		}
	}
}