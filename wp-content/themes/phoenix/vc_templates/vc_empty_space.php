<?php
$output = array();
extract(shortcode_atts(array(
    'height' => 40,
    'visibility'		=>'',
    'el_class'			=>'',
), $atts));

$class          = !empty($el_class) ? 'empty-space '.esc_attr( $el_class ) : 'empty-space';
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
$pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
// allowed metrics: http://www.w3schools.com/cssref/css_units.asp
$regexr = preg_match($pattern,$height,$matches);
$value = isset( $matches[1] ) ? (float) $matches[1] : 40;
$unit = isset( $matches[2] ) ? $matches[2] : 'px';
$height = $value . $unit;

$inline_style = ( (float) $height >= 0.0 ) ? ' style="height: '.$height.'"' : '';
$output []='<div class="'.$class.'" '.$inline_style.'></div>';
echo implode("\n",$output);
