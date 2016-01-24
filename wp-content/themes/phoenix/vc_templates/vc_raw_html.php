<?php
$output = array();
extract(shortcode_atts(array(
	'visibility'		=>'',
    'el_class'			=>'',
), $atts));
$class = ($this->settings['base']=='vc_raw_html') ? 'raw-html' : 'raw-js';
$class .= !empty($el_class) ? ' '.esc_attr( $el_class ) : '';
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
$content =  rawurldecode(base64_decode(strip_tags($content)));
$output []='<div class="'.$class.'">';
$output [] = $content;
$output []='</div>';

echo implode("\n",$output);