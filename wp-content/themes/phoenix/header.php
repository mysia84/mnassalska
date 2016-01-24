<?php 
	$header_style = dh_get_theme_option('header-style','below');
// 	if(dh_get_theme_option('site-style','default')=='creative')
// 		$header_style = 'center';
	
	$menu_transparent = dh_get_theme_option('menu-transparent',0);
	$page_heading = dh_get_post_meta('page_heading',get_the_ID(),'heading');
	$page_heading_background_image = dh_get_post_meta('page_heading_background_image');
	$page_heading_background_image_url = wp_get_attachment_url($page_heading_background_image);
	$page_heading_title = dh_get_post_meta('page_heading_title');
	if(empty($page_heading_title))
		$page_heading_title = dh_page_title(false);
	$page_heading_sub_title = dh_get_post_meta('page_heading_sub_title');
	
	$logo_url = dh_get_theme_option('logo');
	$logo_fixed_url = dh_get_theme_option('logo-fixed','');
	$logo_transparent_url = dh_get_theme_option('logo-transparent','');
	$logo_mobile_url = dh_get_theme_option('logo-mobile','');
	if(empty($logo_fixed_url))
		$logo_fixed_url = $logo_url;
	if(empty($logo_mobile_url))
		$logo_mobile_url = $logo_url;
	
	if($menu_transparent)
		$logo_url = $logo_transparent_url;
?>
<!doctype html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
<title><?php wp_title("-",true, 'right'); ?></title>
<?php if ($favicon_url = dh_get_theme_option('favicon')) { ?>
<link rel="shortcut icon" href="<?php echo esc_url($favicon_url); ?>">
<?php } ?>
<?php if ($apple57_url = dh_get_theme_option('apple57')) { ?>
<link rel="apple-touch-icon-precomposed" href="<?php echo esc_url($apple57_url); ?>"><?php } ?>   
<?php if ($apple72 = dh_get_theme_option('apple72')) { ?>
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo esc_url($apple72); ?>"><?php } ?>   
<?php if ($apple114 = dh_get_theme_option('apple114')) { ?>
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo esc_url($apple114); ?>"><?php } ?> 
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php if(defined('DHINC_ASSETS_URL')):?>
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="<?php echo DHINC_ASSETS_URL ?>/vendor/html5shiv.min.js"></script>
<![endif]-->
<?php endif;?>
<?php wp_head(); ?>
</head> 
<body <?php body_class(); ?> data-spy="scroll">
<?php do_action( 'dh_before_body' ); ?>
<a class="sr-only sr-only-focusable" href="#main"><?php echo esc_html_e('Skip to main content',DH_THEME_DOMAIN) ?></a>
<div id="wrapper" class="<?php echo dh_get_theme_option('site-layout','wide') ?>-wrap">		
	<?php 
	if(function_exists('dh_morphsearchform') && ($header_style =='center') && dh_get_theme_option('show-topbar',1))
		echo dh_morphsearchform();
	?>
	<?php 
	dh_get_template('header/'.$header_style.'.php',array(
		'header_style'					=>$header_style,
		'menu_transparent'				=>$menu_transparent,
		'logo_url'						=>$logo_url,
		'logo_fixed_url'				=>$logo_fixed_url,
		'logo_mobile_url'				=>$logo_mobile_url,
	));
	?>
	<?php 
	$heading_menu_anchor = dh_get_post_meta('heading_menu_anchor');
	?>
	<?php if($page_heading === 'rev' && ($rev_alias = dh_get_post_meta('rev_alias'))):?>
	<div<?php echo (!empty($heading_menu_anchor) ? ' id ="'.esc_attr($heading_menu_anchor).'"': '')?> class="main-slider">
		<div class="main-slider-wrap">
			<?php echo do_shortcode('[rev_slider '.$rev_alias.']')?>
		</div>
	</div>
	<?php endif;?>
	<?php if($page_heading ==='highlighted_post'):?>
		<?php 
		$cats = dh_get_post_meta('highligh_cat');
		$intro_cats = dh_get_post_meta('highligh_intro_cat');
		dh_highlighted_post($cats,$intro_cats);
		?>
	<?php endif;?>
	<?php if($page_heading == 'heading'):?>
		<?php if(!empty($page_heading_background_image_url) && !empty($page_heading_title)):?>
		<div<?php echo (!empty($heading_menu_anchor) ? ' id ="'.esc_attr($heading_menu_anchor).'"': '')?> class="heading-container heading-resize">
			<div class="heading-background heading-parallax" style="background-image: url('<?php echo esc_url($page_heading_background_image_url) ?>');">			
				<?php 
				/**
				 * script
				 * {{
				 */
				wp_enqueue_script('vendor-parallax');
				wp_enqueue_script('vendor-imagesloaded');
				?>
				<div class="<?php dh_container_class() ?>">
					<div class="row heading-wrap">
						<div class="col-md-12 page-title parallax-content">
							<h1><?php echo esc_html($page_heading_title) ?></h1>
							<?php if(!empty($page_heading_sub_title)):?>
							<span class="subtitle"><?php echo dhecho($page_heading_sub_title) ?></span>
							<?php endif;?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php else : ?>
		<div<?php echo (!empty($heading_menu_anchor) ? ' id ="'.esc_attr($heading_menu_anchor).'"': '')?> class="heading-container <?php /*heading-border*/?>">
			<div class="<?php dh_container_class() ?> heading-standar">
				<div class="heading-wrap">
					<div class="page-title">
						<h1><?php dh_page_title()?></h1>
					</div>
					<?php if(dh_get_theme_option('breadcrumb',1)):?>
					<div class="page-breadcrumb" itemprop="breadcrumb">
						<?php dh_the_breadcrumb()?>
					</div>
					<?php endif;?>
				</div>
			</div>
		</div>
		<?php endif;?>
	<?php endif;?>
	<?php do_action('dh_heading')?>