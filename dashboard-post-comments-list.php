<?php
	$pfe_feed_id=htmlentities($last_comments->row()->pfe_feed_id);
	$pfe_created_on=htmlentities($last_comments->row()->pfe_created_on);
	$comment=htmlentities($last_comments->row()->pfe_message);
	$pfe_pm_post_id=htmlentities($last_comments->row()->pfe_pm_post_id)
?>
<li class="comment_content_div pt-2 YB_inactive_comment_<?=$pfe_feed_id;?>" >
<?php
$img_rand=rand(1,6);
if($SESS_PROFILE_PICTURE!=''){ ?>
  <img src="<?=base_url('preview/36/'.md5(IMGSKEY.$SESS_PROFILE_PICTURE).'/'.base64_encode($SESS_PROFILE_PICTURE));?>" class="profile_logo float-left mr-2" alt="profile">
<?php 
}else{
$ses_name=explode(" ",htmlentities($SESS_NAME));
$ses_f_letter="";
$i=1;
foreach ($ses_name as $s_n) {
$ses_f_letter.=ucfirst(substr($s_n, 0, 1));	
if($i==2){
break;
}
$i++;	
}
?>    
<span class="profile_logo profile_img_char<?=$img_rand;?> float-left mr-2" alt="profile"><?=$ses_f_letter; ?></span>
<?php
}
?>
<div class="comment_content">
  <p class="mb-0 cmnt_title_text">
   <a href="<?=base_url();?>profile/<?=htmlentities($um_code);?>" class="db_profile" target="_blank"><?=htmlentities($SESS_NAME); ?></a>
    <span class="float-right fixed_font text_grey1">
<?=strtolower(date('d M Y g:i a',strtotime($pfe_created_on)));?>
    </span>
  </p>
  <p class="text_darkgrey cmnt_desc mb-0">
<?php  
if(preg_match(REGEX_URL,$comment,$url)){
$comment=preg_replace(REGEX_URL,"<a target='_blank' href='{$url[0]}'>{$url[0]}</a> ",$comment);
} ?>
<?=nl2br($comment);?>
    <?php
if($SESS_USER_ID==$last_comments->row()->pfe_um_user_id){
?>
    <a href="#" class="YB_post_comment_delete post_comment_delete" data-pid="<?=$pfe_pm_post_id;?>" data-fid="<?=$pfe_feed_id;?>" data-toggle="modal" data-target=".YB_delete_comment">  
		<i class="far fa-trash-alt pl-3 cmt_delete"></i>
	</a>
    <?php
}
?> </p>
</div>
</li>