<?php
$output = array();
extract( shortcode_atts( array(
	'type' 				=> '',
	'icon'				=> '',
	'icon_size'			=> '',
	'icon_align'		=> '',
	'title' 			=> '',
	'title_size' 		=> '20',
	'title_align' 		=> '',
	'line_align'		=> '',
	'color' 			=> '',
	'style' 			=> '',
	'width_type'		=> 'percent',
	'el_with_px'		=> '',
	'el_width'			=>'',
	'visibility'		=>'',
	'el_id'				=>'',
	'el_class'			=>'',
), $atts ) );
$class          = !empty($el_class) ? 'separator '.esc_attr( $el_class ) : 'separator';
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
$line_css="";
if($type === 'line'){
	if($line_align === 'align-left'){
		$line_css ='margin-left:0;';
	}elseif($line_align === 'align-right'){
		$line_css ='margin-right:0;';
	}
}
if($width_type == 'fix'){
	$line_css .='width:'.$el_with_px.'px;';
}
if(!empty($line_css)){
	$line_css = ' style="'.$line_css.'"';
}
if ($type == 'line_icon'){
	$title_align = $icon_align;
}
$class .=($title_align !='') ? '  separator-'.$title_align:'';
if($width_type == 'percent'){
	$class .= ($el_width!='') ? ' separator-width-'.$el_width : ' separator-width-100';
}
$class .= ($style!='') ? ' separator-'.$style : '';
$inline_style = ($color!='') ? ' border-color:'.$color.';color:'.$color.';' : '';

$output []='<div class="'.$class.'"'.$line_css.'>';
$output []='<span class="separator-left"><span class="separator-line"  style="'.$inline_style.'"></span></span>';
if($type=='line_text'){
	if(!empty($title))
		$output []='<h4 style="font-size:'.$title_size.'px;'.$inline_style.'">'.$title.'</h4>';
}elseif ($type == 'line_icon'){
	if(!empty($icon))
		$output []='<i class="'.$icon.'" style="font-size:'.$icon_size.'px;'.$inline_style.'"></i>';
}
$output []='<span class="separator-right"><span class="separator-line" style="'.$inline_style.'"></span></span>';
$output []='</div>';
echo implode("\n",$output);