<!-- START Add Post -->			 
<div class="new_post_div bg-white mb-4">
    <ul class="new_post_div_tab p-0 pl-3 mx-auto">
        <li class="py-1 my-2 d-inline-block">
            <div class="open_my_post active cursor" title="Post">
            	<img id="cam" class="img-fluid pr-2" src="<?=YH_SOURCE_PATH;?>img/yh_2_share_update.png">
                <span class="d-none d-sm-none d-md-inline-block d-xl-inline-block">Share an</span> Update
            </div>
        </li>
        <li class="py-1 my-2 d-inline-block">
            <a class="dh_add_photo open_my_post YB_post_add_media" href="javascript:void(0)" title="Add Media"><img id="vid" class="img-fluid pr-2" src="<?=YH_SOURCE_PATH;?>img/yh_2_add_media.png">
            <span class="d-none d-sm-none d-md-none d-xl-inline-block pr-2">Upload</span> Photo / Video</a>
        </li>                                             
    </ul>
	<?=form_open(base_url().'post-add',array('id'=>'YB_post_add_form','class'=>'mb-0'));?>
        <div class="media mx-3 pb-3">
			<?php 
               if($SESS_PROFILE_PICTURE!=''){ ?>  
                 <img src="<?=base_url('preview/36/'.md5(IMGSKEY.$SESS_PROFILE_PICTURE).'/'.base64_encode($SESS_PROFILE_PICTURE));?>" class="img-fluid post_pre_profile">
                 <?php		
              }else{
                $names=explode(" ",htmlentities($SESS_NAME));
                $f_letter="";
                $i=1;
                foreach ($names as $n){
                   $f_letter.=ucfirst(substr($n, 0, 1));
                   if($i==2){
                     break;
                  }	
                  $i++;	
               }
               ?>
			    <span  class="profile_img_char1 float-left mr-2 profile_logo_db_l" alt="profile"><?=$f_letter; ?></span>
            <?php   } ?>
			
			
            <div class="media-body align-self-center">
            	<textarea placeholder="Share your journey" rows="1" class="fixed_font text_grey border-0 w-100 ml-2 share_update YB_post_title" name="post_title"></textarea> 
            </div>
        </div>

		<div class="col-12 share_post_div" style="display:none">
		<input type="text" id="YB_tag_chosen" class="span12 select2 span10 m-wrap" name="tag_name" value="" placeholder="Select a tag"/>
					<?php
                    $tag=get_all_tag_list();
                    if(isset($tag) && count($tag)>0){ foreach($tag as $tg){$tag_name[]=$tg->tg_name; $tag_id[]=$tg->tg_tag_id;} }
                    $prime_tag=$this->Common_model->get_prime_tags();
                    if(count($prime_tag)>0)
					{ ?>	 
                        <div id="list">
                            <ul class="col-xs-12 col-sm-12 no_padding select_category_div p-0 m-0 my-3 YB_post_tags" id="YB_prime_tags">
                            <?php
                            foreach($prime_tag as $prime)
							{
								$tg_tag_id=htmlentities($prime->tg_tag_id);
								$tg_name=htmlentities($prime->tg_name);
								$tg_icon=htmlentities($prime->tg_icon);
								?>
                                <li class="select_category div_category1 <?=str_replace(' ','_',$tg_name);?> cursor">
                                    <a class="pstag pstaga<?=$tg_tag_id;?>" data-val="<?=$tg_tag_id;?>">
                                    <img class="img-responsive" src="<?=YH_SOURCE_PATH;?>img/<?=$tg_icon;?>"><?=$tg_name;?></a>
                                    <input type="hidden" id="YB_tags" class="YB_choosed_tag<?=$tg_tag_id;?>" name="tag_id[]" value="">
                                </li> 
                                <?php
                            }
							?>
                            </ul>
                        </div>
                    	<?php
                 	}
					?> 
                	<input type="hidden" id='YB_prime_tags' value=''>
			<?php if($SESS_TYPE_ID=='6') /*Only for youth*/
			{
				if(isset($my_journey_list) && count($my_journey_list) > 0)
				{ ?>
				<p class="mt-2 mb-0 col-12 p-0">Choose a milestone to report too</p>
				<div class="d-flex flex-wrap mb-2">
				<?php 
					foreach($my_journey_list as $j_list)
					{
						$jum_journey_id=htmlentities($j_list->jum_journey_id);
						$jum_title=htmlentities($j_list->jum_title);
					?>
					<label class="custom_check tags_common_choose mile_stone_tag_div YB_my_journey_<?=$jum_journey_id;?>"><?=$jum_title;?>
						<input type="checkbox" name="journey_id[]" value="<?=$jum_journey_id;?>" class="YB_my_journey" data-j-id="<?=$jum_journey_id;?>">
						<span class="checkmark"></span>
					</label> 
					<?php 	
					}
					?>
				</div>
				<?php
				}
			}
			?>
		</div>
		<input type="hidden" name="att_code" class="YB_string_time" value="<?=time();?>">
		<input type="hidden" name="profile_user_id" value="<?=htmlentities($profile_id);?>">
    	<input type="hidden" name="group_id" value="<?=htmlentities($group_id);?>">
    	<input type="hidden" name="group_code" value="<?=htmlentities($group_code);?>">		
	<?=form_close();?>
        <div class="posts share_post_div" style="display:none">
			<div class="padding_head">
			<?=form_open('',array('id'=>''));?>
				<div id="YB_dashboard_fileupload" class="col-12 p-0">
					<input id="YB_add_photos" type="file" name="files[]" class="d-none" multiple>
						<div class="files modify_upload">
							<span class="modify_post">
								<img src="<?=YH_SOURCE_PATH;?>img/yh_2_add_photo.png" class="add_media_upload img-fluid YB_post_add_media">
							</span>
							<span class="modify_post">
								<img src="<?=YH_SOURCE_PATH;?>img/yh_2_social_plus.png" class="img-fluid YB_post_social_media">
							</span>
						</div>
					<div class="col-12 p-0 text-center"></div>
				</div>
				 <?=form_close();?>
			</div>
            <div class="new_post_button_div p-3 text-right">
			<?php if($SESS_TYPE_ID=='1' || $SESS_TYPE_ID=='6' || $group_id > 0){
			}else{ ?>
			<select class="form-control btn_public text-white mr-2" name="share_type">
				<option value="1">Public</option>
				<option value="0">Private</option>
				<option value="2">Followers</option>
			</select>
			<?php } ?>	
                <button type="button" class="btn_post btn YB_post_add">Post</button>
            </div>
        </div>
</div>
<!-- END Add Post -->
<!-- START : PostTag select2 script-->
<script type="text/javascript">
	var tag_name='<?=json_encode($tag_name);?>';
	$(function(){ $("#YB_tag_chosen").select2({tags:JSON.parse(tag_name)}); });
</script>
<!-- END : PostTag select2 script-->
<!-- The template to display files available for upload-->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
	<span class="modify_post">
		<span class="preview">
			<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                <div class="progress-bar progress-bar-success"></div></div>
		</span>
	</span>
{% } %}
</script>
<!-- The template to display files available for download-->
<script id="template-download" type="text/x-tmpl">
{%
	var vformats=["webm","mkv","flv","vob","ogv","ogg","drc","gifv","mng","avi","mov","qt","wmv","yuv","rm","rmvb","asf","amv","mp4","m4p","m4v","mpg","mp2","mpeg","mpe","mpv","m2v","svi","3gp","3g2","mxf","roq","nsv","f4v","f4p","f4a","f4b"];		
	var mediaurl1="<?=base_url()."post-add-gallery-temp";?>";	
	for(var i=0,file;file=o.files[i];i++)
	{
		if(!file.error)
		{
			var att_type=1; var ext=file.name.split('.').pop(); var ext=ext.toLowerCase();
			if(jQuery.inArray(ext,vformats)>=0){var att_type=3;}
			var att_code=$('.YH .YB_string_time').val();
			var fname=file.name;
			$.post(mediaurl1,{fname:fname,att_type:att_type,att_code:att_code,fsize:file.size,youthhub_csrf_token:$.cookie('youthhub_csrf_cookie')});
			%}
			<span class="modify_post">
				{%
					if(att_type=='3')
					{
						%}<video onclick="this.paused?this.play():this.pause();" controlsList="nodownload">
						<source src="{%=file.url%}"/></video>
						<i class="fa fa-times-circle remove_img_post delete YB_gallery_delete" data-att-type="{%=att_type%}" data-val="{%=file.name%}" data-att-code="{%=att_code%}" 
						data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}" aria-hidden="true"></i>{%
					}
					else
					{
						%}<img alt="{%=file.name%}" src="{%=file.url%}" class="new_post_img_div img-fluid"/>
						<i class="far fa-times-circle remove_img_post delete YB_gallery_delete" data-att-type="{%=att_type%}" data-val="{%=file.name%}" data-att-code="{%=att_code%}"
						data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}" aria-hidden="true"></i>{%
					}
				%}
			</span>			
		{%
		}
		else
		{
			toastr.warning(file.name + ' ' + file.error);
		}
	}
%}
</script>
<script src="https://static.filestackapi.com/v3/filestack.js"></script>
<script>
var dfsClient=filestack.init('AmVYQaizRNiSMiyIZoG7gz');
function open_social_media_post()
{
	var att_code1=$('.YH_1 .YB_string_time').val();
	var post_social_media ='';
	var vformats1=["webm","mkv","flv","vob","ogv","ogg","drc","gif","gifv","mng","avi","mov","qt","wmv","yuv","rm","rmvb","asf","amv","mp4","m4p","m4v","mpg","mp2","mpeg","mpe","mpv","m2v","svi","3gp","3g2","mxf","roq","nsv","f4v","f4p","f4a","f4b"];
	var mediaurl2="<?=base_url()."post-add-gallery-temp";?>";
	
	dfsClient.pick({
		accept:['image/*','video/*'],
		maxFiles:15,
		fromSources:["url","imagesearch","facebook","instagram","googledrive","dropbox","flickr","gmail","picasa","onedrive"]
	}).then(function(response){
		$.each(response.filesUploaded,function(){
			var fname1=this.filename; var pt_img=this.handle; var fsize=this.size; var pt_img_url=this.url;
			var att_type1='1a'; var ext=fname1.split('.').pop(); var ext=ext.toLowerCase();
			if(jQuery.inArray(ext,vformats1)>=0){var att_type1='3a';}
			
			if(att_type1=='1a')
			{
				post_social_media=post_social_media+'<span class="modify_post"><img alt="'+fname1+'" src="'+pt_img_url+'" class="new_post_img_div img-fluid"/><i class="fa fa-times-circle remove_img_post delete YB_gallery_delete" data-att-type="'+att_type1+'" data-val="'+fname1+'" data-att-code="'+att_code1+'" aria-hidden="true"></i></span>';

			}else if(att_type1=='3a')
			{
				post_social_media=post_social_media+'<span class="modify_post"><video onclick="this.paused?this.play():this.pause();" controlsList="nodownload"><source src="'+pt_img_url+'"/></video><i class="fa fa-times-circle remove_img_post delete YB_gallery_delete" data-att-type="'+att_type1+'" data-val="'+fname1+'" data-att-code="'+att_code1+'" aria-hidden="true"></i></span>';
			}
			$.post(mediaurl2,{fname:fname1,att_type:att_type1,att_code:att_code1,fsize:fsize,pt_img:pt_img,pt_img_url:pt_img_url,youthhub_csrf_token:$.cookie('youthhub_csrf_cookie')});
			
			$("#dashboard .post-cam li[id='"+att_code1+"'][data-val='"+fname1+"']").remove();
		});
		$('.files').append(post_social_media);
	});
}
</script>
