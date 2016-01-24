<?php
/**
 * Show options for ordering
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $wp_query;

if ( 1 == $wp_query->found_posts || ! woocommerce_products_will_display() )
	return;
?>
<form class="woocommerce-ordering clearfix" method="get">
	<div class="woocommerce-ordering-select">
		<label><?php _e('Sorting:',DH_THEME_DOMAIN)?></label>
		<div class="form-flat-select">
			<select name="orderby" class="orderby">
				<?php
					$catalog_orderby = apply_filters( 'woocommerce_catalog_orderby', array(
						'menu_order' => __( 'Default Sorting', DH_THEME_DOMAIN ),
						'popularity' => __( 'Sort by Popularity', DH_THEME_DOMAIN ),
						'rating'     => __( 'Sort by Average Rating', DH_THEME_DOMAIN ),
						'date'       => __( 'Sort by Newness', DH_THEME_DOMAIN ),
						'price'      => __( 'Sort by Price: low to high', DH_THEME_DOMAIN ),
						'price-desc' => __( 'Sort by Price: high to low', DH_THEME_DOMAIN )
					) );
		
					if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' )
						unset( $catalog_orderby['rating'] );
		
					foreach ( $catalog_orderby as $id => $name )
						echo '<option value="' . esc_attr( $id ) . '" ' . selected( $orderby, $id, false ) . '>' . esc_attr( $name ) . '</option>';
				?>
			</select>
			<i class="fa fa-angle-down"></i>
		</div>
	</div>
	<?php
		// Keep query string vars intact
		foreach ( $_GET as $key => $val ) {
			if ( 'per_page' === $key || 'orderby' === $key || 'submit' === $key )
				continue;
			
			if ( is_array( $val ) ) {
				foreach( $val as $innerVal ) {
					echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
				}
			
			} else {
				echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
			}
		}
	?>
	<div class="woocommerce-ordering-select">
		<label><?php _e('Show:',DH_THEME_DOMAIN)?></label>
		<div class="form-flat-select">
			<select name="per_page" class="per_page">
				<?php 
				$catalog_per_page =  dh_get_theme_option('woo-per-page',12);
				$pager_page = isset($_GET['per_page']) ? $_GET['per_page'] :  $catalog_per_page;
				?>
				<option value="<?php echo esc_attr($catalog_per_page) ?>" <?php selected($pager_page,$catalog_per_page)?>><?php echo sprintf('%1$s',$catalog_per_page)?></option>
				<option value="<?php echo esc_attr($catalog_per_page * 2) ?>" <?php selected($pager_page,($catalog_per_page * 2))?>><?php echo sprintf('%1$s',($catalog_per_page * 2))?></option>
				<option value="<?php echo esc_attr($catalog_per_page * 3) ?>" <?php selected($pager_page,($catalog_per_page * 3))?>><?php echo sprintf('%1$s',($catalog_per_page * 3))?></option>
			</select>
			<i class="fa fa-angle-down"></i>
		</div>
	</div>
</form>
