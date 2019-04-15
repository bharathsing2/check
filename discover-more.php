<?php
$next=htmlentities($ps_page_no)+1;
if(isset($discover_list) && count($discover_list) > 0)
{
    foreach($discover_list as $post)
	{
		$pm_description=htmlentities($post->pm_description);
		$pm_code=htmlentities($post->pm_code);
		$um_profile_picture=htmlentities($post->um_profile_picture);
		$um_name=htmlentities($post->um_name);
		$pm_created_on=htmlentities($post->pm_created_on);
?>
<div class="col-3 discover">
  <?php
        /** Start : To Pass Title in URL **/ 
	    $first10words=implode(' ',array_slice(explode(' ',$pm_description),0,9));
        $title1       = preg_replace('/[^A-Za-z0-9\. -]/', '', $first10words); //to avoid #(any special char)in url
        /** End : To Pass Title in URL **/
?>
 <a href="<?=base_url();?>discover/<?=$pm_code;?>/<?=$title1;?>">
    <div class="row m-0 discover_pro_img_div">
      <?php
        if($post->um_profile_picture!='')
		{ ?>
			<img class="img-fluid discover_pro_img" src="<?=base_url('preview/36/'.md5(IMGSKEY.$um_profile_picture).'/'.base64_encode($um_profile_picture));?>" alt="profile">	
      <?php
        }else
		{
            $posted_user_name =explode(" ", $um_name);
            $user_f_letter    ="";
            $i                =1;
            foreach ($posted_user_name as $s_n){
                $user_f_letter.=ucfirst(substr($s_n, 0, 1));
                if ($i==2){
                    break;
                }
                $i++;
            }
?>  
      <span class="profile_logo profile_img_char<?=$img_rand;?> float-left mr-2" alt="profile">
        <?=$user_f_letter; ?>
     </span>
      <?php
        }
?>
     <div class="user_details">
        <p class="user_name">
          <?=$um_name; ?>
       </p>
        <small class="update_time">
          <?=dh_time_ago($pm_created_on, 0, 1); ?>
       </small>
      </div>    
    </div>
    <div class="row m-0 discover_img_div">
      <?php
        $gallery_list = $this->dashboard_model->get_gallery_list($post->pm_post_id);
        if (count($gallery_list) > 0) 
		{
            foreach(array_slice($gallery_list, 0, 1) as $gallery) 
			{
                $post_type=htmlentities($gallery->pgal_type);
                $pgal_video_poster=htmlentities($gallery->pgal_video_poster);
                $pgal_image=htmlentities($gallery->pgal_image);
				
		if ($post_type==1) { ?>
		<img src="<?=base_url('preview/1/'.md5(IMGSKEY.$pgal_image).'/'.base64_encode($pgal_image));?>" class="img-fluid discover_img" alt="">
    <?php } else if($post_type==2){ ?>
		<img src="<?=$pgal_image; ?>" class="img-fluid discover_img" alt="">
    <?php } else if($post_type==3){ ?>
		<img src="<?=base_url('preview/3/'.md5(IMGSKEY.$pgal_video_poster).'/'.base64_encode($pgal_video_poster));?>" class="img-fluid discover_img" alt="">
		<i class="far fa-play-circle lg_font discover_play_icon"></i> 
    <?php  }else if ($post_type==4){ ?>
		<img src="<?=$pgal_video_poster;?>" class="img-fluid discover_img" alt="">
		<i class="far fa-play-circle lg_font discover_play_icon"></i> <?php }
			}
        }
?>
   </div>                        
  <div class="row m-0 discover_box">
    <div class="col-12 content_div">
      <p class="content">
        <?php
        if(preg_match(REGEX_URL, $pm_description,$url)){
            $pm_description=preg_replace(REGEX_URL, "<a target='_blank' href='{$url[0]}'>{$url[0]}</a> ", $pm_description);
        } ?>
			<?=nl2br($pm_description);?>
     </p>
    </div> 
  </div>
  </a>                    
</div>
<?php
    }
}
?>
<div class="row m-0" id="YB_discover_list_<?=$next;?>">
  <input type="hidden" value="<?=$next;?>" id="YB_discover_next">
</div>