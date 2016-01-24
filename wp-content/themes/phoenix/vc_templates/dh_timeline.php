<?php
$output = '';
extract(shortcode_atts(array(
	'type'						=>'',
	'columns'					=>'',
	'columns_align'				=>'',
	'stype'						=>'solid',
	'color'						=>'default',
	'line_color'				=>'',
	'badge_color'				=>'',
	'badge_border_color'		=>'',
	'badge_background_color'	=>'',
	'content_color'				=>'',
	'content_border_color'		=>'',
	'content_background_color'	=>'',
	'visibility'				=>'',
	'el_class'					=>'',
), $atts));

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
global $dh_timeline_color,$dh_timeline_line_color,$dh_timeline_badge_color,$dh_timeline_badge_border_color,$dh_timeline_badge_background_color,$dh_timeline_content_color,$dh_timeline_content_border_color,$dh_timeline_content_background_color;
$dh_timeline_color = $color;
$dh_timeline_line_color = dh_format_color($line_color);
$dh_timeline_badge_color = dh_format_color($badge_color);
$dh_timeline_badge_border_color = dh_format_color($badge_border_color);
$dh_timeline_badge_background_color = dh_format_color($badge_background_color);
$dh_timeline_content_color = dh_format_color($content_color);
$dh_timeline_content_border_color = dh_format_color($content_border_color);
$dh_timeline_content_background_color = dh_format_color($content_background_color);

$output .='<div class="timeline '.(!empty($type) && $type !='none' ?' timeline-'.$stype:'').($color !='custom'?' timeline-'.$color:'').(!empty($type) && $type !='none' ?' timeline-'.$type:'' ).' '.$columns.'-columns'.($columns === 'one' ? ' timeline-'.$columns_align:'').$el_class.'">';
$output .='<div class="timeline-wrap">';
$output .= wpb_js_remove_wpautop( $content );
$output .='</div>';
$output .='</div>';
echo $output;