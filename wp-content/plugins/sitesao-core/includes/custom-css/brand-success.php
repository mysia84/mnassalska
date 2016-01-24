<?php 
$brand_success = dh_format_color(dh_get_theme_option('brand-success','#262626'));
$brand_success = apply_filters('dh_brand_success', dhecho($brand_success));
if($brand_success === '#262626'){
	return '';
}
$darken_10_brand_success = darken(dh_format_color($brand_success),'10%');
$darken_12_brand_success = darken(dh_format_color($brand_success),'12%');
?>
.text-success {
  color: <?php echo dhecho($brand_success); ?>;
}
.blockquote-success {
  border-color: <?php echo dhecho($brand_success); ?>;
}
.btn-success {
  background-color: <?php echo dhecho($brand_success); ?>;
  border-color: <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-style-square-outlined,
.btn-success.btn-style-outlined {
  color: <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-style-3d {
  -webkit-box-shadow: 0 5px 0 <?php echo dhecho($brand_success); ?>;
  box-shadow: 0 5px 0 <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-effect-border-fade {
  box-shadow: 0 0 0 2px <?php echo dhecho($brand_success); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -moz-box-shadow: 0 0 0 2px <?php echo dhecho($brand_success); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -webkit-box-shadow: 0 0 0 2px <?php echo dhecho($brand_success); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
}
.btn-success.btn-effect-border-fade:hover,
.btn-success.btn-effect-border-fade:focus,
.btn-success.btn-effect-border-fade:active,
.btn-success.btn-effect-border-fade.active {
  background-color: <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-effect-border-hollow {
  box-shadow: 0 0 0 2px <?php echo dhecho($brand_success); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -moz-box-shadow: 0 0 0 2px <?php echo dhecho($brand_success); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -webkit-box-shadow: 0 0 0 2px <?php echo dhecho($brand_success); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
}
.btn-success.btn-effect-border-hollow:hover,
.btn-success.btn-effect-border-hollow:focus,
.btn-success.btn-effect-border-hollow:active,
.btn-success.btn-effect-border-hollow.active {
  color: <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-effect-border-outline-outward:before {
  border: 2px solid <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-effect-border-outline-inward:before {
  border: 2px solid <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-effect-bg-fade-in {
  color: <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-effect-bg-fade-in:hover,
.btn-success.btn-effect-bg-fade-in:focus,
.btn-success.btn-effect-bg-fade-in:active,
.btn-success.btn-effect-bg-fade-in.active {
  background-color: <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-effect-bg-fade-out {
  background-color: <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-effect-bg-fade-out:hover,
.btn-success.btn-effect-bg-fade-out:focus,
.btn-success.btn-effect-bg-fade-out:active,
.btn-success.btn-effect-bg-fade-out.active {
  color: <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-effect-bg-top {
  color: <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-effect-bg-top:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-effect-bg-right {
  color: <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-effect-bg-right:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-effect-bg-center {
  color: <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-effect-bg-center:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-effect-bg-skew-center {
  color: <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-effect-bg-skew-center:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-effect-bg-horizontal-center {
  color: <?php echo dhecho($brand_success); ?>;
}
.btn-success.btn-effect-bg-horizontal-center:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_success); ?>;
}
.tabs-success a:hover,
.tabs-success a:focus {
  color: <?php echo dhecho($brand_success); ?>;
}
.tabs-success.tabs-top .nav-tabs > li.active > a,
.tabs-success.tabs-top .nav-tabs > li.active > a:hover,
.tabs-success.tabs-top .nav-tabs > li.active > a:focus {
  border-top-color: <?php echo dhecho($brand_success); ?>;
  color: <?php echo dhecho($brand_success); ?>;
}
.tabs-success.tabs-left .nav-tabs > li.active > a,
.tabs-success.tabs-left .nav-tabs > li.active > a:hover,
.tabs-success.tabs-left .nav-tabs > li.active > a:focus {
  border-left-color: <?php echo dhecho($brand_success); ?>;
  color: <?php echo dhecho($brand_success); ?>;
}
.tabs-success.tabs-right .nav-tabs > li.active > a,
.tabs-success.tabs-right .nav-tabs > li.active > a:hover,
.tabs-success.tabs-right .nav-tabs > li.active > a:focus {
  border-right-color: <?php echo dhecho($brand_success); ?>;
  color: <?php echo dhecho($brand_success); ?>;
}
.tabs-success.tabs-below .nav-tabs > li.active > a,
.tabs-success.tabs-below .nav-tabs > li.active > a:hover,
.tabs-success.tabs-below .nav-tabs > li.active > a:focus {
  border-bottom-color: <?php echo dhecho($brand_success); ?>;
  color: <?php echo dhecho($brand_success); ?>;
}
.label-success {
  background-color: <?php echo dhecho($brand_success); ?>;
}
.alert-success {
  color: <?php echo dhecho($brand_success); ?>;
}
.panel-success > .panel-heading {
  color: <?php echo dhecho($brand_success); ?>;
}
.panel-success > .panel-heading .badge {
  background-color: <?php echo dhecho($brand_success); ?>;
}
.iconbox .iconbox-icon.icon-color-success i {
  color: <?php echo dhecho($brand_success); ?>;
}
.iconbox-effect-1.iconbox .iconbox-icon.icon-color-success i:after {
  -webkit-box-shadow: 0 0 0 1px <?php echo dhecho($brand_success); ?>;
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_success); ?>;
}
.iconbox-effect-1:hover.iconbox .iconbox-icon.icon-color-success i {
  background: <?php echo dhecho($brand_success); ?>;
}
.iconbox-effect-2.iconbox .iconbox-icon.icon-color-success i {
  -webkit-box-shadow: 0 0 0 1px <?php echo dhecho($brand_success); ?>;
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_success); ?>;
}
.iconbox-effect-2.iconbox .iconbox-icon.icon-color-success i:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_success); ?>;
}
.iconbox-effect-3.iconbox .iconbox-icon.icon-color-success i {
  -webkit-box-shadow: 0 0 0 1px <?php echo dhecho($brand_success); ?>;
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_success); ?>;
}
.iconbox-effect-3.iconbox .iconbox-icon.icon-color-success i:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_success); ?>;
}
.iconbox-effect-4.iconbox .iconbox-icon.icon-color-success i:after {
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_success); ?>;
}
.iconbox-effect-4:hover.iconbox .iconbox-icon.icon-color-success i {
  box-shadow: 0 0 0 6px <?php echo dhecho($brand_success); ?>;
  color: <?php echo dhecho($brand_success); ?>;
}
.iconbox .iconbox-icon.icon-color-success.icon-circle i {
  border: 1px solid <?php echo dhecho($brand_success); ?>;
}
.piechart .piechart-success {
  background: <?php echo dhecho($brand_success); ?>;
}
.pricing-table .pricing-success .pricing-title {
  background: <?php echo dhecho($brand_success); ?>;
  border-color: <?php echo dhecho($brand_success); ?>;
}
.pricing-table .pricing-success .pricing-price {
  background: <?php echo dhecho($darken_10_brand_success); ?>;
  border-color: <?php echo dhecho($darken_10_brand_success); ?>;
}
.timeline.timeline-success .timeline-line {
  border-left-color: <?php echo dhecho($brand_success); ?> !important;
}
.timeline.timeline-success .timeline-content {
  border-color: <?php echo dhecho($brand_success); ?>;
}
.timeline.timeline-success .timeline-badge {
  border-color: <?php echo dhecho($brand_success); ?>;
}
.timeline.timeline-success .timeline-badge a {
  color: <?php echo dhecho($brand_success); ?>;
}
.timeline.timeline-success.timeline-text .timeline-badge a,
.timeline.timeline-success.timeline-image .timeline-badge a,
.timeline.timeline-success.timeline-icon .timeline-badge a {
  color: #fff;
  background-color: <?php echo dhecho($brand_success); ?>;
}
.timeline.timeline-success .timeline-arrow {
  background-color: <?php echo dhecho($brand_success); ?> !important;
}
.portfolio .portfolio-wrap.portfolio-layout-wall .wall-success {
  background: <?php echo dhecho($brand_success); ?>;
}
.portfolio .portfolio-wrap.portfolio-layout-wall .wall-success .portfolio-caption:after {
  border-bottom-color: <?php echo dhecho($brand_success); ?>;
}
@media (min-width: 768px) {
  .portfolio .portfolio-wrap.portfolio-layout-wall .wall-success .portfolio-caption:after {
    border-bottom-color: transparent;
    border-right-color: <?php echo dhecho($brand_success); ?>;
  }
}
@media (min-width: 768px) {
  .portfolio .portfolio-wrap.portfolio-layout-wall .wall-success .pull-right ~ .portfolio-caption:after {
    border-left-color: <?php echo dhecho($brand_success); ?>;
  }
}
.product-slider-title.color-success .el-heading {
  color: <?php echo dhecho($brand_success); ?>;
}
.product-slider-title.color-success .el-heading:before {
  border-color: <?php echo dhecho($brand_success); ?>;
}
.mailchimp-form-result .success{
 color:<?php echo dhecho($brand_success); ?>;
}
<?php //10?>
a.text-success:hover {
  color: <?php echo dhecho($darken_10_brand_success); ?>;
}
a.bg-success:hover {
  background-color: <?php echo dhecho($darken_10_brand_success); ?>;
}
.btn-success:hover,
.btn-success:focus,
.btn-success:active,
.btn-success.active {
  background-color: <?php echo dhecho($darken_10_brand_success); ?>;
  border-color: <?php echo dhecho($darken_10_brand_success); ?>;
}
.btn-success:hover.btn-style-square-outlined,
.btn-success:focus.btn-style-square-outlined,
.btn-success:active.btn-style-square-outlined,
.btn-success.active.btn-style-square-outlined,
.btn-success:hover.btn-style-outlined,
.btn-success:focus.btn-style-outlined,
.btn-success:active.btn-style-outlined,
.btn-success.active.btn-style-outlined {
  color: <?php echo dhecho($darken_10_brand_success); ?>;
}
.btn-success.btn-effect-click-state:hover,
.btn-success.btn-effect-click-state:focus,
.btn-success.btn-effect-click-state:active,
.btn-success.btn-effect-click-state.active {
  -webkit-box-shadow: 0 2px 0 <?php echo dhecho($darken_10_brand_success); ?>;
  box-shadow: 0 2px 0 <?php echo dhecho($darken_10_brand_success); ?>;
}
.label-success[href]:hover,
.label-success[href]:focus {
  background-color: <?php echo dhecho($darken_10_brand_success); ?>;
}

<?php //12?>
.btn-success:hover.btn-style-3d,
.btn-success:focus.btn-style-3d,
.btn-success:active.btn-style-3d,
.btn-success.active.btn-style-3d {
  -webkit-box-shadow: 0 5px 0 <?php echo dhecho($darken_12_brand_success); ?>;
  box-shadow: 0 5px 0 <?php echo dhecho($darken_12_brand_success); ?>;
}
.btn-success.btn-effect-border-fade:hover,
.btn-success.btn-effect-border-fade:focus,
.btn-success.btn-effect-border-fade:active,
.btn-success.btn-effect-border-fade.active {
  box-shadow: 0 0 0 2px <?php echo dhecho($darken_12_brand_success); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -moz-box-shadow: 0 0 0 2px <?php echo dhecho($darken_12_brand_success); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -webkit-box-shadow: 0 0 0 2px <?php echo dhecho($darken_12_brand_success); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
}

