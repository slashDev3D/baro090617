<?php while($content = $list->hasNextReply()):?>
<div style="margin-left: <?php echo ($depth+1) * 30?>px;" class="kboard-list-item<?php if($content->uid == kboard_uid()):?> kboard-list-selected<?php endif?><?php if(date('Ymd', current_time('timestamp')) == date('Ymd', strtotime($content->getDate()))):?> date-type1<?php endif?>">
	<div class="kboard-list-item-header">
		<div class="kboard-list-avatar">
			<?php echo get_avatar($content->member_uid, 32, '', $content->member_display, array('class'=>'kboard-avatar'))?>
		</div>
		<div class="kboard-list-title">
			<a href="<?php echo $url->getDocumentURLWithUID($content->uid)?>" title="<?php echo esc_attr($content->title)?>">
				<div class="">
					<?php if($content->isNew()):?><span class="kboard-biz-community-new-notify">New</span><?php endif?>
					<?php if($content->secret):?><img src="<?php echo $skin_path?>/images/icon-lock.png" alt="<?php echo __('Secret', 'kboard')?>" class="kboard-icon-lock"><?php endif?>
					<?php echo $content->title?>
				</div>
			</a>
		</div>
		<div class="kboard-list-date"><i class="far fa-clock"></i> <?php echo $content->getDate()?></div>
	</div>
	<div class="kboard-biz-community-info">
		<div class="kboard-biz-community-content kboard-biz-community-cut-strings">
			<?php
			if($content->secret){
				echo __('Secret', 'kboard');
			}
			else{
				$content->content = str_replace('[', '&#91;', $content->getContent());
				$content->content = str_replace(']', '&#93;', $content->getContent());
				echo wp_trim_words(strip_tags($content->content), 200, '...');
			}
			?>
		</div>
		<hr class="kboard-biz-community-content-hr">
		<div class="contents-item">
			<div class="contents-item">
				<div class="item-detail kboard-list-user">
					<?php echo $content->getUserDisplay()?>
				</div>
				<div class="item-detail contents-item-like">
					<i class="far fa-thumbs-up"></i>
					<span class="kboard-list-like"><?php echo $content->like?></span>
				</div>
				<div class="item-detail contents-item-unlike">
					<i class="far fa-thumbs-down"></i>
					<span class="kboard-list-unlike"><?php echo $content->unlike?></span>
				</div>
				<div class="item-detail contents-item-view">
					<i class="far fa-eye"></i>
					<span class="kboard-list-view"><?php echo $content->view?></span>
				</div>
				<div class="item-detail contents-item-comments">
					<i class="far fa-comments"></i>
					<span class="kboard-comments-count">
					<?php if($content->visibleComments()):?>
						<?php echo intval($content->getCommentsCount('', '', '0'))?>
					<?php else:?>
						<?php echo kboard_biz_commnunity_reply_cound($content->uid)?>
					<?php endif?>
					</span>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $boardBuilder->builderReply($content->uid, $depth+1)?>
<?php endwhile?>