<?php

$output = $title = $interval = $el_class = $active_tab = '';
//
extract(shortcode_atts(array(
    'title'				=> '',
    'interval'		 	=> 0,
    'style'				=>'default',
    'icon_position'		=>'left',
    'icon_style'		=>'none',
    'active_tab' 		=>'1',
    'visibility'		=>'',
    'el_class'			=>'',
), $atts));
global $dh_accordion_active, $dh_accordion_count,$dh_accordion_id,$dh_accordion_style,$dh_accordion_icon_class;
$dh_accordion_active = $active_tab;
$dh_accordion_count = 0;
$dh_accordion_id ++;
$dh_accordion_style = $style;

$dh_accordion_icon_class = ' panel-icon-'.$icon_style.'-'.$icon_position;

$class          = !empty($el_class) ? 'accordion '. esc_attr( $el_class ) : 'accordion';
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

$output .= '<div class="'.$class.'">';
$output .= !empty($title) ? '<h3 class="el-heading">'.$title.'</h3>':'';
$output .= '<div id="accordion-'.$dh_accordion_id.'" class="panel-group">';
$output .= wpb_js_remove_wpautop($content);
$output .= '</div>';
$output .= '</div>';

echo $output;