<?php
/*
Plugin Name: KBoard 비즈 커뮤니티 스킨
Plugin URI: https://www.cosmosfarm.com/wpstore/product/kboard-biz-community-skin
Description: KBoard 비즈 커뮤니티 스킨입니다.
Version: 1.2
Author: 코스모스팜 - Cosmosfarm
Author URI: https://www.cosmosfarm.com/
*/

if(!defined('ABSPATH')) exit;

add_filter('kboard_skin_list', 'kboard_skin_list_biz_commnumity', 10, 1);
function kboard_skin_list_biz_commnumity($list){
    
	$skin = new stdClass();
	$skin->dir = dirname(__FILE__);
	$skin->url = plugins_url('', __FILE__);
	$skin->name = basename($skin->dir);
    
	$list[$skin->name] = $skin;
    
	return $list;
}