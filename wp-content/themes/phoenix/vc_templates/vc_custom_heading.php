<?php
$output = $text = $google_fonts = $font_container = $el_class = $css = $google_fonts_data = $font_container_data = '';
extract( $this->getAttributes( $atts ) );
extract(shortcode_atts(array(
	'heading_type'			=>'',
	'inherit_style'			=>'yes',
	'heading_type_color'	=>'',
	'visibility'			=>'',
), $atts));
if($inherit_style === 'yes')
	$google_fonts_data = '';
extract( $this->getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) );

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
$settings = get_option( 'wpb_js_google_fonts_subsets' );
$subsets  = '';
if ( is_array( $settings ) && ! empty( $settings ) ) {
	$subsets = '&subset=' . implode( ',', $settings );
}
$heading_type_color = dh_format_color($heading_type_color);
if($heading_type == 'typed_effect'){
	wp_enqueue_script('vendor-typed');
	$el_class .= ' heading-typed';
	list($word,$string) = dh_nth_word($text,'last',false,true, $heading_type_color);
	$text = $word;
}
if($heading_type == 'bold_first_word'){
	$el_class .= ' heading-bold-first-word';
	$text = dh_nth_word($text,'first',false);
}

if($inherit_style !='yes')
	wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
$output .= '<'. $font_container_data['values']['tag'] . ''.(!empty($el_class) ? ' class="'.$el_class.'"':'').(($heading_type == 'typed_effect') ? ' data-typed-string="'.$string.'" ':'').' style="' . implode( ';', $styles ) . '">';
$output .= $text;
$output .= '</' . $font_container_data['values']['tag'] . '>';
echo $output;