<?php
$output ='';
extract( shortcode_atts( array(
	'color'				=>'default',
	'control_position' 	=> '',
	'control_center'	=>'',
	'control_fullwith'	=>'',
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

// Extract tab titles
preg_match_all( '/vc_tab([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
$tab_titles = array();

if ( isset( $matches[1] ) ) {
	$tab_titles = $matches[1];
}
$output .='<div class="tabbable tabs-'.$color.(!empty($control_position) ? ' tabs-'.$control_position:'').(!empty($control_center) && ($control_position =='top'|| $control_position == 'below') ? ' tabs-center':'').(!empty($control_fullwith) && ($control_position =='top'|| $control_position == 'below') ? ' tabs-full':'').$el_class.'">';
$tabs_nav = '';
$tabs_nav .= '<ul class="nav nav-tabs" role="tablist">';
$i = 0;
global $tab_active;
$tab_active = '';
foreach ( $tab_titles as $tab ) {
	$tab_atts = shortcode_parse_atts($tab[0]);
	if($i == 0 && empty($tab_active)){
		$tab_active =  ( isset( $tab_atts['tab_id'] ) ? $tab_atts['tab_id'] : sanitize_title( $tab_atts['title'] ) );
	}
	$tabs_nav .= '<li'.($i == 0 ? ' class="active"':'').'><a href="#' . ( isset( $tab_atts['tab_id'] ) ? $tab_atts['tab_id'] : sanitize_title( $tab_atts['title'] ) ) . '" role="tab" data-toggle="tab">' . ( isset( $tab_atts['icon']) && !empty($tab_atts['icon']) ? '<i class="'.esc_attr($tab_atts['icon']).'"></i>' : '' ) . '' . $tab_atts['title'] . '</a></li>';
	$i++;
}
$tabs_nav .= '</ul>';
if($control_position != 'below' && $control_position != 'right')
	$output .= $tabs_nav;
$output .='<div class="tab-content">';
$output .= wpb_js_remove_wpautop( $content );
$output .='</div>';
if($control_position == 'below' || $control_position == 'right')
	$output .= $tabs_nav;
$output .='</div>';
echo $output;