<?php
if ( ! class_exists( 'DHWoocommerce' ) ) :
	class DHWoocommerce {
	
		protected static $_instance = null;
	
		public function __construct() {
			add_action( 'init', array( &$this, 'init' ) );
// 			if(!is_admin())
// 				add_action( 'template_redirect', array( &$this, 'add_navbar_minicart' ) );
			global $pagenow;
			if ( is_admin() && isset( $_GET['activated'] ) && $pagenow === 'themes.php' ) {
				add_action( 'init', array( &$this, 'update_product_image_size' ), 1 );
			}
			
			add_action( 'wp_ajax_dh_json_search_products', array( &$this, 'json_search_products' ) );
		}

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
		
		public function init() {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) ) {
				return;
			}
			
			//add_action( 'wp_enqueue_scripts', array( &$this, 'removeprettyPhoto' ), 199 );
			
			if ( version_compare( WOOCOMMERCE_VERSION, "2.1" ) >= 0 ) {
				// WooCommerce 2.1 or above is active
				add_filter( 'woocommerce_enqueue_styles', '__return_false' );
			} else {
				// WooCommerce is less than 2.1
				define( 'WOOCOMMERCE_USE_CSS', false );
			}
			
			// remove wrapper
			remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
			remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
			
			//remove result count
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
			
			// remove page title
			add_filter( 'woocommerce_show_page_title', '__return_false' );
			
			// remove default loop price
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
			
			// Loop shop per page
			add_filter( 'loop_shop_per_page', array( &$this, 'loop_shop_per_page' ) );
			// Loop thumbnail
			remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
			add_action( 'woocommerce_before_shop_loop_item_title', array( &$this, 'template_loop_product_thumbnail' ), 10 );
			add_action( 'woocommerce_before_shop_loop_item_title', array( &$this, 'template_loop_product_frist_thumbnail' ), 11 );
			
			// Loop actions
			//add_action( 'woocommerce_after_shop_loop_item', array( &$this, 'template_loop_quickview' ), 11 );
			
			// wishlist
			//add_action( 'woocommerce_before_shop_loop_item_title', array( &$this, 'template_loop_wishlist' ), 12 );
			
			// Quick view
			add_action( 'wp_ajax_wc_loop_product_quickview', array( &$this, 'quickview' ) );
			add_action( 'wp_ajax_nopriv_wc_loop_product_quickview', array( &$this, 'quickview' ) );
			
			// Remove minicart
			add_action( 'wp_ajax_wc_minicart_remove_item', array( &$this, 'minicart_remove_item' ) );
			add_action( 'wp_ajax_nopriv_wc_minicart_remove_item', array( &$this, 'minicart_remove_item' ) );
			// add_to_cart_fragments
			add_filter( 'add_to_cart_fragments', array( &$this, 'add_to_cart_fragments' ) );
			
			// Upsell products
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
			add_action( 'woocommerce_after_single_product_summary', array( &$this, 'upsell_display' ), 15 );
			
			// Related products
			add_filter( 'woocommerce_output_related_products_args', array( &$this, 'related_products_args' ) );
			
			// Cross sell products
			remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
			add_action( 'woocommerce_cart_collaterals', array( &$this, 'cross_sell_display' ), 15 );
			
			//form field
			
			add_action('template_redirect', array(&$this,'single_fullwidth_layout'),99);
		}
// 		public function add_navbar_minicart(){
// 			$header_style = dh_get_theme_option('header-style','default');
// 			// add minicart in nav
// 			if($header_style =='classic')
// 				add_filter( 'wp_nav_menu_items', array( &$this, 'navbar_minicart' ), 12, 2 );
// 			if( $header_style =='below')
// 				add_filter( 'wp_nav_menu_items', array( &$this, 'navbar_minicart' ), 10, 2 );
// 		}

		public function single_fullwidth_layout(){
			//remove_shortcode('yith_wcwl_add_to_wishlist');
			//single full with
			if (dh_get_theme_option( 'woo-product-layout', 'full-width' ) === 'full-width' ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
				add_action( 'woocommerce_single_product_summary', array( &$this, 'single_sharing' ), 50 );
				//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
				//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
			}else{
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );
				add_action( 'woocommerce_single_product_summary', array( &$this, 'single_sharing' ), 50 );
			}
		}

		public static function content(){
			?>
			<?php
			if ( is_singular( 'product' ) ) {
	
				while ( have_posts() ) : the_post();
	
					wc_get_template_part( 'content', 'single-product' );
	
				endwhile;
	
			} else { ?>
				<?php 
				/**
				 * script
				 * {{
				 */
				wp_enqueue_script('vendor-carouFredSel');
				?>
				<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
	
					<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
	
				<?php endif; ?>
				
				<?php do_action( 'woocommerce_archive_description' ); ?>
				
				<?php if ( have_posts() ) : ?>
					<?php 
					
					$current_view_mode = dh_get_theme_option('dh_woocommerce_view_mode','grid');
					if(isset($_GET['mode']) && in_array($_GET['mode'], array('grid','list'))){
						$current_view_mode =  $_GET['mode'];
					}
					$grid_mode_href= ($current_view_mode == 'list' ? ' href="'.esc_url(add_query_arg('mode','grid')).'"' :'');
					$list_mode_href= ($current_view_mode == 'grid' ? ' href="'.esc_url(add_query_arg('mode','list')).'"' :'');
					?>
					<div class="shop-toolbar">
						<div class="view-mode">
							<a class="grid-mode<?php echo ($current_view_mode == 'grid' ? ' active' :'')?>" title="<?php esc_attr_e('Grid',DH_DOMAIN)?>" <?php echo ($grid_mode_href)?>><i class="fa fa-th"></i></a>
							<a class="list-mode<?php echo ($current_view_mode == 'list' ? ' active' :'')?>" title="<?php esc_attr_e('List',DH_DOMAIN)?>" <?php echo ($list_mode_href) ?>><i class="fa fa-th-list"></i></a>
						</div>
						<?php do_action('woocommerce_before_shop_loop'); ?>
						<nav class="woocommerce-pagination">
						  <?php
						   dh_paginate_links_short();
						  ?>
						</nav>
					</div>
					<div class="shop-loop <?php echo esc_attr($current_view_mode)?>">
					<?php woocommerce_product_loop_start(); ?>
						<?php woocommerce_product_subcategories(); ?>
						<?php while ( have_posts() ) : the_post(); ?>
	
							<?php wc_get_template_part( 'content', 'product' ); ?>
	
						<?php endwhile; // end of the loop. ?>
					<?php woocommerce_product_loop_end(); ?>
					</div>
					<?php do_action('woocommerce_after_shop_loop'); ?>
	
				<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
	
					<?php wc_get_template( 'loop/no-products-found.php' ); ?>
	
				<?php endif;
	
			}
		}
		
		public function cross_sell_display() {
			woocommerce_cross_sell_display( 2, 2 );
		}

		public function upsell_display() {
			if ( dh_get_theme_option( 'woo-product-layout', 'full-width' ) === 'full-width' ) {
				woocommerce_upsell_display( 4, 4 );
			} else {
				woocommerce_upsell_display( 3, 3 );
			}
		}

		public function related_products_args() {
			if ( dh_get_theme_option( 'woo-product-layout', 'full-width' ) === 'full-width' ) {
				$args = array( 'posts_per_page' => 4, 'columns' => 4 );
				return $args;
			}
			
			$args = array( 'posts_per_page' => 3, 'columns' => 3 );
			return $args;
		}
		
		// Number of products per page
		public function loop_shop_per_page() {
			$per_page = dh_get_theme_option( 'woo-per-page', 12 );
			if ( isset( $_GET['per_page'] ) )
				$per_page = absint( $_GET['per_page'] );
			return $per_page;
		}

		public function removeprettyPhoto() {
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
			wp_dequeue_script( 'prettyPhoto-init' );
			wp_dequeue_script( 'prettyPhoto' );
		}

		public function update_product_image_size() {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) ) {
				return;
			}
			$catalog = array( 'width' => '565', 'height' => '585', 'crop' => 1 );
			$single = array( 'width' => '700', 'height' => '700', 'crop' => 1 );
			$thumbnail = array( 'width' => '300', 'height' => '300', 'crop' => 1 );
			
			update_option( 'shop_catalog_image_size', $catalog );
			update_option( 'shop_single_image_size', $single );
			update_option( 'shop_thumbnail_image_size', $thumbnail );
		}

		protected function _cart_items_text( $count ) {
			$product_item_text = "";
			
			if ( $count > 1 ) {
				$product_item_text = str_replace( '%', number_format_i18n( $count ), __( '% items', DH_DOMAIN ) );
			} elseif ( $count == 0 ) {
				$product_item_text = __( '0 items', DH_DOMAIN );
			} else {
				$product_item_text = __( '1 item', DH_DOMAIN );
			}
			
			return $product_item_text;
		}

		public function add_to_cart_fragments( $fragments ) {
			$fragments['div.minicart'] = $this->_get_minicart( true, true );
			$fragments['span.minicart-icon'] = $this->_get_minicart_icon();
			$fragments['a.cart-icon-mobile'] = $this->_get_minicart_mobile();
			return $fragments;
		}

// 		public function navbar_minicart( $items, $args ) {
// 			if ( $args->theme_location == 'primary' 
// 					&& defined( 'WOOCOMMERCE_VERSION' ) 
// 					&& dh_get_theme_option( 'woo-cart-nav', 1 )
// 				) {
// 				$items .= '<li class="navbar-minicart">'.$this->_get_minicart().'</li>';
// 			}
// 			return $items;
// 		}

		public function minicart_remove_item() {
			global $woocommerce;
			$response = array();
			if ( ! isset( $_GET['item'] ) && ! isset( $_GET['_wpnonce'] ) ) {
				exit();
			}
			$woocommerce->cart->set_quantity( $_GET['item'], 0 );
			$cart_total = $woocommerce->cart->get_cart_total();
			$cart_count = absint($woocommerce->cart->cart_contents_count);
			$response['minicart_text'] = (dh_get_theme_option('header-style','below') === 'center' || dh_get_theme_option('site-style','default')=='creative') ? $cart_count : $cart_total;
			$response['minicart'] = $this->_get_minicart( true );
			// widget cart update
			ob_start();
			woocommerce_mini_cart();
			$mini_cart = ob_get_clean();
			$response['widget'] = '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>';
			
			echo json_encode( $response );
			exit();
		}

		protected function _get_minicart_mobile() {
			global $woocommerce;
			$cart_total = $woocommerce->cart->get_cart_total();
			$cart_count = $woocommerce->cart->cart_contents_count;
			$cart_output = '<a href="' . $woocommerce->cart->get_cart_url() . '" title="' .
				 __( 'View Cart', DH_DOMAIN ) . '"  class="cart-icon-mobile"><i class="minicart-icon-svg elegant_icon_cart_alt"></i> '.( ! empty( $cart_count ) ? '<span>' . $cart_count . '</span>' : '' ) . '</a>';
			return $cart_output;
		}

		protected function _get_minicart_icon() {
			global $woocommerce;
			$cart_total = $woocommerce->cart->get_cart_total();
			$cart_count = absint( $woocommerce->cart->cart_contents_count );
			$cart_has_item = '';
			if ( ! empty( $cart_count ) ) {
				$cart_has_item = ' has-item';
			}
			
			return '<span class="minicart-icon' . $cart_has_item . '">'.$this->_get_minicart_icon2().((dh_get_theme_option('header-style','below') === 'center' || dh_get_theme_option('site-style','default')=='creative') ? '<span>'.$cart_count.'</span>' : $cart_total).'</span>';
		}
		
		public function get_topbar_minicart(){
			return $this->_get_minicart(false,false,true);
		}

		public function get_minicart(){
			return '<div class="navbar-minicart">'.$this->_get_minicart().'</div>';	
		}
		
		protected function _get_minicart( $content = false, $_flag = false ,$topbar=false) {
			global $woocommerce;
			$cart_total = $woocommerce->cart->get_cart_total();
			$cart_count = absint( $woocommerce->cart->cart_contents_count );
			$cart_count_text = $this->_cart_items_text( $cart_count );
			
			if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {
				$cart_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_cart_url() );
			} else {
				$cart_url = esc_url( $woocommerce->cart->get_cart_url() );
			}
			$cart_has_item = '';
			
			if ( ! empty( $cart_count ) ) {
				$cart_has_item = ' has-item';
			}
			$minicart = '';
			if ( ! $content ) {
				$minicart .= '<a class="minicart-link" href="' . $cart_url . '">'.($topbar ? '':__('Shopping cart',DH_DOMAIN)).' <span class="minicart-icon '.$cart_has_item.'">'.$this->_get_minicart_icon2() . ($topbar ? '<span>'.$cart_count.'</span>' : $cart_total) . '</span></a>';
				$minicart .= '<div class="minicart" style="display:none">';
			}
			if ( $content && $_flag ) {
				$minicart .= '<div class="minicart" style="display:none">';
			}
			if ( ! empty( $cart_count ) ) {
				$minicart .= '<div class="minicart-header">' . $cart_count_text . ' ' . __( 'in the shopping cart', DH_DOMAIN ) . '</div>';
				$minicart .= '<div class="minicart-body">';
				foreach ( $woocommerce->cart->cart_contents as $cart_item_key => $cart_item ) {
					
					$cart_product = $cart_item['data'];
					$product_title = $cart_product->get_title();
					$product_short_title = ( strlen( $product_title ) > 25 ) ? substr( $product_title, 0, 22 ) . '...' : $product_title;
					
					if ( $cart_product->exists() && $cart_item['quantity'] > 0 ) {
						$minicart .= '<div class="cart-product clearfix">';
						$minicart .= '<div class="cart-product-image"><a class="cart-product-img" href="' .
							 get_permalink( $cart_item['product_id'] ) . '">' . $cart_product->get_image() . '</a></div>';
						$minicart .= '<div class="cart-product-details">';
						$minicart .= '<div class="cart-product-title"><a href="' .
							 get_permalink( $cart_item['product_id'] ) . '">' .
							 apply_filters( 
								'woocommerce_cart_widget_product_title', 
								$product_short_title, 
								$cart_product ) . '</a></div>';
						$minicart .= '<div class="cart-product-quantity-price">' . $cart_item['quantity'] . ' x ' .
							 woocommerce_price( $cart_product->get_price() ) . '</div>';
						// $minicart .= '<div class="cart-product-quantity">' . __('Quantity:', DH_DOMAIN) . ' ' .
						// $cart_item['quantity'] . '</div>';
						$minicart .= '</div>';
						$minicart .= apply_filters( 
							'woocommerce_cart_item_remove_link', 
							sprintf( 
								'<a href="%s" class="remove" title="%s">&times;</a>', 
								esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), 
								__( 'Remove this item', DH_DOMAIN ) ), 
							$cart_item_key );
						$minicart .= '</div>';
					}
				}
				$minicart .= '</div>';
				$minicart .= '<div class="minicart-footer">';
				//$minicart .= '<div class="minicart-total">' . __( 'Cart Subtotal', DH_DOMAIN ) . ' ' . $cart_total . '</div>';
				$minicart .= '<div class="minicart-actions clearfix">';
				if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {
// 					$cart_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_cart_url() );
					$checkout_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_checkout_url() );
					
					//$minicart .= '<a class="button" href="' . esc_url( $cart_url ) . '"><span class="text">' . __( 'View Cart', DH_DOMAIN ) . '</span></a>';
					$minicart .= '<a class="checkout-button button" href="' . esc_url( $checkout_url ) .'"><span class="text">' . __( 'Checkout', DH_DOMAIN ) . '</span></a>';
				} else {
					//$minicart .= '<a class="button" href="' . esc_url( $woocommerce->cart->get_cart_url() ) . '"><span class="text">' . __( 'View Cart', DH_DOMAIN ) . '</span></a>';
					$minicart .= '<a class="checkout-button button" href="' . esc_url( $woocommerce->cart->get_checkout_url() ) . '"><span class="text">' . __( 'Checkout', DH_DOMAIN ) . '</span></a>';
				}
				$minicart .= '</div>';
				$minicart .= '</div>';
			} else {
				$minicart .= '<div class="minicart-header show">' . __( 'Your shopping bag is empty.', DH_DOMAIN ) . '</div>';
				$shop_page_url = "";
				if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {
					$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
				} else {
					$shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
				}
				
				$minicart .= '<div class="minicart-footer">';
				$minicart .= '<div class="minicart-actions clearfix">';
				$minicart .= '<a class="button" href="' . esc_url( $shop_page_url ) . '"><span class="text">' . __( 'Go to the shop', DH_DOMAIN ) . '</span></a>';
				$minicart .= '</div>';
				$minicart .= '</div>';
			}
			if ( $content && $_flag ) {
				$minicart .= '</div>';
			}
			if ( ! $content ) {
				$minicart .= '</div>';
			}
			
			return $minicart;
		}

		public function single_sharing() {
			if ( dh_get_theme_option( 'show-woo-share', 1 ) ) {
				dh_share( 
					'', 
					dh_get_theme_option( 'woo-fb-share', 1 ), 
					dh_get_theme_option( 'woo-tw-share', 1 ), 
					dh_get_theme_option( 'woo-go-share', 1 ), 
					dh_get_theme_option( 'woo-pi-share', 0 ), 
					dh_get_theme_option( 'woo-li-share', 1 ) );
			}
		}

		public function template_loop_wishlist() {
			if ( $this->_yith_wishlist_is_active() ) {
				echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
			}
			return;
		}
		
		public function yith_wishlist_is_active(){
			return $this->_yith_wishlist_is_active();
		}

		protected function _yith_wishlist_is_active() {
			return defined( 'YITH_FUNCTIONS' );
		}
		

		public function template_loop_quickview() {
			global $product;
			if(apply_filters('dh_woocommerce_quickview', true))
				echo '<a class="shop-loop-quickview" data-product_id ="' . $product->id . '" title="' .esc_attr__( 'Quick view', DH_DOMAIN ) . '" href="' . esc_url( $product->get_permalink() ) . '"></a>';
		}

		public function quickview() {
			global $woocommerce, $post, $product;
			$product_id = $_POST['product_id'];
			$product = get_product( $product_id );
			$post = get_post( $product_id );
			$output = '';
			
			ob_start();
			woocommerce_get_template( 'content-quickview.php' );
			$output = ob_get_contents();
			ob_end_clean();
			
			echo trim($output);
			die();
		}

		public function template_loop_product_thumbnail() {
			$frist = $this->_product_get_frist_thumbnail();
			$current_view_mode = get_option('dh_woocommerce_view_mode',dh_get_theme_option('dh_woocommerce_view_mode','grid'));
			if(isset($_GET['mode']) && in_array($_GET['mode'], array('grid','list'))){
				$current_view_mode =  $_GET['mode'];
			}
			$thumbnail_size = 'shop_catalog';
			if($current_view_mode == 'list'){
				$thumbnail_size='shop_thumbnail';
			}
			echo '<div class="shop-loop-thumbnail'.($frist != '' ? ' shop-loop-front-thumbnail':'').'">' . woocommerce_get_product_thumbnail($thumbnail_size) . '</div>';
		}

		public function template_loop_product_frist_thumbnail() {
			if ( ( $frist = $this->_product_get_frist_thumbnail() ) != '' ) {
				echo '<div class="shop-loop-thumbnail shop-loop-back-thumbnail">' . $frist . '</div>';
			}
		}

		protected function _product_get_frist_thumbnail() {
			global $product, $post;
			$image = '';
			$current_view_mode = get_option('dh_woocommerce_view_mode',dh_get_theme_option('dh_woocommerce_view_mode','grid'));
			if(isset($_GET['mode']) && in_array($_GET['mode'], array('grid','list'))){
				$current_view_mode =  $_GET['mode'];
			}
			$thumbnail_size = 'shop_catalog';
			if($current_view_mode == 'list'){
				$thumbnail_size='shop_thumbnail';
			}
			if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
				$attachment_ids = $product->get_gallery_attachment_ids();
				$image_count = 0;
				if ( $attachment_ids ) {
					foreach ( $attachment_ids as $attachment_id ) {
						if ( get_post_meta( $attachment_id, '_woocommerce_exclude_image' ) )
							continue;
						
						$image = wp_get_attachment_image( $attachment_id, $thumbnail_size );
						
						$image_count++;
						if ( $image_count == 1 )
							break;
					}
				}
			} else {
				$attachments = get_posts( 
					array( 
						'post_type' => 'attachment', 
						'numberposts' => - 1, 
						'post_status' => null, 
						'post_parent' => $post->ID, 
						'post__not_in' => array( get_post_thumbnail_id() ), 
						'post_mime_type' => 'image', 
						'orderby' => 'menu_order', 
						'order' => 'ASC' ) );
				$image_count = 0;
				if ( $attachments ) {
					foreach ( $attachments as $attachment ) {
						
						if ( get_post_meta( $attachment->ID, '_woocommerce_exclude_image' ) == 1 )
							continue;
						
						$image = wp_get_attachment_image( $attachment->ID, $thumbnail_size );
						
						$image_count++;
						
						if ( $image_count == 1 )
							break;
					}
				}
			}
			return $image;
		}

		public function json_search_products() {
			$term = (string) sanitize_text_field( stripslashes( $_GET['term'] ) );
			if ( empty( $term ) )
				die();
			$post_types = array( 'product', 'product_variation' );
			if ( is_numeric( $term ) ) {
				
				$args = array( 
					'post_type' => $post_types, 
					'post_status' => 'publish', 
					'posts_per_page' => - 1, 
					'post__in' => array( 0, $term ), 
					'fields' => 'ids' );
				$args2 = array( 
					'post_type' => $post_types, 
					'post_status' => 'publish', 
					'posts_per_page' => - 1, 
					'post_parent' => $term, 
					'fields' => 'ids' );
				$args3 = array( 
					'post_type' => $post_types, 
					'post_status' => 'publish', 
					'posts_per_page' => - 1, 
					'meta_query' => array( 
						array( 'key' => '_sku', 'value' => $term, 'compare' => 'LIKE' ) ), 
					'fields' => 'ids' );
				$posts = array_unique( array_merge( get_posts( $args ), get_posts( $args2 ), get_posts( $args3 ) ) );
			} else {
				$args = array( 
					'post_type' => $post_types, 
					'post_status' => 'publish', 
					'posts_per_page' => - 1, 
					's' => $term, 
					'fields' => 'ids' );
				$args2 = array( 
					'post_type' => $post_types, 
					'post_status' => 'publish', 
					'posts_per_page' => - 1, 
					'meta_query' => array( 
						array( 'key' => '_sku', 'value' => $term, 'compare' => 'LIKE' ) ), 
					'fields' => 'ids' );
				$posts = array_unique( array_merge( get_posts( $args ), get_posts( $args2 ) ) );
			}
			$found_products = array();
			if ( $posts )
				foreach ( $posts as $post ) {
					$product = get_product( $post );
					$found_products[$post] = $this->_formatted_name( $product );
				}
			wp_send_json( $found_products );
		}

		protected function _formatted_name( WC_Product $product ) {
			if ( $product->get_sku() ) {
				$identifier = $product->get_sku();
			} else {
				$identifier = '#' . $product->id;
			}
			
			return sprintf( __( '%s &ndash; %s', DH_DOMAIN ), $identifier, $product->get_title() );
		}
		
		public function _get_minicart_icon2(){
			return '<i class="minicart-icon-svg elegant_icon_cart"></i> ';
		}
	}
	new DHWoocommerce();

endif;
