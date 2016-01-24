<?php
$output = '';
$default_atts =  array(
	'title'				=>'',
	'posts_per_page'	=>'',
	'orderby'			=>'',
	'categories'		=>'',
	'hide_date'			=>'',
	'hide_comment'		=>'',
);
extract(shortcode_atts($default_atts, $atts));

$type = 'DH_Post_Thumbnail_Widget';
$args = array('widget_id'=>'dh_widget_post_thumbnail');
ob_start();
the_widget( $type, wp_parse_args($atts,$default_atts), $args );
$output .= ob_get_clean();
echo $output;