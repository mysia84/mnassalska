<?php
$output = $title = $el_class = $sortby = $exclude = '';
extract( shortcode_atts( array(
	'title' => __( 'Pages' , DH_THEME_DOMAIN ),
	'sortby' => 'menu_order',
	'exclude' => null,
	'el_class' => ''
), $atts ) );

$el_class = $this->getExtraClass( $el_class );

if(!empty($el_class))
	$output .= '<div class="' . $el_class . '">';
$type = 'WP_Widget_Pages';
$args = array();

ob_start();
the_widget( $type, $atts, $args );
$output .= ob_get_clean();
if(!empty($el_class))
	$output .= '</div>';

echo $output;