<div id="kboard-hwaikeul-gallery-list">
	<div class="kboard-header">
		<div class="kboard-total-count">
			<span class="entry-title"><?php echo $board->board_name?></span>
			<?php if(!$board->isPrivate()):?>
			<span class="count"><?php echo number_format($board->getListTotal())?></span>
			<?php endif?>
		</div>
		
		<div class="kboard-sort">
			<form id="kboard-sort-form-<?php echo $board->id?>" method="get" action="<?php echo $url->toString()?>">
				<?php echo $url->set('pageid', '1')->set('category1', '')->set('category2', '')->set('target', '')->set('keyword', '')->set('mod', 'list')->set('kboard_list_sort_remember', $board->id)->toInput()?>
				
				<select data-form="#kboard-sort-form-<?php echo $board->id?>" name="kboard_list_sort" onchange="jQuery('#kboard-sort-form-<?php echo $board->id?>').submit()">
					<option value="newest"<?php if($list->getSorting() == 'newest'):?> selected<?php endif?>><?php echo __('Newest', 'kboard')?></option>
					<option value="best"<?php if($list->getSorting() == 'best'):?> selected<?php endif?>><?php echo __('Best', 'kboard')?></option>
					<option value="viewed"<?php if($list->getSorting() == 'viewed'):?> selected<?php endif?>><?php echo __('Viewed', 'kboard')?></option>
					<option value="updated"<?php if($list->getSorting() == 'updated'):?> selected<?php endif?>><?php echo __('Updated', 'kboard')?></option>
				</select>
			</form>
		</div>
	</div>
	
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
	
	<!-- 리스트 시작 -->
	<?php if($list->getNoticeList()):?>
	<ul class="kboard-list notice-list<?php if(kboard_hwaikeul_gallery_list($board)):?> <?php echo kboard_hwaikeul_gallery_list($board)?><?php endif?>">
		<?php while($content = $list->hasNextNotice()):?>
		<li class="kboard-list-item kboard-list-notice<?php if($content->uid == kboard_uid()):?> kboard-list-selected<?php endif?>">
			<div class="item-padding">
				<div class="kboard-hwaikeul-gallery-thumbnail">
					<?php if($content->getThumbnail(600, 338)):?>
						<div class="kboard-light-gallery">
							<div class="kboard-hwaikeul-gallery-container wide target-image" data-thumb="<?php echo $content->getThumbnail(200, 200)?>" data-src="<?php echo $content->getThumbnail()?>">
								<img src="<?php echo $content->getThumbnail(600, 338)?>" alt="<?php echo esc_attr($content->title)?>">
							</div>
							<?php $media_list = $content->getMediaList()?>
							<?php if($media_list):?>
								<?php foreach($media_list as $media_item):?>
									<?php if($content->getThumbnail() == site_url($media_item->file_path)) continue?>
									<div class="kboard-hwaikeul-gallery-container wide target-image" data-thumb="<?php echo kboard_resize($media_item->file_path, 200, 200)?>" data-src="<?php echo site_url($media_item->file_path)?>">
										<img src="<?php echo kboard_resize($media_item->file_path, 600, 338)?>" alt="<?php echo esc_attr(basename($media_item->file_name))?>">
									</div>
								<?php endforeach?>
							<?php endif?>
						</div>
						<div class="kboard-hwaikeul-gallery-foreground"></div>
						<div class="kboard-hwaikeul-gallery-foreground-search"></div>
					<?php else:?>
						<a href="<?php echo $url->getDocumentURLWithUID($content->uid)?>">
							<div class="kboard-hwaikeul-gallery-container wide no-image"></div>
							<div class="kboard-hwaikeul-gallery-foreground"></div>
							<div class="kboard-hwaikeul-gallery-foreground-search"></div>
						</a>
					<?php endif?>
				</div>
				<div class="kboard-hwaikeul-gallery-wrap">
					<div class="kboard-hwaikeul-gallery-wrap">
						<a href="<?php echo $url->getDocumentURLWithUID($content->uid)?>">
							<div class="kboard-hwaikeul-gallery-title">
								<?php if($content->isNew()):?><span class="kboard-hwaikeul-gallery-new-notify">New</span><?php endif?>
								<?php if($content->secret):?><img src="<?php echo $skin_path?>/images/lock-gray-14.png" srcset="<?php echo $skin_path?>/images/lock-gray-28.png 2x, <?php echo $skin_path?>/images/lock-gray-42.png 3x" alt="<?php echo __('Secret', 'kboard')?>"><?php endif?>
								<?php echo $content->title?>
							</div>
						</a>
						<?php
						$info_value = array();
						$info_value[] = sprintf('<span class="kboard-info-value kboard-date">%s</span>', $content->getDate());
						$info_value[] = sprintf('<span class="kboard-info-value">%s</span>', __('Notice', 'kboard'));
						if($content->category1){
							$info_value[] = sprintf('<span class="kboard-info-value kboard-category1">%s</span>', $content->category1);
						}
						if($content->category2){
							$info_value[] = sprintf('<span class="kboard-info-value kboard-category2">%s</span>', $content->category2);
						}
						if($content->option->tree_category_1){
							for($i=1; $i<=$content->getTreeCategoryDepth(); $i++){
								$info_value[] = sprintf('<span class="kboard-info-value kboard-tree-category-'.$i.'">%s</span>', $content->option->{'tree_category_'.$i});
							}
						}
						?>
						<?php if($info_value):?>
						<div class="kboard-hwaikeul-gallery-info kboard-hwaikeul-gallery-cut-strings">
							<?php echo implode('<span class="kboard-info-separator">ㆍ</span>', $info_value);?>
						</div>
						<?php endif?>
					</div>
				</div>
			</div>
		</li>
		<?php endwhile?>
	</ul>
	<?php endif?>
	
	<ul class="kboard-list<?php if(kboard_hwaikeul_gallery_list($board)):?> <?php echo kboard_hwaikeul_gallery_list($board)?><?php endif?>">
	<?php while($content = $list->hasNext()):?>
		<li class="kboard-list-item<?php if($content->uid == kboard_uid()):?> kboard-list-selected<?php endif?>">
			<div class="item-padding">
				<div class="kboard-hwaikeul-gallery-thumbnail">
					<?php if($content->getThumbnail(600, 338)):?>
						<div class="kboard-light-gallery">
							<div class="kboard-hwaikeul-gallery-container wide target-image" data-thumb="<?php echo $content->getThumbnail(200, 200)?>" data-src="<?php echo $content->getThumbnail()?>">
								<img src="<?php echo $content->getThumbnail(600, 338)?>" alt="<?php echo esc_attr($content->title)?>">
							</div>
							<?php $media_list = $content->getMediaList()?>
							<?php if($media_list):?>
								<?php foreach($media_list as $media_item):?>
									<?php if($content->getThumbnail() == site_url($media_item->file_path)) continue?>
									<div class="kboard-hwaikeul-gallery-container wide target-image" data-thumb="<?php echo kboard_resize($media_item->file_path, 200, 200)?>" data-src="<?php echo site_url($media_item->file_path)?>">
										<img src="<?php echo kboard_resize($media_item->file_path, 600, 338)?>" alt="<?php echo esc_attr(basename($media_item->file_name))?>">
									</div>
								<?php endforeach?>
							<?php endif?>
						</div>
						<div class="kboard-hwaikeul-gallery-foreground"></div>
						<div class="kboard-hwaikeul-gallery-foreground-search"></div>
					<?php else:?>
						<a href="<?php echo $url->getDocumentURLWithUID($content->uid)?>">
							<?php if($content->getThumbnail(600, 338)):?>
								<div class="kboard-hwaikeul-gallery-container wide" style="background-image:url(<?php echo $content->getThumbnail(600, 338)?>)"></div>
							<?php else:?>
								<div class="kboard-hwaikeul-gallery-container wide no-image"></div>
							<?php endif?>
							<div class="kboard-hwaikeul-gallery-foreground"></div>
							<div class="kboard-hwaikeul-gallery-foreground-search"></div>
						</a>
					<?php endif?>
				</div>
				<div class="kboard-hwaikeul-gallery-wrap">
					<a href="<?php echo $url->getDocumentURLWithUID($content->uid)?>">
						<div class="kboard-hwaikeul-gallery-title">
							<?php if($content->isNew()):?><span class="kboard-hwaikeul-gallery-new-notify">New</span><?php endif?>
							<?php if($content->secret):?><img src="<?php echo $skin_path?>/images/lock-gray-14.png" srcset="<?php echo $skin_path?>/images/lock-gray-28.png 2x, <?php echo $skin_path?>/images/lock-gray-42.png 3x" alt="<?php echo __('Secret', 'kboard')?>"><?php endif?>
							<?php echo $content->title?>
						</div>
					</a>
					<?php
					$info_value = array();
					$info_value[] = sprintf('<span class="kboard-info-value kboard-date">%s</span>', $content->getDate());
					if($content->category1){
						$info_value[] = sprintf('<span class="kboard-info-value kboard-category1">%s</span>', $content->category1);
					}
					if($content->category2){
						$info_value[] = sprintf('<span class="kboard-info-value kboard-category2">%s</span>', $content->category2);
					}
					if($content->option->tree_category_1){
						for($i=1; $i<=$content->getTreeCategoryDepth(); $i++){
							$info_value[] = sprintf('<span class="kboard-info-value kboard-tree-category-'.$i.'">%s</span>', $content->option->{'tree_category_'.$i});
						}
					}
					?>
					<?php if($info_value):?>
					<div class="kboard-hwaikeul-gallery-info kboard-hwaikeul-gallery-cut-strings">
						<?php echo implode('<span class="kboard-info-separator">ㆍ</span>', $info_value);?>
					</div>
					<?php endif?>
				</div>
			</div>
		</li>
	<?php endwhile?>
	</ul>
	<!-- 리스트 끝 -->
	
	<!-- 페이징 시작 -->
	<div class="kboard-pagination">
		<ul class="kboard-pagination-pages">
			<?php echo kboard_pagination($list->page, $list->total, $list->rpp)?>
		</ul>
	</div>
	<!-- 페이징 끝 -->
	
	<div class="kboard-search">
    	<form id="kboard-search-form-<?php echo $board->id?>" method="get" action="<?php echo $url->toString()?>">
    		<?php echo $url->set('pageid', '1')->set('target', '')->set('keyword', '')->set('mod', 'list')->toInput()?>
    		
    		<div class="kboard-search-wrap">
	    		<select name="target">
	    			<option value=""><?php echo __('All', 'kboard')?></option>
	    			<option value="title"<?php if(kboard_target() == 'title'):?> selected<?php endif?>><?php echo __('Title', 'kboard')?></option>
	    			<option value="content"<?php if(kboard_target() == 'content'):?> selected<?php endif?>><?php echo __('Content', 'kboard')?></option>
	    			<option value="member_display"<?php if(kboard_target() == 'member_display'):?> selected<?php endif?>><?php echo __('Author', 'kboard')?></option>
	    		</select>
	    		<input type="text" name="keyword" value="<?php echo esc_attr(kboard_keyword())?>">
	    		<button type="submit" class="button-search" title="<?php echo __('Search', 'kboard')?>"><img src="<?php echo $skin_path?>/images/search-gray-20.png" srcset="<?php echo $skin_path?>/images/search-gray-40.png 2x, <?php echo $skin_path?>/images/search-gray-60.png 3x" alt="<?php echo __('Search', 'kboard')?>"></button>
    		</div>
    	</form>
	</div>
	
	<?php if($board->isWriter()):?>
	<!-- 버튼 시작 -->
	<div class="kboard-control">
		<a href="<?php echo $url->getContentEditor()?>" class="kboard-hwaikeul-gallery-button-small"><img class="button-icon icon-new" src="<?php echo $skin_path?>/images/edit-16.png" srcset="<?php echo $skin_path?>/images/edit-32.png 2x, <?php echo $skin_path?>/images/edit-48.png 3x" alt="<?php echo __('New', 'kboard')?>"> <span class="button-text text-new"><?php echo __('New', 'kboard')?></span></a>
	</div>
	<!-- 버튼 끝 -->
	<?php endif?>
	
	<?php if($board->contribution()):?>
	<div class="kboard-hwaikeul-gallery-poweredby">
		<a href="https://www.cosmosfarm.com/products/kboard" onclick="window.open(this.href);return false;" title="<?php echo __('KBoard is the best community software available for WordPress', 'kboard')?>">Powered by KBoard</a>
	</div>
	<?php endif?>
</div>

<?php
wp_enqueue_style('jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
wp_enqueue_script('jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js');
wp_enqueue_style('lightgallery', "{$skin_path}/lightgallery/css/lightgallery.min.css", array(), '1.6.11');
wp_enqueue_script('lightgallery-all', "{$skin_path}/lightgallery/js/lightgallery-all.min.js", array(), '1.6.11', true);
wp_enqueue_script('mousewheel', "{$skin_path}/lightgallery/js/mousewheel.min.js", array(), '3.1.12', true);
wp_enqueue_script('kboard-hwaikeul-gallery-list', "{$skin_path}/list.js", array('jquery'), KBOARD_VERSION, true);
?>