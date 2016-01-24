<?php
$output = '';
extract(shortcode_atts(array(
	'background_transparent'=>'',
	'color'					=>'',
	'fx'					=>'scroll',
	'visibility'			=>'',
	'el_class'				=>'',
), $atts));

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
$color = dh_format_color($color);
$output .='<div class="testimonial'.(!empty($background_transparent)?' bg-transparent':'').$el_class.'">';
$output .='<div class="caroufredsel" data-visible="1" data-scroll-fx="'.$fx.'"  data-speed="5000" data-responsive="1" data-infinite="1" data-autoplay="0">';
$output .='<div class="caroufredsel-wrap">';
$output .='<ul class="caroufredsel-items"'.(!empty($color) ? ' style="color:'.$color.'"':'').'>';
$output .= wpb_js_remove_wpautop( $content );
$output .='</ul>';
$output .='</div>';
$output .= (!empty($color)?'<style>.testimonial .caroufredsel .caroufredsel-pagination a{border-color:'.$color.'}.testimonial .caroufredsel .caroufredsel-pagination a.selected,.testimonial .caroufredsel .caroufredsel-pagination a:hover{background-color:'.$color.'}</style>':'');
$output .='<div class="caroufredsel-pagination">';
$output .='</div>';
$output .='</div>';
$output .='</div>';
echo $output;
