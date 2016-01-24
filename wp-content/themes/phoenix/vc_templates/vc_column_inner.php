<?php
$output = array();
extract(shortcode_atts(array(
	'border'			=> '',
	'fade'      		=> '',
    'fade_animation' 	=> '',
    'fade_offset'    	=> '40',
    'bg_color'			=>'',
    'width'				=>'1/1',
    'visibility'		=>'',
    'el_class'			=>'',
), $atts));
$class          = !empty($el_class) ? 'column '.esc_attr( $el_class ) : 'column';
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
if ( preg_match( '/^(\d{1,2})\/12$/', $width, $match ) ) {
	$width = ' col-md-'.$match[1].' col-sm-6';
}else{
	switch ( $width ) {
		case '1/1' :
			$width = ' col-md-12';
			break;
		case '1/2' :
			$width = ' col-md-6 col-sm-6';
			break;
		case '1/3' :
			$width = ' col-md-4 col-sm-4';
			break;
		case '2/3' :
			$width = ' col-md-8 col-sm-6';
			break;
		case '1/4' :
			$width = ' col-md-3 col-sm-6';
			break;
		case '3/4' :
			$width = ' col-md-9 col-sm-6';
			break;
		case '1/6' :
			$width = ' col-md-2 col-sm-6';
			break;
		case '5/6' :
			$width = ' col-md-10 col-sm-12';
			break;
		default:
			$width = ' col-md-12';
	}
}
switch ( $border ) {
	case 'top' :
		$border = ' border-top';
		break;
	case 'left' :
		$border = ' border-left';
		break;
	case 'right' :
		$border = ' border-right';
		break;
	case 'bottom' :
		$border = ' border-bottom';
		break;
	case 'top-right' :
		$border = ' border-top border-right';
		break;
	case 'top-left' :
		$border = ' border-top border-left';
		break;
	case 'bottom-right' :
		$border = ' border-bottom border-right';
		break;
	case 'bottom-left' :
		$border = ' border-bottom border-left';
		break;
	case 'top-right-bottom' :
		$border = ' border-top border-right border-bottom';
		break;
	case 'top-left-bottom' :
		$border = ' border-top border-left border-bottom';
		break;
	case 'top-right-left' :
		$border = ' border-top border-right border-left';
		break;
	case 'bottom-right-left' :
		$border = ' border-bottom border-right border-left';
		break;
	case 'vertical' :
		$border = ' border-top border-bottom';
		break;
	case 'horizontal' :
		$border = ' border-left border-right';
		break;
	case 'all' :
		$border = ' border-top border-left border-right border-bottom';
		break;
	default :
		$border = '';
		break;
}
$class .= $border;


$fade_animation = (!empty($fade_animation)) ? $fade_animation : 'in';

if ( !empty($fade)) {
	$fade = 'data-fade="1" data-fade-animation="' . $fade_animation . '"';
	switch ( $fade_animation ) {
		case 'in' :
			$fade_offset = '';
			break;
		case 'in-from-top' :
			$fade_offset = ' top: -' . $fade_offset . 'px;';
			break;
		case 'in-from-left' :
			$fade_offset = ' left: -' . $fade_offset . 'px;';
			break;
		case 'in-from-right' :
			$fade_offset = ' right: -' . $fade_offset . 'px;';
			break;
		case 'in-from-bottom' :
			$fade_offset = ' bottom: -' . $fade_offset . 'px;';
			break;
	}
} else {
	$fade                  = '';
	$fade_offset = '';
}

$style = '';
if(!empty($fade_offset)){
	$style = ''.$fade_offset.';';
}
$bg_color = dh_format_color($bg_color);
if(!empty($bg_color))
	$style .= 'background-color:'.$bg_color.';';
if(!empty($style)){
	$style = 'style="'.$style.'"';
}

$output []= "<div class=\"{$class}{$width}\" {$style} {$fade}>" . wpb_js_remove_wpautop( $content ) . "</div>";
echo implode("\n",$output);