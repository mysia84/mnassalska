<?php 
$brand_danger = dh_format_color(dh_get_theme_option('brand-danger','#bb5857'));
$brand_danger = apply_filters('dh_brand_danger', $brand_danger);
if($brand_danger === '#bb5857'){
	return '';
}
$darken_10_brand_danger = darken(dh_format_color(dh_get_theme_option('brand-danger','#bb5857')),'10%');
$darken_12_brand_danger = darken(dh_format_color(dh_get_theme_option('brand-danger','#bb5857')),'12%');
?>
.text-danger {
  color: <?php echo dhecho($brand_danger); ?>;
}
.blockquote-danger {
  border-color: <?php echo dhecho($brand_danger); ?>;
}
.btn-danger {
  background-color: <?php echo dhecho($brand_danger); ?>;
  border-color: <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-style-square-outlined,
.btn-danger.btn-style-outlined {
  color: <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-style-3d {
  -webkit-box-shadow: 0 5px 0 <?php echo dhecho($brand_danger); ?>;
  box-shadow: 0 5px 0 <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-effect-border-fade {
  box-shadow: 0 0 0 2px <?php echo dhecho($brand_danger); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -moz-box-shadow: 0 0 0 2px <?php echo dhecho($brand_danger); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -webkit-box-shadow: 0 0 0 2px <?php echo dhecho($brand_danger); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
}
.btn-danger.btn-effect-border-fade:hover,
.btn-danger.btn-effect-border-fade:focus,
.btn-danger.btn-effect-border-fade:active,
.btn-danger.btn-effect-border-fade.active {
  background-color: <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-effect-border-hollow {
  box-shadow: 0 0 0 2px <?php echo dhecho($brand_danger); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -moz-box-shadow: 0 0 0 2px <?php echo dhecho($brand_danger); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -webkit-box-shadow: 0 0 0 2px <?php echo dhecho($brand_danger); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
}
.btn-danger.btn-effect-border-hollow:hover,
.btn-danger.btn-effect-border-hollow:focus,
.btn-danger.btn-effect-border-hollow:active,
.btn-danger.btn-effect-border-hollow.active {
  color: <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-effect-border-outline-outward:before {
  border: 2px solid <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-effect-border-outline-inward:before {
  border: 2px solid <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-effect-bg-fade-in {
  color: <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-effect-bg-fade-in:hover,
.btn-danger.btn-effect-bg-fade-in:focus,
.btn-danger.btn-effect-bg-fade-in:active,
.btn-danger.btn-effect-bg-fade-in.active {
  background-color: <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-effect-bg-fade-out {
  background-color: <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-effect-bg-fade-out:hover,
.btn-danger.btn-effect-bg-fade-out:focus,
.btn-danger.btn-effect-bg-fade-out:active,
.btn-danger.btn-effect-bg-fade-out.active {
  color: <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-effect-bg-top {
  color: <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-effect-bg-top:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-effect-bg-right {
  color: <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-effect-bg-right:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-effect-bg-center {
  color: <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-effect-bg-center:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-effect-bg-skew-center {
  color: <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-effect-bg-skew-center:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-effect-bg-horizontal-center {
  color: <?php echo dhecho($brand_danger); ?>;
}
.btn-danger.btn-effect-bg-horizontal-center:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_danger); ?>;
}
.tabs-danger a:hover,
.tabs-danger a:focus {
  color: <?php echo dhecho($brand_danger); ?>;
}
.tabs-danger.tabs-top .nav-tabs > li.active > a,
.tabs-danger.tabs-top .nav-tabs > li.active > a:hover,
.tabs-danger.tabs-top .nav-tabs > li.active > a:focus {
  border-top-color: <?php echo dhecho($brand_danger); ?>;
  color: <?php echo dhecho($brand_danger); ?>;
}
.tabs-danger.tabs-left .nav-tabs > li.active > a,
.tabs-danger.tabs-left .nav-tabs > li.active > a:hover,
.tabs-danger.tabs-left .nav-tabs > li.active > a:focus {
  border-left-color: <?php echo dhecho($brand_danger); ?>;
  color: <?php echo dhecho($brand_danger); ?>;
}
.tabs-danger.tabs-right .nav-tabs > li.active > a,
.tabs-danger.tabs-right .nav-tabs > li.active > a:hover,
.tabs-danger.tabs-right .nav-tabs > li.active > a:focus {
  border-right-color: <?php echo dhecho($brand_danger); ?>;
  color: <?php echo dhecho($brand_danger); ?>;
}
.tabs-danger.tabs-below .nav-tabs > li.active > a,
.tabs-danger.tabs-below .nav-tabs > li.active > a:hover,
.tabs-danger.tabs-below .nav-tabs > li.active > a:focus {
  border-bottom-color: <?php echo dhecho($brand_danger); ?>;
  color: <?php echo dhecho($brand_danger); ?>;
}
.label-danger {
  background-color: <?php echo dhecho($brand_danger); ?>;
}
.alert-danger {
  color: <?php echo dhecho($brand_danger); ?>;
}
.panel-danger > .panel-heading {
  color: <?php echo dhecho($brand_danger); ?>;
}
.panel-danger > .panel-heading .badge {
  background-color: <?php echo dhecho($brand_danger); ?>;
}
.iconbox .iconbox-icon.icon-color-danger i {
  color: <?php echo dhecho($brand_danger); ?>;
}
.iconbox-effect-1.iconbox .iconbox-icon.icon-color-danger i:after {
  -webkit-box-shadow: 0 0 0 1px <?php echo dhecho($brand_danger); ?>;
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_danger); ?>;
}
.iconbox-effect-1:hover.iconbox .iconbox-icon.icon-color-danger i {
  background: <?php echo dhecho($brand_danger); ?>;
}
.iconbox-effect-2.iconbox .iconbox-icon.icon-color-danger i {
  -webkit-box-shadow: 0 0 0 1px <?php echo dhecho($brand_danger); ?>;
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_danger); ?>;
}
.iconbox-effect-2.iconbox .iconbox-icon.icon-color-danger i:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_danger); ?>;
}
.iconbox-effect-3.iconbox .iconbox-icon.icon-color-danger i {
  -webkit-box-shadow: 0 0 0 1px <?php echo dhecho($brand_danger); ?>;
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_danger); ?>;
}
.iconbox-effect-3.iconbox .iconbox-icon.icon-color-danger i:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_danger); ?>;
}
.iconbox-effect-4.iconbox .iconbox-icon.icon-color-danger i:after {
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_danger); ?>;
}
.iconbox-effect-4:hover.iconbox .iconbox-icon.icon-color-danger i {
  box-shadow: 0 0 0 6px <?php echo dhecho($brand_danger); ?>;
  color: <?php echo dhecho($brand_danger); ?>;
}
.iconbox .iconbox-icon.icon-color-danger.icon-circle i {
  border: 1px solid <?php echo dhecho($brand_danger); ?>;
}
.piechart .piechart-danger {
  background: <?php echo dhecho($brand_danger); ?>;
}
.pricing-table .pricing-danger .pricing-title {
  background: <?php echo dhecho($brand_danger); ?>;
  border-color: <?php echo dhecho($brand_danger); ?>;
}
.pricing-table .pricing-danger .pricing-price {
  background: <?php echo dhecho($darken_10_brand_danger); ?>;
  border-color: <?php echo dhecho($darken_10_brand_danger); ?>;
}
.timeline.timeline-danger .timeline-line {
  border-left-color: <?php echo dhecho($brand_danger); ?> !important;
}
.timeline.timeline-danger .timeline-content {
  border-color: <?php echo dhecho($brand_danger); ?>;
}
.timeline.timeline-danger .timeline-badge {
  border-color: <?php echo dhecho($brand_danger); ?>;
}
.timeline.timeline-danger .timeline-badge a {
  color: <?php echo dhecho($brand_danger); ?>;
}
.timeline.timeline-danger.timeline-text .timeline-badge a,
.timeline.timeline-danger.timeline-image .timeline-badge a,
.timeline.timeline-danger.timeline-icon .timeline-badge a {
  background-color: <?php echo dhecho($brand_danger); ?>;
}
.timeline.timeline-danger .timeline-arrow {
  background-color: <?php echo dhecho($brand_danger); ?> !important;
}
.portfolio .portfolio-wrap.portfolio-layout-wall .wall-danger {
  background: <?php echo dhecho($brand_danger); ?>;
}
.portfolio .portfolio-wrap.portfolio-layout-wall .wall-danger .portfolio-caption:after {
  border-bottom-color: <?php echo dhecho($brand_danger); ?>;
}
.user-login-modal .user-login-modal-result .error-response{
	color: <?php echo dhecho($brand_danger); ?>;
}
@media (min-width: 768px) {
  .portfolio .portfolio-wrap.portfolio-layout-wall .wall-danger .portfolio-caption:after {
    border-bottom-color: transparent;
    border-right-color: <?php echo dhecho($brand_danger); ?>;
  }
}
@media (min-width: 768px) {
  .portfolio .portfolio-wrap.portfolio-layout-wall .wall-danger .pull-right ~ .portfolio-caption:after {
    border-left-color: <?php echo dhecho($brand_danger); ?>;
  }
}
<?php if(defined('WOOCOMMERCE_VERSION')):?>
	.product-slider-title.color-danger .el-heading {
	  color: <?php echo dhecho($brand_danger); ?>;
	}
	.product-slider-title.color-danger .el-heading:before {
	  border-color: <?php echo dhecho($brand_danger); ?>;
	}
	.woocommerce .woocommerce-error {
	  border-left: 3px solid <?php echo dhecho($brand_danger) ?>;
	}
	.woocommerce .woocommerce-error:before {
	  color: <?php echo dhecho($brand_danger) ?>;
	}
<?php endif;?>
.mailchimp-form-result .error{
 color:<?php echo dhecho($brand_danger); ?>;
}
<?php //10?>
a.text-danger:hover {
  color: <?php echo dhecho($darken_10_brand_danger); ?>;
}
a.bg-danger:hover {
  background-color: <?php echo dhecho($darken_10_brand_danger); ?>;
}
.btn-danger:hover,
.btn-danger:focus,
.btn-danger:active,
.btn-danger.active {
  background-color: <?php echo dhecho($darken_10_brand_danger); ?>;
  border-color: <?php echo dhecho($darken_10_brand_danger); ?>;
}
.btn-danger:hover.btn-style-square-outlined,
.btn-danger:focus.btn-style-square-outlined,
.btn-danger:active.btn-style-square-outlined,
.btn-danger.active.btn-style-square-outlined,
.btn-danger:hover.btn-style-outlined,
.btn-danger:focus.btn-style-outlined,
.btn-danger:active.btn-style-outlined,
.btn-danger.active.btn-style-outlined {
  color: <?php echo dhecho($darken_10_brand_danger); ?>;
}
.btn-danger.btn-effect-click-state:hover,
.btn-danger.btn-effect-click-state:focus,
.btn-danger.btn-effect-click-state:active,
.btn-danger.btn-effect-click-state.active {
  -webkit-box-shadow: 0 2px 0 <?php echo dhecho($darken_10_brand_danger); ?>;
  box-shadow: 0 2px 0 <?php echo dhecho($darken_10_brand_danger); ?>;
}
.label-danger[href]:hover,
.label-danger[href]:focus {
  background-color: <?php echo dhecho($darken_10_brand_danger); ?>;
}

<?php //12?>
.btn-danger:hover.btn-style-3d,
.btn-danger:focus.btn-style-3d,
.btn-danger:active.btn-style-3d,
.btn-danger.active.btn-style-3d {
  -webkit-box-shadow: 0 5px 0 <?php echo dhecho($darken_10_brand_danger); ?>;
  box-shadow: 0 5px 0 <?php echo dhecho($darken_10_brand_danger); ?>;
}
.btn-danger.btn-effect-border-fade:hover,
.btn-danger.btn-effect-border-fade:focus,
.btn-danger.btn-effect-border-fade:active,
.btn-danger.btn-effect-border-fade.active {
  box-shadow: 0 0 0 2px <?php echo dhecho($darken_10_brand_danger); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -moz-box-shadow: 0 0 0 2px <?php echo dhecho($darken_10_brand_danger); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -webkit-box-shadow: 0 0 0 2px <?php echo dhecho($darken_10_brand_danger); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
}

