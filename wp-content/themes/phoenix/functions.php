<?php

$themeInfo            =  wp_get_theme();
$themeName            = trim($themeInfo['Title']);
$themeAuthor          = trim($themeInfo['Author']);
$themeAuthor_URI      = trim($themeInfo['AuthorURI']);
$themeVersion         = trim($themeInfo['Version']);

define('DH_THEME_NAME', $themeName);
define('DH_THEME_AUTHOR', $themeAuthor);
define('DH_THEME_AUTHOR_URI', $themeAuthor_URI);
define('DH_THEME_VERSION', $themeVersion);
define('DH_THEME_DOMAIN',basename(dirname(__FILE__)));
if(!defined('DH_DOMAIN'))
	define( 'DH_DOMAIN' , DH_THEME_DOMAIN );

function dh_load_textdomain(){
	load_theme_textdomain( DH_THEME_DOMAIN, get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'dh_load_textdomain' );

function download_free_product(){
	if(isset($_GET['download_free'])){
		$filename = basename($_GET['file']);
		$file_path = $_GET['file'];
		do_action( 'woocommerce_download_file_force', $file_path, $filename );
	}
}
add_action('init', 'download_free_product');

if(defined('SITESAO_CORE_DIR')){
	include_once (SITESAO_CORE_DIR.'/includes/init.php');
	//woo coomerce lookbook
	include_once (SITESAO_CORE_DIR . '/lookbook/lookbook.php');
	//woo coomerce brand
	include_once (SITESAO_CORE_DIR . '/dhwc-brand/dhwc-brand.php');
}else{
	include_once (dirname( __FILE__ ).'/includes/functions.php');
}

require_once (dirname( __FILE__ ).'/includes/walker.php');

if ( ! isset( $content_width ) )
	$content_width = 1200;

function dh_portfolio_post_type_archive_title($title,$post_type){
	if($post_type == 'portfolio')
		return esc_html(dh_get_theme_option('portfolio-archive-title',__('My Portfolio',DH_THEME_DOMAIN)));
}
add_filter('post_type_archive_title', 'dh_portfolio_post_type_archive_title',100,2);

function dh_theme_support() {
	add_theme_support( 'nav-menus' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( "title-tag" );
	add_editor_style();
	add_image_size('dh-thumbnail-square',600,450, true);
	add_image_size('dh-thumbnail',700,350, true);
	
	add_image_size( 'wide', 1000, 500, true );
// 	add_image_size( 'regular', 500, 500, true );
	add_image_size( 'tall', 500, 1000, true );
// 	add_image_size( 'wide_tall', 1000, 1000, true );
	
	add_theme_support( 'woocommerce' );
	add_theme_support( 'post-formats', array('image', 'video', 'audio', 'quote', 'link', 'gallery'));
	
	$nav_menus['top'] = __( 'Top Menu', DH_THEME_DOMAIN );
	$nav_menus['primary'] = __( 'Primary Menu', DH_THEME_DOMAIN );
	$nav_menus['footer'] = __( 'Footer Menu', DH_THEME_DOMAIN );
	register_nav_menus( $nav_menus );
}
add_action( 'after_setup_theme', 'dh_theme_support' );

$plugin_path = get_template_directory() . '/includes/plugins';
if ( file_exists( $plugin_path . '/tgmpa_register.php' ) ) {
	include_once ( $plugin_path. '/tgm-plugin-activation.php');
	include_once ($plugin_path . '/tgmpa_register.php');
}


function dh_register_vendor_assets() {
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_deregister_style('dhvc-form-font-awesome');

	wp_register_style('vendor-font-awesome',get_template_directory_uri().'/assets/vendor/font-awesome/css/font-awesome' . $suffix . '.css', array(), '4.2.0' );
	wp_register_style('vendor-elegant-icon',get_template_directory_uri().'/assets/vendor/elegant-icon/css/elegant-icon.css');

	wp_register_style( 'vendor-preloader', get_template_directory_uri().'/assets/vendor/preloader/preloader.css', '1.0.0' );
	wp_register_script( 'vendor-preloader', get_template_directory_uri().'/assets/vendor/preloader/preloader'.$suffix.'.js', array('jquery') , '1.0.0', false );
	
	
	wp_register_script( 'vendor-ajax-chosen', get_template_directory_uri().'/assets/vendor/chosen/ajax-chosen.jquery' . $suffix . '.js', array( 'jquery', 'vendor-chosen' ), '1.0.0', true );
	wp_register_script( 'vendor-appear', get_template_directory_uri().'/assets/vendor/jquery.appear' . $suffix . '.js', array( 'jquery' ), '1.0.0', true );
	wp_register_script( 'vendor-typed', get_template_directory_uri().'/assets/vendor/typed' . $suffix .'.js', array( 'jquery','vendor-appear' ), '1.0.0', true );
	wp_register_script( 'vendor-easing', get_template_directory_uri().'/assets/vendor/easing' . $suffix . '.js', array( 'jquery' ), '1.3.0', true );
	wp_register_script( 'vendor-waypoints', get_template_directory_uri().'/assets/vendor/waypoints' . $suffix . '.js', array( 'jquery' ), '2.0.5', true );
	wp_register_script( 'vendor-transit', get_template_directory_uri().'/assets/vendor/jquery.transit' . $suffix . '.js', array( 'jquery' ), '0.9.12', true );
	wp_register_script( 'vendor-imagesloaded', get_template_directory_uri().'/assets/vendor/imagesloaded.pkgd' . $suffix . '.js', array( 'jquery' ), '3.1.8', true );

	wp_register_script( 'vendor-requestAnimationFrame', get_template_directory_uri().'/assets/vendor/requestAnimationFrame' . $suffix . '.js', null, '0.0.17', true );
	wp_register_script( 'vendor-parallax', get_template_directory_uri().'/assets/vendor/jquery.parallax' . $suffix . '.js', array( 'jquery'), '1.1.3', true );

	wp_register_script( 'vendor-boostrap', get_template_directory_uri().'/assets/vendor/bootstrap' . $suffix . '.js', array('jquery','vendor-imagesloaded'), '3.2.0', true );
	wp_register_script( 'vendor-superfish',get_template_directory_uri().'/assets/vendor/superfish-1.7.4.min.js',array( 'jquery' ),'1.7.4',true );

	wp_register_script( 'vendor-countTo', get_template_directory_uri().'/assets/vendor/jquery.countTo' . $suffix . '.js', array( 'jquery', 'vendor-appear' ), '2.0.2', true );
	wp_register_script( 'vendor-infinitescroll', get_template_directory_uri().'/assets/vendor/jquery.infinitescroll' . $suffix . '.js', array( 'jquery','vendor-imagesloaded' ), '2.0.2', true );

	wp_register_script( 'vendor-ProgressCircle', get_template_directory_uri().'/assets/vendor/ProgressCircle' . $suffix . '.js', array( 'jquery','vendor-appear'), '2.0.2', true );

	wp_register_style( 'vendor-magnific-popup', get_template_directory_uri().'/assets/vendor/magnific-popup/magnific-popup.css' );
	wp_register_script( 'vendor-magnific-popup', get_template_directory_uri().'/assets/vendor/magnific-popup/jquery.magnific-popup' . $suffix . '.js', array( 'jquery' ,'mediaelement'), '0.9.9', true );

	wp_register_script( 'vendor-touchSwipe', get_template_directory_uri().'/assets/vendor/jquery.touchSwipe' . $suffix . '.js', array( 'jquery'), '1.6.6', true );

	wp_register_script( 'vendor-carouFredSel', get_template_directory_uri().'/assets/vendor/jquery.carouFredSel' . $suffix . '.js', array( 'jquery','vendor-touchSwipe', 'vendor-easing','vendor-imagesloaded' ), '6.2.1', true );
	wp_register_script( 'vendor-isotope', get_template_directory_uri().'/assets/vendor/isotope.pkgd' . $suffix . '.js', array( 'jquery', 'vendor-imagesloaded' ), '6.2.1', true );

}
add_action( 'template_redirect', 'dh_register_vendor_assets' );

function dh_enqueue_theme_styles(){ 
	$protocol = dh_get_protocol();
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	$main_css_id = basename(get_template_directory());
	dh_enqueue_google_font();
	if(dh_get_theme_option('site-style','default') == 'creative')
		wp_enqueue_style('dh-google-font-montserrat','http://fonts.googleapis.com/css?family=Montserrat:400,700');
	
	wp_enqueue_style('vendor-elegant-icon');
	wp_enqueue_style('vendor-font-awesome');

	wp_register_style($main_css_id,get_template_directory_uri().'/assets/css/style-'.dh_get_theme_option('site-style','default').$suffix.'.css',false,DH_THEME_VERSION);

	if ( class_exists( 'WPCF7_ContactForm' ) ) :
		wp_deregister_style( 'contact-form-7' );
	endif;

	wp_enqueue_style($main_css_id);
	
	if(defined('WOOCOMMERCE_VERSION')){
		if(is_woocommerce() || is_cart()){
			wp_enqueue_style('vendor-magnific-popup');
			wp_enqueue_script('vendor-magnific-popup');
			wp_enqueue_script('vendor-carouFredSel');
		}
		wp_enqueue_style($main_css_id.'-woocommerce',get_template_directory_uri().'/assets/css/woocommerce-'.dh_get_theme_option('site-style','default').$suffix.'.css',array($main_css_id),DH_THEME_VERSION);
	}
	wp_register_style($main_css_id.'-wp',get_stylesheet_uri(),false,DH_THEME_VERSION);
	
	if($custom_css=dh_get_theme_option('custom-css')){
		wp_add_inline_style($main_css_id.'-wp',$custom_css);
	}
	$fix_el_apper = '@media (max-width: '.apply_filters('dh_js_breakpoint', 992).'px) {.animate-box.animated{visibility: visible;}.column[data-fade="1"]{opacity: 1;filter: alpha(opacity=100);}.el-appear{opacity: 1;filter: alpha(opacity=100);-webkit-transform: scale(1);-ms-transform: scale(1);-o-transform: scale(1);transform: scale(1);}}';
	wp_add_inline_style($main_css_id.'-wp', $fix_el_apper);
	wp_enqueue_style($main_css_id.'-wp');
}
add_action( 'wp_enqueue_scripts', 'dh_enqueue_theme_styles' );

function dh_enqueue_theme_script(){
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
	if(defined('WOOCOMMERCE_VERSION')) {
		wp_enqueue_script( 'wc-add-to-cart-variation' );
	}else{
		wp_enqueue_script( 'vendor-cookie', get_template_directory_uri().'/assets/vendor/jquery.cookie'.$suffix.'.js', array('jquery') , '1.4.1', false );
	}
		
	//wp_register_script('dh', get_template_directory_uri().'/assets/js/script'.$suffix.'.js',array('jquery','vendor-easing','vendor-infinitescroll','vendor-imagesloaded','vendor-parallax','vendor-boostrap','vendor-superfish','vendor-carouFredSel','vendor-isotope','vendor-appear','vendor-countTo','vendor-ProgressCircle'),DH_THEME_VERSION,true);
	wp_register_script('dh', get_template_directory_uri().'/assets/js/script'.$suffix.'.js',array('jquery','vendor-easing','vendor-boostrap','vendor-superfish','vendor-appear'),DH_THEME_VERSION,true);
	$logo_retina = '';
	$dhL10n = array(
		'ajax_url'=>admin_url( 'admin-ajax.php', 'relative' ),
		'protocol'=>dh_get_protocol(),
		'breakpoint'=>apply_filters('dh_js_breakpoint', 992),
		'nav_breakpoint'=>apply_filters('dh_nav_breakpoint', 992),
		'cookie_path'=>COOKIEPATH,
		'screen_sm'=>768,
		'screen_md'=>992,
		'screen_lg'=>1200,
		'touch_animate'=>apply_filters('dh_js_touch_animate', true),
		'logo_retina'=>$logo_retina,
		'ajax_finishedMsg'=>esc_attr__('All posts displayed',DH_THEME_DOMAIN),
		'ajax_msgText'=>esc_attr__('Loading the next set of posts...',DH_THEME_DOMAIN),
		'woocommerce'=>(defined('WOOCOMMERCE_VERSION') ? 1 : 0),
		'add_to_wishlist_text'=>(defined( 'YITH_FUNCTIONS' ) ? apply_filters( 'dh_yith_wcwl_button_label', get_option( 'yith_wcwl_add_to_wishlist_text' )) : ''),
		'user_logged_in'=>(is_user_logged_in() ? 1 : 0),
		'loadingmessage'=>esc_attr__('Sending info, please wait...',DH_THEME_DOMAIN),
	);
	wp_localize_script('dh', 'dhL10n', $dhL10n);
	wp_enqueue_script('dh');
}
add_action( 'wp_enqueue_scripts', 'dh_enqueue_theme_script' );

function dh_register_sidebar() {
	// Default Sidebar (WP main sidebar)
	register_sidebar( 
		array(  // 1
			'name' => __( 'Main Sidebar', DH_THEME_DOMAIN ), 
			'description' => __( 'This is main sidebar', DH_THEME_DOMAIN ), 
			'id' => 'sidebar-main', 
			'before_widget' => '<div id="%1$s" class="widget %2$s">', 
			'after_widget' => '</div>', 
			'before_title' => '<h4 class="widget-title"><span>', 
			'after_title' => '</span></h4>' ) );
	// Shop Sidebar (WP shop sidebar)
	register_sidebar( 
		array(  // 1
			'name' => __( 'Shop Sidebar', DH_THEME_DOMAIN ), 
			'description' => __( 'This sidebar use for Woocommerce page', DH_THEME_DOMAIN ), 
			'id' => 'sidebar-shop', 
			'before_widget' => '<div id="%1$s" class="widget %2$s">', 
			'after_widget' => '</div>', 
			'before_title' => '<h4 class="widget-title"><span>', 
			'after_title' => '</span></h4>' ) );
	
	for ( $i = 1; $i <= 5; $i++ ) :
		register_sidebar( 
			array( 
				'name' => __( 'Footer Sidebar #', DH_THEME_DOMAIN ) . $i, 
				'id' => 'sidebar-footer-' . $i, 
				'before_widget' => '<div id="%1$s" class="widget %2$s">', 
				'after_widget' => '</div>', 
				'before_title' => '<h3 class="widget-title"><span>', 
				'after_title' => '</span></h3>' ) );
	endfor;
	// Shop Sidebar (WP shop sidebar)
	register_sidebar( 
		array(  // 1
			'name' => __( 'Header Below Sidebar', DH_THEME_DOMAIN ), 
			'description' => __( 'This sidebar use for header style below', DH_THEME_DOMAIN ), 
			'id' => 'sidebar-header', 
			'before_widget' => '<div id="%1$s" class="widget %2$s">', 
			'after_widget' => '</div>', 
			'before_title' => '<h4 class="widget-title"><span>', 
			'after_title' => '</span></h4>' ) );
}

add_action( 'widgets_init', 'dh_register_sidebar' );