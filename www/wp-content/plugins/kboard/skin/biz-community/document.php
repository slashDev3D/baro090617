<div id="kboard-document">
	<div id="kboard-biz-community-document">
		<div class="kboard-header">
			<div class="kboard-control">
				<div class="left">
					<a href="<?php echo $url->getBoardList()?>" class="kboard-biz-community-button-gray"><?php echo __('List', 'kboard')?></a>
					<?php if($board->isWriter() && !$content->notice):?><a href="<?php echo $url->set('parent_uid', $content->uid)->set('mod', 'editor')->toString()?>" class="kboard-biz-community-button-gray"><?php echo __('Reply', 'kboard')?></a><?php endif?>
				</div>
				<?php if($content->isEditor() || $board->permission_write=='all'):?>
				<div class="right">
					<a href="<?php echo $url->getContentEditor($content->uid)?>" class="kboard-biz-community-button-gray"><?php echo __('Edit', 'kboard')?></a>
					<a href="<?php echo $url->getContentRemove($content->uid)?>" class="kboard-biz-community-button-gray" onclick="return confirm('<?php echo __('Are you sure you want to delete?', 'kboard')?>');"><?php echo __('Delete', 'kboard')?></a>
				</div>
				<?php endif?>
			</div>
		</div>
		
		<div class="kboard-document-wrap" itemscope itemtype="http://schema.org/Article">
			<meta itemprop="name" content="<?php echo kboard_htmlclear(strip_tags($content->title))?>">
			
			<h1 class="kboard-title"><?php echo $content->title?></h1>
			
			<div class="kboard-content" itemprop="description">
				<div class="content-view">
					<?php echo $content->getDocumentOptionsHTML()?>
					<?php echo $content->content?>
				</div>
			</div>
			
			<div class="kboard-detail">
				<span class="detail-attr kboard-user"><?php echo $content->getUserDisplay(sprintf('%s %s', get_avatar($content->getUserID(), 24, '', $content->getUserName()), $content->getUserName()))?></span>
				<span class="detail-separator kboard-date">·</span>
				<span class="detail-attr kboard-date"><?php echo date('Y-m-d H:i', strtotime($content->date))?></span>
				<span class="detail-separator kboard-view">·</span>
				<span class="detail-attr kboard-view"><?php echo __('Views', 'kboard')?> <?php echo $content->view?></span>
				
				<?php if($content->category1):?>
					<span class="detail-separator kboard-category1">·</span>
					<span class="detail-attr kboard-category1"><?php echo $content->category1?></span>
				<?php endif?>
				
				<?php if($content->category2):?>
					<span class="detail-separator kboard-category2">·</span>
					<span class="detail-attr kboard-category2"><?php echo $content->category2?></span>
				<?php endif?>
				
				<?php if($content->option->tree_category_1):?>
					<?php for($i=1; $i<=$content->getTreeCategoryDepth(); $i++):?>
						<span class="detail-separator kboard-tree-category category-<?php echo $i?>">·</span>
						<span class="detail-attr kboard-tree-category category-<?php echo $i?>"><?php echo $content->option->{'tree_category_'.$i}?></span>
					<?php endfor?>
				<?php endif?>
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
			
			<?php if($content->isAttached()):?>
			<div class="kboard-attach">
				<div class="kboard-attach-title"><?php echo __('Attachments', 'kboard')?> <span class="files-count">(<?php echo count((array)$content->getAttachmentList())?>)</span></div>
				<?php foreach($content->attach as $key=>$attach):?>
				<button type="button" class="kboard-button-action kboard-button-download" onclick="window.location.href='<?php echo $url->getDownloadURLWithAttach($content->uid, $key)?>'" title="<?php echo sprintf(__('Download %s', 'kboard'), $attach[1])?>"><?php echo $attach[1]?></button>
				<?php endforeach?>
			</div>
			<?php endif?>
		</div>
		
		<?php if($content->visibleComments()):?>
			<div class="kboard-comments-area"><?php echo $board->buildComment($content->uid)?></div>
		<?php else:
			$list = new KBContentList();
			$list->getReplyList($content->uid);?>
			<?php while($reply = $list->hasNextReply()):?>
			<div class="kboard-reply-area">
				<div class="reply-list-username" itemprop="author">
				<?php echo $reply->getUserDisplay(sprintf('%s %s', get_avatar($reply->getUserID(), 24, '', $reply->getUserName()), $reply->getUserName()))?>
				</div>
				<div class="reply-list-create" itemprop="dateCreated"><?php echo date('Y-m-d H:i', strtotime($reply->date))?></div>
				<div class="reply-list-content" itemprop="description"><?php echo wpautop($reply->content)?></div>
				<div class="reply-list-votes">
					<button type="button" class="kboard-button-action kboard-button-like" onclick="kboard_document_like(this)" data-uid="<?php echo $reply->uid?>" title="<?php echo __('Like', 'kboard')?>"><?php echo __('Like', 'kboard')?> <span class="kboard-document-like-count"><?php echo intval($reply->like)?></span></button>
					<button type="button" class="kboard-button-action kboard-button-unlike" onclick="kboard_document_unlike(this)" data-uid="<?php echo $reply->uid?>" title="<?php echo __('Unlike', 'kboard')?>"><?php echo __('Unlike', 'kboard')?> <span class="kboard-document-unlike-count"><?php echo intval($reply->unlike)?></span></button>
				</div>
				<?php if($reply->isAttached()):?>
					<div class="reply-list-attach">
					<?php foreach($reply->attach as $key=>$attach):?>
					<button type="button" class="kboard-button-action kboard-button-download" onclick="window.location.href='<?php echo $url->getDownloadURLWithAttach($reply->uid, $key)?>'" title="<?php echo sprintf(__('Download %s', 'kboard'), $attach[1])?>"><?php echo $attach[1]?></button>
					<?php endforeach?>
					</div>
				<?php endif?>
				<?php if($content->isEditor() || $board->permission_write=='all'):?>
				<div class="reply-list-contol">
					<a href="<?php echo $url->getContentEditor($reply->uid)?>" class="kboard-biz-community-button-gray"><?php echo __('Edit', 'kboard')?></a>
					<a href="<?php echo $url->getContentRemove($reply->uid)?>" class="kboard-biz-community-button-gray" onclick="return confirm('<?php echo __('Are you sure you want to delete?', 'kboard')?>');"><?php echo __('Delete', 'kboard')?></a>
				</div>
				<?php endif?>
			</div>
			<?php endwhile?>
		<?php endif?>
		
		<div class="kboard-document-navi">
			<div class="kboard-prev-document">
				<?php
				$bottom_content_uid = $content->getPrevUID();
				if($bottom_content_uid):
				$bottom_content = new KBContent();
				$bottom_content->initWithUID($bottom_content_uid);
				?>
				<a href="<?php echo $url->getDocumentURLWithUID($bottom_content_uid)?>">
					<span class="navi-arrow">«</span>
					<span class="navi-document-title kboard-biz-community-cut-strings"><?php echo $bottom_content->title?></span>
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
				<a href="<?php echo $url->getDocumentURLWithUID($top_content_uid)?>">
					<span class="navi-document-title kboard-biz-community-cut-strings"><?php echo $top_content->title?></span>
					<span class="navi-arrow">»</span>
				</a>
				<?php endif?>
			</div>
		</div>
		
		<?php if($board->contribution() && !$board->meta->always_view_list):?>
		<div class="kboard-biz-community-poweredby">
			<a href="http://www.cosmosfarm.com/products/kboard" onclick="window.open(this.href);return false;" title="<?php echo __('KBoard is the best community software available for WordPress', 'kboard')?>">Powered by KBoard</a>
		</div>
		<?php endif?>
	</div>
</div>