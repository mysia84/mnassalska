<?php
$output = $title = $number = $el_class = '';
extract( shortcode_atts( array(
	'title' => __( 'Recent Comments' , DH_THEME_DOMAIN),
	'number' => 5,
	'el_class' => ''
), $atts ) );

$el_class = $this->getExtraClass( $el_class );
if(!empty($el_class))
	$output .= '<div class="' . $el_class . '">';
$type = 'WP_Widget_Recent_Comments';
$args = array();

ob_start();
the_widget( $type, $atts, $args );
$output .= ob_get_clean();
if(!empty($el_class))
	$output .= '</div>';

echo $output;