<?php
$output = '';
extract(shortcode_atts(array(
    'cite' 				=> '',
    'alignment'			=> 'left',
	'border_color'		=>'border_color',
    'visibility'		=>'',
    'el_class'			=>'',
), $atts));

$class          = !empty($el_class) ? 'blockquote-'.$border_color.($alignment === 'right' ? ' blockquote-reverse':'').' '. esc_attr( $el_class ) : 'blockquote-'.$border_color.($alignment === 'right' ? ' blockquote-reverse':'');
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
$output .='<blockquote class="'.$class.'">';
$output .= wpb_js_remove_wpautop($content, true);
if(!empty($cite)){
	$output .='<footer><cite>'.$cite.'</cite></footer>';
}
$output .='</blockquote>';
echo $output;