<?php
$output = $title = $options = $el_class = '';
extract( shortcode_atts( array(
	'title' => __( 'Archives' , DH_THEME_DOMAIN ),
	'options' => '',
	'el_class' => ''
), $atts ) );
$options = explode( ",", $options );
if ( in_array( "dropdown", $options ) ) $atts['dropdown'] = true;
if ( in_array( "count", $options ) ) $atts['count'] = true;

$el_class = $this->getExtraClass( $el_class );

if(!empty($el_class))
	$output .= '<div class="' . $el_class . '">';
$type = 'WP_Widget_Archives';
$args = array();
ob_start();
the_widget( $type, $atts, $args );
$output .= ob_get_clean();
if(!empty($el_class))
	$output .= '</div>';
echo $output;