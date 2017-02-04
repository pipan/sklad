<!doctype html>
<html>
	<head>
		<link type = "text/css" rel = "stylesheet" href = "<?php echo assets_url()."style/cms/default.css";?>" />
		<link type = "text/css" rel = "stylesheet" href = "<?php echo assets_url()."style/page_counter.css";?>" />
		<?php 
		if (isset($style)){
			foreach ($style as $s){
				?>
				<link rel="stylesheet" type="text/css" href="<?php echo assets_url()."style/".$s.".css";?>" />
				<?php
			}	
		}
		?>
		<!-- <script src="//code.jquery.com/jquery-1.11.0.min.js"></script> -->
		<script src="<?php echo assets_url()."jscript/jquery-1.11.0.min.js";?>"></script>
		<script type="text/javascript" src="<?php echo assets_url()."jscript/cms/events.js";?>"></script>
		<?php 
		if (isset($jscript)){
			foreach ($jscript as $j){
				?>
				<script type="text/javascript" src="<?php echo assets_url()."jscript/".$j.".js";?>"></script>
				<?php
			}
		}
		if (isset($language) && isset($lang_use) && isset($lang_label)){
			foreach ($language as $l){
				if ($l['id'] != $lang_use['id']){
					?>
					<link rel="alternate" hreflang="<?php echo $l['lang_shortcut'];?>" href="<?php echo $lang_label[$l['lang_shortcut']]['link'];?>">
					<?php
				}
			}
		}
		?>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700,400italic,700italic&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
		<link rel="shortcut icon" href="" type="image/x-icon" />
		<title><?php echo $title;?></title>
	</head>
	<body>
		<div id="index">
			<div id="header">
				<?php echo $header;?>
			</div>
		
			<div id="workspace">
				<div id="workspace_box">
					<div id="menu">
						<?php echo $menu;?>
					</div>
					<div id="screen">
						<div class="horiz_space horiz_space_left">
						</div>
						<div class="horiz_space horiz_space_right">
						</div>
						<div id="workscreen">
							<div id="workscreen_padding">
								<div class="outercenter">
									<div class="innercenter">
										<?php echo $body;?>
									</div>
								</div>
							</div>
						</div>
						
					</div>
					<div id="item_menu">
						<?php 
						if (isset($submenu)){
							echo $submenu;
						}
						?>
					</div>
				</div>
			</div>
		
		</div>
		
		<div id="footer">
			<?php echo $footer;?>
		</div>
	</body>
</html>