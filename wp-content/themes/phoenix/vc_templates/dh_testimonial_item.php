<?php
$output = '';
extract(shortcode_atts(array(
	'text'=>'I am testimonial. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
	'author'=>'',
	'company'=>'',
	'avatar'=>'',
), $atts));

$output .='<li class="caroufredsel-item">';
$output .='<div class="testimonial-wrap">';
$output .='<div class="testimonial-text">';
$output .= '<span>&#147;</span>'.trim( vc_value_from_safe( $text ) ).'<span>&#148;</span>';
$output .='</div>';
if(!empty($avatar)){
	$avatar_image = wp_get_attachment_image_src($avatar,'dh-full');
	$output .='<div class="testimonial-avatar">';
	$output .= '<img src="'.$avatar_image[0].'" alt="'.$avatar.'"/>'; 
	$output .="</div>";
}
if(!empty($author)){
	$output .='<div class="testimonial-author">';
	$output .=esc_html($author);
	$output .='</div>';
}
if(!empty($company)){
	$output .='<div class="testimonial-company">';
	$output .=esc_html($company);
	$output .='</div>';
}
$output .='</div>';
$output .='</li>';
echo $output;
