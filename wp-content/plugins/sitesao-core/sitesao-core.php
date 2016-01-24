<?php
/*
Plugin Name: Sitesao Core
Plugin URI: http://sitesao.com/
Description: Sitesao Core Plugin for Phoenix Themes
Version: 1.1.13
Author: Sitesao Team
Author URI: http://sitesao.com/
Text Domain: sitesao
*/
if ( ! defined( 'ABSPATH' ) ) die( '-1' );

if(!defined('SITESAO_CORE_VERSION'))
	define('SITESAO_CORE_VERSION', '1.1.13');

if(!defined('DH_DOMAIN'))
	define( 'DH_DOMAIN' , 'sitesao' );

if(!defined('SITESAO_CORE_URL'))
	define('SITESAO_CORE_URL',untrailingslashit( plugins_url( '/', __FILE__ ) ));

if(!defined('SITESAO_CORE_DIR'))
	define('SITESAO_CORE_DIR',untrailingslashit( plugin_dir_path( __FILE__ ) ));

class SitesaoCore {
	public function __construct(){
		
	}
	
}
new SitesaoCore();