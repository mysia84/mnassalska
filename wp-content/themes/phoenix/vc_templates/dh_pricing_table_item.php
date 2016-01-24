<?php
$output = '';
extract(shortcode_atts(array(
	'recommend'				=>'',
	'color'					=>'',
	'title'					=>'',
	'title_background_color'=>'',
	'title_color'			=>'',
	'price'					=>'',
	'currency'				=>'',
	'units'					=>'',
	'price_background_color'=>'',
	'price_color'			=>'',
	'features'				=>'',
	'features_alignment'	=>'',
	'href'					=>'',
	'target'				=>'',
	'btn_title'				=>'',
	'btn_style'				=>'',
	'btn_size'				=>'',
	'btn_icon'				=>'',
	'btn_effect'			=>'',
), $atts));

$title_background_color = dh_format_color($title_background_color);
$title_color = dh_format_color($title_color);
$price_background_color = dh_format_color($price_background_color);
$price_color  = dh_format_color($price_color);
$title_css = '';
if(!empty($title_background_color) || !empty($title_color)){
	$title_css .=' style="';
	if(!empty( $title_background_color)){
		$title_css .='background-color:'.$title_background_color.';border-color:'.$title_background_color.';';
	}
	if(!empty($title_color)){
		$title_css .='color:'.$title_color.';';
	}
	$title_css .='"';
}
$price_css = '';
if(!empty($price_background_color) || !empty($price_color)){
	$price_css .=' style="';
	if(!empty( $price_background_color)){
		$price_css .='background-color:'.$price_background_color.';border-color:'.$price_background_color.';';
	}
	if(!empty($price_color)){
		$price_css .='color:'.$price_color.';';
	}
	$price_css .='"';
}

$output .='<div class="pricing-column pricing-'.$color.''.(!empty($recommend) ? ' pricing-recommend':'').'">';
$output .='<div class="pricing-column-wrap">';
$output .='<div class="pricing-header">';
if(!empty($title)){
	$output .='<div class="pricing-title"'.$title_css.'>';
	$output .='<h3>'.$title.'</h3>';
	$output .='</div>';
}
if(!empty($price)){
	$output .='<div class="pricing-price"'.$price_css.'>';
	$output .='<span class="price-value">'.(!empty($currency) ? '<sub>'.$currency.'</sub>':'').$price.'</span>';
	if(!empty($units)){
		$output .='<span class="price-unit">'.$units.'</span>';
	}
	$output .='</div>';
}
$output .='</div>';
$output .='<div class="pricing-body">';
if(!empty($features)):
	$features = json_decode(base64_decode($features));
	$output .='<ul class="pricing-features">';
	foreach ((array)$features as $feature){
		$output .='<li class="text-'.$features_alignment.'">'.$feature->content.'</li>';
	}
	$output .'<ul>';
endif;
$output .='</div>';
$output .='<div class="pricing-footer">';
$output .= do_shortcode('[vc_button title="'.$btn_title.'" href="'.$href.'" color="'.$color.'"  target="'.$target.'" style="'.$btn_style.'" size="'.$btn_size.'"  icon="'.$btn_icon.'" effect="'.$btn_effect.'"]');
$output .='</div>';
$output .='</div>';
$output .='</div>';
echo $output;

