<?php
$output = $title = $values = $units = $bgcolor = $custombgcolor = $options = $el_class = '';
extract( shortcode_atts( array(
	'title' 			=> '',
	'values' 			=> '',
	'units' 			=> '',
	'style' 			=> 'label-above',
	'title_color'		=>'',
	'label_color'		=>'',
	'progress_height'	=>'',
	'progress_height_value'=>'20',
	'options' 			=> '',
	'visibility'		=>'',
    'el_class'			=>'',
), $atts ) );

/**
 * script
 * {{
 */
wp_enqueue_script('vendor-countTo');

$class          = !empty($el_class) ? 'progress-bars '.$style.' '. esc_attr( $el_class ) : 'progress-bars '.$style;
switch ($visibility) {
	case 'hidden-phone':
		$class .= ' hidden-xs';
		break;
	case 'hidden-tablet':
		$class .= ' hidden-sm hidden-md';
		break;
	case 'hidden-pc':
		$class .= ' hidden-lg';
		break;
	case 'visible-phone':
		$class .= ' visible-xs-inline';
		break;
	case 'visible-tablet':
		$class .= ' visible-sm-inline visible-md-inline';
		break;
	case 'visible-pc':
		$class .= ' visible-lg-inline';
		break;
}
$progress_height_style = '';
if($progress_height ==='custom'){
	$progress_height_value = absint($progress_height_value);
	$progress_height_style = 'style="line-height: '.$progress_height_value.'px;height: '.$progress_height_value.'px;"';
}
$title_color = dh_format_color($title_color);
$label_color = dh_format_color($label_color);
$bar_options = '';
$options = explode( ",", $options );
if ( in_array( "animated", $options ) ) $bar_options .= " progress-bar-animated active";
if ( in_array( "striped", $options ) ) $bar_options .= " progress-bar-striped";

$output[] = '<div  class="' . $class . '">';
$output[] = !empty($title) ? '<h3 class="el-heading" '.(!empty($title_color) ? 'style="color:'.$title_color.'"':'').'>'.$title.'</h3>':'';

$graph_lines = explode( ",", $values );
$max_value = 0.0;
$graph_lines_data = array();
foreach ( $graph_lines as $line ) {
	$new_line = array();
	$color_index = 2;
	$data = explode( "|", $line );
	$new_line['value'] = isset( $data[0] ) ? $data[0] : 0;
	$new_line['percentage_value'] = isset( $data[1] ) && preg_match( '/^\d{1,2}\%$/', $data[1] ) ? (float)str_replace( '%', '', $data[1] ) : false;
	if ( $new_line['percentage_value'] != false ) {
		$color_index += 1;
		$new_line['label'] = isset( $data[2] ) ? $data[2] : '';
	} else {
		$new_line['label'] = isset( $data[1] ) ? $data[1] : '';
	}
	$new_line['bgcolor'] = ( isset( $data[$color_index] ) ) ? ' style="background-color: ' . $data[$color_index] . ';"' : $custombgcolor;

	if ( $new_line['percentage_value'] === false && $max_value < (float)$new_line['value'] ) {
		$max_value = $new_line['value'];
	}

	$graph_lines_data[] = $new_line;
}

foreach ( $graph_lines_data as $line ) {
	$unit = ( $units != '' ) ? '<span>'.$line['value'].'</span> '.$units : '';
	if ( $line['percentage_value'] !== false ) {
		$percentage_value = $line['percentage_value'];
	} elseif ( $max_value > 100.00 ) {
		$percentage_value = (float)$line['value'] > 0 && $max_value > 100.00 ? round( (float)$line['value'] / $max_value * 100, 4 ) : 0;
	} else {
		$percentage_value = $line['value'];
	}
	
	$output[] = '<div class="progress" '.$progress_height_style.'>';
	if( $line['label'] != '' && $style ==='label-above' ) {
		$output[] = '<div class="progress-title" '.(!empty($label_color) ? 'style="color:'.$label_color.'"':'').'>' . esc_attr( $line['label'] ) . '</div>';
		$output[] = '<div class="progress-label">'.$unit.'</div>';
	}
	$output[] = '<div class="progress-bar'.$bar_options.'" role="progressbar" data-valuenow="' . ( $line['value'] ) . '" aria-valuenow="' . ( $line['value'] ) . '" aria-valuemin="0" aria-valuemax="100"'.$line['bgcolor'].'>';
	if( $line['label'] != '' && $style ==='label-inner' ) {
		$output[] = '<div class="progress-title" '.(!empty($label_color) ? 'style="color:'.$label_color.'"':'').'>' . esc_attr( $line['label'] ) . '</div>';
		$output[] = '<div class="progress-label">'.$unit.'</div>';
	}
	$output[] = '</div>';
	$output[] = '</div>';
}

$output[] = '</div>';

echo implode("\n",$output);