<html lang="en">
<head>
  <title>Youth Hub - Discover</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">  
  <link rel="icon" href="https://youthhub.co.nz/assets-new/img/img_logo1.png" type="image/png" sizes="16x16">
  <link rel="stylesheet" href="<?=YH_SOURCE_PATH;?>css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"><!----font-awesome css---->
  <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet"><!----font-awesome css---->
  <script src="<?=YH_SOURCE_PATH;?>js/jquery.min.js"></script><!----default js---->
  <script src="<?=YH_SOURCE_PATH;?>js/popper.min.js"></script><!----default js---->
  <script src="<?=YH_SOURCE_PATH;?>js/bootstrap.min.js"></script><!----default js---->  
  <script src="https://use.fontawesome.com/c28f83d5b6.js"></script><!----font-awesome js---->
  <link rel="stylesheet" href="<?=YH_SOURCE_PATH;?>css/style_yh_1.css">	
  <link rel="stylesheet" href="<?=YH_SOURCE_PATH;?>css/style_yh_1_media.css">
</head>
<body class="YH YH_40 YH_40_7">
<div class="container-fluid p-0">	
	<div class="container-fluid p-0 header_bg">	
	  <?php $this->load->view('includes/menu');?>
		<div class="container discover_div">
			<div class="row m-0">
				<h3 class="title col-12">Discover</h3>
			</div>
			<div class="row m-0 discover_row">
				<div class="row m-0 w-100" id="YB_discover_list_1">				 
					<?php $this->load->view('dashboard/discover-more',$discover_list); ?>
                </div>	
			    <div class="YB_discover_loader w-100">
					<div class="text-center"><img class="load_event img-fluid" width="90" src="<?=YH_SOURCE_PATH;?>img/yh_2_youth_loader.svg"/></div>
				</div>
			</div>	
		</div>
	</div>	
</div>	
</body>
</html>
<script type="text/javascript" src="<?=base_url();?>asset/plugins/jquery.cookie.min.js"></script>
<script src="<?=base_url();?>asset/js/dashboard/discover.js" type="text/javascript"></script>