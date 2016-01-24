<?php
$output = '';
extract(shortcode_atts(array(
	'title' 		=> '',
	'title_color'	=>'',
	'value' 		=> '50',
	'units' 		=> '',
	'style'			=>'',
	'box_size'		=>'',
	'text_size'		=>'',
	'border_size'	=>'',
	'background_color'=>'',
	'border_color'	=>'',
	'text_color'	=>'',
	'color' 		=> 'default',
	'visibility'	=>'',
	'el_class'		=>'',
), $atts));

/**
 * script
 * {{
 */
wp_enqueue_script('vendor-ProgressCircle');

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
$border_color = dh_format_color($border_color);
$text_color =dh_format_color($text_color);
$background_color = dh_format_color($background_color);
$title_color = dh_format_color($title_color);
$data_custom = '';
if($color == 'custom'){
	$data_custom .=' data-background-color="'.$background_color.'" data-border-color="'.$border_color.'" data-text-color="'.$text_color.'"';
}
$output .='<div class="piechart piechart-style-'.$style.$el_class.'" data-value="'.absint($value).'"  data-units="'.$units.'" data-border-size="'.$border_size.'" data-size="'.absint($box_size).'">';
$output .='<div class="pichart-canvas'.($color != 'custom'? ' piechart-'.$color:' pichart-custom').'"'.$data_custom.'>';
$output .='<span class="pichart-canvas-back"></span>';
$output .='<span class="pichart-canvas-value" style="font-size:'.$text_size.'px;"></span>';
$output .='<canvas width="'.$box_size.'" height="'.$box_size.'"></canvas>';
$output .='</div>';
if(!empty($title)){
	$output .='<h3 class="el-heading" '.(!empty($title_color)? ' style="color:'.$title_color.'"':'').'>'.$title.'</h3>';
}
$output .='</div>';

echo $output;