<div id="kboard-biz-community-list">
	
	<!-- 게시판 정보 시작 -->
	<div class="kboard-list-header">
		<div class="kboard-left">
			<!-- 카테고리 시작 -->
			<?php
			if($board->use_category == 'yes'){
				if($board->isTreeCategoryActive()){
					$category_type = 'tree-select';
				}
				else{
					$category_type = 'default';
				}
				$category_type = apply_filters('kboard_skin_category_type', $category_type, $board, $boardBuilder);
				echo $skin->load($board->skin, "list-category-{$category_type}.php", $vars);
			}
			?>
			<!-- 카테고리 끝 -->
		</div>
		
		<div class="kboard-right">
			<form id="kboard-sort-form-<?php echo $board->id?>" method="get" action="<?php echo $url->toString()?>">
				<?php echo $url->set('pageid', '1')->set('category1', '')->set('category2', '')->set('target', '')->set('keyword', '')->set('mod', 'list')->set('kboard_list_sort_remember', $board->id)->toInput()?>
				
				<select name="kboard_list_sort" onchange="jQuery('#kboard-sort-form-<?php echo $board->id?>').submit();">
					<option value="newest"<?php if($list->getSorting() == 'newest'):?> selected<?php endif?>><?php echo __('Newest', 'kboard')?></option>
					<option value="best"<?php if($list->getSorting() == 'best'):?> selected<?php endif?>><?php echo __('Best', 'kboard')?></option>
					<option value="viewed"<?php if($list->getSorting() == 'viewed'):?> selected<?php endif?>><?php echo __('Viewed', 'kboard')?></option>
					<option value="updated"<?php if($list->getSorting() == 'updated'):?> selected<?php endif?>><?php echo __('Updated', 'kboard')?></option>
				</select>
			</form>
		</div>
	</div>
	<!-- 게시판 정보 끝 -->
	
	<!-- 리스트 시작 -->
	<div class="kboard-list">
		<?php while($content = $list->hasNextNotice()):?>
		<div class="kboard-list-item<?php if($content->uid == kboard_uid()):?> kboard-list-selected<?php endif?><?php if(date('Ymd', current_time('timestamp')) == date('Ymd', strtotime($content->getDate()))):?> date-type1<?php endif?>">
			<div class="kboard-list-item-header">
				<div class="kboard-list-avatar">
					<?php echo get_avatar($content->member_uid, 32, '', $content->member_display, array('class'=>'kboard-avatar'))?>
				</div>
				<div class="kboard-list-title">
					<a href="<?php echo $url->getDocumentURLWithUID($content->uid)?>" title="<?php echo esc_attr($content->title)?>">
						<div class="">
							<?php if($content->isNew()):?><span class="kboard-biz-community-new-notify">New</span><?php endif?>
							<?php if($content->secret):?><img src="<?php echo $skin_path?>/images/icon-lock.png" alt="<?php echo __('Secret', 'kboard')?>" class="kboard-icon-lock"><?php endif?>
							<span style="color:#0083ff;">*<?php echo __('Notice', 'kboard')?>*</span>
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
		<?php endwhile?>
		<?php while($content = $list->hasNext()):?>
		<div class="kboard-list-item<?php if($content->uid == kboard_uid()):?> kboard-list-selected<?php endif?><?php if(date('Ymd', current_time('timestamp')) == date('Ymd', strtotime($content->getDate()))):?> date-type1<?php endif?>">
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
		<?php if($content->visibleComments()):?>
			<?php $boardBuilder->builderReply($content->uid)?>
		<?php endif?>
		<?php endwhile?>
	</div>
	<!-- 리스트 끝 -->
	
	<!-- 페이징 시작 -->
	<div class="kboard-pagination">
		<ul class="kboard-pagination-pages">
			<?php echo kboard_pagination($list->page, $list->total, $list->rpp)?>
		</ul>
	</div>
	<!-- 페이징 끝 -->
	
	<!-- 검색폼 시작 -->
	<div class="kboard-search">
		<form id="kboard-search-form-<?php echo $board->id?>" method="get" action="<?php echo $url->toString()?>">
			<?php echo $url->set('pageid', '1')->set('target', '')->set('keyword', '')->set('mod', 'list')->toInput()?>
			
			<select name="target">
				<option value=""><?php echo __('All', 'kboard')?></option>
				<option value="title"<?php if(kboard_target() == 'title'):?> selected="selected"<?php endif?>><?php echo __('Title', 'kboard')?></option>
				<option value="content"<?php if(kboard_target() == 'content'):?> selected="selected"<?php endif?>><?php echo __('Content', 'kboard')?></option>
				<option value="member_display"<?php if(kboard_target() == 'member_display'):?> selected="selected"<?php endif?>><?php echo __('Author', 'kboard')?></option>
			</select>
			<input type="text" name="keyword" value="<?php echo kboard_keyword()?>">
			<button type="submit" class="kboard-biz-community-button-search" title="<?php echo __('Search', 'kboard')?>"><img src="<?php echo $skin_path?>/images/icon-search.png" alt="<?php echo __('Search', 'kboard')?>"></button>
		</form>
	</div>
	<!-- 검색폼 끝 -->
	
	<?php if($board->isWriter()):?>
	<div class="kboard-control">
		<a href="<?php echo $url->getContentEditor()?>" class="kboard-biz-community-button-small"><?php echo __('New', 'kboard')?></a>
	</div>
	<?php endif?>
	
	<?php if($board->contribution()):?>
	<div class="kboard-biz-community-poweredby">
		<a href="https://www.cosmosfarm.com/products/kboard" onclick="window.open(this.href);return false;" title="<?php echo __('KBoard is the best community software available for WordPress', 'kboard')?>">Powered by KBoard</a>
	</div>
	<?php endif?>
</div>