<html lang="en">
  <head>
    <title><?php 
if($tittle!=''){echo htmlentities($tittle);}else{echo "Youth Hub - Discover";}?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <link rel="icon" href="https://youthhub.co.nz/assets-new/img/img_logo1.png" type="image/png" sizes="16x16">
    <link rel="stylesheet" href="<?=YH_SOURCE_PATH;?>css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!----font-awesome css---->
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <!----font-awesome css---->
    <script src="<?=YH_SOURCE_PATH;?>js/jquery.min.js">
    </script>
    <!----default js---->
    <script src="<?=YH_SOURCE_PATH;?>js/popper.min.js">
    </script>
    <!----default js---->
    <script src="<?=YH_SOURCE_PATH;?>js/bootstrap.min.js">
    </script>
    <!----default js---->  
    <script src="https://use.fontawesome.com/c28f83d5b6.js">
    </script>
    <!----font-awesome js---->
    <link rel="stylesheet" href="<?=YH_SOURCE_PATH;?>css/style_yh_1.css">	
    <link rel="stylesheet" href="<?=YH_SOURCE_PATH;?>css/style_yh_1_media.css">
  </head>
  <body class="YH YH_40 YH_40_7 YH_40_11">
    <div class="container-fluid p-0">	
      <div class="container-fluid p-0 header_bg">
		<?php $this->load->view('includes/menu');?>
		
        <div class="container-fluid p-0 news_article_div discover_div">
          <div class="container">
            <div class="row m-0">
              <div class="col-9 p-0 discover_title">
                <h3 class="title">Discover</h3>
              </div>		
              <div class="col-3 p-0 discover_back">	
                <a href="<?=base_url();?>discover" class="title_back">Back</a>
              </div>		
            </div>
          </div>	
        </div>
        <div class="container news_article">
          <div class="row m-0 news_article_row">
            <div class="col-12 news_article_details">
			<h3 class="discover_title w-100">
				  	<?php
					$img_rand=rand(1,6);
					if(isset($post_detail) && count($post_detail)>0){
						foreach($post_detail as $post){ 
							$um_profile_picture=htmlentities($post->um_profile_picture);
							$um_name=htmlentities($post->um_name);
							$pm_created_on=htmlentities($post->pm_created_on);
							$pm_description=htmlentities($post->pm_description);
						
						if($post->um_profile_picture!=''){ ?>
						  <img class="img-fluid discover_pro_img" src="<?=base_url('preview/36/'.md5(IMGSKEY.$um_profile_picture).'/'.base64_encode($um_profile_picture));?>" alt="profile">
						 
						<?php	
						}else{
						 $posted_user_name=explode(" ",$um_name);
						 $user_f_letter="";
						 $i=1;
						 foreach ($posted_user_name as $s_n){
							$user_f_letter.=ucfirst(substr($s_n, 0, 1));	
							if($i==2){
							 break;
							}
						 $i++;	
						 }
					 ?>  
						<span class="profile_logo profile_img_char<?=$img_rand;?> float-left mr-2" alt="profile"><?=$user_f_letter; ?></span>
					<?php
					} ?>
					<?=$um_name;?>
					<?php
					} }?>
                </h3>	
			<?php if(isset($post_detail) && count($post_detail)>0){
					foreach($post_detail as $post){ ?>
              <p class="published_date"><?php
					if($post->pm_created_on!='0000-00-00 00:00:00'){
						echo date('d M Y',strtotime($pm_created_on)) .','. date('g:ia',strtotime($pm_created_on));
					}
			  ?></p>
					<?php
					$gallery_list =$this->dashboard_model->get_gallery_list($post->pm_post_id);
						if(count($gallery_list) > 0){
							foreach(array_slice($gallery_list,0,1) as $gallery){
								$post_type=htmlentities($gallery->pgal_type);
								$pgal_video_poster=htmlentities($gallery->pgal_video_poster);
								$pgal_image=htmlentities($gallery->pgal_image);
								
								if($post_type==1){ ?>
									<p class="w-100 my-4"><img src="<?=base_url('preview/1/'.md5(IMGSKEY.$pgal_image).'/'.base64_encode($pgal_image));?>" class="img-fluid" alt="PostImg"></p>			
								<?php   }else if($post_type==2){?>
									<p class="w-100 my-4"><img src="<?=$pgal_image;?>" class="img-fluid" alt="PostImg"></p>
								<?php	}else if($post_type==3){ ?>
									<p class="w-100 my-4"><img src="<?=base_url('preview/3/'.md5(IMGSKEY.$pgal_video_poster).'/'.base64_encode($pgal_video_poster));?>" class="img-fluid" alt="PostImg" /><i class="far fa-play-circle lg_font discover_play_icon"></i> </p>
								<?php	}else if($post_type==4){ ?>
								<p class="w-100 my-4"><img src="<?=$pgal_video_poster;?>" class="img-fluid" alt="PostImg">
								<i class="far fa-play-circle lg_font discover_play_icon"></i> </p>
								<?php } 
							}
                        }
                    ?>
              <p><?php
				if(preg_match(REGEX_URL,$pm_description,$url))
				{
					$pm_description=preg_replace(REGEX_URL,"<a target='_blank' href='{$url[0]}'>{$url[0]}</a> ",$pm_description);
				} ?>
				<?=nl2br($pm_description);?>
              </p>
			<?php } } ?>
            </div>	
          </div>	
        </div>
	<?php $this->load->view('includes/footer-1')?>
      </div>	
    </div>	
  </body>
</html>