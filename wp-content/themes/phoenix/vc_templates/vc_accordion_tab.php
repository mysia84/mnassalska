<?php
$output = $title = '';

extract(shortcode_atts(array(
	'title' => __("Section", DH_THEME_DOMAIN)
), $atts));
global $dh_accordion_active, $dh_accordion_count, $dh_accordion_id, $dh_accordion_style,$dh_accordion_icon_class;
$dh_accordion_count ++;

$dh_accordion_tab = dh_vc_el_increment();

$acvive_class = '';
if($dh_accordion_active == $dh_accordion_count){
	$acvive_class =' in';
}
$style = ' panel-'.$dh_accordion_style;

$output .= '<div class="panel '.$style.'">';
$output .= '<div class="panel-heading'.$dh_accordion_icon_class.'"><h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-'.$dh_accordion_id.'" href="#accordion-'.$dh_accordion_id.'-'.$dh_accordion_tab.'">'.$title.'</a></h4></div>';
$output .= '<div id="accordion-'.$dh_accordion_id.'-'.$dh_accordion_tab.'" class="panel-collapse collapse'.$acvive_class.'">';
$output .= '<div class="panel-body">';
$output .= ($content=='' || $content==' ') ? __("Empty section. Edit page to add content here.", DH_THEME_DOMAIN) : wpb_js_remove_wpautop($content);
$output .= '</div>';
$output .= '</div>';
$output .= '</div> ';
echo $output;