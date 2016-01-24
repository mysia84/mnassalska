<?php
$output = $title = $el_class = '';
extract( shortcode_atts( array(
	'title' => __( 'Meta' , DH_THEME_DOMAIN ),
	'el_class' => ''
), $atts ) );

$el_class = $this->getExtraClass( $el_class );

if(!empty($el_class))
	$output .= '<div class="' . $el_class . '">';
$type = 'WP_Widget_Meta';
$args = array();

ob_start();
the_widget( $type, $atts, $args );
$output .= ob_get_clean();
if(!empty($el_class))
	$output .= '</div>';

echo $output;