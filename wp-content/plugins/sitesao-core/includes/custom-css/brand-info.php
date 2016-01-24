<?php 
$brand_info = dh_format_color(dh_get_theme_option('brand-info','#5788bb'));
$brand_info = apply_filters('dh_brand_info', $brand_info);
if($brand_info === '#5788bb'){
	return '';
}
$darken_10_brand_info = darken($brand_info,'10%');
$darken_12_brand_info = darken($brand_info,'12%');
?>
.text-info {
  color: <?php echo dhecho($brand_info); ?>;
}
.blockquote-info {
  border-color: <?php echo dhecho($brand_info); ?>;
}
.btn-info {
  background-color: <?php echo dhecho($brand_info); ?>;
  border-color: <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-style-square-outlined,
.btn-info.btn-style-outlined {
  color: <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-style-3d {
  -webkit-box-shadow: 0 5px 0 <?php echo dhecho($brand_info); ?>;
  box-shadow: 0 5px 0 <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-effect-border-fade {
  box-shadow: 0 0 0 2px <?php echo dhecho($brand_info); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -moz-box-shadow: 0 0 0 2px <?php echo dhecho($brand_info); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -webkit-box-shadow: 0 0 0 2px <?php echo dhecho($brand_info); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
}
.btn-info.btn-effect-border-fade:hover,
.btn-info.btn-effect-border-fade:focus,
.btn-info.btn-effect-border-fade:active,
.btn-info.btn-effect-border-fade.active {
  background-color: <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-effect-border-hollow {
  box-shadow: 0 0 0 2px <?php echo dhecho($brand_info); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -moz-box-shadow: 0 0 0 2px <?php echo dhecho($brand_info); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -webkit-box-shadow: 0 0 0 2px <?php echo dhecho($brand_info); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
}
.btn-info.btn-effect-border-hollow:hover,
.btn-info.btn-effect-border-hollow:focus,
.btn-info.btn-effect-border-hollow:active,
.btn-info.btn-effect-border-hollow.active {
  color: <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-effect-border-outline-outward:before {
  border: 2px solid <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-effect-border-outline-inward:before {
  border: 2px solid <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-effect-bg-fade-in {
  color: <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-effect-bg-fade-in:hover,
.btn-info.btn-effect-bg-fade-in:focus,
.btn-info.btn-effect-bg-fade-in:active,
.btn-info.btn-effect-bg-fade-in.active {
  background-color: <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-effect-bg-fade-out {
  background-color: <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-effect-bg-fade-out:hover,
.btn-info.btn-effect-bg-fade-out:focus,
.btn-info.btn-effect-bg-fade-out:active,
.btn-info.btn-effect-bg-fade-out.active {
  color: <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-effect-bg-top {
  color: <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-effect-bg-top:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-effect-bg-right {
  color: <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-effect-bg-right:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-effect-bg-center {
  color: <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-effect-bg-center:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-effect-bg-skew-center {
  color: <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-effect-bg-skew-center:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-effect-bg-horizontal-center {
  color: <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-effect-bg-horizontal-center:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_info); ?>;
}
.tabs-info a:hover,
.tabs-info a:focus {
  color: <?php echo dhecho($brand_info); ?>;
}
.tabs-info.tabs-top .nav-tabs > li.active > a,
.tabs-info.tabs-top .nav-tabs > li.active > a:hover,
.tabs-info.tabs-top .nav-tabs > li.active > a:focus {
  border-top-color: <?php echo dhecho($brand_info); ?>;
  color: <?php echo dhecho($brand_info); ?>;
}
.tabs-info.tabs-left .nav-tabs > li.active > a,
.tabs-info.tabs-left .nav-tabs > li.active > a:hover,
.tabs-info.tabs-left .nav-tabs > li.active > a:focus {
  border-left-color: <?php echo dhecho($brand_info); ?>;
  color: <?php echo dhecho($brand_info); ?>;
}
.tabs-info.tabs-right .nav-tabs > li.active > a,
.tabs-info.tabs-right .nav-tabs > li.active > a:hover,
.tabs-info.tabs-right .nav-tabs > li.active > a:focus {
  border-right-color: <?php echo dhecho($brand_info); ?>;
  color: <?php echo dhecho($brand_info); ?>;
}
.tabs-info.tabs-below .nav-tabs > li.active > a,
.tabs-info.tabs-below .nav-tabs > li.active > a:hover,
.tabs-info.tabs-below .nav-tabs > li.active > a:focus {
  border-bottom-color: <?php echo dhecho($brand_info); ?>;
  color: <?php echo dhecho($brand_info); ?>;
}
.label-info {
  background-color: <?php echo dhecho($brand_info); ?>;
}
.alert-info {
  color: <?php echo dhecho($brand_info); ?>;
}
.panel-info > .panel-heading {
  color: <?php echo dhecho($brand_info); ?>;
}
.panel-info > .panel-heading .badge {
  background-color: <?php echo dhecho($brand_info); ?>;
}
.iconbox .iconbox-icon.icon-color-info i {
  color: <?php echo dhecho($brand_info); ?>;
}
.iconbox-effect-1.iconbox .iconbox-icon.icon-color-info i:after {
  -webkit-box-shadow: 0 0 0 1px <?php echo dhecho($brand_info); ?>;
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_info); ?>;
}
.iconbox-effect-1:hover.iconbox .iconbox-icon.icon-color-info i {
  background: <?php echo dhecho($brand_info); ?>;
}
.iconbox-effect-2.iconbox .iconbox-icon.icon-color-info i {
  -webkit-box-shadow: 0 0 0 1px <?php echo dhecho($brand_info); ?>;
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_info); ?>;
}
.iconbox-effect-2.iconbox .iconbox-icon.icon-color-info i:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_info); ?>;
}
.iconbox-effect-3.iconbox .iconbox-icon.icon-color-info i {
  -webkit-box-shadow: 0 0 0 1px <?php echo dhecho($brand_info); ?>;
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_info); ?>;
}
.iconbox-effect-3.iconbox .iconbox-icon.icon-color-info i:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_info); ?>;
}
.iconbox-effect-4.iconbox .iconbox-icon.icon-color-info i:after {
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_info); ?>;
}
.iconbox-effect-4:hover.iconbox .iconbox-icon.icon-color-info i {
  box-shadow: 0 0 0 6px <?php echo dhecho($brand_info); ?>;
  color: <?php echo dhecho($brand_info); ?>;
}
.iconbox .iconbox-icon.icon-color-info.icon-circle i {
  border: 1px solid <?php echo dhecho($brand_info); ?>;
}
.piechart .piechart-info {
  background: <?php echo dhecho($brand_info); ?>;
}
.pricing-table .pricing-info .pricing-title {
  background: <?php echo dhecho($brand_info); ?>;
  border-color: <?php echo dhecho($brand_info); ?>;
}
.pricing-table .pricing-info .pricing-price {
  background: <?php echo dhecho($darken_10_brand_info); ?>;
  border-color: <?php echo dhecho($darken_10_brand_info); ?>;
}
.timeline.timeline-info .timeline-line {
  border-left-color: <?php echo dhecho($brand_info); ?> !important;
}
.timeline.timeline-info .timeline-content {
  border-color: <?php echo dhecho($brand_info); ?>;
}
.timeline.timeline-info .timeline-badge {
  border-color: <?php echo dhecho($brand_info); ?>;
}
.timeline.timeline-info .timeline-badge a {
  color: <?php echo dhecho($brand_info); ?>;
}
.timeline.timeline-info.timeline-text .timeline-badge a,
.timeline.timeline-info.timeline-image .timeline-badge a,
.timeline.timeline-info.timeline-icon .timeline-badge a {
  color: #fff;
  background-color: <?php echo dhecho($brand_info); ?>;
}
.timeline.timeline-info .timeline-arrow {
  background-color: <?php echo dhecho($brand_info); ?> !important;
}
.portfolio .portfolio-wrap.portfolio-layout-wall .wall-info {
  background: <?php echo dhecho($brand_info); ?>;
}
.portfolio .portfolio-wrap.portfolio-layout-wall .wall-info .portfolio-caption:after {
  border-bottom-color: <?php echo dhecho($brand_info); ?>;
}
@media (min-width: 768px) {
  .portfolio .portfolio-wrap.portfolio-layout-wall .wall-info .portfolio-caption:after {
    border-bottom-color: transparent;
    border-right-color: <?php echo dhecho($brand_info); ?>;
  }
}
@media (min-width: 768px) {
  .portfolio .portfolio-wrap.portfolio-layout-wall .wall-info .pull-right ~ .portfolio-caption:after {
    border-left-color: <?php echo dhecho($brand_info); ?>;
  }
}
<?php if(defined('WOOCOMMERCE_VERSION')):?>
	.product-slider-title.color-info .el-heading {
	  color: <?php echo dhecho($brand_info); ?>;
	}
	.product-slider-title.color-info .el-heading:before {
	  border-color: <?php echo dhecho($brand_info); ?>;
	}
	.woocommerce .woocommerce-info {
	  border-left: 3px solid <?php echo dhecho($brand_info) ?>;
	}
	.woocommerce .woocommerce-info:before {
	  color: <?php echo dhecho($brand_info) ?>;
	}
<?php endif;?>
<?php //10?>
a.text-info:hover {
  color: <?php echo dhecho($brand_info); ?>;
}
a.bg-info:hover {
  background-color: <?php echo dhecho($brand_info); ?>;
}
.btn-info:hover,
.btn-info:focus,
.btn-info:active,
.btn-info.active {
  background-color: <?php echo dhecho($brand_info); ?>;
  border-color: <?php echo dhecho($brand_info); ?>;
}
.btn-info:hover.btn-style-square-outlined,
.btn-info:focus.btn-style-square-outlined,
.btn-info:active.btn-style-square-outlined,
.btn-info.active.btn-style-square-outlined,
.btn-info:hover.btn-style-outlined,
.btn-info:focus.btn-style-outlined,
.btn-info:active.btn-style-outlined,
.btn-info.active.btn-style-outlined {
  color: <?php echo dhecho($brand_info); ?>;
}
.btn-info.btn-effect-click-state:hover,
.btn-info.btn-effect-click-state:focus,
.btn-info.btn-effect-click-state:active,
.btn-info.btn-effect-click-state.active {
  -webkit-box-shadow: 0 2px 0 <?php echo dhecho($brand_info); ?>;
  box-shadow: 0 2px 0 <?php echo dhecho($brand_info); ?>;
}
.label-info[href]:hover,
.label-info[href]:focus {
  background-color: <?php echo dhecho($brand_info); ?>;
}

<?php //12?>
.btn-info:hover.btn-style-3d,
.btn-info:focus.btn-style-3d,
.btn-info:active.btn-style-3d,
.btn-info.active.btn-style-3d {
  -webkit-box-shadow: 0 5px 0 <?php echo dhecho($darken_12_brand_info); ?>;
  box-shadow: 0 5px 0 <?php echo dhecho($darken_12_brand_info); ?>;
}
.btn-info.btn-effect-border-fade:hover,
.btn-info.btn-effect-border-fade:focus,
.btn-info.btn-effect-border-fade:active,
.btn-info.btn-effect-border-fade.active {
  box-shadow: 0 0 0 2px <?php echo dhecho($darken_12_brand_info); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -moz-box-shadow: 0 0 0 2px <?php echo dhecho($darken_12_brand_info); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -webkit-box-shadow: 0 0 0 2px <?php echo dhecho($darken_12_brand_info); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
}

