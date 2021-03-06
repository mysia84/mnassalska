<?php
$output = '';
extract( shortcode_atts( array(
	'title' 				=> __( 'Button', DH_THEME_DOMAIN ),
	'href' 					=> '',
	'data_toggle'			=>'',
	'data_target'			=>'',
	'target'				=>'_self',
	'style'					=>'',
	'size' 					=> '',
	'font_size' 			=> '14',
	'border_width' 			=> '1',
	'padding_top' 			=> '6',
	'padding_right'			=>'12',
	'padding_bottom'		=>'6',
	'padding_left'			=>'12',
	'color' 				=> '',
	'background_color'		=>'',
	'border_color'			=>'',
	'text_color'			=>'',
	'hover_background_color'=>'',
	'hover_border_color'	=>'',
	'hover_text_color'		=>'',
	'icon'					=>'',
	'icon_position'			=>'',
	'effect'				=>'',
	'text_uppercase'		=>'',
	'block_button'			=>'',
	'tooltip'				=>'',
	'tooltip_position'		=>'',
	'tooltip_title'			=>'',
	'tooltip_content'		=>'',
	'tooltip_trigger'		=>'',
	'alignment'				=>'left',
	'visibility'			=>'',
	'el_class'				=>'',
), $atts ) );

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
if ( $target == 'same' || $target == '_self' ) {
	$target = '';
}
$target = ( $target != '' ) ? ' target="' . $target . '"' : '';
$inline_style = '';
$btn_size = '';
if($size == 'custom'){
	$inline_style .= 'padding:'.$padding_top.'px '.$padding_right.'px '.$padding_bottom.'px '.$padding_left.'px;border-width:'.$border_width.'px;font-size:'.$font_size.'px;';
}elseif($size != 'default'){
	$btn_size = ' btn-'.$size;
}
$btn_color = '';
$data_cusotom_color='';
if($color == 'custom'){
	$inline_style .='background-color:'.$background_color.';border-color:'.$border_color.';color:'.$text_color;
	$btn_color = ' btn-custom-color';
	$hover_background_color = dh_format_color($hover_background_color);
	$hover_border_color = dh_format_color($hover_border_color);
	$hover_text_color = dh_format_color($hover_text_color);
	$data_cusotom_color .= ' data-hover-background-color="'.$hover_background_color.'" data-hover-border-color="'.$hover_border_color.'" data-hover-color="'.$hover_text_color.'"';
}else{
	$btn_color = ' btn-'.$color;
}
$btn_style = $style != 'custom' ? ' btn-style-'.$style:'';

$btn_effect = !empty($effect) && $color != 'custom' && $color != 'default' ? ' btn-effect-'.str_replace('_','-',$effect):'';

$btn_class = 'btn'.$btn_color.(!empty($icon) ? ' btn-icon-'.$icon_position:'').(!empty($text_uppercase) ? ' btn-uppercase':'').(!empty($block_button)?' btn-block':'').$btn_size.$btn_style.$btn_effect.(empty($block_button) ? ' btn-align-'.$alignment: '') ;
$data_el = '';
if(!empty($tooltip)){
	$data_toggle = $tooltip;
	$data_el = ' data-container="body" data-original-title="'.($tooltip === 'tooltip' ? esc_attr($tooltip_content) : esc_attr($tooltip_title)).'" data-trigger="'.$tooltip_trigger.'" data-placement="'.$tooltip_position.'" '.($tooltip === 'popover'?' data-content="'.esc_attr($tooltip_content).'"':'').'';
}
if(!empty($data_toggle)){
	$data_toggle = ' data-toggle="'.$data_toggle.'"';
}
if(!empty($data_target)){
	$data_target = ' data-target="'.$data_target.'"';
}
$btn_content = !empty($icon) ? 
	(($icon_position == 'right' || (!empty($effect) && $effect == 'icon_slide_in' && $color != 'custom' && $color != 'default' )) ? '<span>'.esc_html($title).'</span><i class="'.$icon.'"></i>' : '<i class="'.$icon.'"></i><span>'.esc_html($title).'</span>' ) 
		: '<span>'.$title.'</span>' ;

if($href != ''){
	$output .='<a'.$data_el.$data_toggle.$data_target.' class="'.$btn_class.$el_class.'" href="'.esc_url($href).'" '.$target.$data_cusotom_color.''.(!empty($inline_style) ? ' style="'.$inline_style.'"': '').'>';
	$output .= $btn_content;
	$output .='</a>';
}else{
	$output .='<button'.$data_el.$data_toggle.$data_target.' class="'.$btn_class.$el_class.'" '.$data_cusotom_color.' type="button"'.(!empty($inline_style) ? ' style="'.$inline_style.'"': '').'>';
	$output .= $btn_content;
	$output .='</button>';
}

echo $output."\n";