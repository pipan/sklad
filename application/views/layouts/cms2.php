<!doctype html>
<html>
	<head>
		<link type = "text/css" rel = "stylesheet" href = "<?php echo assets_url()."style/menu.css";?>" />
		<link type = "text/css" rel = "stylesheet" href = "<?php echo assets_url()."style/cms/style.css";?>" />
		<link type = "text/css" rel = "stylesheet" href = "<?php echo assets_url()."style/cms/style800.css";?>" media="all and (max-width: 800px)" />
		<link type = "text/css" rel = "stylesheet" href = "<?php echo assets_url()."style/page_counter.css";?>" />
		<?php 
		if (isset($style)){
			foreach ($style as $s){
				?>
				<link type="text/css" rel="stylesheet" href="<?php echo assets_url()."style/".$s.".css";?>" />
				<?php
			}	
		}
		?>
		<!-- <script src="//code.jquery.com/jquery-1.11.0.min.js"></script> -->
		<script type="text/javascript" src="<?php echo assets_url()."jscript/jquery-1.11.0.min.js";?>"></script>
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
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="shortcut icon" href="<?php echo assets_url()."images/cms/icon.ico";?>" type="image/x-icon" />
		<title><?php echo $title;?></title>
	</head>
	<body>
		<div id="main">
			<div id="header">
				<div id="header-body">
					<?php echo $header;?>
				</div>
				<div id="header-menu">
					<?php echo $menu;?>
				</div>
			</div>
			
			<div id="workspace">
				<div id="workspace-top">
					<?php
					if (isset($message)){
					?>
					<div id="message">
						<div id="message-center">
							<div id="message-cancel"><img class="action-image-small" src="<?php echo assets_url()."images/delete.png"?>" onClick="$('#message').hide();"></div>
							<div id="message-body"><?php echo $message;?></div>
						</div>
					</div>
					<?php
					}
					?>
					<div id="body">
						<div id="content">
							<div id="content-header">
							
							</div>
							<div id="content-body">
								<?php echo $body;?>
							</div>
						</div>
						<div id="body-menu">
							<?php 
							if (isset($submenu)){
								echo $submenu;
							}
							?>
						</div>
					</div>
				</div>
			</div>
		
		</div>
		
		<div id="footer">
			<?php echo $footer;?>
		</div>
	</body>
</html>