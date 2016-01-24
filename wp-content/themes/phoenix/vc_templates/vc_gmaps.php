<?php
$output = '';
extract( shortcode_atts( array(
	'title'=>'',
	'size'=>'400',
	'center_latitude'=>'',
	'center_longitude'=>'',
	'zoom'=>'14',
	'eanble_zoom'=>'',
	'images'=>'',
	'greyscale_color'=>'',
	'locations'=>'',
	'visibility'		=>'',
	'el_class'			=>'',
), $atts ) );

$el_class  = !empty($el_class) ? ' '.esc_attr( $el_class ) : '';
switch ($visibility) {
	case 'hidden-phone':
		$el_class .= ' hidden-xs';
		break;
	case 'hidden-tablet':
		$el_class .= ' hidden-sm hidden-md';
		break;
	case 'hidden-pc':
		$el_class .= ' hidden-lg';
		break;
	case 'visible-phone':
		$el_class .= ' visible-xs-inline';
		break;
	case 'visible-tablet':
		$el_class .= ' visible-sm-inline visible-md-inline';
		break;
	case 'visible-pc':
		$el_class .= ' visible-lg-inline';
		break;
}

$marker_image_src = null;
if(!empty($images)) {
	$marker_image_src = wp_get_attachment_url($images);
}else{
	$marker_image_src = get_template_directory_uri().'/assets/images/marker-icon.png';
}
$locations_arr = explode(',', $locations);
$markers = array();
$count = 0;
foreach ($locations_arr as $location){
	$marker = array();
	$location_data = explode('|', $location);
	$marker['latitude'] = isset($location_data[0]) ? floatval($location_data[0]) : 0;
	$marker['longitude'] = isset($location_data[1]) ? floatval($location_data[1]) : 0;
	$marker['info'] = isset($location_data[2]) ? esc_html($location_data[2]) : '';
	$count ++;
	$markers[]=$marker;
}
$output .='<div data-toggle="gmap" class="google-map'.$el_class.'">';
$output .= !empty($title) ? '<h3 class="el-heading">'.$title.'</h3>':'';
$output .='<div id="'.uniqid('gmap_').'" class="gmap" data-greyscale="'.(!empty($greyscale_color) ? '1':'0').'" data-markers="'.esc_attr(json_encode($markers)).'" data-enable-zoom="'.(!empty($eanble_zoom) ? '1':'0').'" data-zoom="'.esc_attr($zoom).'" data-center-latitude="'.esc_attr($center_latitude).'" data-center-longitude="'.esc_attr($center_longitude).'" data-marker-icon="'.$marker_image_src.'" style="height: '.$size.'px;">';
$output .='</div>';
$output .='</div>';
echo $output;