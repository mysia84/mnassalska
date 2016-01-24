<?php
$output = $color = $el_class = $css_animation = '';
extract(shortcode_atts(array(
    'color' 			=> 'default',
    'style' 			=> '',
    'dismissible'		=> '',
    'visibility'		=>'',
    'el_class'			=>'',
), $atts));

$class          = !empty($el_class) ? ' '. esc_attr( $el_class ) : '';
switch ($visibility) {
	case 'hidden-phone':
		$class .= ' hidden-xs';
		break;
	case 'hidden-tablet':
		$class .= ' hidden-sm hidden-md';
		break;
	case 'hidden-pc':
		$class .= ' hidden-lg';
		break;
	case 'visible-phone':
		$class .= ' visible-xs-inline';
		break;
	case 'visible-tablet':
		$class .= ' visible-sm-inline visible-md-inline';
		break;
	case 'visible-pc':
		$class .= ' visible-lg-inline';
		break;
}

$output .='<div class="alert alert-'.$color.' alert-style-'.$style.''.(!empty($dismissible) ? ' alert-dismissible':'').$class.'" role="alert">';
if(!empty($dismissible)){
	$output .='<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">'.__('Close',DH_THEME_DOMAIN).'</span></button>';
}
$output .= wpb_js_remove_wpautop($content, true);
$output .= '</div>';
echo $output;