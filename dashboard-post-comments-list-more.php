<?php    
$img_rand=rand(1,6);               
$totel_comments=count($comment_list);
$SESS_USER_ID=htmlentities($this->session->userdata('SESS_USER_ID'));
$limit=5;
if($totel_comments > 0) {
foreach(array_slice($comment_list,0,$limit) as $comments){
	$pfe_feed_id=htmlentities($comments->pfe_feed_id);
	$um_profile_picture=htmlentities($comments->um_profile_picture);
	$um_name=htmlentities($comments->um_name);
	$um_code=htmlentities($comments->um_code);
	$pfe_pm_post_id=htmlentities($comments->pfe_pm_post_id);
	$pfe_created_on=htmlentities($comments->pfe_created_on);
	$pfe_message=htmlentities($comments->pfe_message);
?>  
<li class="comment_content_div pt-2 YB_inactive_comment_<?=$pfe_feed_id;?>">
  <?php
if($comments->um_profile_picture!=''){ ?>
  <img  class="img-fluid profile_logo float-left mr-2" src="<?=base_url('preview/36/'.md5(IMGSKEY.$um_profile_picture).'/'.base64_encode($um_profile_picture));?>" alt="profile">
<?php   
}else{
$commented_user_name=explode(" ",$um_name); //commented User
$user_f_letter="";
$i=1;
foreach ($commented_user_name as $s_n) {
$user_f_letter.=ucfirst(substr($s_n, 0, 1));    
if($i==2){
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
<div class="comment_content">
  <p class="mb-0 cmnt_title_text">
    <a href="<?=base_url();?>profile/<?=$um_code;?>" class="db_profile" target="_blank"><?=$um_name;?></a>
    <span class="float-right fixed_font text_grey1">
      <?php
if($comments->pfe_created_on!='0000-00-00 00:00:00'){  ?>
<?=strtolower(date('d M Y g:i a',strtotime($pfe_created_on)));?>
<?php
}
?>
    </span>
  </p>
  <p class="text_darkgrey cmnt_desc mb-0">
    <?php
	if(preg_match(REGEX_URL,$pfe_message,$url)){
		$pfe_message=preg_replace(REGEX_URL,"<a target='_blank' href='{$url[0]}'>{$url[0]}</a> ",$pfe_message);
	} ?>
	<?=nl2br($pfe_message);?>
    <?php
if($SESS_USER_ID==$comments->pfe_um_user_id){
?>
    <a href="#" class="YB_post_comment_delete post_comment_delete" data-pid="<?=$pfe_pm_post_id;?>" data-fid="<?=$pfe_feed_id;?>" data-toggle="modal" data-target=".YB_delete_comment">  
      <i class="far fa-trash-alt pl-3 cmt_delete">
      </i>
    </a>
    <?php
}
?>
  </p>
</div>
</li>
<?php
}
$last_fid=$pfe_feed_id;
$post_id=$pfe_pm_post_id;
}
?>
<?php
if($totel_comments > 5){
$balence_comments=$totel_comments-5;
?>
<a href="javascript:void;" style="display:block" class="pt-2 col-12 text-center text_theme fixed_font YB_post_comments_more YB_comment_<?=$last_fid;?>" data-fid="<?=$last_fid;?>" data-pid="<?=$post_id;?>"> View 
  <?=$balence_comments;?> more 
  <?php if($balence_comments==1){ ?>Comment 
  <?php }else{ ?> Comments
  <?php } ?>
</a>
<?php
}   
?>