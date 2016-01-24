<?php

$output = '';

extract( shortcode_atts( array(
	'image' => '',
	'img_size' => 'thumbnail',
	'img_link_large' => false,
	'img_link' => '',
	'link' => '',
	'img_link_target' => '_self',
	'alignment' => 'left',
	'css_animation' => '',
	'style' => 'default',
	'border_size'=>'1',
	'border_color' => '',
	'visibility'		=>'',
	'el_class'			=>'',
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
$border_color = dh_format_color($border_color);
$border_size = absint($border_size);
$img_id = preg_replace( '/[^\d]/', '', $image );
$img = wpb_getImageBySize( array( 'attach_id' => $img_id, 'thumb_size' => $img_size,'class'=>'box-'.$style));
if ( $img == NULL ) $img['thumbnail'] = '<img class="box-' . $style.'" src="' . vc_asset_url( 'vc/no_image.png' ) . '" />'; //' <small>'.__('This is image placeholder, edit your page to replace it.', 'js_composer').'</small>';
if((!empty($border_color) || !empty($border_size)) && ($style=='border' || $style=='border-circle' || $style=='outline' || $style=='outline-circle')){
	$img = str_replace('<img','<img style="border-width:'.$border_size.'px;border-color:'.$border_color.'"', $img);
}
$a_rel ='';
$link_to = '';
if ( $img_link_large == true ) {
	$link_to = wp_get_attachment_url( $img_id);
	$a_rel = ' data-rel="magnific-single-popup"';
	/**
	 * script
	 * {{
	 */
	wp_enqueue_style('vendor-magnific-popup');
	wp_enqueue_script('vendor-magnific-popup');
} else if ( strlen($link) > 0 ) {
	$link_to = $link;
}
//to disable relative links uncomment this..


$img_output = ( $style == 'shadow-3d' ) ? '<span class="shadow-3d-wrap">' . $img['thumbnail'] . '</span>' : $img['thumbnail'];
$image_string = ! empty( $link_to ) ? '<a' . $a_rel . ' href="' . $link_to . '"' . ' target="' . $img_link_target . '"'. '>' . $img_output . '</a>' : $img_output;


$output .='<div class="single-image '.(!empty($alignment)?' image-'.$alignment:'').$el_class.'">';
$output .=$image_string;
$output .='</div>';
echo $output;