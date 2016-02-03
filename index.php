<?php
/*
Plugin Name: WP AjaxApi
Plugin URI:  http://www.easecloud.cn/
Description: WordPress AjaxApi
Version:     0.1
Author:      Alfred
Author URI:  http://www.huangwenchao.com.cn/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: null
Text Domain: wp_ajax_api
*/

define('AJA_DOMAIN', 'wp_ajax_api');

/**
 * 翻译支持
 */
add_action('plugins_loaded', function() {
    load_plugin_textdomain(
        AJA_DOMAIN,
        false,
        plugin_basename(dirname(__FILE__)).'/languages'
    );
});

require_once 'AjaxApi.class.php';
