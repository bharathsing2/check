<!-- BEGIN : Display Encourager's List on Modal -->
<div class="col-12 col-sm-12 YB_append_encouragers_more">
<?php
$img_rand=rand(1,6);
$total_encouragers=count($encouragers_list);
if( $total_encouragers > 0){
$limit=7; 	
$likes=1;
foreach(array_slice($encouragers_list,0,$limit) as $encourager){
	$um_code=htmlentities($encourager->um_code);
	$um_profile_picture=htmlentities($encourager->um_profile_picture);
	$um_name=htmlentities($encourager->um_name);
	$pfe_created_on=htmlentities($encourager->pfe_created_on);
	$pfe_feed_id=htmlentities($encourager->pfe_feed_id);
	$pfe_pm_post_id=htmlentities($encourager->pfe_pm_post_id);
?>
  <div class="media bg-white align-items-center profile_liked">
    <a href="<?=base_url();?>profile/<?=$um_code;?>" target="_blank"> 
<?php 
if($encourager->um_profile_picture!=''){
?>
     <img src="<?=base_url('preview/36/'.md5(IMGSKEY.$um_profile_picture).'/'.base64_encode($um_profile_picture));?>" class="profile_img_logo_c img-fluid rounded-circle" alt="profile"> 
<?php	 
}else{
$f_letter="";
$names=explode(" ",$um_name);
$i=1;
foreach ($names as $n) {
$f_letter.= ucfirst(substr($n,0, 1));
if($i==2){
break;
}
$i++;
}
?>
<span class="profile_img_logo_c profile_img_char<?=$img_rand;?> float-left mr-2" alt="profile"><?=$f_letter;?></span>
      <?php		
}	
?>
    </a>
    <div class="media-body ml-2">
      <h6 class="text_theme font-weight-bold mb-0">
        <a href="<?=base_url();?>profile/<?=$um_code;?>" target="_blank">
          <?=$um_name;?>
        </a>
      </h6>
      <p class="text_grey mb-0 fixed_font">
        <?php
if($encourager->pfe_created_on!='0000-00-00 00:00:00'){ ?>
	<?=strtolower(date('d M Y g:i a',strtotime($pfe_created_on)));?>
<?php
	}
?>
      </p>
    </div>       
  </div>
  <?php 
$last_fid=$pfe_feed_id;
$post_id=$pfe_pm_post_id; 
$likes++;
	if($likes > $limit){
	break;
	}
}
}
if($total_encouragers > $limit){
$balence_encourager=$total_encouragers-$limit;
?>
	<a href="#" style="display:block" class="text_theme fixed_font YB_post_encouragers_more YB_encouragers_<?=$last_fid;?>" data-fid="<?=$last_fid;?>" data-pid="<?=$post_id;?>"> View <?=$balence_encourager;?> more <?php if($balence_encourager==1) { ?>enocurager<?php } else { ?> encouragers<?php } ?> </a>
<?php } ?>
</div>  
<!-- END : Display Encourager's List on Modal -->