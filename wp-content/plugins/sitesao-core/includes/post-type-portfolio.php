<?php
if(!class_exists('DHPostTypePortfolio')):
	class DHPostTypePortfolio {
		public function __construct(){
			add_action ( 'init', array (&$this,'register' ) );
			add_filter( 'template_include', array( $this, 'template_loader' ) );
			if(is_admin()){
				add_action ( 'admin_enqueue_scripts', array( &$this, 'assets' ) );
				add_action ( 'add_meta_boxes', array (&$this, 'add_meta_boxes' ), 30 );
				add_action ( 'save_post', array (&$this,'save_meta_boxes' ), 1, 2 );
				
				//permalink setting
				add_action( 'admin_init', array( $this, 'permalink_setting' ) );
				add_action( 'admin_init', array( $this, 'permalink_save' ) );
			}
		}
		
		public function assets(){
			global $current_screen;
			if($current_screen->post_type === 'portfolio'){
				wp_enqueue_script( 'jquery-ui-sortable' );
				wp_enqueue_script('dh-postype-portfolio',DHINC_ASSETS_URL.'/js/posttype-portfolio.js',array('jquery-ui-sortable'),DHINC_VERSION,true);
			}
			return;
		}
		
		public function add_meta_boxes(){
// 			//Gallery
			$meta_box = array (
					'id' => 'dh-metabox-post-gallery',
					'title' => __ ( 'Gallery Settings', DH_DOMAIN ),
					'description' =>'',
					'post_type' => 'portfolio',
					'context' => 'normal',
					'priority' => 'high',
					'fields' => array (
							array (
									'label' => __ ( 'Gallery', DH_DOMAIN ),
									'name' => '_dh_gallery',
									'type' => 'gallery',
							),
					)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dh_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
			
			//Video
			$meta_box = array(
					'id' => 'dh-metabox-post-video',
					'title' => __('Video Settings', DH_DOMAIN),
					'description' => '',
					'post_type' => 'portfolio',
					'context' => 'normal',
					'priority' => 'high',
					'fields' => array(
							array(
									'label' => __('Embedded Code', DH_DOMAIN),
									'description' => __('Used when you select Video format. Enter a Youtube, Vimeo, Soundcloud, etc URL. See supported services at <a href="http://codex.wordpress.org/Embeds">http://codex.wordpress.org/Embeds</a>.', DH_DOMAIN),
									'name' => '_dh_video_embed',
									'type' => 'text',
							),
							array(
									'label' => __('MP4 File URL', DH_DOMAIN),
									'description' => __('Please enter in the URL to the .m4v video file.', DH_DOMAIN),
									'name' => '_dh_video_mp4',
									'type' => 'media',
							),
							array(
									'label' => __('OGV/OGG File URL', DH_DOMAIN),
									'description' => __('Please enter in the URL to the .ogv or .ogg video file.', DH_DOMAIN),
									'name' => '_dh_video_ogv',
									'type' => 'media',
							),
							array(
								'label' => __('WEBM File URL', DH_DOMAIN),
								'description' => __('Please enter in the URL to the .webm video file.', DH_DOMAIN),
								'name' => '_dh_video_webm',
								'type' => 'media',
							),
							array(
								'label' => __('Preview Image', DH_DOMAIN),
								'description' => __('Image should be at least 680px wide. Click the "Upload" button to begin uploading your image', DH_DOMAIN),
								'name' => '_dh_video_poster',
								'type' => 'image',
								)
					)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dh_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
			//Settings
			$meta_box = array (
					'id' => 'dh-metabox-portfolio-setting',
					'title' => __ ( 'Portfolio Settings', DH_DOMAIN ),
					'description' =>'',
					'post_type' => 'portfolio',
					'context' => 'normal',
					'priority' => 'high',
					'fields' => array (
							array (
									'label' => __ ( 'Project URL', DH_DOMAIN ),
									'description' => __ ( 'If you would like your project to link to a custom location, enter it here.(remember to include "http://")', DH_DOMAIN ),
									'name' => '_dh_url',
									'type' => 'text',
							),
							array (
									'label' => __ ( 'Project Accent Color', DH_DOMAIN ),
									'description' => __ ( 'This will be used in applicable project styles in the portfolio thumbnail view.', DH_DOMAIN ),
									'name' => '_dh_accent_color',
									'type' => 'color',
							),
							array (
									'label' => __ ( 'Masonry Item Sizing', DH_DOMAIN ),
									'description' => __ ( 'This will only be used if you choose to display your portfolio in the masonry/grid format.', DH_DOMAIN ),
									'name' => '_dh_masonry_size',
									'type' => 'select',
									'options'=>array(
										'normal'=>__('Normal',DH_DOMAIN),
										'double'=>__('Wide',DH_DOMAIN),
										'tall'=>__('Tall',DH_DOMAIN),
										'wide_tall'=>__('Wide Tall',DH_DOMAIN)
									)	
							),
					)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dh_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
				
			//Post format
			$meta_box = array(
					'id' => 'portfolio-formats-select',
					'title' =>  __('Format', DH_DOMAIN),
					'description' => '',
					'post_type' => 'portfolio',
					'context' => 'side',
					'priority' => 'core',
					'fields' => array(
							array(
									'label' =>  __('Standard', DH_DOMAIN),
									'name' => '_dh_portfolio_format',
									'id'   =>'post-format-standard',
									'type' => 'format_standard',
							),
							array(
									'label' =>  __('Gallery', DH_DOMAIN),
									'name' => '_dh_portfolio_format',
									'id'   =>'post-format-gallery',
									'type' => 'format_gallery',
							),
							array(
									'label' =>  __('Video', DH_DOMAIN),
									'name' => '_dh_portfolio_format',
									'id'   =>'post-format-video',
									'type' => 'format_video',
							)
					)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], array (&$this,'render_meta_boxes_format' ), $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
				
		}
		
		public function render_meta_boxes_format($post, $meta_box){
			$args = $meta_box ['args'];
			?>
			<style>
			<!--
			.dh-metaboxes-side {
				line-height: 2em;
			}
			
			.dh-metaboxes-side .post-format-icon:before {
				top: 5px;
			}
			-->
			</style>
			<?php
			if (! is_array ( $args ))
				return false;
			
			echo '<div class="dh-metaboxes-side">';
			if (isset ( $args ['description'] ) && $args ['description'] != '') {
				echo '<p>' . $args ['description'] . '</p>';
			}
			foreach ( $args ['fields'] as $field ) {
				if(!isset($field['type']))
					continue;
				
				$field['name']          = isset( $field['name'] ) ? $field['name'] : '';
				$value = get_post_meta( $post->ID,$field['name'], true );
				$field['value']         = isset( $field['value'] ) ? $field['value'] : '';
				if($value)
					$field['value']         = $value;
				$field['id'] 			= isset( $field['id'] ) ? $field['id'] : $field['name'];
				$field['description'] 	= isset($field['description']) ? $field['description'] : '';
				$field['label'] 		= isset( $field['label'] ) ? $field['label'] : '';
				$field['placeholder']   = isset( $field['placeholder'] ) ? $field['placeholder'] : $field['label'];
				$field['name'] = 'dh_meta['.$field['name'].']';
				
				switch ($field['type']){
					case 'format_video':
						echo '<input '.checked($field['value'],'video',false).' id="'.$field['id'].'" class="post-format" type="radio" value="video" name="'.esc_attr( $field['name'] ).'">';
						echo '<label class="post-format-icon post-format-video" for="'.$field['id'].'">'.esc_html( $field['label'] ).'</label><br/>';
					break;
					case 'format_gallery':
						echo '<input '.checked($field['value'],'gallery',false).' id="post-format-gallery" class="post-format" type="radio" value="gallery" name="'.esc_attr( $field['name'] ).'">';
						echo '<label class="post-format-icon post-format-gallery" for="'.$field['id'].'">'.esc_html( $field['label'] ).'</label><br/>';
					break;
					case 'format_standard':
						echo '<input id="post-format-standard" '.checked($field['value'],'',false).' class="post-format" type="radio" value="" name="'.esc_attr( $field['name'] ).'">';
						echo '<label class="post-format-icon post-format-standard" for="'.$field['id'].'">'.esc_html( $field['label'] ).'</label><br/>';
					break;
				}
			}
			echo '</div>';
				
		}
		public function save_meta_boxes($post_id, $post) {
			// $post_id and $post are required
			if (empty ( $post_id ) || empty ( $post )) {
				return;
			}
			
			// Dont' save meta boxes for revisions or autosaves
			if (defined ( 'DOING_AUTOSAVE' ) || is_int ( wp_is_post_revision ( $post ) ) || is_int ( wp_is_post_autosave ( $post ) )) {
				return;
			}
			// Check the nonce
			if (empty ( $_POST ['dh_meta_box_nonce'] ) || ! wp_verify_nonce ( $_POST ['dh_meta_box_nonce'], 'dh_meta_box_nonce' )) {
				return;
			}
			
			// Check the post being saved == the $post_id to prevent triggering this call for other save_post events
			if (empty ( $_POST ['post_ID'] ) || $_POST ['post_ID'] != $post_id) {
				return;
			}
			
			// Check user has permission to edit
			if (! current_user_can ( 'edit_post', $post_id )) {
				return;
			}
			
			// Process
			foreach( $_POST['dh_meta'] as $key=>$val ){
				update_post_meta( $post_id, $key, $val );
			}
		}
		
		public function template_loader($template){
			if(is_post_type_archive( 'portfolio' ) || is_tax( 'portfolio_category' ) ){
				$template       = locate_template( 'archive-portfolio.php' );
			}
			return $template;
		}
		
		public function register(){

			$permalinks = get_option( 'dh_portfolio_permalinks' );
			
			if(!post_type_exists('portfolio')):
				register_post_type ( "portfolio", 
					apply_filters ( 'dh_register_post_type_portfolio', array (
						'labels' => array (
								'name' => __ ( 'Portfolio', DH_DOMAIN ),
								'singular_name' => __ ( 'Portfolio', DH_DOMAIN ),
								'menu_name' => _x ( 'Portfolio', 'Admin menu name', DH_DOMAIN ),
								'add_new' => __ ( 'Add Portfolio', DH_DOMAIN ),
								'add_new_item' => __ ( 'Add New Portfolio', DH_DOMAIN ),
								'edit' => __ ( 'Edit', DH_DOMAIN ),
								'edit_item' => __ ( 'Edit Portfolio', DH_DOMAIN ),
								'new_item' => __ ( 'New Portfolio', DH_DOMAIN ),
								'view' => __ ( 'View Portfolio', DH_DOMAIN ),
								'view_item' => __ ( 'View Portfolio', DH_DOMAIN ),
								'search_items' => __ ( 'Search Portfolio', DH_DOMAIN ),
								'not_found' => __ ( 'No Portfolio found', DH_DOMAIN ),
								'not_found_in_trash' => __ ( 'No Portfolio found in trash', DH_DOMAIN ),
								'parent' => __ ( 'Parent Portfolio', DH_DOMAIN ) 
						),
						'public' => true,
						'show_ui' => true,
						'menu_position' => 21,
						'map_meta_cap' => true,
						'publicly_queryable' => true,
						'exclude_from_search' => false,
						'hierarchical' => false, // Hierarchical causes memory issues - WP loads all records!
						'query_var' => true,
						'supports' => array ('title','editor','excerpt','thumbnail','comments','custom-fields'),
						'has_archive'=> empty( $permalinks['archive_slug'] ) ? _x( 'portfolios', 'slug', DH_DOMAIN ) : $permalinks['archive_slug'] ,
						'show_in_nav_menus' => true,
						'menu_icon'=>'dashicons-screenoptions',
						'rewrite' => array(
								'slug'         => empty( $permalinks['archive_slug'] ) ? _x( 'portfolios', 'slug', DH_DOMAIN ) : $permalinks['archive_slug'],
								'with_front'   => false,
								'hierarchical' => true,
						),
					))
				);	
			endif;
			if(!taxonomy_exists('portfolio_category')):
				register_taxonomy( 'portfolio_category', 
					apply_filters ( 'dh_taxonomy_objects_portfolio_category', array ('portfolio') ), 
					apply_filters ( 'dh_taxonomy_args_portfolio_category', array (
						'hierarchical' => true,
						'label' => __ ( 'Portfolio Categories', DH_DOMAIN ),
						'labels' => array (
								'name' => __ ( 'Portfolio Categories', DH_DOMAIN ),
								'singular_name' => __ ( 'Portfolio Category', DH_DOMAIN ),
								'menu_name' => _x ( 'Categories', 'Admin menu name', DH_DOMAIN ),
								'search_items' => __ ( 'Search Portfolio Categories', DH_DOMAIN ),
								'all_items' => __ ( 'All Portfolio Categories', DH_DOMAIN ),
								'parent_item' => __ ( 'Parent Portfolio Category', DH_DOMAIN ),
								'parent_item_colon' => __ ( 'Parent Portfolio Category:', DH_DOMAIN ),
								'edit_item' => __ ( 'Edit Portfolio Category', DH_DOMAIN ),
								'update_item' => __ ( 'Update Portfolio Category', DH_DOMAIN ),
								'add_new_item' => __ ( 'Add New Portfolio Category', DH_DOMAIN ),
								'new_item_name' => __ ( 'New Portfolio Category Name', DH_DOMAIN ) 
						),
						'show_ui' => true,
						'query_var' => true,
						'rewrite' 				=> array(
							'slug'         => empty( $permalinks['category_slug'] ) ? _x( 'portfolio-category', 'slug', DH_DOMAIN ) : $permalinks['category_slug'],
							'with_front'   => false,
							'hierarchical' => true,
						),
					) ) 
				);
			endif;
			if(!taxonomy_exists('portfolio_attribute')):
				register_taxonomy ( 'portfolio_attribute', 
					apply_filters ( 'dh_taxonomy_objects_portfolio_attribute', array ('portfolio' ) ), 
					apply_filters ( 'dh_taxonomy_args_portfolio_attribute', array (
						'hierarchical' => true,
						'label' => __ ( 'Portfolio Attributes', DH_DOMAIN ),
						'labels' => array (
								'name' => __ ( 'Portfolio Attributes', DH_DOMAIN ),
								'singular_name' => __ ( 'Portfolio Attribute', DH_DOMAIN ),
								'menu_name' => _x ( 'Attributes', 'Admin menu name', DH_DOMAIN ),
								'search_items' => __ ( 'Search Portfolio Attributes', DH_DOMAIN ),
								'all_items' => __ ( 'All Portfolio Attributes', DH_DOMAIN ),
								'parent_item' => __ ( 'Parent Portfolio Attribute', DH_DOMAIN ),
								'parent_item_colon' => __ ( 'Parent Portfolio Attribute:', DH_DOMAIN ),
								'edit_item' => __ ( 'Edit Portfolio Attribute', DH_DOMAIN ),
								'update_item' => __ ( 'Update Portfolio Attribute', DH_DOMAIN ),
								'add_new_item' => __ ( 'Add New Portfolio Attribute', DH_DOMAIN ),
								'new_item_name' => __ ( 'New Portfolio Attribute Name', DH_DOMAIN ) 
						),
						'show_ui' => true,
						'query_var' => true,
						'public'=>false
					) ) 
				);
			endif;
		}
		
		public function permalink_setting(){
			add_settings_field(
				'dh_portfolio_archive_slug',      	            // id
				__( 'Archive portfolio  base', DH_DOMAIN ), 	// setting title
				array( &$this, 'archive_slug_callback' ),       // display callback
				'permalink',                 				    // settings page
				'optional'                  				    // settings section
			);
			add_settings_field(
				'dh_portfolio_category_slug',      	            // id
				__( 'Portfolio category base', DH_DOMAIN ), 	// setting title
				array( &$this, 'category_slug_callback' ),       // display callback
				'permalink',                 				    // settings page
				'optional'                  				    // settings section
			);
		}
		public function archive_slug_callback(){
			$permalinks = get_option( 'dh_portfolio_permalinks' );
		?>
			<input name="dh_portfolio_archive_slug" type="text" class="regular-text code" value="<?php if ( isset( $permalinks['archive_slug'] ) ) echo esc_attr( $permalinks['archive_slug'] ); ?>" placeholder="<?php echo _x('portfolios', 'slug', DH_DOMAIN) ?>" />
		<?php
		}
		
		public function category_slug_callback(){
			$permalinks = get_option( 'dh_portfolio_permalinks' );
		?>
			<input name="dh_portfolio_category_slug" type="text" class="regular-text code" value="<?php if ( isset( $permalinks['category_slug'] ) ) echo esc_attr( $permalinks['category_slug'] ); ?>" placeholder="<?php echo _x('portfolio-category', 'slug', DH_DOMAIN) ?>" />
		<?php
		}
		
		public function permalink_save(){
			if ( ! is_admin() )
				return;
			if ( isset( $_POST['permalink_structure'] ) || isset( $_POST['dh_portfolio_category_slug'] ) && isset( $_POST['dh_portfolio_archive_slug'] ) ) {
				$portfolio_archive_slug = sanitize_text_field( $_POST['dh_portfolio_archive_slug'] );
				$portfolio_category_slug = sanitize_text_field( $_POST['dh_portfolio_category_slug'] );
				$permalinks = get_option( 'dh_portfolio_permalinks' );
				if ( ! $permalinks )
					$permalinks = array();
				$permalinks['archive_slug'] 	= untrailingslashit( $portfolio_archive_slug );
				$permalinks['category_slug'] 		= untrailingslashit( $portfolio_category_slug );
				
				update_option( 'dh_portfolio_permalinks', $permalinks );
			}
		}
	}
	
	new DHPostTypePortfolio();
endif;