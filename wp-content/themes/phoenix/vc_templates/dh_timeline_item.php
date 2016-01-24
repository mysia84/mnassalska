<?php
$output = '';
extract(shortcode_atts(array(
	'title'=>'',
	'badge_type'=>'',
	'badge_icon'=>'',
	'badge_text'=>'',
	'badge_image'=>'',
), $atts));

global $dh_timeline_color,$dh_timeline_line_color,$dh_timeline_badge_color,$dh_timeline_badge_border_color,$dh_timeline_badge_background_color,$dh_timeline_content_color,$dh_timeline_content_border_color,$dh_timeline_content_background_color;

$badge = '<a'.($dh_timeline_color == 'custom' && !empty($dh_timeline_badge_color) ? ' style="color:'.$dh_timeline_badge_color.'"':'').'><i class="fa fa-dot-circle-o"></i></a>';
$inline_style = '';
if($dh_timeline_color == 'custom'){
	if(!empty($dh_timeline_badge_color)){
		$inline_style .='color:'.$dh_timeline_badge_color.';';
	}
	if(!empty($dh_timeline_badge_background_color)){
		$inline_style .='background-color:'.$dh_timeline_badge_background_color.';';
	}
}
if($badge_type === 'text' && !empty($badge_text)){
	$badge = '<a'.(!empty($inline_style)?' style="'.$inline_style.'"':'').'><span>'.$badge_text.'</span></a>';
}
if ($badge_type === 'icon'){
	$badge ='<a'.(!empty($inline_style)?' style="'.$inline_style.'"':'').'><i class="'.$badge_icon.'"></i></a>';
}
if($badge_type === 'image' && !empty($badge_image)){
	$badge_image_src = wp_get_attachment_url($badge_image);
	$badge ='<a'.(!empty($inline_style)?' style="'.$inline_style.'"':'').'><img src="'.$badge_image_src.'"/></a>';
}
$content_style='';
$arrow_style='';
if($dh_timeline_color == 'custom'){
	if(!empty($dh_timeline_content_color)){
		$content_style .='color:'.$dh_timeline_content_color.';';
	}
	if(!empty($dh_timeline_content_border_color)){
		$content_style .='border-color:'.$dh_timeline_content_border_color.';';
		$arrow_style .='background-color:'.$dh_timeline_content_border_color.';';
	}
	if(!empty($dh_timeline_content_background_color)){
		$content_style .='background-color:'.$dh_timeline_content_background_color.';';
	}
}
if(!empty($content)){
	$output .='<div class="timeline-item">';
	$output .='<div class="timeline-line el-appear"'.($dh_timeline_color == 'custom' && !empty($dh_timeline_line_color) ? ' style="border-left-color:'.$dh_timeline_line_color.'"':'').'></div>';
	$output .='<div class="timeline-item-wrap">';
	$output .='<div class="timeline-badge el-appear"'.($dh_timeline_color == 'custom' && !empty($dh_timeline_badge_border_color) ? ' style="border-color:'.$dh_timeline_badge_border_color.'"':'').'>'.$badge.'</div>';
	$output .='<div class="animate-box animated" data-animate="1">';
	$output .='<div class="timeline-arrow"'.(!empty($arrow_style)?' style="'.$arrow_style.'"':'').'></div>';
	$output .='<div class="timeline-content"'.(!empty($content_style)?' style="'.$content_style.'"':'').'>';
	$output .= wpb_js_remove_wpautop( $content,true);
	$output .='</div>';
	$output .='</div>';
	$output .='</div>';
	$output .='</div>';
}
echo $output;
