<?php
$output = $el_position = $title = $width = $el_class = $sidebar_id = '';
extract(shortcode_atts(array(
    'el_position' => '',
    'title' => '',
    'width' => '1/1',
    'el_class' => '',
    'sidebar_id' => ''
), $atts));
if ( $sidebar_id == '' ) return null;

$el_class = $this->getExtraClass($el_class);

ob_start();
dynamic_sidebar($sidebar_id);
$sidebar_value = ob_get_contents();
ob_end_clean();

$sidebar_value = trim($sidebar_value);
$sidebar_value = (substr($sidebar_value, 0, 3) == '<li' ) ? '<ul>'.$sidebar_value.'</ul>' : $sidebar_value;
//
$css_class          = !empty($el_class) ? 'widgetised '.esc_attr( $el_class ) : 'widgetised';
$output .= '<div class="'.$css_class.'">';
$output .= '<div class="widgetised-wrap">';
$output .= ($title != '' ? '<h4 class="widget-title">'.$title.'</h4>':'');
$output .= $sidebar_value;
$output .= '</div>';
$output .= '</div>';

echo $output;