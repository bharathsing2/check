<?php
    /** Shared Members List On Modal **/
	$img_rand=rand(1,6);
	if(count($shared_members_list) > 0){
		$members=1;
		foreach($shared_members_list as $shared){
			$shared_by_pic=htmlentities($shared->shared_by_pic);
			$shared_by_name=htmlentities($shared->shared_by_name);
			$shared_by_code=htmlentities($shared->shared_by_code);
			$shared_date=htmlentities($shared->shared_date);
			$pus_share_title=htmlentities($shared->pus_share_title);
			if($shared->pus_type=='1'){ //avoid to display post owner
			}else{
				if($members!='1'){ //To avoid display of first members
			  ?>
		  <div class="col-12">
            <div class="posts mb-3">
              <div class="padding_head2"> 
			  	<?php if($shared->shared_by_pic!=''){ ?>
						<img src="<?=base_url('preview/36/'.md5(IMGSKEY.$shared_by_pic).'/'.base64_encode($shared_by_pic));?>" class="profile_logo  float-left mr-2" alt="profile"> 
					 <?php }else{
						 $names=explode(" ",$shared_by_name);
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
					  <span  class="profile_logo profile_img_char<?=$img_rand;?> float-left mr-2" alt="profile"><?=$f_letter; ?> </span>
					 <?php } ?>
                <p class="mb-0 title_text d-inline ">
                  <a href="<?=base_url();?>profile/<?=$shared_by_code;?>" class="db_profile" target="_blank"><?=$shared_by_name;?></a>
                </p>								
                <p class="time_post text_grey mb-0"><?=strtolower(date("d M Y, g:i a",strtotime($shared_date)));?></p>
				<?php if ($shared->pus_share_title!="") { ?>
                <p class="text_darkgrey post_desc YB_post_description mb-3 word_break"><?=$pus_share_title;?></p>
				<?php } ?>
              </div>                   
            </div>
          </div>
			<?php
				}
				$members++;
			}
		}
	} ?> 