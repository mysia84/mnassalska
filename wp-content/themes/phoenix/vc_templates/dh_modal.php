<?php
$output = '';
extract(shortcode_atts(array(
	'title'      	  			=> '',
	'modal_size'				=>'',
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
$id = uniqid('modal_');
$output .= do_shortcode('[vc_button data_toggle="modal" data_target="#'.$id.'" title="'.$btn_title.'" style="'.$btn_style.'" size="'.$btn_size.'"  font_size="'.$btn_font_size.'" border_width="'.$btn_border_width.'" padding_top="'.$btn_padding_top.'" padding_right="'.$btn_padding_right.'" padding_bottom="'.$btn_padding_bottom.'" padding_left="'.$btn_padding_left.'" color="'.$btn_color.'"  background_color="'.$btn_background_color.'" border_color="'.$btn_border_color.'" text_color="'.$btn_text_color.'" hover_background_color="'.$btn_hover_background_color.'" hover_border_color="'.$btn_hover_border_color.'" hover_text_color="'.$btn_hover_text_color.'" icon="'.$btn_icon.'" icon_position="'.$btn_icon_position.'" effect="'.$btn_effect.'" text_uppercase="'.$btn_text_uppercase.'" block_button="'.$block_button.'"]');
$output .= '<div class="modal fade" id="'.$id.'" tabindex="-1" role="dialog" aria-hidden="true">';
$output .= '<div class="modal-dialog modal-dialog-center modal-'.$modal_size.'">';
$output .= '<div class="modal-content">';
if(!empty($title)):
$output .= '<div class="modal-header">';
$output .= '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">'.__('Close',DH_THEME_DOMAIN).'</span></button>';
$output .= '<h4 class="modal-title">'.esc_html($title).'</h4>';
$output .= '</div>';
endif;
$output .= '<div class="modal-body">';
$output .= wpb_js_remove_wpautop($content, true);
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';
echo $output;