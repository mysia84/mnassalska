<?php
$output = array();
extract(shortcode_atts(array(
	'animation'			=>'',
	'animation_timing'	=>'',
	'animation_duration'=>'',
	'animation_delay'	=>'',
	'visibility'		=>'',
	'el_class'			=>'',
), $atts));

$class          = !empty($el_class) ? 'animate-box '.esc_attr( $el_class ) : 'animate-box';
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

$inline_style='';
$data_animation = '';
if(!empty($animation)){
	$data_animation = 'data-animate="1"';
	$class .=' animated '.$animation.' '.$animation_timing;
	$inline_style = "style=\"-webkit-animation-duration:".$animation_duration."ms;animation-duration:".$animation_duration."ms; -webkit-animation-delay:".$animation_delay."ms;animation-delay:".$animation_delay."ms\"";
}

!empty($class) ? $class = 'class="'.$class.'"' : '';

$output []= "<div {$class} {$data_animation} {$inline_style}>" . wpb_js_remove_wpautop( $content ) . "</div>";
echo implode("\n",$output);
