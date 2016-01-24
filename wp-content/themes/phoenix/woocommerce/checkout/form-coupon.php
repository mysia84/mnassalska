<?php
/**
 * Checkout coupon form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! WC()->cart->coupons_enabled() ) {
	return;
}

$info_message = apply_filters( 'woocommerce_checkout_coupon_message', __( 'Have a coupon?', DH_THEME_DOMAIN ) . ' <a href="#" class="showcoupon">' . __( 'Click here to enter your code', DH_THEME_DOMAIN ) . '</a>' );
?>
<div class="woocommerce-info woocommerce-info-coupon"><?php echo dhecho( $info_message ); ?></div>
<form class="checkout_coupon" method="post" style="display:none">

	<p class="form-row form-row-first">
		<input type="text" name="coupon_code" class="input-text" placeholder="<?php _e( 'Coupon code', DH_THEME_DOMAIN ); ?>" id="coupon_code" value="" />
	</p>

	<p class="form-row form-row-last">
		<input type="submit" class="button" name="apply_coupon" value="<?php _e( 'Apply Coupon', DH_THEME_DOMAIN ); ?>" />
	</p>

	<div class="clear"></div>
</form>
