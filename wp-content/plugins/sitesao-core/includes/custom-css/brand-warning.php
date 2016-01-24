<?php 
$brand_warning = dh_format_color(dh_get_theme_option('brand-warning','#f0ad4e'));
$brand_warning = apply_filters('dh_brand_warning', $brand_warning);
if($brand_warning === '#f0ad4e'){
	return '';
}
$darken_10_brand_warning = darken(dh_format_color($brand_warning),'10%');
$darken_12_brand_warning = darken(dh_format_color($brand_warning),'12%');
?>
.text-warning {
  color: <?php echo dhecho($brand_warning); ?>;
}
.blockquote-warning {
  border-color: <?php echo dhecho($brand_warning); ?>;
}
.btn-warning {
  background-color: <?php echo dhecho($brand_warning); ?>;
  border-color: <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-style-square-outlined,
.btn-warning.btn-style-outlined {
  color: <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-style-3d {
  -webkit-box-shadow: 0 5px 0 <?php echo dhecho($brand_warning); ?>;
  box-shadow: 0 5px 0 <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-effect-border-fade {
  box-shadow: 0 0 0 2px <?php echo dhecho($brand_warning); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -moz-box-shadow: 0 0 0 2px <?php echo dhecho($brand_warning); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -webkit-box-shadow: 0 0 0 2px <?php echo dhecho($brand_warning); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
}
.btn-warning.btn-effect-border-fade:hover,
.btn-warning.btn-effect-border-fade:focus,
.btn-warning.btn-effect-border-fade:active,
.btn-warning.btn-effect-border-fade.active {
  background-color: <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-effect-border-hollow {
  box-shadow: 0 0 0 2px <?php echo dhecho($brand_warning); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -moz-box-shadow: 0 0 0 2px <?php echo dhecho($brand_warning); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -webkit-box-shadow: 0 0 0 2px <?php echo dhecho($brand_warning); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
}
.btn-warning.btn-effect-border-hollow:hover,
.btn-warning.btn-effect-border-hollow:focus,
.btn-warning.btn-effect-border-hollow:active,
.btn-warning.btn-effect-border-hollow.active {
  color: <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-effect-border-outline-outward:before {
  border: 2px solid <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-effect-border-outline-inward:before {
  border: 2px solid <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-effect-bg-fade-in {
  color: <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-effect-bg-fade-in:hover,
.btn-warning.btn-effect-bg-fade-in:focus,
.btn-warning.btn-effect-bg-fade-in:active,
.btn-warning.btn-effect-bg-fade-in.active {
  background-color: <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-effect-bg-fade-out {
  background-color: <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-effect-bg-fade-out:hover,
.btn-warning.btn-effect-bg-fade-out:focus,
.btn-warning.btn-effect-bg-fade-out:active,
.btn-warning.btn-effect-bg-fade-out.active {
  color: <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-effect-bg-top {
  color: <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-effect-bg-top:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-effect-bg-right {
  color: <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-effect-bg-right:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-effect-bg-center {
  color: <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-effect-bg-center:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-effect-bg-skew-center {
  color: <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-effect-bg-skew-center:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-effect-bg-horizontal-center {
  color: <?php echo dhecho($brand_warning); ?>;
}
.btn-warning.btn-effect-bg-horizontal-center:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_warning); ?>;
}
.tabs-warning a:hover,
.tabs-warning a:focus {
  color: <?php echo dhecho($brand_warning); ?>;
}
.tabs-warning.tabs-top .nav-tabs > li.active > a,
.tabs-warning.tabs-top .nav-tabs > li.active > a:hover,
.tabs-warning.tabs-top .nav-tabs > li.active > a:focus {
  border-top-color: <?php echo dhecho($brand_warning); ?>;
  color: <?php echo dhecho($brand_warning); ?>;
}
.tabs-warning.tabs-left .nav-tabs > li.active > a,
.tabs-warning.tabs-left .nav-tabs > li.active > a:hover,
.tabs-warning.tabs-left .nav-tabs > li.active > a:focus {
  border-left-color: <?php echo dhecho($brand_warning); ?>;
  color: <?php echo dhecho($brand_warning); ?>;
}
.tabs-warning.tabs-right .nav-tabs > li.active > a,
.tabs-warning.tabs-right .nav-tabs > li.active > a:hover,
.tabs-warning.tabs-right .nav-tabs > li.active > a:focus {
  border-right-color: <?php echo dhecho($brand_warning); ?>;
  color: <?php echo dhecho($brand_warning); ?>;
}
.tabs-warning.tabs-below .nav-tabs > li.active > a,
.tabs-warning.tabs-below .nav-tabs > li.active > a:hover,
.tabs-warning.tabs-below .nav-tabs > li.active > a:focus {
  border-bottom-color: <?php echo dhecho($brand_warning); ?>;
  color: <?php echo dhecho($brand_warning); ?>;
}
.label-warning {
  background-color: <?php echo dhecho($brand_warning); ?>;
}
.alert-warning {
  color: <?php echo dhecho($brand_warning); ?>;
}
.panel-warning > .panel-heading {
  color: <?php echo dhecho($brand_warning); ?>;
}
.panel-warning > .panel-heading .badge {
  background-color: <?php echo dhecho($brand_warning); ?>;
}
.iconbox .iconbox-icon.icon-color-warning i {
  color: <?php echo dhecho($brand_warning); ?>;
}
.iconbox-effect-1.iconbox .iconbox-icon.icon-color-warning i:after {
  -webkit-box-shadow: 0 0 0 1px <?php echo dhecho($brand_warning); ?>;
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_warning); ?>;
}
.iconbox-effect-1:hover.iconbox .iconbox-icon.icon-color-warning i {
  background: <?php echo dhecho($brand_warning); ?>;
}
.iconbox-effect-2.iconbox .iconbox-icon.icon-color-warning i {
  -webkit-box-shadow: 0 0 0 1px <?php echo dhecho($brand_warning); ?>;
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_warning); ?>;
}
.iconbox-effect-2.iconbox .iconbox-icon.icon-color-warning i:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_warning); ?>;
}
.iconbox-effect-3.iconbox .iconbox-icon.icon-color-warning i {
  -webkit-box-shadow: 0 0 0 1px <?php echo dhecho($brand_warning); ?>;
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_warning); ?>;
}
.iconbox-effect-3.iconbox .iconbox-icon.icon-color-warning i:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_warning); ?>;
}
.iconbox-effect-4.iconbox .iconbox-icon.icon-color-warning i:after {
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_warning); ?>;
}
.iconbox-effect-4:hover.iconbox .iconbox-icon.icon-color-warning i {
  box-shadow: 0 0 0 6px <?php echo dhecho($brand_warning); ?>;
  color: <?php echo dhecho($brand_warning); ?>;
}
.iconbox .iconbox-icon.icon-color-warning.icon-circle i {
  border: 1px solid <?php echo dhecho($brand_warning); ?>;
}
.piechart .piechart-warning {
  background: <?php echo dhecho($brand_warning); ?>;
}
.pricing-table .pricing-warning .pricing-title {
  background: <?php echo dhecho($brand_warning); ?>;
  border-color: <?php echo dhecho($brand_warning); ?>;
}
.pricing-table .pricing-warning .pricing-price {
  background: <?php echo dhecho($darken_10_brand_warning); ?>;
  border-color: <?php echo dhecho($darken_10_brand_warning); ?>;
}
.timeline.timeline-warning .timeline-line {
  border-left-color: <?php echo dhecho($brand_warning); ?> !important;
}
.timeline.timeline-warning .timeline-content {
  border-color: <?php echo dhecho($brand_warning); ?>;
}
.timeline.timeline-warning .timeline-badge {
  border-color: <?php echo dhecho($brand_warning); ?>;
}
.timeline.timeline-warning .timeline-badge a {
  color: <?php echo dhecho($brand_warning); ?>;
}
.timeline.timeline-warning.timeline-text .timeline-badge a,
.timeline.timeline-warning.timeline-image .timeline-badge a,
.timeline.timeline-warning.timeline-icon .timeline-badge a {
  color: #fff;
  background-color: <?php echo dhecho($brand_warning); ?>;
}
.timeline.timeline-warning .timeline-arrow {
  background-color: <?php echo dhecho($brand_warning); ?> !important;
}
.portfolio .portfolio-wrap.portfolio-layout-wall .wall-warning {
  background: <?php echo dhecho($brand_warning); ?>;
}
.portfolio .portfolio-wrap.portfolio-layout-wall .wall-warning .portfolio-caption:after {
  border-bottom-color: <?php echo dhecho($brand_warning); ?>;
}
@media (min-width: 768px) {
  .portfolio .portfolio-wrap.portfolio-layout-wall .wall-warning .portfolio-caption:after {
    border-bottom-color: transparent;
    border-right-color: <?php echo dhecho($brand_warning); ?>;
  }
}
@media (min-width: 768px) {
  .portfolio .portfolio-wrap.portfolio-layout-wall .wall-warning .pull-right ~ .portfolio-caption:after {
    border-left-color: <?php echo dhecho($brand_warning); ?>;
  }
}
<?php if(defined('WOOCOMMERCE_VERSION')):?>
	.product-slider-title.color-warning .el-heading {
	  color: <?php echo dhecho($brand_warning); ?>;
	}
	.product-slider-title.color-warning .el-heading:before {
	  border-color: <?php echo dhecho($brand_warning); ?>;
	}
<?php endif;?>
<?php //10?>
a.text-warning:hover {
  color: <?php echo dhecho($darken_10_brand_warning); ?>;
}
a.bg-warning:hover {
  background-color: <?php echo dhecho($darken_10_brand_warning); ?>;
}
.btn-warning:hover,
.btn-warning:focus,
.btn-warning:active,
.btn-warning.active {
  background-color: <?php echo dhecho($darken_10_brand_warning); ?>;
  border-color: <?php echo dhecho($darken_10_brand_warning); ?>;
}
.btn-warning:hover.btn-style-square-outlined,
.btn-warning:focus.btn-style-square-outlined,
.btn-warning:active.btn-style-square-outlined,
.btn-warning.active.btn-style-square-outlined,
.btn-warning:hover.btn-style-outlined,
.btn-warning:focus.btn-style-outlined,
.btn-warning:active.btn-style-outlined,
.btn-warning.active.btn-style-outlined {
  color: <?php echo dhecho($darken_10_brand_warning); ?>;
}
.btn-warning.btn-effect-click-state:hover,
.btn-warning.btn-effect-click-state:focus,
.btn-warning.btn-effect-click-state:active,
.btn-warning.btn-effect-click-state.active {
  -webkit-box-shadow: 0 2px 0 <?php echo dhecho($darken_10_brand_warning); ?>;
  box-shadow: 0 2px 0 <?php echo dhecho($darken_10_brand_warning); ?>;
}
.label-warning[href]:hover,
.label-warning[href]:focus {
  background-color: <?php echo dhecho($darken_10_brand_warning); ?>;
}

<?php //12?>
.btn-warning:hover.btn-style-3d,
.btn-warning:focus.btn-style-3d,
.btn-warning:active.btn-style-3d,
.btn-warning.active.btn-style-3d {
  -webkit-box-shadow: 0 5px 0 <?php echo dhecho($darken_12_brand_warning); ?>;
  box-shadow: 0 5px 0 <?php echo dhecho($darken_12_brand_warning); ?>;
}
.btn-warning.btn-effect-border-fade:hover,
.btn-warning.btn-effect-border-fade:focus,
.btn-warning.btn-effect-border-fade:active,
.btn-warning.btn-effect-border-fade.active {
  box-shadow: 0 0 0 2px <?php echo dhecho($darken_12_brand_warning); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -moz-box-shadow: 0 0 0 2px <?php echo dhecho($darken_12_brand_warning); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -webkit-box-shadow: 0 0 0 2px <?php echo dhecho($darken_12_brand_warning); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
}

