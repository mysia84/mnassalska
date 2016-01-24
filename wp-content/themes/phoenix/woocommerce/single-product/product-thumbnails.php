<?php
/**
 * Single Product Thumbnails
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product, $woocommerce;

$attachment_ids = $product->get_gallery_attachment_ids();

if ( $attachment_ids ) {
	?>
	<div class="single-product-thumbnails">
		<div class="<?php echo ((count($attachment_ids) > 3) ? 'caroufredsel': '') ?> product-thumbnails-slider" data-visible-max="4" data-visible-min="3" data-responsive="1" data-infinite="1">
			<div class="caroufredsel-wrap">
				<ul class="caroufredsel-items">
					<?php if(has_post_thumbnail()):?>
					<?php 
					$image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
					$image       = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), array('title' => $image_title) );
					?>
					<li class="caroufredsel-item">
						<div class="thumb selected">
							<a href="#" data-rel="0" title="<?php echo esc_attr($image_title)?>">
								<?php echo dhecho($image)?>
							</a>
						</div>
					</li>
					<?php endif;?>
				    <?php
				    $loop=1;
					foreach ( $attachment_ids as $attachment_id ) {
						$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
						$image_title = esc_attr( get_the_title( $attachment_id ) );	
					?>
					<li class="caroufredsel-item">
						<div class="thumb">
							<a href="#" data-rel="<?php echo esc_attr($loop)?>" title="<?php echo esc_attr($image_title)?>">
								<?php echo dhecho($image)?>
							</a>
						</div>
					</li>
					<?php
					$loop ++;
					}
					?>
				</ul>
				<a href="#" class="caroufredsel-prev"></a>
				<a href="#" class="caroufredsel-next"></a>
			</div>
		</div>
	</div>
	<?php
}