		<div class="media">	  
		<?php if($post_share_content->num_rows() > 0){
			$gallery_type=htmlentities($post_share_content->row()->pgal_type);	
			$description=htmlentities($post_share_content->row()->pm_description);
			$pgal_image=htmlentities($post_share_content->row()->pgal_image);
			$pgal_video_poster=htmlentities($post_share_content->row()->pgal_video_poster);
			$um_name=htmlentities($post_share_content->row()->um_name);
			$pm_created_on=htmlentities($post_share_content->row()->pm_created_on);
			$pm_post_id=htmlentities($post_share_content->row()->pm_post_id);

			if($post_share_content->row()->pm_type=='4'){ //event
			$event_post_list=$this->dashboard_model->get_event_post_list($post_share_content->row()->pm_type_id);
				$em_logo=htmlentities($event_post_list->row()->em_logo);
				if(isset($event_post_list) && $event_post_list->num_rows() >0){
					if($event_post_list->row()->em_logo!="")
					{ ?>
						<img src="<?=base_url('preview/24/'.md5(IMGSKEY.$em_logo).'/'.base64_encode($em_logo));?>" class="align-self-start share_phot_thumb mr-sm-3 mr-1 w-25" alt="EventImg">
					<?php	
					}
				}
			}else if($post_share_content->row()->pm_type=='6'){  //explore
				$explore_post_list=$this->dashboard_model->get_explore_post_list($post_share_content->row()->pm_type_id);
				$xc_content=htmlentities($explore_post_list->row()->xc_content);
				$xm_logo=htmlentities($explore_post_list->row()->xm_logo);
				if(isset($explore_post_list) && $explore_post_list->num_rows() > 0)
				{
					if($explore_post_list->row()->xc_type=='1')
					{ ?>
							<div class="tab_image">
						<?php if($explore_post_list->row()->xm_logo!=""){ ?>
								<img src="<?=base_url('preview/29/'.md5(IMGSKEY.$xm_logo).'/'.base64_encode($xm_logo));?>" class="img-fluid res-img"> 
							<?php }else if($explore_post_list->row()->xc_type=='1'){ ?>
								<img src="<?=base_url('preview/26/'.md5(IMGSKEY.$xm_logo).'/'.base64_encode($xm_logo));?>" alt="explore" class="img-fluid res-img">
							<?php } ?>
							</div>
					<?php
					}
				}
			}
			if($gallery_type==1){ ?>
				<img src="<?=base_url('preview/1/'.md5(IMGSKEY.$pgal_image).'/'.base64_encode($pgal_image));?>" class="align-self-start share_phot_thumb mr-sm-3 mr-1 w-25" alt="PostImg">
			<?php
			}else if($gallery_type==2){ ?>
				<img src="<?=$pgal_image;?>" class="align-self-start share_phot_thumb mr-sm-3 mr-1" width="150" alt="PostImg">
			<?php
			}else if($gallery_type==3){ ?>
				<img src="<?=base_url('preview/3/'.md5(IMGSKEY.$pgal_video_poster).'/'.base64_encode($pgal_video_poster));?>" class="align-self-start share_phot_thumb mr-sm-3 mr-1" width="150" alt="PostImg">
				<i class="far fa-play-circle lg_font play_icn_post_b"></i>
			<?php
			}else if($gallery_type==4){ ?>
				<img src="<?=$pgal_video_poster; ?>" class="align-self-start share_phot_thumb mr-sm-3 mr-1" width="150" alt="PostImg">
				<i class="far fa-play-circle lg_font play_icn_post_b"></i>
			<?php
			} ?>
		<div class="media-body ml-2">	
		<h6 class="media-heading share_pname font-weight-bold"><?=$um_name;?> 
			<span class="pl-2 xs_font text_grey"><?=strtolower(date('d M Y g:i a',strtotime($pm_created_on)));?> 
			</span>
		</h6>
		<p class="text_grey1 normal_font mb-0 share_post_data word_break">
		<?php
			if(preg_match(REGEX_URL,$description,$url)){
				$description=preg_replace(REGEX_URL,"<a target='_blank' href='{$url[0]}'>{$url[0]}</a> ",$description);
			}?>
			<?=nl2br($description);?>
		</p>	
		<p>
		<?php
		if($post_share_content->row()->pm_tags==1)
		{
			$tag_list=$this->dashboard_model->get_tag_list($post_share_content->row()->pm_post_id);
			if(count($tag_list) > 0)
			{	
				foreach($tag_list as $tag)
				{
					$tg_name=htmlentities($tag->tg_name);
					 ?>
					<a href="#" class="text_theme fixed_font"><?="#" . str_replace("#","",$tg_name);?></a>
					<?php
				}
			}
		}
		?>
		</p>
		</div>
		</div>	
		<div class="col-12 col-sm-12 mt-2 p-0"> <?php/*YB_shared_content */?>
			<div class="new_post_button_div p-sm-3 p-0 text-sm-right text-center">
				<?php if($post_share_content->row()->pm_share_type=='2') {  ?>
					<select class="form-control btn_public text-white d-inline-block mr-0 mr-sm-2 YB_post_share_type">
					  <option value="2">Followers</option>
					</select>
				<?php }else{ ?>
					<select class="form-control btn_public text-white d-inline-block YB_post_share_type">
					  <option value="1">Public</option>
					 <?php /* <option value="0">Private</option>*/ ?>
					  <option value="2">Followers</option>
					 <?php /*<option value="3">Specific</option>*/ ?>
					</select>
				<?php } ?>
				 <button type="button" class="btn_post btn YB_post_share_cancel">Cancel</button>
				<button type="button" class="btn_post btn YB_post_share_confirm" data-pid="<?=$pm_post_id;?>">Share</button>
		<?php
		}
		?>