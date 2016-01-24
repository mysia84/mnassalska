<?php
$output = $title = $alias = $el_class = '';
extract( shortcode_atts( array(
    'title' => '',
    'alias' => '',
    'el_class' => ''
), $atts ) );

$output .= '<div class="revslider">';
$output .= apply_filters('vc_revslider_shortcode', do_shortcode('[rev_slider '.$alias.']'));
$output .= '</div>';

echo $output;