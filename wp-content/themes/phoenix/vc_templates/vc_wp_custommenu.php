<?php
$output = $title = $el_class = $nav_menu = '';
extract( shortcode_atts( array(
	'title' => '',
	'nav_menu' => '',
	'el_class' => ''
), $atts ) );
$el_class = $this->getExtraClass( $el_class );
if(!empty($el_class))
	$output .= '<div class="' . $el_class . '">';
$type = 'WP_Nav_Menu_Widget';
$args = array();
ob_start();
the_widget( $type, $atts, $args );
$output .= ob_get_clean();
if(!empty($el_class))
	$output .= '</div>';

echo $output;