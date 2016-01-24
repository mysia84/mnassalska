<?php
$output = '';
extract(shortcode_atts(array(
	'heading'      	  			=> '',
	'heading_color'           	=> '',
	'second_heading'            => '',
	'heading_second_color'    	=> '',
	'style'            			=> '',
	'txt_align'            		=> '',
	'background_color'        	=> '',
	'border_color'				=> '',
	'href'    					=> '',
	'target'     				=> '',
	'btn_title'     			=> '',
	'btn_text_uppercase'     	=> '',
	'btn_style'     			=> '',
	'btn_size'     				=> '',
	'btn_font_size'     		=> '',
	'btn_border_width'     		=> '',
	'btn_padding_top'     		=> '',
	'btn_padding_right'     	=> '',
	'btn_padding_bottom'     	=> '',
	'btn_padding_left'     		=> '',
	'btn_color'     			=> '',
	'btn_background_color'     	=> '',
	'btn_border_color'     		=> '',
	'btn_text_color'     		=> '',
	'btn_hover_background_color'=> '',
	'btn_hover_border_color'    => '',
	'btn_hover_text_color'		=> '',
	'btn_icon'     				=> '',
	'btn_icon_position'     	=> '',
	'btn_effect'     			=> '',
	'buton_position'			=> '',
	'block_button'				=> '',
	'tooltip'					=>'',
	'tooltip_position'			=>'',
	'tooltip_title'				=>'',
	'tooltip_content'			=>'',
	'tooltip_trigger'			=>'',
	'visibility'				=> '',
	'el_class'					=> '',
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
$cta_class = 'cta cta-style-'.$style;
$cta_class .=' text-'.$txt_align;
$cta_class .=' cta-btn-'.$buton_position;
$cta_inline_style="";
$background_color = dh_format_color($background_color);
if(!empty($background_color)){
	$cta_inline_style .='background-color:'.$background_color.';';
}
$border_color = dh_format_color($border_color);
if(!empty($border_color)){
	$cta_inline_style .= 'border-color:'.$border_color.';';
}

$output .='<div class="'.$cta_class.$el_class.'" '.(!empty($cta_inline_style) ? ' style="'.$cta_inline_style.'"':'').'>';
if($buton_position == 'left'){
	$output .='<div class="cta-btn">';
	$output .= do_shortcode('[vc_button title="'.$btn_title.'" href="'.$href.'"  target="'.$target.'" style="'.$btn_style.'" size="'.$btn_size.'"  font_size="'.$btn_font_size.'" border_width="'.$btn_border_width.'" padding_top="'.$btn_padding_top.'" padding_right="'.$btn_padding_right.'" padding_bottom="'.$btn_padding_bottom.'" padding_left="'.$btn_padding_left.'" color="'.$btn_color.'"  background_color="'.$btn_background_color.'" border_color="'.$btn_border_color.'" text_color="'.$btn_text_color.'" hover_background_color="'.$btn_hover_background_color.'" hover_border_color="'.$btn_hover_border_color.'" hover_text_color="'.$btn_hover_text_color.'" icon="'.$btn_icon.'" icon_position="'.$btn_icon_position.'" effect="'.$btn_effect.'" text_uppercase="'.$btn_text_uppercase.'" block_button="'.$block_button.'" tooltip="'.$tooltip.'" tooltip_position="'.$tooltip_position.'" tooltip_title="'.$tooltip_title.'" tooltip_content="'.$tooltip_content.'" tooltip_trigger="'.$tooltip_trigger.'"]');
	$output .='</div>';
}
$output .='<div class="cta-heading">';
if(!empty($heading) || !empty($second_heading) ){
	$output .='<div class="cta-hgroup">';
	if(!empty($heading)){
		$heading_color = dh_format_color($heading_color);
		$output .='<h3'.(!empty($heading_color) ? ' style="color:'.$heading_color.'"':'').'>'.$heading.'</h3>';
	}
	if(!empty($second_heading)){
		$heading_second_color = dh_format_color($heading_second_color);
		$output .='<h4'.(!empty($heading_second_color) ? ' style="color:'.$heading_second_color.'"':'').'>'.$second_heading.'</h4>';
	}
	$output .='</div>';
}
$output .= wpb_js_remove_wpautop($content, true);
$output .='</div>';
if($buton_position != 'left'){
	$output .='<div class="cta-btn">';
	$output .= do_shortcode('[vc_button title="'.$btn_title.'" href="'.$href.'"  target="'.$target.'" style="'.$btn_style.'" size="'.$btn_size.'"  font_size="'.$btn_font_size.'" border_width="'.$btn_border_width.'" padding_top="'.$btn_padding_top.'" padding_right="'.$btn_padding_right.'" padding_bottom="'.$btn_padding_bottom.'" padding_left="'.$btn_padding_left.'" color="'.$btn_color.'"  background_color="'.$btn_background_color.'" border_color="'.$btn_border_color.'" text_color="'.$btn_text_color.'" hover_background_color="'.$btn_hover_background_color.'" hover_border_color="'.$btn_hover_border_color.'" hover_text_color="'.$btn_hover_text_color.'" icon="'.$btn_icon.'" icon_position="'.$btn_icon_position.'" effect="'.$btn_effect.'" text_uppercase="'.$btn_text_uppercase.'" block_button="'.$block_button.'" tooltip="'.$tooltip.'" tooltip_position="'.$tooltip_position.'" tooltip_title="'.$tooltip_title.'" tooltip_content="'.$tooltip_content.'" tooltip_trigger="'.$tooltip_trigger.'"]');
	$output .='</div>';
}
$output .='</div>';
echo $output;



