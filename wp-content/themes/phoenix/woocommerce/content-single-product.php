<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>
<?php 
$entry_image_col = 'col-md-6';
$entry_summary = 'col-md-6';
if ( dh_get_theme_option( 'woo-product-layout', 'full-width' ) === 'full-width' ) {
	$entry_summary = 'col-md-4';
	$entry_image_col = 'col-md-5';
}
?>
<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="row summary-container">
		<div class="<?php echo esc_attr($entry_image_col)?> col-sm-6 entry-image">
			<div class="single-product-images">
			<?php
				/**
				 * woocommerce_before_single_product_summary hook
				 *
				 * @hooked woocommerce_show_product_sale_flash - 10
				 * @hooked woocommerce_show_product_images - 20
				 */
				do_action( 'woocommerce_before_single_product_summary' );
			?>
			</div>
		</div>
		<div class="<?php echo esc_attr($entry_summary)?> col-sm-6 entry-summary">
			<div class="summary">
		
				<?php
					/**
					 * woocommerce_single_product_summary hook
					 *
					 * @hooked woocommerce_template_single_title - 5
					 * @hooked woocommerce_template_single_rating - 10
					 * @hooked woocommerce_template_single_price - 10
					 * @hooked woocommerce_template_single_excerpt - 20
					 * @hooked woocommerce_template_single_add_to_cart - 30
					 * @hooked woocommerce_template_single_meta - 40
					 * @hooked woocommerce_template_single_sharing - 50
					 */
					if ( dh_get_theme_option( 'woo-product-layout', 'full-width' ) === 'full-width' ) {
						woocommerce_template_single_title();
						woocommerce_template_single_rating();
						woocommerce_template_single_excerpt();
					}else{
						do_action( 'woocommerce_single_product_summary' );
					}
				?>
		
			</div><!-- .summary -->
		</div>
		<?php if ( dh_get_theme_option( 'woo-product-layout', 'full-width' ) === 'full-width' ) {?>
		<div class="col-md-3 col-sm-6 action-summary">
			<div class="summary">
				<?php do_action( 'woocommerce_single_product_summary' );?>
			</div>
		</div>
		<?php }?>
	</div>
	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
