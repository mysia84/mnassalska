<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.14
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $woocommerce, $product;

?>
<?php
	if ( has_post_thumbnail() ) {

		$image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
		$image       = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
			'title' => $image_title
			) );
		
		$attachment_ids = $product->get_gallery_attachment_ids();
		$image_full = wp_get_attachment_image_src(get_post_thumbnail_id(),'full');

		?>
		<?php $shop_single = wc_get_image_size( 'shop_single' );?>
		<div class="single-product-images-slider">
			<div class="caroufredsel product-images-slider" data-height="variable" data-visible="1" data-responsive="1" data-infinite="1">
				<div class="caroufredsel-wrap">
					<ul class="caroufredsel-items">
						<li class="caroufredsel-item">
							<a data-itemprop="image" href="<?php echo esc_attr(@$image_full[0])?>" data-rel="magnific-popup" title="<?php echo esc_attr($image_title)?>">
								<?php echo dhecho($image)?>
							</a>
						</li>
						<?php if ( $attachment_ids ) {?>
							<?php  $loop=1; ?>
							<?php foreach ( $attachment_ids as $attachment_id ) {?>
							<?php 
							$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );
							$image_title = esc_attr( get_the_title( $attachment_id ) );
							$image_full = wp_get_attachment_image_src($attachment_id,'full');
							?>
							<li class="caroufredsel-item">
								<div class="thumb">
									<a href="<?php echo esc_attr(@$image_full[0])?>" data-rel="magnific-popup" title="<?php echo esc_attr($image_title)?>">
										<?php echo dhecho($image)?>
									</a>
								</div>
							</li>
							<?php
								$loop ++; 
								}
							 ?>
						<?php } ?>
					</ul>
					<a href="#" class="caroufredsel-prev"></a>
					<a href="#" class="caroufredsel-next"></a>
				</div>
			</div>
		</div>
		<?php
	} else {
		echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', wc_placeholder_img_src() ), $post->ID );

	}
?>
<?php 
if ( dh_get_theme_option( 'woo-product-layout', 'full-width' ) != 'full-width' )
	do_action( 'woocommerce_product_thumbnails' ); 
?>
