<div id="kboard-document">
    <div id="kboard-hwaikeul-gallery-document">
    	<div class="kboard-document-wrap" itemscope itemtype="http://schema.org/Article">
    		<h1 class="kboard-title" itemprop="name">
    			<?php echo $content->title?>
    		</h1>
    		
			<?php if($content->getThumbnail()):?>
			<div class="kboard-hwaikeul-gallery-thumbnail">
				<div class="kboard-light-gallery">
					<div class="kboard-hwaikeul-gallery-container wide target-image" data-thumb="<?php echo $content->getThumbnail(200, 200)?>" data-src="<?php echo $content->getThumbnail()?>">
						<img src="<?php echo $content->getThumbnail()?>" alt="<?php echo esc_attr($content->title)?>">
					</div>
					<?php $media_list = $content->getMediaList()?>
					<?php if($media_list):?>
						<?php foreach($media_list as $media_item):?>
							<?php if($content->getThumbnail() == site_url($media_item->file_path)) continue?>
							<div class="kboard-hwaikeul-gallery-container wide target-image" data-thumb="<?php echo kboard_resize($media_item->file_path, 200, 200)?>" data-src="<?php echo site_url($media_item->file_path)?>">
								<img src="<?php echo site_url($media_item->file_path)?>" alt="<?php echo esc_attr(basename($media_item->file_name))?>">
							</div>
						<?php endforeach?>
					<?php endif?>
				</div>
				<div class="kboard-hwaikeul-gallery-foreground"></div>
				<div class="kboard-hwaikeul-gallery-foreground-search"></div>
			</div>
			<?php endif?>
			
			<?php if($content->isAttached()):?>
			<div class="kboard-attach-group">
				<div class="kboard-attach-group-download"><?php echo __('Download', 'kboard')?> <span class="files-count">(<?php echo count((array)$content->getAttachmentList())?>)</span></div>
				<?php foreach($content->getAttachmentList() as $key=>$file):?>
				<div class="kboard-attach">
					<button type="button" onclick="window.location.href='<?php echo $url->getDownloadURLWithAttach($content->uid, $key)?>'" title="<?php echo esc_attr(sprintf(__('Download %s', 'kboard'), $file[1]))?>">
						<div class="download-icon">
							<img src="<?php echo $skin_path?>/images/download-14.png" srcset="<?php echo $skin_path?>/images/download-28.png 2x, <?php echo $skin_path?>/images/download-42.png 3x" alt="download">
						</div>
						<div class="file-name kboard-hwaikeul-gallery-cut-strings"><?php echo $file[1]?></div>
					</button>
				</div>
				<?php endforeach?>
			</div>
			<?php endif?>
			
			<?php if($content->category1 || $content->category2):?>
			<div class="kboard-category">
				<?php if($content->category1):?>
				<span class="category-name category1">#<?php echo $content->category1?></span>
				<?php endif?>
				<?php if($content->category2):?>
				<span class="category-name category2">#<?php echo $content->category2?></span>
				<?php endif?>
			</div>
			<?php endif?>
			<?php if($content->option->tree_category_1):?>
			<div class="kboard-category">
				<?php for($i=1; $i<=$content->getTreeCategoryDepth(); $i++):?>
				<span class="category-name category<?php echo $i?>">#<?php echo $content->option->{'tree_category_'.$i}?></span>
				<?php endfor?>
			</div>
			<?php endif?>
			
			<div class="kboard-content" itemprop="description">
				<div class="content-view">
					<?php echo $content->getDocumentOptionsHTML()?>
					<?php echo $content->content?>
				</div>
			</div>
			
			<div class="kboard-detail">
				<span class="kboard-user-display">
					<?php echo get_avatar($content->member_uid, 20, '', $content->member_display, array('class'=>'kboard-avatar'))?>
					<?php echo $content->getUserDisplay()?>
				</span>
				<span class="detail-separator kboard-date">·</span>
				<span class="detail-attr kboard-date"><?php echo date('Y-m-d H:i', strtotime($content->date))?></span>
				<span class="detail-separator kboard-view">·</span>
				<span class="detail-attr kboard-view"><?php echo __('Views', 'kboard')?> <?php echo $content->view?></span>
			</div>
			
			<div class="kboard-document-action">
				<div class="left">
					<button type="button" class="kboard-button-action kboard-button-like" onclick="kboard_document_like(this)" data-uid="<?php echo $content->uid?>" title="<?php echo __('Like', 'kboard')?>"><?php echo __('Like', 'kboard')?> <span class="kboard-document-like-count"><?php echo intval($content->like)?></span></button>
					<button type="button" class="kboard-button-action kboard-button-unlike" onclick="kboard_document_unlike(this)" data-uid="<?php echo $content->uid?>" title="<?php echo __('Unlike', 'kboard')?>"><?php echo __('Unlike', 'kboard')?> <span class="kboard-document-unlike-count"><?php echo intval($content->unlike)?></span></button>
				</div>
				<div class="right">
					<button type="button" class="kboard-button-action kboard-button-print" onclick="kboard_document_print('<?php echo $url->getDocumentPrint($content->uid)?>')" title="<?php echo __('Print', 'kboard')?>"><?php echo __('Print', 'kboard')?></button>
				</div>
			</div>
		</div>
		
		<?php if($content->visibleComments()):?>
		<div class="kboard-comments-area"><?php echo $board->buildComment($content->uid)?></div>
		<?php endif?>
		
		<div class="kboard-document-navi">
			<div class="kboard-prev-document">
				<?php
				$bottom_content_uid = $content->getPrevUID();
				if($bottom_content_uid):
				$bottom_content = new KBContent();
				$bottom_content->initWithUID($bottom_content_uid);
				?>
				<a href="<?php echo $url->getDocumentURLWithUID($bottom_content_uid)?>"title="<?php echo esc_attr($bottom_content->title)?>">
					<span class="navi-arrow"><img src="<?php echo $skin_path?>/images/prev-16.png" srcset="<?php echo $skin_path?>/images/prev-32.png 2x, <?php echo $skin_path?>/images/prev-48.png 3x" alt="<?php echo __('Prev', 'kboard')?>"></span>
					<span class="navi-document-title kboard-hwaikeul-gallery-cut-strings"><?php echo $bottom_content->title?></span>
				</a>
				<?php endif?>
			</div>
			
			<div class="kboard-next-document">
				<?php
				$top_content_uid = $content->getNextUID();
				if($top_content_uid):
				$top_content = new KBContent();
				$top_content->initWithUID($top_content_uid);
				?>
				<a href="<?php echo $url->getDocumentURLWithUID($top_content_uid)?>" title="<?php echo esc_attr($top_content->title)?>">
					<span class="navi-document-title kboard-hwaikeul-gallery-cut-strings"><?php echo $top_content->title?></span>
					<span class="navi-arrow"><img src="<?php echo $skin_path?>/images/next-16.png" srcset="<?php echo $skin_path?>/images/next-32.png 2x, <?php echo $skin_path?>/images/next-48.png 3x" alt="<?php echo __('Next', 'kboard')?>"></span>
				</a>
				<?php endif?>
			</div>
		</div>
		
		<div class="kboard-control">
			<div class="left">
				<a href="<?php echo $url->getBoardList()?>" class="kboard-hwaikeul-gallery-button-small"><img class="button-icon icon-list" src="<?php echo $skin_path?>/images/list-16.png" srcset="<?php echo $skin_path?>/images/list-32.png 2x, <?php echo $skin_path?>/images/list-48.png 3x" alt="<?php echo __('List', 'kboard')?>"> <span class="button-text text-list"><?php echo __('List', 'kboard')?></span></a>
			</div>
			<?php if($content->isEditor() || $board->permission_write=='all'):?>
			<div class="right">
				<a href="<?php echo $url->getContentEditor($content->uid)?>" class="kboard-hwaikeul-gallery-button-small"><img class="button-icon icon-edit" src="<?php echo $skin_path?>/images/edit-16.png" srcset="<?php echo $skin_path?>/images/edit-32.png 2x, <?php echo $skin_path?>/images/edit-48.png 3x" alt="<?php echo __('Edit', 'kboard')?>"> <span class="button-text text-edit"><?php echo __('Edit', 'kboard')?></span></a>
				<a href="<?php echo $url->getContentRemove($content->uid)?>" class="kboard-hwaikeul-gallery-button-small" onclick="return confirm('<?php echo __('Are you sure you want to delete?', 'kboard')?>');"><img class="button-icon icon-remove" src="<?php echo $skin_path?>/images/remove-16.png" srcset="<?php echo $skin_path?>/images/remove-32.png 2x, <?php echo $skin_path?>/images/remove-48.png 3x" alt="<?php echo __('Delete', 'kboard')?>"> <span class="button-text text-delete"><?php echo __('Delete', 'kboard')?></span></a>
			</div>
			<?php endif?>
		</div>
		
		<?php if($board->contribution() && !$board->meta->always_view_list):?>
		<div class="kboard-hwaikeul-gallery-poweredby">
			<a href="https://www.cosmosfarm.com/products/kboard" onclick="window.open(this.href);return false;" title="<?php echo __('KBoard is the best community software available for WordPress', 'kboard')?>">Powered by KBoard</a>
		</div>
		<?php endif?>
	</div>
</div>

<?php
wp_enqueue_style('lightgallery', "{$skin_path}/lightgallery/css/lightgallery.min.css", array(), '1.6.11');
wp_enqueue_script('lightgallery-all', "{$skin_path}/lightgallery/js/lightgallery-all.min.js", array(), '1.6.11', true);
wp_enqueue_script('mousewheel', "{$skin_path}/lightgallery/js/mousewheel.min.js", array(), '3.1.12', true);
wp_enqueue_script('kboard-hwaikeul-gallery-document', "{$skin_path}/document.js", array('jquery'), KBOARD_VERSION, true);
?>