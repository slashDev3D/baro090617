<?php
if(!defined('ABSPATH')) exit;

wp_enqueue_style('font-awesome-5', 'https://use.fontawesome.com/releases/v5.13.0/css/all.css', array(), '5.13.0');

if(!function_exists('kboard_biz_commnunity_reply_cound')){
	function kboard_biz_commnunity_reply_cound($content_uid){
		global $wpdb;
		
		$reply_count = 0;
		
		if($content_uid){
			$reply_count = $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}kboard_board_content` WHERE `parent_uid`='{$content_uid}' AND (`status`='' OR `status` IS NULL OR `status`='pending_approval')");
		}
		
		return $reply_count;
	}
}