<?php
$output = $title = $id = $el_class = '';
extract( shortcode_atts( array(
    'title' => '',
    'id' => '',
    'el_class' => ''
), $atts ) );

$output .= '<div class="layerslider">';
$output .= apply_filters('vc_layerslider_shortcode', do_shortcode('[layerslider id="'.$id.'"]'));
$output .= '</div>';

echo $output;