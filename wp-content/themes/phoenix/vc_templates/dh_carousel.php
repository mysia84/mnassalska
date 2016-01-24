<?php
$output = '';
extract(shortcode_atts(array(
	'title'				=>'',
	'fx'				=>'scroll',
	'visible'			=>'1',
	'scroll_item'		=>'1',
	'scroll_speed'		=>'',
	'easing'			=>'',
	'auto_play'			=>'',
	'hide_pagination'	=>'',
	'hide_control'		=>'',
	'visibility'		=>'',
	'el_class'			=>'',
), $atts));
global $dh_carousel_visible;
$dh_carousel_visible = $visible;
$el_class  = !empty($el_class) ? ' '.esc_attr( $el_class ) : '';
switch ($visibility) {
	case 'hidden-phone':
		$el_class .= ' hidden-xs';
		break;
	case 'hidden-tablet':
		$el_class .= ' hidden-sm hidden-md';
		break;
	case 'hidden-pc':
		$el_class .= ' hidden-lg';
		break;
	case 'visible-phone':
		$el_class .= ' visible-xs-inline';
		break;
	case 'visible-tablet':
		$el_class .= ' visible-sm-inline visible-md-inline';
		break;
	case 'visible-pc':
		$el_class .= ' visible-lg-inline';
		break;
}
/**
 * script
 * {{
 */
wp_enqueue_script('vendor-carouFredSel');
$output .='<div class="caroufredsel'.$el_class.'" data-scroll-fx="'.$fx.'" data-speed="'.$scroll_speed.'" data-easing="'.$easing.'" data-scroll-item="'.$scroll_item.'" data-visible-min="1" data-visible-max="'.$visible.'" data-responsive="1" data-infinite="1" data-autoplay="'.(!empty($auto_play)?'1':'0').'">';
$output .= !empty($title) ? '<h3 class="el-heading">'.$title.'</h3>':'';
$output .='<div class="caroufredsel-wrap">';
$output .='<ul class="caroufredsel-items row">';
$output .= wpb_js_remove_wpautop( $content );
$output .='</ul>';
if(empty($hide_control)){
	$output .='<a href="#" class="caroufredsel-prev"></a>';
	$output .='<a href="#" class="caroufredsel-next"></a>';
}
$output .='</div>';
if(empty($hide_pagination)){
	$output .='<div class="caroufredsel-pagination">';
	$output .='</div>';
}
$output .='</div>';
echo $output;
