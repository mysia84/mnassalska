<?php
$output = array();
extract(shortcode_atts(array(
	'el_class'			=>'',
), $atts));

$class          = !empty($el_class) ? 'clearfix '.esc_attr( $el_class ) : 'clearfix';
$output[] ='<hr class="'.$class.'"/>';
echo implode("\n",$output);