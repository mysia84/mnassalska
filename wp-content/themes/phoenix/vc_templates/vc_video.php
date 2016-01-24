<?php
$output = '';
extract(shortcode_atts(array(
	'type'			=> '',
	'link'     		=> '',
	'mp4'     		=> '',
	'ogv'     		=> '',
	'webm'     		=> '',
	'preview'     	=> '',
	'visibility'  	=> '',
	'el_class'		=> '',
), $atts));

/**
 * script
 * {{
 */
wp_enqueue_style( 'mediaelement' );
wp_enqueue_script('mediaelement');

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
$video_args = array();
if(!empty($mp4))
	$video_args['mp4'] = $mp4;
if ( !empty($ogv) )
	$video_args['ogv'] = $ogv;
if(!empty($webm))
	$video_args['webm'] = $webm;
	
$poster = $preview;
$poster_attr='';
	
if(!empty($preview)){
	$post_thumb = wp_get_attachment_image_src($preview,'dh-full');
	$poster_attr = ' poster="' . esc_url(@$post_thumb[0]) . '"';
}
	
	
if($type != 'link'):
	$video = '<div id="'.uniqid('video_').'" class="video-embed-wrap'.$el_class.'"><video controls="controls" '.$poster_attr.' preload="0" class="video-embed">';
	$source = '<source type="%s" src="%s" />';
	foreach ( $video_args as $video_type => $video_src ) {
		$video_type = wp_check_filetype( $video_src, wp_get_mime_types() );
		$video .= sprintf( $source, $video_type['type'], esc_url( $video_src ) );
	}
	$video .= '</video></div>';
	$output .= $video;
elseif(!empty($link)):
	$output .=  '<div id="'.uniqid('video_').'" class="embed-wrap'.$el_class.'">';
	$output .=  apply_filters('dh_embed_video', $link);
	$output .=  '</div>';
endif;

echo $output;