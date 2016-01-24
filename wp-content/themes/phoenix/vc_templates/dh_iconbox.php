<?php
$output = '';
extract(shortcode_atts(array(
	'box_bg'						=>'',
	'icon'							=>'',
	'link'							=>'',
	'link_type'						=>'text_link',
	'link_text'						=>'See More',
	'link_target'					=>'',
	'title'							=>'',
	'title_color'					=>'',
	'text'							=>'',
	'text_color'					=>'',
	'icon_position'					=>'',
	'is_circle'						=>'',
	'size'							=>'',
	'icon_size'						=>'',
	'icon_font_size'				=>'',
	'icon_border_width'				=>'',
	'color'							=>'',
	'icon_color'					=>'',
	'icon_border_color'				=>'',
	'icon_background_color'			=>'',
	'hover_icon_hover_color'		=>'',
	'hover_icon_border_color'		=>'',
	'hover_icon_background_color'	=>'',
	'effect'						=>'',
	'disable_appear_animate'		=>'',
	'visibility'					=>'',
	'el_class'						=>'',
), $atts));
if ( $link_target == 'same' || $link_target == '_self' ) {
	$link_target = '';
}
$target = ( $link_target != '' ) ? ' target="' . $link_target . '"' : '';
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
$el_appear = empty($disable_appear_animate) ? 'el-appear ':'';
$icon_color_class = '';
$icon_css = '';
$data_hover= '';
$icon_size_class = '';
$link_style='';
if($size == 'custom'){
	$icon_css .='width:'.$icon_size.'px;height:'.$icon_size.'px;font-size:'.$icon_font_size.'px;border-with:'.$icon_border_width.'px;';
}else{
	$icon_size_class = ' icon-size-'.$size;
}
if($color == 'custom'){
	$icon_color = dh_format_color($icon_color);
	$icon_border_color = dh_format_color($icon_border_color);
	$icon_background_color = dh_format_color($icon_background_color);
	$hover_icon_hover_color = dh_format_color($hover_icon_hover_color);
	$hover_icon_border_color  = dh_format_color($hover_icon_border_color);
	$hover_icon_background_color = dh_format_color($hover_icon_background_color);
	if(!empty($icon_color)){
		$icon_css .='color:'.$icon_color.';';
		$link_style .='color:'.$icon_color.';';
	}
	if(!empty($icon_border_color))
		$icon_css .='border-color:'.$icon_border_color.';';
	if(!empty($icon_background_color))
		$icon_css .='background-color:'.$icon_background_color.';';
	$data_hover = 'data-hover-color="'.$hover_icon_hover_color.'" data-hover-border-color="'.$hover_icon_border_color.'" data-hover-background-color="'.$hover_icon_background_color.'"';
}else{
	$icon_color_class = ' icon-color-'.$color;
}

$output .='<div class="iconbox iconbox-pos-'.$icon_position.(!empty($box_bg) ?' iconbox-bg':'').(!empty($effect) && $color != 'custom' ? ' iconbox-'.$effect:'').$el_class.'">';
if($icon_position !='right'){
if($link != '' && $link_type == 'icon_link'){
	$href = ! strstr( $link, 'http:' ) && ! strstr( $link, 'https:' ) ?  home_url($link) :   $link;
	$output .='<a href="'.esc_url($href).'"'.$target.'>';
}
$output .='<div class="iconbox-icon '.(!empty($is_circle) ? ' icon-circle':'').$icon_color_class.$icon_size_class.'">';
$output .='<i data-toggle="iconbox" class="'.$el_appear.$icon.'" '.$data_hover.''.(!empty($icon_css) ? ' style="'.$icon_css.'"':'').'></i>';
$output .='</div>';
if($link != '' && $link_type == 'icon_link'){
	$output .='</a>';
}
}
if(!empty($title) || !empty($text) || ($link != '' && $link_type == 'text_link')){
$output .='<div class="iconbox-content">';
	if(!empty($title)){
		$title_color = dh_format_color($title_color);
		$output .='<h3 class="el-heading"'.(!empty($title_color) ? ' style="color:'.$title_color.'"':'').'>'.esc_html($title).'</h3>';
	}
	if(!empty($text)){
		$text_color = dh_format_color($text_color);
		$output .='<p'.(!empty($text_color) ? ' style="color:'.$text_color.'"':'').'>'.esc_html($text).'</p>';
	}
	if($link != '' && $link_type == 'text_link'){
		$href = ! strstr( $link, 'http:' ) && ! strstr( $link, 'https:' ) ?  home_url($link) :   $link;
		$link_style = !empty($link_style) ? ' style="'.$link_style.'"':'';
		$output .='<a class="text-'.$color.'" href="'.esc_url($href).'"'.$target.$link_style.'>'.esc_html($link_text).' <i class="fa fa-arrow-circle-o-right"></i></a>';
	}
$output .='</div>';
}
if($icon_position =='right'){
	if($link != '' && $link_type == 'icon_link'){
		$href = ! strstr( $link, 'http:' ) && ! strstr( $link, 'https:' ) ?  home_url($link) :   $link;
		$output .='<a href="'.esc_url($href).'"'.$target.'>';
	}
	$output .='<div class="iconbox-icon '.(!empty($is_circle) ? ' icon-circle':'').$icon_color_class.$icon_size_class.'" '.$data_hover.''.(!empty($icon_css) ? ' style="'.$icon_css.'"':'').'>';
	$output .='<i class="'.$el_appear.$icon.'"></i>';
	$output .='</div>';
	if($link != '' && $link_type == 'icon_link'){
		$output .='</a>';
	}
}
$output .='</div>';
echo $output;