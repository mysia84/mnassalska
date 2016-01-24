<?php
$output = '';
extract(shortcode_atts(array(
	'icon'=>'',
	'items'=>'',
	'icon_color'=>'',
	'item_color'=>'',
	'text_size'=>'',
	'disable_animation'=>'no',
	'visibility'=>'',
	'el_class'=>'',
), $atts));

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
$output .='<div class="dh-lists'.$el_class.'" data-animation="'.($disable_animation == 'yes'?'0':'1').'">';
$output .='<ul>';
$items_arr = explode(',', $items);
$icon_color= dh_format_color($icon_color);
$item_color = dh_format_color($item_color);
foreach ($items_arr as $item){
	$output .= '<li style="font-size:'.absint($text_size).'px;'.(!empty($item_color)?'color:'.$item_color:'').'">';
	if(!empty($icon)){
		$output .= '<i class="'.$icon.'" style="'.(!empty($icon_color)?'color:'.$icon_color:'').'"></i>';
	}
	$output .= $item;
	$output .= '</li>';
}
$output .='</ul>';
$output .='</div>';
echo $output;