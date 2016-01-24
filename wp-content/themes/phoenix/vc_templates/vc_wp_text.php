<?php
$output = $title = $el_class = $text = $filter = '';
extract( shortcode_atts( array(
	'title' => '',
	'text' => '',
	'filter' => true,
	'el_class' => ''
), $atts ) );
$atts['filter'] = true; //Hack to make sure that <p> added

$el_class = $this->getExtraClass( $el_class );

if(!empty($el_class))
	$output .= '<div class="' . $el_class . '">';
$type = 'WP_Widget_Text';
$args = array();
if ( strlen( $content ) > 0 ) $atts['text'] = $content;
ob_start();
the_widget( $type, $atts, $args );
$output .= ob_get_clean();
if(!empty($el_class))
	$output .= '</div>';

echo $output;