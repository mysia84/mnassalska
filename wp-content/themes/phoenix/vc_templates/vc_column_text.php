<?php
$output = array();
extract(shortcode_atts(array(
    'visibility'		=>'',
    'el_class'			=>'',
), $atts));

$class          = !empty($el_class) ? 'text-block '.esc_attr( $el_class ) : 'text-block';
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
!empty($class) ? $class = 'class="'.$class.'"' : '';
$output []= "<div {$class}>" . wpb_js_remove_wpautop( $content ,true) . "</div>";
echo implode("\n",$output);