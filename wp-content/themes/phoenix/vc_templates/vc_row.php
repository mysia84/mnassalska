<?php
$output = array();
extract(shortcode_atts(array(
	'inner_container'	=> '',
    'background_type' 	=> '',
    'bg_color'        	=> '',
    'bg_image'        	=> '',
    'bg_image_repeat' 	=> '',
    'parallax_bg'     	=> '',
    'parallax_bg_speed'	=> '',
    'bg_video_src_mp4'  => '',
    'bg_video_src_ogv'  => '',
    'bg_video_src_webm' => '',
    'bg_video_poster' 	=> '',
    'bg_overlay'		=> '',
    'border'			=> '',
    'padding_top'		=> '',
    'padding_bottom'	=> '',
    'visibility'		=> '',
    'el_class'			=> '',
), $atts));

wp_enqueue_script( 'wpb_composer_front_js' );

$class          = !empty($el_class) ? 'row ' . esc_attr( $el_class ) : 'row';
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

$style_inline = '';
$style_inline .= !empty($padding_top) ? 'padding-top:'.esc_attr($padding_top).'px;' : '';
$style_inline .= !empty($padding_bottom) ? 'padding-bottom:'.esc_attr($padding_bottom).'px;' : '';

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
if(!empty($padding_top) || !empty($padding_bottom))
	$class .= ' row-custom-padding';
if(!empty($inner_container))
	$output []='<div class="container">';

if($background_type == 'image'){
	if($parallax_bg == 'yes'){
		/**
		 * script
		 * {{
		 */
		wp_enqueue_script('vendor-parallax');
	}
	$bg_image = wp_get_attachment_image_src($bg_image,'full');
	if(!empty($bg_overlay)){
		$bg_overlay = dh_format_color($bg_overlay);
		if(!empty($bg_overlay))
			$style_inline .= 'background-color:'.esc_attr($bg_overlay).';';
	}
	if($bg_image){
		$class .= ' bg-image';
	}
	$style = !empty($style_inline) ? 'style="'.esc_attr($style_inline).'"':'';
	
	$output []='<div class="'.$class.'" '.$style.'>';
	$output []= wpb_js_remove_wpautop($content);
	if($bg_image){
		$output []='<div class="row-image-bg'.(!empty($bg_image_repeat) ? ' bg-repeat':'').'" '.($parallax_bg == 'yes' ? ' data-parallax="1" data-parallax-speed="'.((float)$parallax_bg_speed).'"':'').($parallax_bg == 'fixed' ? ' data-fixed="1" ':'').' style="background-image: url('.@$bg_image[0].');"></div>';
	}
	$output []='</div>';
}elseif ($background_type == 'video'){
	/**
	 * script
	 * {{
	 */
	wp_enqueue_style( 'mediaelement' );
	wp_enqueue_script('mediaelement');
	$bg_video = '';
	$bg_video_args = array();
	
	if ( $bg_video_src_mp4 ) {
		$bg_video_args['mp4'] = $bg_video_src_mp4;
	}
	
	if ( $bg_video_src_ogv ) {
		$bg_video_args['ogv'] = $bg_video_src_ogv;
	}
	
	if ( $bg_video_src_webm ) {
		$bg_video_args['webm'] = $bg_video_src_webm;
	}
	
	if ( !empty($bg_video_args) ) {
		$attr_strings = array(
				'loop="1"',
				'preload="1"',
				'autoplay=""',
				'muted="muted"'
		);
			
		if (!empty($bg_video_poster)) {
			$bg_image_path = wp_get_attachment_image_src($bg_video_poster, 'dh-full');
			$attr_strings[] = 'poster="' . esc_url($bg_image_path[0]) . '"';
		}
	
		$bg_video .= sprintf( '<div class="row-video-bg"><video %s controls="controls" class="full-video">', join( ' ', $attr_strings ) );
	
		$source = '<source type="%s" src="%s" />';
		foreach ( $bg_video_args as $video_type=>$video_src ) {
	
			$video_type = wp_check_filetype( $video_src, wp_get_mime_types() );
			$bg_video .= sprintf( $source, $video_type['type'], esc_url( $video_src ) );
	
		}
		$bg_video .= '</video></div>';
	}
	
	if(!empty($bg_overlay)){
		$bg_overlay = dh_format_color($bg_overlay);
		if(!empty($bg_overlay))
			$style_inline .= 'background-color:'.$bg_overlay.';';
	}
	$style = !empty($style_inline) ? 'style="'.esc_attr($style_inline).'"':'';
	$output []='<div class="'.$class.'" '.$style.'>';
	$output []= wpb_js_remove_wpautop($content);
	$output []= $bg_video;
	$output []='</div>';
}else{
	if($background_type === 'color'){
		$bg_color = dh_format_color($bg_color);
		if(!empty($bg_color))
			$style_inline .= 'background-color:'.$bg_color.';';
	}
	$style = !empty($style_inline) ? 'style="'.esc_attr($style_inline).'"':'';
	$output []='<div class="'.$class.'" '.$style.'>';
	$output []= wpb_js_remove_wpautop($content);
	$output []='</div>';
}

if(!empty($inner_container))
	$output []='</div>';

echo implode("\n",$output);