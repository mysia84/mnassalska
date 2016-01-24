<?php 
$brand_primary = dh_format_color(dh_get_theme_option('brand-primary','#262626'));
$brand_primary = apply_filters('dh_brand_primary', $brand_primary);
if($brand_primary === '#262626'){
	return '';
}
$darken_10_brand_primary = darken(dh_format_color($brand_primary),'10%');
$darken_12_brand_primary = darken(dh_format_color($brand_primary),'12%');
?>
.fade-loading i {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_primary); ?>;
}

.text-primary {
  color: <?php echo dhecho($brand_primary); ?>;
}
.bg-primary {
  background-color: <?php echo dhecho($brand_primary); ?>;
}
.blockquote-primary {
  border-color: <?php echo dhecho($brand_primary); ?>;
}
.form-control:focus {
  border-color: <?php echo dhecho($brand_primary); ?>;
}
.btn-primary {
  background-color: <?php echo dhecho($brand_primary); ?>;
  border-color: <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-style-square-outlined,
.btn-primary.btn-style-outlined {
  color: <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-style-3d {
  -webkit-box-shadow: 0 5px 0 <?php echo dhecho($brand_primary); ?>;
  box-shadow: 0 5px 0 <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-effect-border-fade {
  box-shadow: 0 0 0 2px <?php echo dhecho($brand_primary); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -moz-box-shadow: 0 0 0 2px <?php echo dhecho($brand_primary); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -webkit-box-shadow: 0 0 0 2px <?php echo dhecho($brand_primary); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
}
.btn-primary.btn-effect-border-fade:hover,
.btn-primary.btn-effect-border-fade:focus,
.btn-primary.btn-effect-border-fade:active,
.btn-primary.btn-effect-border-fade.active {
  background-color: <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-effect-border-hollow {
  box-shadow: 0 0 0 2px <?php echo dhecho($brand_primary); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -moz-box-shadow: 0 0 0 2px <?php echo dhecho($brand_primary); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
  -webkit-box-shadow: 0 0 0 2px <?php echo dhecho($brand_primary); ?> inset, 0 0 1px rgba(0, 0, 0, 0);
}
.btn-primary.btn-effect-border-hollow:hover,
.btn-primary.btn-effect-border-hollow:focus,
.btn-primary.btn-effect-border-hollow:active,
.btn-primary.btn-effect-border-hollow.active {
  color: <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-effect-border-outline-outward:before {
  border: 2px solid <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-effect-border-outline-inward:before {
  border: 2px solid <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-effect-bg-fade-in {
  color: <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-effect-bg-fade-in:hover,
.btn-primary.btn-effect-bg-fade-in:focus,
.btn-primary.btn-effect-bg-fade-in:active,
.btn-primary.btn-effect-bg-fade-in.active {
  background-color: <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-effect-bg-fade-out {
  background-color: <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-effect-bg-fade-out:hover,
.btn-primary.btn-effect-bg-fade-out:focus,
.btn-primary.btn-effect-bg-fade-out:active,
.btn-primary.btn-effect-bg-fade-out.active {
  color: <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-effect-bg-top {
  color: <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-effect-bg-top:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-effect-bg-right {
  color: <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-effect-bg-right:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-effect-bg-center {
  color: <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-effect-bg-center:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-effect-bg-skew-center {
  color: <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-effect-bg-skew-center:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-effect-bg-horizontal-center {
  color: <?php echo dhecho($brand_primary); ?>;
}
.btn-primary.btn-effect-bg-horizontal-center:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_primary); ?>;
}
.dropdown-menu > .active > a,
.dropdown-menu > .active > a:hover,
.dropdown-menu > .active > a:focus {
  background-color: <?php echo dhecho($brand_primary); ?>;
}
.tabs-top > .nav-tabs > .active > a,
.tabs-top > .nav-tabs > .active > a:hover,
.tabs-top > .nav-tabs > .active > a:focus {
  border-top: 2px solid <?php echo dhecho($brand_primary); ?>;
}
.tabs-below > .nav-tabs > .active > a,
.tabs-below > .nav-tabs > .active > a:hover,
.tabs-below > .nav-tabs > .active > a:focus {
  border-color-bottom: <?php echo dhecho($brand_primary); ?>;
}

.tabs-primary a:hover,
.tabs-primary a:focus {
  color: <?php echo dhecho($brand_primary); ?>;
}
.tabs-primary.tabs-top .nav-tabs > li.active > a,
.tabs-primary.tabs-top .nav-tabs > li.active > a:hover,
.tabs-primary.tabs-top .nav-tabs > li.active > a:focus {
  border-top-color: <?php echo dhecho($brand_primary); ?>;
  color: <?php echo dhecho($brand_primary); ?>;
}
.tabs-primary.tabs-left .nav-tabs > li.active > a,
.tabs-primary.tabs-left .nav-tabs > li.active > a:hover,
.tabs-primary.tabs-left .nav-tabs > li.active > a:focus {
  border-left-color: <?php echo dhecho($brand_primary); ?>;
  color: <?php echo dhecho($brand_primary); ?>;
}
.tabs-primary.tabs-right .nav-tabs > li.active > a,
.tabs-primary.tabs-right .nav-tabs > li.active > a:hover,
.tabs-primary.tabs-right .nav-tabs > li.active > a:focus {
  border-right-color: <?php echo dhecho($brand_primary); ?>;
  color: <?php echo dhecho($brand_primary); ?>;
}
.tabs-primary.tabs-below .nav-tabs > li.active > a,
.tabs-primary.tabs-below .nav-tabs > li.active > a:hover,
.tabs-primary.tabs-below .nav-tabs > li.active > a:focus {
  border-bottom-color: <?php echo dhecho($brand_primary); ?>;
  color: <?php echo dhecho($brand_primary); ?>;
}
<?php /*?>
.navbar-fixed-top .primary-nav > .open > a:hover,
.navbar-fixed-top .primary-nav > li > a:hover,
.navbar-fixed-top .primary-nav > .open > a:focus,
.navbar-fixed-top .primary-nav > li > a:focus {
  color: <?php echo dhecho($brand_primary); ?> !important;
}
.navbar-fixed-top .primary-nav > .open > a > .underline:before,
.navbar-fixed-top .primary-nav > li > a > .underline:before,
.navbar-fixed-top .primary-nav > .open > a > .underline:after,
.navbar-fixed-top .primary-nav > li > a > .underline:after {
  background-color: <?php echo dhecho($brand_primary); ?> !important;
}
.navbar-fixed-top .minicart-icon span {
  background: <?php echo dhecho($brand_primary); ?>;
}
@media (min-width: 992px) {
  .header-type-default .primary-nav > li > a .navicon {
    color: <?php echo dhecho($brand_primary); ?>;
  }
}
*/ ?>
.navbar-search .search-form-wrap.show-popup .searchform:before {
  background: <?php echo dhecho($brand_primary); ?>;
}
.cart-icon-mobile span {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_primary); ?>;
}
.paginate .paginate_links .pagination-meta.current,
.paginate .paginate_links .page-numbers.current {
  background: <?php echo dhecho($brand_primary); ?>;
  border: 1px solid <?php echo dhecho($brand_primary); ?>;
}
.paginate .paginate_links a.page-numbers:hover,
.paginate .paginate_links a.page-numbers:focus {
  border: 1px solid <?php echo dhecho($brand_primary); ?>;
  color: <?php echo dhecho($brand_primary); ?>;
}
.label-primary {
  background-color: <?php echo dhecho($brand_primary); ?>;
}
.alert-primary {
  color: <?php echo dhecho($brand_primary); ?>;
}
.progress-bars .progress-bar {
  background-color: <?php echo dhecho($brand_primary); ?>;
}
.panel-primary {
  border-color: <?php echo dhecho($brand_primary); ?>;
}
.panel-primary > .panel-heading {
  background-color: <?php echo dhecho($brand_primary); ?>;
  border-color: <?php echo dhecho($brand_primary); ?>;
}
.panel-primary > .panel-heading + .panel-collapse > .panel-body {
  border-top-color: <?php echo dhecho($brand_primary); ?>;
}
.panel-primary > .panel-heading .badge {
  color: <?php echo dhecho($brand_primary); ?>;
}
.panel-primary > .panel-footer + .panel-collapse > .panel-body {
  border-bottom-color: <?php echo dhecho($brand_primary); ?>;
}
.morphsearch-input {
  color: <?php echo dhecho($brand_primary); ?>;
}
.dummy-media-object:hover h3 {
  color: <?php echo dhecho($brand_primary); ?>;
}
.mejs-controls .mejs-time-rail .mejs-time-current {
  background: <?php echo dhecho($brand_primary); ?> !important;
}
.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current {
  background: <?php echo dhecho($brand_primary); ?> !important;
}
a[data-toggle="popover"],
a[data-toggle="tooltip"] {
  color: <?php echo dhecho($brand_primary); ?>;
}
.caroufredsel .caroufredsel-wrap .caroufredsel-next:hover,
.caroufredsel .caroufredsel-wrap .caroufredsel-prev:hover {
  background-color: <?php echo dhecho($brand_primary); ?>;
}
.iconbox .iconbox-icon.icon-color-primary i {
  color: <?php echo dhecho($brand_primary); ?>;
}
.iconbox-effect-1.iconbox .iconbox-icon.icon-color-primary i:after {
  -webkit-box-shadow: 0 0 0 1px <?php echo dhecho($brand_primary); ?>;
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_primary); ?>;
}
.iconbox-effect-1:hover.iconbox .iconbox-icon.icon-color-primary i {
  background: <?php echo dhecho($brand_primary); ?>;
}
.iconbox-effect-2.iconbox .iconbox-icon.icon-color-primary i {
  -webkit-box-shadow: 0 0 0 1px <?php echo dhecho($brand_primary); ?>;
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_primary); ?>;
}
.iconbox-effect-2.iconbox .iconbox-icon.icon-color-primary i:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_primary); ?>;
}
.iconbox-effect-3.iconbox .iconbox-icon.icon-color-primary i {
  -webkit-box-shadow: 0 0 0 1px <?php echo dhecho($brand_primary); ?>;
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_primary); ?>;
}
.iconbox-effect-3.iconbox .iconbox-icon.icon-color-primary i:after {
  background: none repeat scroll 0 0 <?php echo dhecho($brand_primary); ?>;
}
.iconbox-effect-4.iconbox .iconbox-icon.icon-color-primary i:after {
  box-shadow: 0 0 0 1px <?php echo dhecho($brand_primary); ?>;
}
.iconbox-effect-4:hover.iconbox .iconbox-icon.icon-color-primary i {
  box-shadow: 0 0 0 6px <?php echo dhecho($brand_primary); ?>;
  color: <?php echo dhecho($brand_primary); ?>;
}
.iconbox .iconbox-icon.icon-color-primary.icon-circle i {
  border: 1px solid <?php echo dhecho($brand_primary); ?>;
}
.piechart .piechart-primary {
  background: <?php echo dhecho($brand_primary); ?>;
}
.pricing-table .pricing-primary .pricing-title {
  background: <?php echo dhecho($brand_primary); ?>;
  border-color: <?php echo dhecho($brand_primary); ?>;
}
.pricing-table .pricing-primary .pricing-price {
  background: <?php echo dhecho($darken_10_brand_primary); ?>;
  border-color: <?php echo dhecho($darken_10_brand_primary); ?>;
}
.timeline.timeline-primary .timeline-line {
  border-left-color: <?php echo dhecho($brand_primary); ?> !important;
}
.timeline.timeline-primary .timeline-content {
  border-color: <?php echo dhecho($brand_primary); ?>;
}
.timeline.timeline-primary .timeline-badge {
  border-color: <?php echo dhecho($brand_primary); ?>;
}
.timeline.timeline-primary .timeline-badge a {
  color: <?php echo dhecho($brand_primary); ?>;
}
.timeline.timeline-primary.timeline-text .timeline-badge a,
.timeline.timeline-primary.timeline-image .timeline-badge a,
.timeline.timeline-primary.timeline-icon .timeline-badge a {
  background-color: <?php echo dhecho($brand_primary); ?>;
}
.timeline.timeline-primary .timeline-arrow {
  background-color: <?php echo dhecho($brand_primary); ?> !important;
}
.latestnews .latestnews-title {
  border-bottom: 2px solid <?php echo dhecho($brand_primary); ?>;
}
.latestnews .latestnews-title .el-heading {
  background: <?php echo dhecho($brand_primary); ?>;
}
.latestnews .latestnews-title .sub-cat li.active a {
  color: <?php echo dhecho($brand_primary); ?>;
}
.latestnews h2 {
  border-left: 2px solid <?php echo dhecho($brand_primary); ?>;
}
.heading-container.heading-single .heading-single-title .subtitle > span i {
  color: <?php echo dhecho($brand_primary); ?>;
}
.footer .footer-info .footer-social .tooltip-inner {
  background: <?php echo dhecho($brand_primary); ?>;
}
.footer .footer-info .footer-social .tooltip.top .tooltip-arrow {
  border-top-color: <?php echo dhecho($brand_primary); ?>;
}
.portfolio .portfolio-wrap.portfolio-layout-wall .portfolio-featured {
  background-color: <?php echo dhecho($brand_primary); ?>;
}
.portfolio .portfolio-wrap.portfolio-layout-wall .wall-primary {
  background: <?php echo dhecho($brand_primary); ?>;
}
.portfolio .portfolio-wrap.portfolio-layout-wall .wall-primary .portfolio-caption:after {
  border-bottom-color: <?php echo dhecho($brand_primary); ?>;
}
@media (min-width: 768px) {
  .portfolio .portfolio-wrap.portfolio-layout-wall .wall-primary .portfolio-caption:after {
    border-right-color: <?php echo dhecho($brand_primary); ?>;
  }
}
@media (min-width: 768px) {
  .portfolio .portfolio-wrap.portfolio-layout-wall .wall-primary .pull-right ~ .portfolio-caption:after {
    border-left-color: <?php echo dhecho($brand_primary); ?>;
  }
}
.entry-meta > span i {
  color: <?php echo dhecho($brand_primary); ?>;
}
.entry-content .link-content,
.entry-content .quote-content {
  background-color: <?php echo dhecho($brand_primary); ?>;
}
.share-links .tooltip-inner {
  background: <?php echo dhecho($brand_primary); ?>;
}
.share-links .tooltip.top .tooltip-arrow {
  border-top-color: <?php echo dhecho($brand_primary); ?>;
}
#wp-calendar > tbody > tr > td > a {
  background: <?php echo dhecho($brand_primary); ?>;
}
<?php if(defined('WOOCOMMERCE_VERSION')):?>
	.woocommerce .product-category:hover h3 {
	  color: #fff;
	  background: <?php echo dhecho($brand_primary); ?>;
	  border-color:<?php echo dhecho($brand_primary); ?>;
	}
	.woocommerce span.onsale{
	border-color:<?php echo dhecho($brand_primary); ?>;
	}
	.woocommerce div.product div.summary .product_meta a {
	  color: <?php echo dhecho($brand_primary); ?>;
	}
	
	.woocommerce .woocommerce-message {
	  border-left: 3px solid <?php echo dhecho($brand_primary); ?>;
	}
	.woocommerce .woocommerce-message:before {
	  color: <?php echo dhecho($brand_primary); ?>;
	}
	.woocommerce span.onsale {
	  background-color: <?php echo dhecho($brand_primary); ?>;
	}
	.woocommerce span.out_of_stock {
	  background: <?php echo dhecho($brand_primary); ?>;
	}
	
	.woocommerce .star-rating:before {
	  color: <?php echo dhecho($brand_primary); ?>;
	}
	.woocommerce .star-rating span {
	  color: <?php echo dhecho($brand_primary); ?>;
	}
	.woocommerce p.stars a.star-1:hover:after,
	.woocommerce p.stars a.star-1.active:after {
	  color: <?php echo dhecho($brand_primary); ?>;
	}
	.woocommerce p.stars a.star-2:hover:after,
	.woocommerce p.stars a.star-2.active:after {
	  color: <?php echo dhecho($brand_primary); ?>;
	}
	.woocommerce p.stars a.star-3:hover:after,
	.woocommerce p.stars a.star-3.active:after {
	  color: <?php echo dhecho($brand_primary); ?>;
	}
	.woocommerce p.stars a.star-4:hover:after,
	.woocommerce p.stars a.star-4.active:after {
	  color: <?php echo dhecho($brand_primary); ?>;
	}
	.woocommerce p.stars a.star-5:hover:after,
	.woocommerce p.stars a.star-5.active:after {
	  color: <?php echo dhecho($brand_primary); ?>;
	}
	.woocommerce table.cart td.actions .coupon .input-text:focus {
	  border-color: <?php echo dhecho($brand_primary); ?>;
	}
	.woocommerce.widget_product_search #s:focus {
	  border-color: <?php echo dhecho($brand_primary); ?>;
	}
	
<?php endif;?>
.tp-bullets.simplebullets.round .bullet.selected {
  background: <?php echo dhecho($brand_primary); ?> !important;
  border-color: <?php echo dhecho($brand_primary); ?> !important;
}
.user-login-modal .modal-header{
  background-color: <?php echo dhecho($brand_primary); ?>;
}
.user-login-modal .user-login-modal-result a,
.user-login-modal .user-login-modal-result .success-response{
	color: <?php echo dhecho($brand_primary); ?>;
}
<?php //10?>
a.text-primary:hover {
  color: <?php echo dhecho($darken_10_brand_primary); ?>;
}
a.bg-primary:hover {
  background-color: <?php echo dhecho($darken_10_brand_primary); ?>;
}
.label-primary[href]:hover,
.label-primary[href]:focus {
  background-color: <?php echo dhecho($darken_10_brand_primary); ?>;
}
a[data-toggle="popover"]:hover,
a[data-toggle="tooltip"]:hover {
  color: <?php echo dhecho($darken_10_brand_primary); ?>;
}