<?php
if ( ! class_exists( 'DHVisualComposer' ) && defined( 'WPB_VC_VERSION' ) ) :
	
	define( 'DHVC_ADD_ITEM_TITLE', __( "Add Item", DH_DOMAIN ) );
	define( 'DHVC_ITEM_TITLE', __( "Item", DH_DOMAIN ) );
	define( 'DHVC_MOVE_TITLE', __( 'Move', DH_DOMAIN ) );
	
	if ( ! class_exists( 'WPBakeryShortCode_VC_Tabs', false ) )
		require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-tabs.php' );
	
	if ( ! class_exists( 'WPBakeryShortCode_VC_Column', false ) )
		require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-column.php' );

	class DHWPBakeryShortcodeContainer extends WPBakeryShortCodesContainer {

		/**
		 * Find html template for shortcode output.
		 */
		protected function findShortcodeTemplate() {
			// Check template path in shortcode's mapping settings
			if ( ! empty( $this->settings['html_template'] ) && is_file( $this->settings( 'html_template' ) ) ) {
				return $this->setTemplate( $this->settings['html_template'] );
			}
			// Check template in theme directory
			$user_template = vc_manager()->getShortcodesTemplateDir( $this->getFilename() . '.php' );
			
			if ( is_file( $user_template ) ) {
				
				return $this->setTemplate( $user_template );
			}
		}

		protected function getFileName() {
			return $this->shortcode;
		}
	}

	class DHWPBakeryShortcode extends WPBakeryShortCode {

		/**
			 * Find html template for shortcode output.
			 */
		protected function findShortcodeTemplate() {
			// Check template path in shortcode's mapping settings
			if ( ! empty( $this->settings['html_template'] ) && is_file( $this->settings( 'html_template' ) ) ) {
				return $this->setTemplate( $this->settings['html_template'] );
			}
			// Check template in theme directory
			$user_template = vc_manager()->getShortcodesTemplateDir( $this->getFilename() . '.php' );
			if ( is_file( $user_template ) ) {
				return $this->setTemplate( $user_template );
			}
		}

		protected function getFileName() {
			return $this->shortcode;
		}
	}

	class WPBakeryShortCode_DH_Carousel extends WPBakeryShortCode_VC_Tabs {

		static $filter_added = false;

		public function __construct( $settings ) {
			parent::__construct( $settings );
			// WPBakeryVisualComposer::getInstance()->addShortCode( array( 'base' => 'vc_tab' ) );
			if ( ! self::$filter_added ) {
				$this->addFilter( 'vc_inline_template_content', 'setCustomTabId' );
				self::$filter_added = true;
			}
		}

		protected $predefined_atts = array( 'tab_id' => DHVC_ITEM_TITLE, 'title' => '' );

		public function contentAdmin( $atts, $content = null ) {
			$width = $custom_markup = '';
			$shortcode_attributes = array( 'width' => '1/1' );
			foreach ( $this->settings['params'] as $param ) {
				if ( $param['param_name'] != 'content' ) {
					if ( isset( $param['value'] ) && is_string( $param['value'] ) ) {
						$shortcode_attributes[$param['param_name']] = $param['value'];
					} elseif ( isset( $param['value'] ) ) {
						$shortcode_attributes[$param['param_name']] = $param['value'];
					}
				} else 
					if ( $param['param_name'] == 'content' && $content == NULL ) {
						$content = $param['value'];
					}
			}
			extract( shortcode_atts( $shortcode_attributes, $atts ) );
			
			// Extract tab titles
			
			preg_match_all( 
				'/dh_carousel_item title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', 
				$content, 
				$matches, 
				PREG_OFFSET_CAPTURE );
			
			$output = '';
			$tab_titles = array();
			
			if ( isset( $matches[0] ) ) {
				$tab_titles = $matches[0];
			}
			$tmp = '';
			if ( count( $tab_titles ) ) {
				$tmp .= '<ul class="clearfix tabs_controls">';
				foreach ( $tab_titles as $tab ) {
					preg_match( 
						'/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', 
						$tab[0], 
						$tab_matches, 
						PREG_OFFSET_CAPTURE );
					if ( isset( $tab_matches[1][0] ) ) {
						$tmp .= '<li><a href="#tab-' .
							 ( isset( $tab_matches[3][0] ) ? $tab_matches[3][0] : sanitize_title( $tab_matches[1][0] ) ) .
							 '">' . $tab_matches[1][0] . '</a></li>';
					}
				}
				$tmp .= '</ul>' . "\n";
			} else {
				$output .= do_shortcode( $content );
			}
			$elem = $this->getElementHolder( $width );
			
			$iner = '';
			foreach ( $this->settings['params'] as $param ) {
				$custom_markup = '';
				$param_value = isset( $$param['param_name'] ) ? $$param['param_name'] : '';
				if ( is_array( $param_value ) ) {
					// Get first element from the array
					reset( $param_value );
					$first_key = key( $param_value );
					$param_value = $param_value[$first_key];
				}
				$iner .= $this->singleParamHtmlHolder( $param, $param_value );
			}
			if ( isset( $this->settings["custom_markup"] ) && $this->settings["custom_markup"] != '' ) {
				if ( $content != '' ) {
					$custom_markup = str_ireplace( "%content%", $tmp . $content, $this->settings["custom_markup"] );
				} else 
					if ( $content == '' && isset( $this->settings["default_content_in_template"] ) &&
						 $this->settings["default_content_in_template"] != '' ) {
						$custom_markup = str_ireplace( 
							"%content%", 
							$this->settings["default_content_in_template"], 
							$this->settings["custom_markup"] );
					} else {
						$custom_markup = str_ireplace( "%content%", '', $this->settings["custom_markup"] );
					}
				$iner .= do_shortcode( $custom_markup );
			}
			$elem = str_ireplace( '%wpb_element_content%', $iner, $elem );
			$output = $elem;
			
			return $output;
		}

		/**
			 * Find html template for shortcode output.
			 */
		protected function findShortcodeTemplate() {
			// Check template path in shortcode's mapping settings
			if ( ! empty( $this->settings['html_template'] ) && is_file( $this->settings( 'html_template' ) ) ) {
				return $this->setTemplate( $this->settings['html_template'] );
			}
			// Check template in theme directory
			$user_template = vc_manager()->getShortcodesTemplateDir( $this->getFilename() . '.php' );
			if ( is_file( $user_template ) ) {
				return $this->setTemplate( $user_template );
			}
		}

		protected function getFileName() {
			return $this->shortcode;
		}

		public function getTabTemplate() {
			return '<div class="wpb_template">' .
				 do_shortcode( '[dh_carousel_item title="' . DHVC_ITEM_TITLE . '" tab_id=""][/dh_carousel_item]' ) .
				 '</div>';
		}
	}

	class WPBakeryShortCode_DH_Carousel_Item extends WPBakeryShortCode_VC_Column {

		protected $controls_css_settings = 'tc vc_control-container';

		protected $controls_list = array( 'add', 'edit', 'clone', 'delete' );

		protected $predefined_atts = array( 'tab_id' => DHVC_ITEM_TITLE, 'title' => '' );

		protected $controls_template_file = 'editors/partials/backend_controls_tab.tpl.php';

		public function __construct( $settings ) {
			parent::__construct( $settings );
		}

		public function customAdminBlockParams() {
			return ' id="tab-' . $this->atts['tab_id'] . '"';
		}

		public function mainHtmlBlockParams( $width, $i ) {
			return 'data-element_type="' . $this->settings["base"] . '" class="wpb_' . $this->settings['base'] .
				 ' wpb_sortable wpb_content_holder"' . $this->customAdminBlockParams();
		}

		public function containerHtmlBlockParams( $width, $i ) {
			return 'class="wpb_column_container vc_container_for_children"';
		}

		public function getColumnControls( $controls, $extended_css = '' ) {
			return $this->getColumnControlsModular( $extended_css );
		}

		/**
		 * Find html template for shortcode output.
		 */
		protected function findShortcodeTemplate() {
			// Check template path in shortcode's mapping settings
			if ( ! empty( $this->settings['html_template'] ) && is_file( $this->settings( 'html_template' ) ) ) {
				return $this->setTemplate( $this->settings['html_template'] );
			}
			// Check template in theme directory
			$user_template = vc_manager()->getShortcodesTemplateDir( $this->getFilename() . '.php' );
			if ( is_file( $user_template ) ) {
				return $this->setTemplate( $user_template );
			}
		}

		protected function getFileName() {
			return $this->shortcode;
		}
	}

	class WPBakeryShortCode_DH_Testimonial extends WPBakeryShortCode_DH_Carousel {

		static $filter_added = false;

		public function __construct( $settings ) {
			parent::__construct( $settings );
			if ( ! self::$filter_added ) {
				$this->addFilter( 'vc_inline_template_content', 'setCustomTabId' );
				self::$filter_added = true;
			}
		}

		protected $predefined_atts = array( 'tab_id' => DHVC_ITEM_TITLE, 'title' => '' );

		public function contentAdmin( $atts, $content = null ) {
			$width = $custom_markup = '';
			$shortcode_attributes = array( 'width' => '1/1' );
			foreach ( $this->settings['params'] as $param ) {
				if ( $param['param_name'] != 'content' ) {
					if ( isset( $param['value'] ) && is_string( $param['value'] ) ) {
						$shortcode_attributes[$param['param_name']] = $param['value'];
					} elseif ( isset( $param['value'] ) ) {
						$shortcode_attributes[$param['param_name']] = $param['value'];
					}
				} else 
					if ( $param['param_name'] == 'content' && $content == NULL ) {
						$content = $param['value'];
					}
			}
			extract( shortcode_atts( $shortcode_attributes, $atts ) );
			
			// Extract tab titles
			
			preg_match_all( 
				'/dh_testimonial_item title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', 
				$content, 
				$matches, 
				PREG_OFFSET_CAPTURE );
			
			$output = '';
			$tab_titles = array();
			
			if ( isset( $matches[0] ) ) {
				$tab_titles = $matches[0];
			}
			$tmp = '';
			if ( count( $tab_titles ) ) {
				$tmp .= '<ul class="clearfix tabs_controls">';
				foreach ( $tab_titles as $tab ) {
					preg_match( 
						'/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', 
						$tab[0], 
						$tab_matches, 
						PREG_OFFSET_CAPTURE );
					if ( isset( $tab_matches[1][0] ) ) {
						$tmp .= '<li><a href="#tab-' .
							 ( isset( $tab_matches[3][0] ) ? $tab_matches[3][0] : sanitize_title( $tab_matches[1][0] ) ) .
							 '">' . $tab_matches[1][0] . '</a></li>';
					}
				}
				$tmp .= '</ul>' . "\n";
			} else {
				$output .= do_shortcode( $content );
			}
			$elem = $this->getElementHolder( $width );
			
			$iner = '';
			foreach ( $this->settings['params'] as $param ) {
				$custom_markup = '';
				$param_value = isset( $$param['param_name'] ) ? $$param['param_name'] : '';
				if ( is_array( $param_value ) ) {
					// Get first element from the array
					reset( $param_value );
					$first_key = key( $param_value );
					$param_value = $param_value[$first_key];
				}
				$iner .= $this->singleParamHtmlHolder( $param, $param_value );
			}
			if ( isset( $this->settings["custom_markup"] ) && $this->settings["custom_markup"] != '' ) {
				if ( $content != '' ) {
					$custom_markup = str_ireplace( "%content%", $tmp . $content, $this->settings["custom_markup"] );
				} else 
					if ( $content == '' && isset( $this->settings["default_content_in_template"] ) &&
						 $this->settings["default_content_in_template"] != '' ) {
						$custom_markup = str_ireplace( 
							"%content%", 
							$this->settings["default_content_in_template"], 
							$this->settings["custom_markup"] );
					} else {
						$custom_markup = str_ireplace( "%content%", '', $this->settings["custom_markup"] );
					}
				$iner .= do_shortcode( $custom_markup );
			}
			$elem = str_ireplace( '%wpb_element_content%', $iner, $elem );
			$output = $elem;
			
			return $output;
		}

		/**
		 * Find html template for shortcode output.
		 */
		protected function findShortcodeTemplate() {
			// Check template path in shortcode's mapping settings
			if ( ! empty( $this->settings['html_template'] ) && is_file( $this->settings( 'html_template' ) ) ) {
				return $this->setTemplate( $this->settings['html_template'] );
			}
			// Check template in theme directory
			$user_template = vc_manager()->getShortcodesTemplateDir( $this->getFilename() . '.php' );
			if ( is_file( $user_template ) ) {
				return $this->setTemplate( $user_template );
			}
		}

		protected function getFileName() {
			return $this->shortcode;
		}

		public function getTabTemplate() {
			return '<div class="wpb_template">' .
				 do_shortcode( '[dh_testimonial_item title="' . DHVC_ITEM_TITLE . '" tab_id=""][/dh_testimonial_item]' ) .
				 '</div>';
		}
	}

	class WPBakeryShortCode_DH_Testimonial_Item extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Timeline extends WPBakeryShortCode_DH_Carousel {

		static $filter_added = false;

		public function __construct( $settings ) {
			parent::__construct( $settings );
			if ( ! self::$filter_added ) {
				$this->addFilter( 'vc_inline_template_content', 'setCustomTabId' );
				self::$filter_added = true;
			}
		}

		protected $predefined_atts = array( 'tab_id' => DHVC_ITEM_TITLE, 'title' => '' );

		public function contentAdmin( $atts, $content = null ) {
			$width = $custom_markup = '';
			$shortcode_attributes = array( 'width' => '1/1' );
			foreach ( $this->settings['params'] as $param ) {
				if ( $param['param_name'] != 'content' ) {
					if ( isset( $param['value'] ) && is_string( $param['value'] ) ) {
						$shortcode_attributes[$param['param_name']] = $param['value'];
					} elseif ( isset( $param['value'] ) ) {
						$shortcode_attributes[$param['param_name']] = $param['value'];
					}
				} else 
					if ( $param['param_name'] == 'content' && $content == NULL ) {
						$content = $param['value'];
					}
			}
			extract( shortcode_atts( $shortcode_attributes, $atts ) );
			
			// Extract tab titles
			
			preg_match_all( 
				'/dh_timeline_item title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', 
				$content, 
				$matches, 
				PREG_OFFSET_CAPTURE );
			
			$output = '';
			$tab_titles = array();
			
			if ( isset( $matches[0] ) ) {
				$tab_titles = $matches[0];
			}
			$tmp = '';
			if ( count( $tab_titles ) ) {
				$tmp .= '<ul class="clearfix tabs_controls">';
				foreach ( $tab_titles as $tab ) {
					preg_match( 
						'/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', 
						$tab[0], 
						$tab_matches, 
						PREG_OFFSET_CAPTURE );
					if ( isset( $tab_matches[1][0] ) ) {
						$tmp .= '<li><a href="#tab-' .
							 ( isset( $tab_matches[3][0] ) ? $tab_matches[3][0] : sanitize_title( $tab_matches[1][0] ) ) .
							 '">' . $tab_matches[1][0] . '</a></li>';
					}
				}
				$tmp .= '</ul>' . "\n";
			} else {
				$output .= do_shortcode( $content );
			}
			$elem = $this->getElementHolder( $width );
			
			$iner = '';
			foreach ( $this->settings['params'] as $param ) {
				$custom_markup = '';
				$param_value = isset( $$param['param_name'] ) ? $$param['param_name'] : '';
				if ( is_array( $param_value ) ) {
					// Get first element from the array
					reset( $param_value );
					$first_key = key( $param_value );
					$param_value = $param_value[$first_key];
				}
				$iner .= $this->singleParamHtmlHolder( $param, $param_value );
			}
			if ( isset( $this->settings["custom_markup"] ) && $this->settings["custom_markup"] != '' ) {
				if ( $content != '' ) {
					$custom_markup = str_ireplace( "%content%", $tmp . $content, $this->settings["custom_markup"] );
				} else 
					if ( $content == '' && isset( $this->settings["default_content_in_template"] ) &&
						 $this->settings["default_content_in_template"] != '' ) {
						$custom_markup = str_ireplace( 
							"%content%", 
							$this->settings["default_content_in_template"], 
							$this->settings["custom_markup"] );
					} else {
						$custom_markup = str_ireplace( "%content%", '', $this->settings["custom_markup"] );
					}
				$iner .= do_shortcode( $custom_markup );
			}
			$elem = str_ireplace( '%wpb_element_content%', $iner, $elem );
			$output = $elem;
			
			return $output;
		}

		/**
		 * Find html template for shortcode output.
		 */
		protected function findShortcodeTemplate() {
			// Check template path in shortcode's mapping settings
			if ( ! empty( $this->settings['html_template'] ) && is_file( $this->settings( 'html_template' ) ) ) {
				return $this->setTemplate( $this->settings['html_template'] );
			}
			// Check template in theme directory
			$user_template = vc_manager()->getShortcodesTemplateDir( $this->getFilename() . '.php' );
			if ( is_file( $user_template ) ) {
				return $this->setTemplate( $user_template );
			}
		}

		protected function getFileName() {
			return $this->shortcode;
		}

		public function getTabTemplate() {
			return '<div class="wpb_template">' .
				 do_shortcode( '[dh_timeline_item title="' . DHVC_ITEM_TITLE . '" tab_id=""][/dh_timeline_item]' ) .
				 '</div>';
		}
	}

	class WPBakeryShortCode_DH_Timeline_Item extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Pricing_Table extends WPBakeryShortCode_VC_Tabs {

		static $filter_added = false;

		public function __construct( $settings ) {
			parent::__construct( $settings );
			if ( ! self::$filter_added ) {
				$this->addFilter( 'vc_inline_template_content', 'setCustomTabId' );
				self::$filter_added = true;
			}
		}

		protected $predefined_atts = array( 'tab_id' => DHVC_ITEM_TITLE, 'title' => '' );

		public function contentAdmin( $atts, $content = null ) {
			$width = $custom_markup = '';
			$shortcode_attributes = array( 'width' => '1/1' );
			foreach ( $this->settings['params'] as $param ) {
				if ( $param['param_name'] != 'content' ) {
					if ( isset( $param['value'] ) && is_string( $param['value'] ) ) {
						$shortcode_attributes[$param['param_name']] = $param['value'];
					} elseif ( isset( $param['value'] ) ) {
						$shortcode_attributes[$param['param_name']] = $param['value'];
					}
				} else 
					if ( $param['param_name'] == 'content' && $content == NULL ) {
						$content = $param['value'];
					}
			}
			extract( shortcode_atts( $shortcode_attributes, $atts ) );
			
			// Extract tab titles
			
			preg_match_all( 
				'/dh_pricing_table_item title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', 
				$content, 
				$matches, 
				PREG_OFFSET_CAPTURE );
			
			$output = '';
			$tab_titles = array();
			
			if ( isset( $matches[0] ) ) {
				$tab_titles = $matches[0];
			}
			$tmp = '';
			if ( count( $tab_titles ) ) {
				$tmp .= '<ul class="clearfix tabs_controls">';
				foreach ( $tab_titles as $tab ) {
					preg_match( 
						'/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', 
						$tab[0], 
						$tab_matches, 
						PREG_OFFSET_CAPTURE );
					if ( isset( $tab_matches[1][0] ) ) {
						$tmp .= '<li><a href="#tab-' .
							 ( isset( $tab_matches[3][0] ) ? $tab_matches[3][0] : sanitize_title( $tab_matches[1][0] ) ) .
							 '">' . $tab_matches[1][0] . '</a></li>';
					}
				}
				$tmp .= '</ul>' . "\n";
			} else {
				$output .= do_shortcode( $content );
			}
			$elem = $this->getElementHolder( $width );
			
			$iner = '';
			foreach ( $this->settings['params'] as $param ) {
				$custom_markup = '';
				$param_value = isset( $$param['param_name'] ) ? $$param['param_name'] : '';
				if ( is_array( $param_value ) ) {
					// Get first element from the array
					reset( $param_value );
					$first_key = key( $param_value );
					$param_value = $param_value[$first_key];
				}
				$iner .= $this->singleParamHtmlHolder( $param, $param_value );
			}
			if ( isset( $this->settings["custom_markup"] ) && $this->settings["custom_markup"] != '' ) {
				if ( $content != '' ) {
					$custom_markup = str_ireplace( "%content%", $tmp . $content, $this->settings["custom_markup"] );
				} else 
					if ( $content == '' && isset( $this->settings["default_content_in_template"] ) &&
						 $this->settings["default_content_in_template"] != '' ) {
						$custom_markup = str_ireplace( 
							"%content%", 
							$this->settings["default_content_in_template"], 
							$this->settings["custom_markup"] );
					} else {
						$custom_markup = str_ireplace( "%content%", '', $this->settings["custom_markup"] );
					}
				$iner .= do_shortcode( $custom_markup );
			}
			$elem = str_ireplace( '%wpb_element_content%', $iner, $elem );
			$output = $elem;
			
			return $output;
		}

		/**
		 * Find html template for shortcode output.
		 */
		protected function findShortcodeTemplate() {
			// Check template path in shortcode's mapping settings
			if ( ! empty( $this->settings['html_template'] ) && is_file( $this->settings( 'html_template' ) ) ) {
				return $this->setTemplate( $this->settings['html_template'] );
			}
			// Check template in theme directory
			$user_template = vc_manager()->getShortcodesTemplateDir( $this->getFilename() . '.php' );
			if ( is_file( $user_template ) ) {
				return $this->setTemplate( $user_template );
			}
		}

		protected function getFileName() {
			return $this->shortcode;
		}

		public function getTabTemplate() {
			return '<div class="wpb_template">' . do_shortcode( 
				'[dh_pricing_table_item title="' . DHVC_ITEM_TITLE . '" tab_id=""][/dh_pricing_table_item]' ) . '</div>';
		}
	}

	class WPBakeryShortCode_DH_Pricing_Table_Item extends DHWPBakeryShortcode {
	}
	// Shortcode Container
	class WPBakeryShortCode_DH_Box extends DHWPBakeryShortcodeContainer {
	}

	class WPBakeryShortCode_DH_Animation extends DHWPBakeryShortcodeContainer {
	}

	class WPBakeryShortCode_DH_Product_Slider extends DHWPBakeryShortcodeContainer {
	}
	// Shortcode
	class WPBakeryShortCode_DH_Clearfix extends DHWPBakeryShortcode {
	}
	
	class WPBakeryShortCode_DH_Menu_Anchor extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Post extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Latestnews extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Post_Thumbnail extends DHWPBakeryShortcode {
	}
	
	class WPBakeryShortCode_DH_Mailchimp extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Portfolio extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Quote extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Client extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Counter extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Iconbox extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Lists extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Modal extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Tooltip extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Member extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Cart extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Layered_Nav_Filters extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Layered_Nav extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Price_Filter extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Product_Categories extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Product_Search extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Product_Tag_Cloud extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Products extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Recent_Reviews extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Recently_Viewed_Products extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Top_Rated_Products extends DHWPBakeryShortcode {
	}

	class DHVisualComposer {

		public $param_holder = 'div';

		public static $button_styles = array('Default'=>'','Square' => 'square', 'Round' => 'round', 'Outlined' => 'outlined', '3D' => '3d' );

		public static $cta_styles = array('Default'=>'','Square' => 'square', 'Round' => 'round', 'Outlined' => 'outlined' );
		
		public function __construct() {
			if ( function_exists( 'vc_set_as_theme' ) ) :
				vc_set_as_theme( true );
			
			
			
			
			
			endif;
			
			if ( function_exists( 'vc_disable_frontend' ) ) :
				vc_disable_frontend();
			 else :
				if ( class_exists( 'NewVisualComposer' ) )
					NewVisualComposer::disableInline();
			endif;
			add_action( 'init', array( &$this, 'map' ), 20 );
			add_action( 'init', array( &$this, 'add_params' ), 50 );
			if ( is_admin() ) {
				add_action( 'admin_init', array( &$this, 'map_update' ), 10 );
				add_action( 'admin_init', array( &$this, 'remove_default_shortcodes' ), 5 );
				add_action( 'admin_init', array( &$this, 'remove_params' ), 30 );
				add_action( 'admin_init', array( &$this, 'update_params' ), 40 );
				add_action( 'do_meta_boxes', array( &$this, 'remove_vc_teaser_meta_box' ), 1 );
				
				add_action( 'admin_print_scripts-post.php', array( &$this, 'enqueue_scripts' ) );
				add_action( 'admin_print_scripts-post-new.php', array( &$this, 'enqueue_scripts' ) );
				
				$vc_params_js = DHINC_ASSETS_URL . '/js/vc-params.js';
				vc_add_shortcode_param( 'nullfield', array( &$this, 'nullfield_param' ), $vc_params_js );
				vc_add_shortcode_param( 
					'product_attribute_filter', 
					array( &$this, 'product_attribute_filter_param' ), 
					$vc_params_js );
				vc_add_shortcode_param( 'product_attribute', array( &$this, 'product_attribute_param' ), $vc_params_js );
				vc_add_shortcode_param( 'products_ajax', array( &$this, 'products_ajax_param' ), $vc_params_js );
				vc_add_shortcode_param( 'product_brand', array( &$this, 'product_brand_param' ), $vc_params_js );
				vc_add_shortcode_param( 'product_category', array( &$this, 'product_category_param' ), $vc_params_js );
				vc_add_shortcode_param( 'ui_datepicker', array( &$this, 'ui_datepicker_param' ) );
				vc_add_shortcode_param( 'portfolio_category', array( &$this, 'portfolio_category_param' ), $vc_params_js );
				vc_add_shortcode_param( 
					'pricing_table_feature', 
					array( &$this, 'pricing_table_feature_param' ), 
					$vc_params_js );
				vc_add_shortcode_param( 'post_category', array( &$this, 'post_category_param' ), $vc_params_js );
				vc_add_shortcode_param( 'ui_slider', array( &$this, 'ui_slder_param' ) );
				vc_add_shortcode_param( 'dropdown_group', array( &$this, 'dropdown_group_param' ) );
			}
		}

		public function map_update() {
			vc_map_update( 
				'vc_row', 
				array( 'class' => 'dh-vc-element dh-vc-element-row', 'icon' => 'dh-vc-icon-row', 'weight' => 1000 ) );
			vc_map_update( 
				'vc_row_inner', 
				array( 'class' => 'dh-vc-element dh-vc-element-row', 'icon' => 'dh-vc-icon-row', 'weight' => 1000 ) );
			
			vc_map_update( 
				'vc_column_text', 
				array( 
					'class' => 'dh-vc-element dh-vc-element-text-block', 
					'icon' => 'dh-vc-icon-text-block', 
					'weight' => 900 ) );
			vc_map_update( 
				'vc_custom_heading', 
				array( 
					'class' => 'dh-vc-element dh-vc-element-vc_custom_heading', 
					'icon' => 'dh-vc-icon-vc_custom_heading', 
					'weight' => 901 ) );
			vc_map_update( 
				'vc_separator', 
				array( 
					'name' => __( 'Divider Separator', DH_DOMAIN ), 
					'class' => 'dh-vc-element dh-vc-element-separator', 
					'icon' => 'dh-vc-icon-separator', 
					'weight' => 890 ) );
			vc_map_update( 
				'vc_empty_space', 
				array( 
					'class' => 'dh-vc-element dh-vc-element-empty-space', 
					'icon' => 'dh-vc-icon-empty-space', 
					'weight' => 880 ) );
			vc_map_update( 
				'vc_progress_bar', 
				array( 
					'class' => 'dh-vc-element dh-vc-element-vc_progress_bar', 
					'icon' => 'dh-vc-icon-vc_progress_bar', 
					'weight' => 840 ) );
			vc_map_update( 
				'vc_message', 
				array( 
					'class' => 'dh-vc-element dh-vc-element-vc_message', 
					'icon' => 'dh-vc-icon-vc_message', 
					'weight' => 830 ) );
			vc_map_update( 
				'vc_accordion', 
				array( 
					'class' => 'dh-vc-element dh-vc-element-vc_accordion', 
					'icon' => 'dh-vc-icon-vc_accordion', 
					'weight' => 820 ) );
			vc_map_update( 
				'vc_button', 
				array( 
					'class' => 'dh-vc-element dh-vc-element-vc_button', 
					'icon' => 'dh-vc-icon-vc_button', 
					'weight' => 810 ) );
			vc_map_update( 
				'vc_cta_button', 
				array( 
					'name' => __( 'Call to Action', DH_DOMAIN ), 
					'class' => 'dh-vc-element dh-vc-element-vc_cta_button', 
					'icon' => 'dh-vc-icon-vc_cta_button', 
					'weight' => 800 ) );
			vc_map_update( 
				'vc_gmaps', 
				array( 
					'class' => 'dh-vc-element dh-vc-element-vc_gmaps', 
					'icon' => 'dh-vc-icon-vc_gmaps', 
					'weight' => 760 ) );
			vc_map_update( 
				'vc_pie', 
				array( 'class' => 'dh-vc-element dh-vc-element-vc_pie', 'icon' => 'dh-vc-icon-vc_pie', 'weight' => 730 ) );
			vc_map_update( 
				'vc_video', 
				array( 
					'class' => 'dh-vc-element dh-vc-element-vc_video', 
					'icon' => 'dh-vc-icon-vc_video', 
					'weight' => 710 ) );
			vc_map_update( 
				'vc_tabs', 
				array( 
					'class' => 'dh-vc-element dh-vc-element-vc_tabs', 
					'icon' => 'dh-vc-icon-vc_tabs', 
					'weight' => 700 ) );
			vc_map_update( 
				'vc_single_image', 
				array( 
					'class' => 'dh-vc-element dh-vc-element-vc_single_image', 
					'icon' => 'dh-vc-icon-vc_single_image', 
					'weight' => 500 ) );
			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once ( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
				vc_map_update( 
					'contact-form-7', 
					array( 
						'class' => 'dh-vc-element dh-vc-element-contact-form-7', 
						'icon' => 'dh-vc-icon-contact-form-7', 
						'weight' => 480 ) );
			}
			if ( is_plugin_active( 'revslider/revslider.php' ) ) {
				vc_map_update( 
					'rev_slider_vc', 
					array( 
						'class' => 'dh-vc-element dh-vc-element-rev_slider_vc', 
						'icon' => 'dh-vc-icon-rev_slider_vc', 
						'weight' => 470 ) );
			}
			vc_map_update( 
				'vc_widget_sidebar', 
				array( 
					'class' => 'dh-vc-element dh-vc-element-vc_widget_sidebar', 
					'icon' => 'dh-vc-icon-vc_widget_sidebar', 
					'weight' => 460 ) );
			vc_map_update( 
				'vc_raw_html', 
				array( 
					'class' => 'dh-vc-element dh-vc-element-raw_html', 
					'icon' => 'dh-vc-icon-raw_html', 
					'weight' => 450 ) );
			vc_map_update( 
				'vc_raw_js', 
				array( 'class' => 'dh-vc-element dh-vc-element-raw_js', 'icon' => 'dh-vc-icon-raw_js', 'weight' => 430 ) );
			return;
		}

		public function map() {
			$is_wp_version_3_6_more = version_compare( 
				preg_replace( '/^([\d\.]+)(\-.*$)/', '$1', get_bloginfo( 'version' ) ), 
				'3.6' ) >= 0;
			vc_map( 
				array( 
					'base' => 'dh_box', 
					'name' => __( 'Box', DH_DOMAIN ), 
					'description' => __( 'Place content elements inside the box.', DH_DOMAIN ), 
					'as_child' => array( 'only' => 'vc_column_inner' ), 
					'as_parent' => array( 'except' => 'vc_row,vc_row_inner,vc_column,vc_column_inner,dh_box' ), 
					'content_element' => true, 
					'weight' => 999, 
					'class' => 'dh-vc-element dh-vc-element-dh_box', 
					'icon' => 'dh-vc-icon-dh_box', 
					'js_view' => 'VcColumnView', 
					'show_settings_on_create' => false, 
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_animation', 
					'name' => __( 'Animation Box', DH_DOMAIN ), 
					'description' => __( 'Enable animation for serveral elements.', DH_DOMAIN ), 
					'as_parent' => array( 
						'only' => 'vc_column_text,dh_lists,vc_empty_space,vc_separator,dh_iconbox,dh_member,vc_custom_heading,vc_cta_button,vc_button,vc_pie,vc_message,vc_single_image,vc_gmaps,vc_raw_html,vc_icon' ), 
					'content_element' => true, 
					'weight' => 950, 
					'class' => 'dh-vc-element dh-vc-element-animation', 
					'icon' => 'dh-vc-icon-animation', 
					'js_view' => 'VcColumnView', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_clearfix', 
					'name' => __( 'Clearfix', DH_DOMAIN ), 
					'description' => __( 'Clear help you fix the normal break style.', DH_DOMAIN ), 
					'weight' => 870, 
					'class' => 'dh-vc-element dh-vc-element-clearfix', 
					'icon' => 'dh-vc-icon-clearfix', 
					'show_settings_on_create' => false, 
					'params' => array() ) );
			vc_map(
				array(
					'base' => 'dh_menu_anchor',
					'name' => __( 'Menu Anchor', DH_DOMAIN ),
					'description' => __( 'Add a menu anchor points.', DH_DOMAIN ),
					'weight' => 869,
					'class' => 'dh-vc-element dh-vc-element-menu_anchor',
					'icon' => 'dh-vc-icon-menu_anchor',
					'show_settings_on_create' => true,
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_post', 
					'name' => __( 'Post', DH_DOMAIN ), 
					'description' => __( 'Display post.', DH_DOMAIN ), 
					'weight' => 860, 
					'class' => 'dh-vc-element dh-vc-element-dh_post', 
					'icon' => 'dh-vc-icon-dh_post', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_latestnews', 
					'name' => __( 'Latest News', DH_DOMAIN ), 
					'description' => __( 'Display latest news.', DH_DOMAIN ), 
					'weight' => 859, 
					'class' => 'dh-vc-element dh-vc-element-dh_latestnews', 
					'icon' => 'dh-vc-icon-dh_latestnews', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_post_thumbnail', 
					'name' => __( 'Post Thumbnail', DH_DOMAIN ), 
					'description' => __( 'Widget post with thumbnail.', DH_DOMAIN ), 
					'weight' => 189, 
					'class' => 'dh-vc-element dh-vc-element-dh_post_thumbnail', 
					'icon' => 'dh-vc-icon-dh_post_thumbnail', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			vc_map(
				array(
					'base' => 'dh_mailchimp',
					'name' => __( 'Mailchimp Subscribe', DH_DOMAIN ),
					'description' => __( 'Widget Mailchimp Subscribe.', DH_DOMAIN ),
					'weight' => 189,
					'class' => 'dh-vc-element dh-vc-element-dh_mailchimp',
					'icon' => 'dh-vc-icon-dh_mailchimp',
					'show_settings_on_create' => true,
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_portfolio', 
					'name' => __( 'Portfolio', DH_DOMAIN ), 
					'description' => __( 'Display list portfolio.', DH_DOMAIN ), 
					'weight' => 850, 
					'class' => 'dh-vc-element dh-vc-element-portfolio', 
					'icon' => 'dh-vc-icon-portfolio', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_quote', 
					'name' => __( 'Blockquote', DH_DOMAIN ), 
					'description' => __( 'A Blockquote.', DH_DOMAIN ), 
					'weight' => 850, 
					'class' => 'dh-vc-element dh-vc-element-dh_quote', 
					'icon' => 'dh-vc-icon-dh_quote', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			
			$sliders = get_terms( 'dh_slider' );
			$slider_option = array();
			$slider_option[__( 'None', 'sitesao' )] = '';
			foreach ( (array) $sliders as $slider ) {
				$slider_option[$slider->name] = $slider->slug;
			}
			vc_map( 
				array( 
					'base' => 'dh_slider', 
					'name' => __( 'DH Slider', DH_DOMAIN ), 
					'description' => __( 'Display DH Slider.', DH_DOMAIN ), 
					'weight' => 850, 
					'class' => 'dh-vc-element dh-vc-element-dh_slider', 
					'icon' => 'dh-vc-icon-dh_slider', 
					'show_settings_on_create' => true, 
					'params' => array( 
						array( 
							'type' => 'dropdown', 
							'admin_label' => true, 
							'heading' => __( 'Slider', DH_DOMAIN ), 
							'param_name' => 'slider_slug', 
							'value' => $slider_option, 
							'description' => __( 'Select a slider.', DH_DOMAIN ) ) ) ) );
			
			vc_map( 
				array( 
					'base' => 'dh_carousel', 
					'name' => __( 'Carousel Content', DH_DOMAIN ), 
					'description' => __( 'Animated carousel with carousel', DH_DOMAIN ), 
					'weight' => 790, 
					'class' => 'dh-vc-element dh-vc-element-dh_carousel', 
					'icon' => 'dh-vc-icon-dh_carousel', 
					'show_settings_on_create' => true, 
					'is_container' => true, 
					'js_view' => 'DHVCCarousel', 
					'params' => array(), 
					"custom_markup" => '
						  <div class="wpb_tabs_holder wpb_holder clearfix vc_container_for_children">
						  <ul class="tabs_controls">
						  </ul>
						  %content%
						  </div>', 
					'default_content' => '
					  [dh_carousel_item title="' . __( 'Item 1', DH_DOMAIN ) . '" tab_id="' . time() . '-1-' . rand( 0, 100 ) . '"][/dh_carousel_item]
					  [dh_carousel_item title="' . __( 'Item 2', DH_DOMAIN ) . '" tab_id="' . time() . '-2-' . rand( 0, 100 ) . '"][/dh_carousel_item]
					  [dh_carousel_item title="' . __( 'Item 3', DH_DOMAIN ) . '" tab_id="' . time() . '-3-' . rand( 0, 100 ) . '"][/dh_carousel_item]
					  ' ) );
			vc_map( 
				array( 
					'name' => __( 'Carousel Item', DH_DOMAIN ), 
					'base' => 'dh_carousel_item', 
					'allowed_container_element' => 'vc_row', 
					'is_container' => true, 
					'content_element' => false, 
					'params' => array(), 
					'js_view' => 'DHVCCarouselItem' ) );
			vc_map( 
				array( 
					'base' => 'dh_testimonial', 
					'name' => __( 'Testimonial', DH_DOMAIN ), 
					'description' => __( 'Animated Testimonial with slider', DH_DOMAIN ), 
					'weight' => 690, 
					'class' => 'dh-vc-element dh-vc-element-dh_testimonial', 
					'icon' => 'dh-vc-icon-dh_testimonial', 
					'show_settings_on_create' => true, 
					'is_container' => true, 
					'js_view' => 'DHVCTestimonial', 
					'params' => array(), 
					"custom_markup" => '
						  <div class="wpb_tabs_holder wpb_holder clearfix vc_container_for_children">
						  <ul class="tabs_controls">
						  </ul>
						  %content%
						  </div>', 
					'default_content' => '
					  [dh_testimonial_item title="' . __( 'Item 1', DH_DOMAIN ) . '" tab_id="' . time() . '-1-' . rand( 0, 100 ) . '"][/dh_testimonial_item]
					  [dh_testimonial_item title="' . __( 'Item 2', DH_DOMAIN ) . '" tab_id="' . time() . '-2-' . rand( 0, 100 ) . '"][/dh_testimonial_item]
					  [dh_testimonial_item title="' . __( 'Item 3', DH_DOMAIN ) . '" tab_id="' . time() . '-3-' . rand( 0, 100 ) . '"][/dh_testimonial_item]
					  ' ) );
			vc_map( 
				array( 
					'name' => __( 'Testimonial Item', DH_DOMAIN ), 
					'base' => 'dh_testimonial_item', 
					'allowed_container_element' => 'vc_row', 
					'is_container' => true, 
					'content_element' => false, 
					'params' => array(), 
					'js_view' => 'DHVCTestimonialItem' ) );
			vc_map( 
				array( 
					'base' => 'dh_member', 
					'name' => __( 'Team Member', DH_DOMAIN ), 
					'description' => __( 'Display team member.', DH_DOMAIN ), 
					'weight' => 781, 
					'class' => 'dh-vc-element dh-vc-element-dh_member', 
					'icon' => 'dh-vc-icon-dh_member', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_client', 
					'name' => __( 'Client', DH_DOMAIN ), 
					'description' => __( 'Display list clients.', DH_DOMAIN ), 
					'weight' => 780, 
					'class' => 'dh-vc-element dh-vc-element-dh_client', 
					'icon' => 'dh-vc-icon-dh_client', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_counter', 
					'name' => __( 'Counter', DH_DOMAIN ), 
					'description' => __( 'Display Counter.', DH_DOMAIN ), 
					'weight' => 770, 
					'class' => 'dh-vc-element dh-vc-element-dh_counter', 
					'icon' => 'dh-vc-icon-dh_counter', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_iconbox', 
					'name' => __( 'Icon Box', DH_DOMAIN ), 
					'description' => __( 'Display icon box.', DH_DOMAIN ), 
					'weight' => 750, 
					'class' => 'dh-vc-element dh-vc-element-dh_iconbox', 
					'icon' => 'dh-vc-icon-dh_iconbox', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_lists', 
					'name' => __( 'Lists', DH_DOMAIN ), 
					'description' => __( 'Display lists content.', DH_DOMAIN ), 
					'weight' => 751, 
					'class' => 'dh-vc-element dh-vc-element-dh_lists', 
					'icon' => 'dh-vc-icon-dh_lists', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_modal', 
					'name' => __( 'Modal', DH_DOMAIN ), 
					'description' => __( 'Display content width modal.', DH_DOMAIN ), 
					'weight' => 740, 
					'class' => 'dh-vc-element dh-vc-element-dh_modal', 
					'icon' => 'dh-vc-icon-dh_modal', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_pricing_table', 
					'name' => __( 'Pricing Table', DH_DOMAIN ), 
					'description' => __( 'Create pricing table', DH_DOMAIN ), 
					'weight' => 720, 
					'class' => 'dh-vc-element dh-vc-element-dh_pricing_table', 
					'icon' => 'dh-vc-icon-dh_pricing_table', 
					'show_settings_on_create' => false, 
					'is_container' => true, 
					'js_view' => 'DHVCPricingTable', 
					'params' => array(), 
					"custom_markup" => '
					  <div class="wpb_tabs_holder wpb_holder clearfix vc_container_for_children">
					  <ul class="tabs_controls">
					  </ul>
					  %content%
					  </div>', 
					'default_content' => '
					  [dh_pricing_table_item title="' . __( 'Item 1', DH_DOMAIN ) . '" tab_id="' . time() . '-1-' . rand( 0, 100 ) . '"][/dh_pricing_table_item]
					  [dh_pricing_table_item title="' . __( 'Item 2', DH_DOMAIN ) . '" tab_id="' . time() . '-2-' . rand( 0, 100 ) . '"][/dh_pricing_table_item]
					  [dh_pricing_table_item title="' . __( 'Item 3', DH_DOMAIN ) . '" tab_id="' . time() . '-3-' . rand( 0, 100 ) . '"][/dh_pricing_table_item]
					  ' ) );
			vc_map( 
				array( 
					'name' => __( 'Pricing Table Item', DH_DOMAIN ), 
					'base' => 'dh_pricing_table_item', 
					'allowed_container_element' => 'vc_row', 
					'is_container' => true, 
					'content_element' => false, 
					'params' => array(), 
					'js_view' => 'DHVCPricingTableItem' ) );
			
			vc_map( 
				array( 
					'base' => 'dh_timeline', 
					'name' => __( 'Timeline', DH_DOMAIN ), 
					'description' => __( 'Display timeline content', DH_DOMAIN ), 
					'weight' => 680, 
					'class' => 'dh-vc-element dh-vc-element-dh_timeline', 
					'icon' => 'dh-vc-icon-dh_timeline', 
					'show_settings_on_create' => true, 
					'is_container' => true, 
					'js_view' => 'DHVCTimeline', 
					'params' => array(), 
					"custom_markup" => '
						  <div class="wpb_tabs_holder wpb_holder clearfix vc_container_for_children">
						  <ul class="tabs_controls">
						  </ul>
						  %content%
						  </div>', 
					'default_content' => '
					  [dh_timeline_item title="' . __( 'Item 1', DH_DOMAIN ) . '" tab_id="' . time() . '-1-' . rand( 0, 100 ) . '"][/dh_timeline_item]
					  [dh_timeline_item title="' . __( 'Item 2', DH_DOMAIN ) . '" tab_id="' . time() . '-2-' . rand( 0, 100 ) . '"][/dh_timeline_item]
					  [dh_timeline_item title="' . __( 'Item 3', DH_DOMAIN ) . '" tab_id="' . time() . '-3-' . rand( 0, 100 ) . '"][/dh_timeline_item]
					  ' ) );
			vc_map( 
				array( 
					'name' => __( 'Timeline Item', DH_DOMAIN ), 
					'base' => 'dh_timeline_item', 
					'allowed_container_element' => 'vc_row', 
					'is_container' => true, 
					'content_element' => false, 
					'params' => array(), 
					'js_view' => 'DHVCTimelineItem' ) );
			$this->_woocommerce_map();
		}

		protected function _woocommerce_map() {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) )
				return;
			
			vc_map( 
				array( 
					'base' => 'dh_product_slider', 
					'name' => __( 'Product Slider', DH_DOMAIN ), 
					'description' => __( 'Animated products with carousel.', DH_DOMAIN ), 
					'as_parent' => array( 
						'only' => 'product_category,product_categories,dhwc_product_brands,products,related_products,product_attribute,featured_products,top_rated_products,best_selling_products,sale_products,recent_products' ), 
					'content_element' => true, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					'weight' => 400, 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'js_view' => 'VcColumnView', 
					'show_settings_on_create' => true, 
					'params' => array( 
						array( 
							'type' => 'textfield', 
							'heading' => __( 'Carousel Title', DH_DOMAIN ), 
							'param_name' => 'title', 
							'description' => __( 
								'Enter text which will be used as widget title. Leave blank if no title is needed.', 
								DH_DOMAIN ) ), 
						array( 
							'type' => 'dropdown', 
							'heading' => __( 'Title color', DH_DOMAIN ), 
							'param_name' => 'title_color', 
							'dependency' => array( 'element' => "title", 'not_empty' => true ), 
							'value' => array( 
								__( 'Default', DH_DOMAIN ) => 'default', 
								__( 'Primary', DH_DOMAIN ) => 'primary', 
								__( 'Success', DH_DOMAIN ) => 'success', 
								__( 'Info', DH_DOMAIN ) => 'info', 
								__( 'Warning', DH_DOMAIN ) => 'warning', 
								__( 'Danger', DH_DOMAIN ) => 'danger' ) ), 
						array( 
							'type' => 'dropdown', 
							'heading' => __( 'Transition', DH_DOMAIN ), 
							'param_name' => 'fx', 
							'std' => 'scroll', 
							'value' => array( 
								'Scroll' => 'scroll', 
								'Directscroll' => 'directscroll', 
								'Fade' => 'fade', 
								'Cross fade' => 'crossfade', 
								'Cover' => 'cover', 
								'Cover fade' => 'cover-fade', 
								'Uncover' => 'cover-fade', 
								'Uncover fade' => 'uncover-fade' ), 
							'description' => __( 'Indicates which effect to use for the transition.', DH_DOMAIN ) ), 
						
						// array(
						// 'param_name' => 'scroll_item',
						// 'heading' => __( 'The number of items to scroll', DH_DOMAIN ),
						// 'type' => 'ui_slider',
						// 'holder' => $this->param_holder,
						// 'value' => '1',
						// 'data_min' => '1',
						// 'data_max' => '6',
						// ),
						array( 
							'param_name' => 'scroll_speed', 
							'heading' => __( 'Transition Scroll Speed (ms)', DH_DOMAIN ), 
							'type' => 'ui_slider', 
							'holder' => $this->param_holder, 
							'value' => '700', 
							'data_min' => '100', 
							'data_step' => '100', 
							'data_max' => '3000' ), 
						
						array( 
							"type" => "dropdown", 
							"holder" => $this->param_holder, 
							"heading" => __( "Easing", DH_DOMAIN ), 
							"param_name" => "easing", 
							"value" => array( 
								'linear' => 'linear', 
								'swing' => 'swing', 
								'easeInQuad' => 'easeInQuad', 
								'easeOutQuad' => 'easeOutQuad', 
								'easeInOutQuad' => 'easeInOutQuad', 
								'easeInCubic' => 'easeInCubic', 
								'easeOutCubic' => 'easeOutCubic', 
								'easeInOutCubic' => 'easeInOutCubic', 
								'easeInQuart' => 'easeInQuart', 
								'easeOutQuart' => 'easeOutQuart', 
								'easeInOutQuart' => 'easeInOutQuart', 
								'easeInQuint' => 'easeInQuint', 
								'easeOutQuint' => 'easeOutQuint', 
								'easeInOutQuint' => 'easeInOutQuint', 
								'easeInExpo' => 'easeInExpo', 
								'easeOutExpo' => 'easeOutExpo', 
								'easeInOutExpo' => 'easeInOutExpo', 
								'easeInSine' => 'easeInSine', 
								'easeOutSine' => 'easeOutSine', 
								'easeInOutSine' => 'easeInOutSine', 
								'easeInCirc' => 'easeInCirc', 
								'easeOutCirc' => 'easeOutCirc', 
								'easeInOutCirc' => 'easeInOutCirc', 
								'easeInElastic' => 'easeInElastic', 
								'easeOutElastic' => 'easeOutElastic', 
								'easeInOutElastic' => 'easeInOutElastic', 
								'easeInBack' => 'easeInBack', 
								'easeOutBack' => 'easeOutBack', 
								'easeInOutBack' => 'easeInOutBack', 
								'easeInBounce' => 'easeInBounce', 
								'easeOutBounce' => 'easeOutBounce', 
								'easeInOutBounce' => 'easeInOutBounce' ), 
							"description" => __( 
								"Select the animation easing you would like for slide transitions <a href=\"http://jqueryui.com/resources/demos/effect/easing.html\" target=\"_blank\"> Click here </a> to see examples of these.", 
								DH_DOMAIN ) ), 
						array( 
							'type' => 'checkbox', 
							'heading' => __( 'Autoplay ?', DH_DOMAIN ), 
							'param_name' => 'auto_play', 
							'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ) ), 
						array( 
							'type' => 'checkbox', 
							'heading' => __( 'Hide Slide Pagination ?', DH_DOMAIN ), 
							'param_name' => 'hide_pagination', 
							'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ) ), 
						array( 
							'type' => 'checkbox', 
							'heading' => __( 'Hide Previous/Next Control ?', DH_DOMAIN ), 
							'param_name' => 'hide_control', 
							'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ) ), 
						array( 
							'param_name' => 'visibility', 
							'heading' => __( 'Visibility', DH_DOMAIN ), 
							'type' => 'dropdown', 
							'holder' => $this->param_holder, 
							'value' => array( 
								__( 'All Devices', DH_DOMAIN ) => "all", 
								__( 'Hidden Phone', DH_DOMAIN ) => "hidden-phone", 
								__( 'Hidden Tablet', DH_DOMAIN ) => "hidden-tablet", 
								__( 'Hidden PC', DH_DOMAIN ) => "hidden-pc", 
								__( 'Visible Phone', DH_DOMAIN ) => "visible-phone", 
								__( 'Visible Tablet', DH_DOMAIN ) => "visible-tablet", 
								__( 'Visible PC', DH_DOMAIN ) => "visible-pc" ) ), 
						array( 
							'param_name' => 'el_class', 
							'heading' => __( '(Optional) Extra class name', DH_DOMAIN ), 
							'type' => 'textfield', 
							'holder' => $this->param_holder, 
							"description" => __( 
								"If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 
								DH_DOMAIN ) ) ) ) );
			vc_map( 
				array( 
					"name" => __( "Product", DH_DOMAIN ), 
					"base" => "product", 
					'weight' => 390, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'Display a single product.', DH_DOMAIN ), 
					"params" => array( 
						array( 
							"type" => "products_ajax", 
							"heading" => __( "Select product", DH_DOMAIN ), 
							'single_select' => true, 
							'admin_label' => true, 
							"param_name" => "id" ) ) ) );
			vc_map( 
				array( 
					"name" => __( "Product Page", DH_DOMAIN ), 
					"base" => "product_page", 
					'weight' => 380, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'Show a single product page.', DH_DOMAIN ), 
					"params" => array( 
						array( 
							"type" => "products_ajax", 
							"heading" => __( "Select product", DH_DOMAIN ), 
							'single_select' => true, 
							'admin_label' => true, 
							"param_name" => "id" ) ) ) );
			vc_map( 
				array( 
					"name" => __( "Product Category", DH_DOMAIN ), 
					"base" => "product_category", 
					'weight' => 370, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'List products in a category shortcode.', DH_DOMAIN ), 
					"params" => array( 
						array( 
							"type" => "product_category", 
							"heading" => __( "Categories", DH_DOMAIN ), 
							"param_name" => "category", 
							"admin_label" => true ), 
						array( 
							"type" => "textfield", 
							"heading" => __( "Product Per Page", DH_DOMAIN ), 
							"param_name" => "per_page", 
							"admin_label" => true, 
							"value" => 12 ), 
						array( 
							"type" => "dropdown", 
							"heading" => __( "Columns", DH_DOMAIN ), 
							"param_name" => "columns", 
							"std" => 4, 
							"admin_label" => true, 
							"value" => array( 2, 3, 4, 5, 6 ) ), 
						array( 
							"type" => "dropdown", 
							"heading" => __( "Products Ordering", DH_DOMAIN ), 
							"param_name" => "orderby", 
							'class' => 'dhwc-woo-product-page-dropdown', 
							"value" => array( 
								__( 'Publish Date', DH_DOMAIN ) => 'date', 
								__( 'Modified Date', DH_DOMAIN ) => 'modified', 
								__( 'Random', DH_DOMAIN ) => 'rand', 
								__( 'Alphabetic', DH_DOMAIN ) => 'title', 
								__( 'Popularity', DH_DOMAIN ) => 'popularity', 
								__( 'Rate', DH_DOMAIN ) => 'rating', 
								__( 'Price', DH_DOMAIN ) => 'price' ) ), 
						array( 
							"type" => "dropdown", 
							"class" => "", 
							"heading" => __( "Ascending or Descending", DH_DOMAIN ), 
							"param_name" => "order", 
							"value" => array( 
								__( 'Ascending', DH_DOMAIN ) => 'ASC', 
								__( 'Descending', DH_DOMAIN ) => 'DESC' ) ), 
						
						array( 
							"type" => "dropdown", 
							"class" => "", 
							"heading" => __( "Query type", DH_DOMAIN ), 
							"param_name" => "operator", 
							"value" => array( 
								__( 'IN', DH_DOMAIN ) => 'IN', 
								__( 'AND', DH_DOMAIN ) => 'AND', 
								__( 'NOT IN', DH_DOMAIN ) => 'NOT IN' ) ) ) ) );
			vc_map( 
				array( 
					"name" => __( "Product Categories", DH_DOMAIN ), 
					"base" => "product_categories", 
					'weight' => 360, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'List all (or limited) product categories.', DH_DOMAIN ), 
					"params" => array( 
						array( 
							"type" => "product_category", 
							"heading" => __( "Categories", DH_DOMAIN ), 
							"param_name" => "ids", 
							'select_field'=>'id',
							"admin_label" => true ), 
						array( 
							"type" => "textfield", 
							"heading" => __( "Number", DH_DOMAIN ), 
							"param_name" => "number", 
							"admin_label" => true, 
							'description' => __( 
								'You can specify the number of category to show (Leave blank to display all categories).', 
								DH_DOMAIN ) ), 
						array( 
							"type" => "dropdown", 
							"heading" => __( "Columns", DH_DOMAIN ), 
							"param_name" => "columns", 
							"std" => 4, 
							"admin_label" => true, 
							"value" => array( 2, 3, 4, 5, 6 ) ), 
						array( 
							"type" => "dropdown", 
							"heading" => __( "Products Ordering", DH_DOMAIN ), 
							"param_name" => "orderby", 
							"value" => array( 
								__( 'Publish Date', DH_DOMAIN ) => 'date', 
								__( 'Modified Date', DH_DOMAIN ) => 'modified', 
								__( 'Random', DH_DOMAIN ) => 'rand', 
								__( 'Alphabetic', DH_DOMAIN ) => 'title', 
								__( 'Popularity', DH_DOMAIN ) => 'popularity', 
								__( 'Rate', DH_DOMAIN ) => 'rating', 
								__( 'Price', DH_DOMAIN ) => 'price' ) ), 
						array( 
							"type" => "dropdown", 
							"class" => "", 
							"heading" => __( "Ascending or Descending", DH_DOMAIN ), 
							"param_name" => "order", 
							"value" => array( 
								__( 'Ascending', DH_DOMAIN ) => 'ASC', 
								__( 'Descending', DH_DOMAIN ) => 'DESC' ) ), 
						array( 
							"type" => "dropdown", 
							"class" => "", 
							"heading" => __( "Hide Empty", DH_DOMAIN ), 
							"param_name" => "hide_empty", 
							"value" => array( __( 'Yes', DH_DOMAIN ) => '1', __( 'No', DH_DOMAIN ) => '0' ) ) ) ) );
			if(taxonomy_exists('product_brand')){
				vc_map( 
					array( 
						"name" => __( "Product Brands", DH_DOMAIN ), 
						"base" => "dhwc_product_brands", 
						'weight' => 360, 
						"category" => __( "WooCommerce", DH_DOMAIN ), 
						"icon" => "dh-vc-icon-dh_woo", 
						"class" => "dh-vc-element dh-vc-element-dh_woo", 
						'description' => __( 'List all (or limited) product brands.', DH_DOMAIN ), 
						"params" => array( 
							array( 
								"type" => "product_brand", 
								"heading" => __( "Brands", DH_DOMAIN ), 
								"param_name" => "ids", 
								"admin_label" => true ), 
							array( 
								"type" => "textfield", 
								"heading" => __( "Number", DH_DOMAIN ), 
								"param_name" => "number", 
								"admin_label" => true, 
								'description' => __( 
									'You can specify the number of brand to show (Leave blank to display all brands).', 
									DH_DOMAIN ) ), 
							array( 
								"type" => "dropdown", 
								"heading" => __( "Columns", DH_DOMAIN ), 
								"param_name" => "columns", 
								"std" => 4, 
								"admin_label" => true, 
								"value" => array( 2, 3, 4, 5, 6 ) ), 
							array( 
								"type" => "dropdown", 
								"heading" => __( "Products Ordering", DH_DOMAIN ), 
								"param_name" => "orderby", 
								"value" => array( 
									__( 'Publish Date', DH_DOMAIN ) => 'date', 
									__( 'Modified Date', DH_DOMAIN ) => 'modified', 
									__( 'Random', DH_DOMAIN ) => 'rand', 
									__( 'Alphabetic', DH_DOMAIN ) => 'title', 
									__( 'Popularity', DH_DOMAIN ) => 'popularity', 
									__( 'Rate', DH_DOMAIN ) => 'rating', 
									__( 'Price', DH_DOMAIN ) => 'price' ) ), 
							array( 
								"type" => "dropdown", 
								"class" => "", 
								"heading" => __( "Ascending or Descending", DH_DOMAIN ), 
								"param_name" => "order", 
								"value" => array( 
									__( 'Ascending', DH_DOMAIN ) => 'ASC', 
									__( 'Descending', DH_DOMAIN ) => 'DESC' ) ), 
							array( 
								"type" => "dropdown", 
								"class" => "", 
								"heading" => __( "Hide Empty", DH_DOMAIN ), 
								"param_name" => "hide_empty", 
								"value" => array( __( 'Yes', DH_DOMAIN ) => '1', __( 'No', DH_DOMAIN ) => '0' ) ) ) ) );
			}
			vc_map( 
				array( 
					"name" => __( "Add To Cart", DH_DOMAIN ), 
					"base" => "add_to_cart", 
					'weight' => 350, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'Display a single product price + cart button.', DH_DOMAIN ), 
					"params" => array( 
						array( 
							"type" => "products_ajax", 
							"heading" => __( "Select product", DH_DOMAIN ), 
							'single_select' => true, 
							'admin_label' => true, 
							"param_name" => "id" ) ) ) );
			
			vc_map( 
				array( 
					"name" => __( "Add To Cart URL", DH_DOMAIN ), 
					"base" => "add_to_cart", 
					'weight' => 340, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'Get the add to cart URL for a product.', DH_DOMAIN ), 
					"params" => array( 
						array( 
							"type" => "products_ajax", 
							"heading" => __( "Select product", DH_DOMAIN ), 
							'single_select' => true, 
							'admin_label' => true, 
							"param_name" => "id" ) ) ) );
			
			vc_map( 
				array( 
					"name" => __( "Products", DH_DOMAIN ), 
					"base" => "products", 
					'weight' => 330, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'List multiple products shortcode.', DH_DOMAIN ), 
					"params" => array( 
						array( 
							"type" => "products_ajax", 
							"heading" => __( "Select products", DH_DOMAIN ), 
							"param_name" => "ids", 
							"admin_label" => true ), 
						array( 
							"type" => "dropdown", 
							"heading" => __( "Columns", DH_DOMAIN ), 
							"param_name" => "columns", 
							"std" => 4, 
							"admin_label" => true, 
							"value" => array( 2, 3, 4, 5, 6 ) ), 
						array( 
							"type" => "dropdown", 
							"heading" => __( "Products Ordering", DH_DOMAIN ), 
							"param_name" => "orderby", 
							"value" => array( 
								__( 'Publish Date', DH_DOMAIN ) => 'date', 
								__( 'Modified Date', DH_DOMAIN ) => 'modified', 
								__( 'Random', DH_DOMAIN ) => 'rand', 
								__( 'Alphabetic', DH_DOMAIN ) => 'title', 
								__( 'Popularity', DH_DOMAIN ) => 'popularity', 
								__( 'Rate', DH_DOMAIN ) => 'rating', 
								__( 'Price', DH_DOMAIN ) => 'price' ) ), 
						array( 
							"type" => "dropdown", 
							"class" => "", 
							"heading" => __( "Ascending or Descending", DH_DOMAIN ), 
							"param_name" => "order", 
							"value" => array( 
								__( 'Ascending', DH_DOMAIN ) => 'ASC', 
								__( 'Descending', DH_DOMAIN ) => 'DESC' ) ) ) ) );
			vc_map( 
				array( 
					"name" => __( "Recent Products", DH_DOMAIN ), 
					"base" => "recent_products", 
					'weight' => 320, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'Recent Products shortcode.', DH_DOMAIN ), 
					"params" => array( 
						array( 
							"type" => "textfield", 
							"heading" => __( "Product Per Page", DH_DOMAIN ), 
							"param_name" => "per_page", 
							"admin_label" => true, 
							"value" => 12 ), 
						array( 
							"type" => "dropdown", 
							"heading" => __( "Columns", DH_DOMAIN ), 
							"param_name" => "columns", 
							"std" => 4, 
							"admin_label" => true, 
							"value" => array( 2, 3, 4, 5, 6 ) ), 
						array( 
							"type" => "dropdown", 
							"heading" => __( "Products Ordering", DH_DOMAIN ), 
							"param_name" => "orderby", 
							"value" => array( 
								__( 'Publish Date', DH_DOMAIN ) => 'date', 
								__( 'Modified Date', DH_DOMAIN ) => 'modified', 
								__( 'Random', DH_DOMAIN ) => 'rand', 
								__( 'Alphabetic', DH_DOMAIN ) => 'title', 
								__( 'Popularity', DH_DOMAIN ) => 'popularity', 
								__( 'Rate', DH_DOMAIN ) => 'rating', 
								__( 'Price', DH_DOMAIN ) => 'price' ) ), 
						array( 
							"type" => "dropdown", 
							"class" => "", 
							"heading" => __( "Ascending or Descending", DH_DOMAIN ), 
							"param_name" => "order", 
							"value" => array( 
								__( 'Ascending', DH_DOMAIN ) => 'ASC', 
								__( 'Descending', DH_DOMAIN ) => 'DESC' ) ) ) ) );
			
			vc_map( 
				array( 
					"name" => __( "Sale Products", DH_DOMAIN ), 
					"base" => "sale_products", 
					'weight' => 310, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'List all products on sale.', DH_DOMAIN ), 
					"params" => array( 
						array( 
							"type" => "textfield", 
							"heading" => __( "Product Per Page", DH_DOMAIN ), 
							"param_name" => "per_page", 
							"value" => 12, 
							"admin_label" => true ), 
						array( 
							"type" => "dropdown", 
							"heading" => __( "Columns", DH_DOMAIN ), 
							"param_name" => "columns", 
							"std" => 4, 
							"admin_label" => true, 
							"value" => array( 2, 3, 4, 5, 6 ) ), 
						array( 
							"type" => "dropdown", 
							"heading" => __( "Products Ordering", DH_DOMAIN ), 
							"param_name" => "orderby", 
							"value" => array( 
								__( 'Publish Date', DH_DOMAIN ) => 'date', 
								__( 'Modified Date', DH_DOMAIN ) => 'modified', 
								__( 'Random', DH_DOMAIN ) => 'rand', 
								__( 'Alphabetic', DH_DOMAIN ) => 'title', 
								__( 'Popularity', DH_DOMAIN ) => 'popularity', 
								__( 'Rate', DH_DOMAIN ) => 'rating', 
								__( 'Price', DH_DOMAIN ) => 'price' ) ), 
						array( 
							"type" => "dropdown", 
							"class" => "", 
							"heading" => __( "Ascending or Descending", DH_DOMAIN ), 
							"param_name" => "order", 
							"value" => array( 
								__( 'Ascending', DH_DOMAIN ) => 'ASC', 
								__( 'Descending', DH_DOMAIN ) => 'DESC' ) ) ) ) );
			
			vc_map( 
				array( 
					"name" => __( "Best Selling Products", DH_DOMAIN ), 
					"base" => "best_selling_products", 
					'weight' => 300, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'List best selling products on sale.', DH_DOMAIN ), 
					"params" => array( 
						array( 
							"type" => "textfield", 
							"heading" => __( "Product Per Page", DH_DOMAIN ), 
							"param_name" => "per_page", 
							"value" => 12, 
							"admin_label" => true ), 
						array( 
							"type" => "dropdown", 
							"heading" => __( "Columns", DH_DOMAIN ), 
							"param_name" => "columns", 
							"std" => 4, 
							"admin_label" => true, 
							"value" => array( 2, 3, 4, 5, 6 ) ) ) ) );
			
			vc_map( 
				array( 
					"name" => __( "Top Rated Products", DH_DOMAIN ), 
					"base" => "top_rated_products", 
					'weight' => 290, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'List top rated products on sale.', DH_DOMAIN ), 
					"params" => array( 
						array( 
							"type" => "textfield", 
							"heading" => __( "Product Per Page", DH_DOMAIN ), 
							"param_name" => "per_page", 
							"value" => 12, 
							"admin_label" => true ), 
						array( 
							"type" => "dropdown", 
							"heading" => __( "Columns", DH_DOMAIN ), 
							"param_name" => "columns", 
							"std" => 4, 
							"admin_label" => true, 
							"value" => array( 2, 3, 4, 5, 6 ) ), 
						array( 
							"type" => "dropdown", 
							"heading" => __( "Products Ordering", DH_DOMAIN ), 
							"param_name" => "orderby", 
							"value" => array( 
								__( 'Publish Date', DH_DOMAIN ) => 'date', 
								__( 'Modified Date', DH_DOMAIN ) => 'modified', 
								__( 'Random', DH_DOMAIN ) => 'rand', 
								__( 'Alphabetic', DH_DOMAIN ) => 'title', 
								__( 'Popularity', DH_DOMAIN ) => 'popularity', 
								__( 'Rate', DH_DOMAIN ) => 'rating', 
								__( 'Price', DH_DOMAIN ) => 'price' ) ), 
						array( 
							"type" => "dropdown", 
							"class" => "", 
							"heading" => __( "Ascending or Descending", DH_DOMAIN ), 
							"param_name" => "order", 
							"value" => array( 
								__( 'Ascending', DH_DOMAIN ) => 'ASC', 
								__( 'Descending', DH_DOMAIN ) => 'DESC' ) ) ) ) );
			
			vc_map( 
				array( 
					"name" => __( "Featured Products", DH_DOMAIN ), 
					"base" => "featured_products", 
					'weight' => 280, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'Output featured products.', DH_DOMAIN ), 
					"params" => array( 
						array( 
							"type" => "textfield", 
							"heading" => __( "Product Per Page", DH_DOMAIN ), 
							"param_name" => "per_page", 
							"value" => 12, 
							"admin_label" => true ), 
						array( 
							"type" => "dropdown", 
							"heading" => __( "Columns", DH_DOMAIN ), 
							"param_name" => "columns", 
							"std" => 4, 
							"admin_label" => true, 
							"value" => array( 2, 3, 4, 5, 6 ) ), 
						array( 
							"type" => "dropdown", 
							"heading" => __( "Products Ordering", DH_DOMAIN ), 
							"param_name" => "orderby", 
							"value" => array( 
								__( 'Publish Date', DH_DOMAIN ) => 'date', 
								__( 'Modified Date', DH_DOMAIN ) => 'modified', 
								__( 'Random', DH_DOMAIN ) => 'rand', 
								__( 'Alphabetic', DH_DOMAIN ) => 'title', 
								__( 'Popularity', DH_DOMAIN ) => 'popularity', 
								__( 'Rate', DH_DOMAIN ) => 'rating', 
								__( 'Price', DH_DOMAIN ) => 'price' ) ), 
						array( 
							"type" => "dropdown", 
							"class" => "", 
							"heading" => __( "Ascending or Descending", DH_DOMAIN ), 
							"param_name" => "order", 
							"value" => array( 
								__( 'Ascending', DH_DOMAIN ) => 'ASC', 
								__( 'Descending', DH_DOMAIN ) => 'DESC' ) ) ) ) );
			
			vc_map( 
				array( 
					"name" => __( "Product Attribute", DH_DOMAIN ), 
					"base" => "product_attribute", 
					'weight' => 270, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'List products with an attribute shortcode.', DH_DOMAIN ), 
					"params" => array( 
						array( 
							"type" => "product_attribute", 
							"heading" => __( "Attribute", DH_DOMAIN ), 
							"param_name" => "attribute", 
							"admin_label" => true ), 
						array( 
							"type" => "product_attribute_filter", 
							"heading" => __( "Filter", DH_DOMAIN ), 
							"param_name" => "filter", 
							"admin_label" => true ), 
						array( 
							"type" => "textfield", 
							"heading" => __( "Product Per Page", DH_DOMAIN ), 
							"param_name" => "per_page", 
							"value" => 12, 
							"admin_label" => true ), 
						array( 
							"type" => "dropdown", 
							"heading" => __( "Columns", DH_DOMAIN ), 
							"param_name" => "columns", 
							"std" => 4, 
							"admin_label" => true, 
							"value" => array( 2, 3, 4, 5, 6 ) ), 
						array( 
							"type" => "dropdown", 
							"heading" => __( "Products Ordering", DH_DOMAIN ), 
							"param_name" => "orderby", 
							"value" => array( 
								__( 'Publish Date', DH_DOMAIN ) => 'date', 
								__( 'Modified Date', DH_DOMAIN ) => 'modified', 
								__( 'Random', DH_DOMAIN ) => 'rand', 
								__( 'Alphabetic', DH_DOMAIN ) => 'title', 
								__( 'Popularity', DH_DOMAIN ) => 'popularity', 
								__( 'Rate', DH_DOMAIN ) => 'rating', 
								__( 'Price', DH_DOMAIN ) => 'price' ) ), 
						array( 
							"type" => "dropdown", 
							"class" => "", 
							"heading" => __( "Ascending or Descending", DH_DOMAIN ), 
							"param_name" => "order", 
							"value" => array( 
								__( 'Ascending', DH_DOMAIN ) => 'ASC', 
								__( 'Descending', DH_DOMAIN ) => 'DESC' ) ) ) ) );
			vc_map( 
				array( 
					"name" => __( "Related products", DH_DOMAIN ), 
					"base" => "related_products", 
					'weight' => 260, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'Output the related products.', DH_DOMAIN ), 
					"params" => array( 
						array( 
							"type" => "textfield", 
							"heading" => __( "Product Per Page", DH_DOMAIN ), 
							"param_name" => "posts_per_page", 
							"value" => 12, 
							"admin_label" => true ), 
						array( 
							"type" => "dropdown", 
							"heading" => __( "Columns", DH_DOMAIN ), 
							"param_name" => "columns", 
							"std" => 4, 
							"admin_label" => true, 
							"value" => array( 2, 3, 4, 5, 6 ) ), 
						array( 
							"type" => "dropdown", 
							"heading" => __( "Products Ordering", DH_DOMAIN ), 
							"param_name" => "orderby", 
							"std" => "rand", 
							"value" => array( 
								__( 'Publish Date', DH_DOMAIN ) => 'date', 
								__( 'Modified Date', DH_DOMAIN ) => 'modified', 
								__( 'Random', DH_DOMAIN ) => 'rand', 
								__( 'Alphabetic', DH_DOMAIN ) => 'title', 
								__( 'Popularity', DH_DOMAIN ) => 'popularity', 
								__( 'Rate', DH_DOMAIN ) => 'rating', 
								__( 'Price', DH_DOMAIN ) => 'price' ) ) ) ) );
			vc_map( 
				array( 
					"name" => __( "Shop Messages", DH_DOMAIN ), 
					"base" => "shop_messages", 
					'weight' => 250, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'show_settings_on_create' => false, 
					'description' => __( 'Show messages.', DH_DOMAIN ), 
					"params" => array( array( 'type' => 'nullfield', 'param_name' => 'nullfield' ) ) ) );
			vc_map( 
				array( 
					"name" => __( "Order Tracking", DH_DOMAIN ), 
					"base" => "woocommerce_order_tracking", 
					'weight' => 240, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'show_settings_on_create' => false, 
					'description' => __( 'Order tracking page shortcode.', DH_DOMAIN ), 
					"params" => array( array( 'type' => 'nullfield', 'param_name' => 'nullfield' ) ) ) );
			vc_map( 
				array( 
					"name" => __( "Cart", DH_DOMAIN ), 
					"base" => "woocommerce_cart", 
					'weight' => 230, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'show_settings_on_create' => false, 
					'description' => __( 'Cart page shortcode.', DH_DOMAIN ), 
					"params" => array( array( 'type' => 'nullfield', 'param_name' => 'nullfield' ) ) ) );
			
			vc_map( 
				array( 
					"name" => __( "Checkout", DH_DOMAIN ), 
					"base" => "woocommerce_checkout", 
					'weight' => 220, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'show_settings_on_create' => false, 
					'description' => __( 'Checkout page shortcode.', DH_DOMAIN ), 
					"params" => array( array( 'type' => 'nullfield', 'param_name' => 'nullfield' ) ) ) );
			vc_map( 
				array( 
					"name" => __( "My Account", DH_DOMAIN ), 
					"base" => "woocommerce_my_account", 
					'weight' => 210, 
					"category" => __( "WooCommerce", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'My account shortcode.', DH_DOMAIN ), 
					"params" => array( 
						array( 
							"type" => "textfield", 
							"heading" => __( "Number of orders", DH_DOMAIN ), 
							"param_name" => "order_count", 
							"admin_label" => true, 
							"value" => 12, 
							'description' => __( 
								'You can specify the number of orders to show (use -1 to display all orders).', 
								DH_DOMAIN ) ) ) ) );
			
			// Woocommerce Widgets
			vc_map( 
				array( 
					"name" => __( "WC Cart", DH_DOMAIN ), 
					"base" => "dh_wc_cart", 
					'weight' => 200, 
					"category" => __( "Woocommerce Widgets", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'Woocommerce Widget Cart.', DH_DOMAIN ), 
					"params" => array( 
						array( 
							'type' => 'textfield', 
							'heading' => __( 'Widget title', DH_DOMAIN ), 
							'param_name' => 'title', 
							'description' => __( 
								'What text use as a widget title. Leave blank to use default widget title.', 
								DH_DOMAIN ) ), 
						array( 
							'param_name' => 'hide_if_empty', 
							'heading' => __( 'Hide if cart is empty', DH_DOMAIN ), 
							'type' => 'checkbox', 
							'value' => array( __( 'Yes,please' ) => '1' ) ), 
						array( 
							'type' => 'textfield', 
							'heading' => __( 'Extra class name', DH_DOMAIN ), 
							'param_name' => 'el_class', 
							'description' => __( 
								'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 
								DH_DOMAIN ) ) ) ) );
			vc_map( 
				array( 
					"name" => __( "WC Layered Nav Filters", DH_DOMAIN ), 
					"base" => "dh_wc_layered_nav_filters", 
					'weight' => 199, 
					"category" => __( "Woocommerce Widgets", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'Woocommerce Widget Layered Nav Filters.', DH_DOMAIN ), 
					"params" => array( 
						array( 
							'type' => 'textfield', 
							'value' => __( 'Active Filters' ), 
							'heading' => __( 'Widget title', DH_DOMAIN ), 
							'param_name' => 'title', 
							'description' => __( 
								'What text use as a widget title. Leave blank to use default widget title.', 
								DH_DOMAIN ) ), 
						array( 
							'type' => 'textfield', 
							'heading' => __( 'Extra class name', DH_DOMAIN ), 
							'param_name' => 'el_class', 
							'description' => __( 
								'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 
								DH_DOMAIN ) ) ) ) );
			
			$attribute_array = array();
			$attribute_taxonomies = wc_get_attribute_taxonomies();
			if ( $attribute_taxonomies )
				foreach ( $attribute_taxonomies as $tax )
					if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) )
						$attribute_array[$tax->attribute_name] = $tax->attribute_name;
			
			vc_map( 
				array( 
					"name" => __( "WC Layered Nav", DH_DOMAIN ), 
					"base" => "dh_wc_layered_nav", 
					'weight' => 198, 
					"category" => __( "Woocommerce Widgets", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'Woocommerce Widget Layered Nav.', DH_DOMAIN ), 
					"params" => array( 
						array( 
							'type' => 'textfield', 
							'value' => __( 'Filter by' ), 
							'heading' => __( 'Widget title', DH_DOMAIN ), 
							'param_name' => 'title', 
							'description' => __( 
								'What text use as a widget title. Leave blank to use default widget title.', 
								DH_DOMAIN ) ), 
						array( 
							'type' => 'dropdown', 
							'param_name' => 'attribute', 
							'heading' => __( 'Attribute', DH_DOMAIN ), 
							'holder' => $this->param_holder, 
							'value' => $attribute_array ), 
						array( 
							'type' => 'dropdown', 
							'param_name' => 'display_type', 
							'heading' => __( 'Display type', DH_DOMAIN ), 
							'holder' => $this->param_holder, 
							'value' => array( __( 'List' ) => 'list', __( 'Dropdown' ) => 'dropdown' ) ), 
						array( 
							'type' => 'dropdown', 
							'param_name' => 'query_type', 
							'heading' => __( 'Query type', DH_DOMAIN ), 
							'holder' => $this->param_holder, 
							'value' => array( __( 'AND' ) => 'and', __( 'OR' ) => 'or' ) ), 
						array( 
							'type' => 'textfield', 
							'heading' => __( 'Extra class name', DH_DOMAIN ), 
							'param_name' => 'el_class', 
							'description' => __( 
								'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 
								DH_DOMAIN ) ) ) ) );
			vc_map( 
				array( 
					"name" => __( "WC Price Filter", DH_DOMAIN ), 
					"base" => "dh_wc_price_filter", 
					'weight' => 197, 
					"category" => __( "Woocommerce Widgets", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'Woocommerce Widget Price Filter.' ), 
					"params" => array( 
						array( 
							'type' => 'textfield', 
							'value' => __( 'Filter by price' ), 
							'heading' => __( 'Widget title', DH_DOMAIN ), 
							'param_name' => 'title', 
							'description' => __( 
								'What text use as a widget title. Leave blank to use default widget title.', 
								DH_DOMAIN ) ), 
						array( 
							'type' => 'textfield', 
							'heading' => __( 'Extra class name', DH_DOMAIN ), 
							'param_name' => 'el_class', 
							'description' => __( 
								'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 
								DH_DOMAIN ) ) ) ) );
			vc_map( 
				array( 
					"name" => __( "WC Product Categories", DH_DOMAIN ), 
					"base" => "dh_wc_product_categories", 
					'weight' => 196, 
					"category" => __( "Woocommerce Widgets", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'Woocommerce Widget Product Categories.' ), 
					"params" => array( 
						array( 
							'type' => 'textfield', 
							'value' => __( 'Product Categories' ), 
							'heading' => __( 'Widget title', DH_DOMAIN ), 
							'param_name' => 'title', 
							'description' => __( 
								'What text use as a widget title. Leave blank to use default widget title.', 
								DH_DOMAIN ) ), 
						array( 
							'type' => 'dropdown', 
							'param_name' => 'orderby', 
							'heading' => __( 'Order by', DH_DOMAIN ), 
							'holder' => $this->param_holder, 
							'value' => array( __( 'Category Order' ) => 'order', __( 'Name' ) => 'name' ) ), 
						array( 
							'param_name' => 'dropdown', 
							'heading' => __( 'Show as dropdown', DH_DOMAIN ), 
							'type' => 'checkbox', 
							'value' => array( __( 'Yes,please' ) => '1' ) ), 
						array( 
							'param_name' => 'count', 
							'heading' => __( 'Show post counts', DH_DOMAIN ), 
							'type' => 'checkbox', 
							'value' => array( __( 'Yes,please' ) => '1' ) ), 
						array( 
							'param_name' => 'hierarchical', 
							'heading' => __( 'Show hierarchy', DH_DOMAIN ), 
							'type' => 'checkbox', 
							'std' => '1', 
							'value' => array( __( 'Yes,please' ) => '1' ) ), 
						array( 
							'param_name' => 'show_children_only', 
							'heading' => __( 'Only show children of the current category', DH_DOMAIN ), 
							'type' => 'checkbox', 
							'value' => array( __( 'Yes,please' ) => '1' ) ), 
						array( 
							'type' => 'textfield', 
							'heading' => __( 'Extra class name', DH_DOMAIN ), 
							'param_name' => 'el_class', 
							'description' => __( 
								'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 
								DH_DOMAIN ) ) ) ) );
			vc_map( 
				array( 
					"name" => __( "WC Product Search", DH_DOMAIN ), 
					"base" => "dh_wc_product_search", 
					'weight' => 195, 
					"category" => __( "Woocommerce Widgets", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'Woocommerce Widget Product Search.' ), 
					"params" => array( 
						array( 
							'type' => 'textfield', 
							'value' => __( 'Search Products' ), 
							'heading' => __( 'Widget title', DH_DOMAIN ), 
							'param_name' => 'title', 
							'description' => __( 
								'What text use as a widget title. Leave blank to use default widget title.', 
								DH_DOMAIN ) ), 
						array( 
							'type' => 'textfield', 
							'heading' => __( 'Extra class name', DH_DOMAIN ), 
							'param_name' => 'el_class', 
							'description' => __( 
								'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 
								DH_DOMAIN ) ) ) ) );
			vc_map( 
				array( 
					"name" => __( "WC Product Tags", DH_DOMAIN ), 
					"base" => "dh_wc_product_tag_cloud", 
					'weight' => 194, 
					"category" => __( "Woocommerce Widgets", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'Woocommerce Widget Product Tags.' ), 
					"params" => array( 
						array( 
							'type' => 'textfield', 
							'value' => __( 'Product Tags' ), 
							'heading' => __( 'Widget title', DH_DOMAIN ), 
							'param_name' => 'title', 
							'description' => __( 
								'What text use as a widget title. Leave blank to use default widget title.', 
								DH_DOMAIN ) ), 
						array( 
							'type' => 'textfield', 
							'heading' => __( 'Extra class name', DH_DOMAIN ), 
							'param_name' => 'el_class', 
							'description' => __( 
								'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 
								DH_DOMAIN ) ) ) ) );
			vc_map( 
				array( 
					"name" => __( "WC Products", DH_DOMAIN ), 
					"base" => "dh_wc_products", 
					'weight' => 193, 
					"category" => __( "Woocommerce Widgets", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'Woocommerce Widget Products.' ), 
					"params" => array( 
						array( 
							'type' => 'textfield', 
							'value' => __( 'Products' ), 
							'heading' => __( 'Widget title', DH_DOMAIN ), 
							'param_name' => 'title', 
							'description' => __( 
								'What text use as a widget title. Leave blank to use default widget title.', 
								DH_DOMAIN ) ), 
						array( 
							'type' => 'textfield', 
							'value' => 5, 
							'heading' => __( 'Number of products to show', DH_DOMAIN ), 
							'param_name' => 'number' ), 
						array( 
							'type' => 'dropdown', 
							'param_name' => 'show', 
							'heading' => __( 'Show', DH_DOMAIN ), 
							'holder' => $this->param_holder, 
							'value' => array( 
								__( 'All Products' ) => '', 
								__( 'Featured Products' ) => 'featured', 
								__( 'On-sale Products' ) => 'onsale' ) ), 
						array( 
							'type' => 'dropdown', 
							'param_name' => 'orderby', 
							'heading' => __( 'Order by', DH_DOMAIN ), 
							'holder' => $this->param_holder, 
							'value' => array( 
								__( 'Date', DH_DOMAIN ) => 'date', 
								__( 'Price', DH_DOMAIN ) => 'price', 
								__( 'Random', DH_DOMAIN ) => 'rand', 
								__( 'Sales', DH_DOMAIN ) => 'sales' ) ), 
						array( 
							'type' => 'dropdown', 
							'param_name' => 'order', 
							'heading' => _x( 'Order', 'Sorting order', DH_DOMAIN ), 
							'holder' => $this->param_holder, 
							'value' => array( __( 'ASC', DH_DOMAIN ) => 'asc', __( 'DESC', DH_DOMAIN ) => 'desc' ) ), 
						array( 
							'param_name' => 'hide_free', 
							'heading' => __( 'Hide free products', DH_DOMAIN ), 
							'type' => 'checkbox', 
							'value' => array( __( 'Yes,please' ) => '1' ) ), 
						array( 
							'param_name' => 'show_hidden', 
							'heading' => __( 'Show hidden products', DH_DOMAIN ), 
							'type' => 'checkbox', 
							'value' => array( __( 'Yes,please' ) => '1' ) ), 
						array( 
							'type' => 'textfield', 
							'heading' => __( 'Extra class name', DH_DOMAIN ), 
							'param_name' => 'el_class', 
							'description' => __( 
								'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 
								DH_DOMAIN ) ) ) ) );
			vc_map( 
				array( 
					"name" => __( "WC Recent Reviews", DH_DOMAIN ), 
					"base" => "dh_wc_recent_reviews", 
					'weight' => 192, 
					"category" => __( "Woocommerce Widgets", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'Woocommerce Widget Recent Reviews.' ), 
					"params" => array( 
						array( 
							'type' => 'textfield', 
							'value' => __( 'Recent Reviews' ), 
							'heading' => __( 'Widget title', DH_DOMAIN ), 
							'param_name' => 'title', 
							'description' => __( 
								'What text use as a widget title. Leave blank to use default widget title.', 
								DH_DOMAIN ) ), 
						array( 
							'type' => 'textfield', 
							'value' => 5, 
							'heading' => __( 'Number of products to show', DH_DOMAIN ), 
							'param_name' => 'number' ), 
						array( 
							'type' => 'textfield', 
							'heading' => __( 'Extra class name', DH_DOMAIN ), 
							'param_name' => 'el_class', 
							'description' => __( 
								'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 
								DH_DOMAIN ) ) ) ) );
			vc_map( 
				array( 
					"name" => __( "WC Recently Viewed", DH_DOMAIN ), 
					"base" => "dh_wc_recently_viewed_products", 
					'weight' => 191, 
					"category" => __( "Woocommerce Widgets", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'Woocommerce Widget Recently Viewed.' ), 
					"params" => array( 
						array( 
							'type' => 'textfield', 
							'value' => __( 'Recently Viewed Products' ), 
							'heading' => __( 'Widget title', DH_DOMAIN ), 
							'param_name' => 'title', 
							'description' => __( 
								'What text use as a widget title. Leave blank to use default widget title.', 
								DH_DOMAIN ) ), 
						array( 
							'type' => 'textfield', 
							'value' => 5, 
							'heading' => __( 'Number of products to show', DH_DOMAIN ), 
							'param_name' => 'number' ), 
						array( 
							'type' => 'textfield', 
							'heading' => __( 'Extra class name', DH_DOMAIN ), 
							'param_name' => 'el_class', 
							'description' => __( 
								'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 
								DH_DOMAIN ) ) ) ) );
			vc_map( 
				array( 
					"name" => __( "WC Top Rated Products", DH_DOMAIN ), 
					"base" => "dh_wc_top_rated_products", 
					'weight' => 190, 
					"category" => __( "Woocommerce Widgets", DH_DOMAIN ), 
					"icon" => "dh-vc-icon-dh_woo", 
					"class" => "dh-vc-element dh-vc-element-dh_woo", 
					'description' => __( 'Woocommerce Widget Top Rated Products.' ), 
					"params" => array( 
						array( 
							'type' => 'textfield', 
							'value' => __( 'Top Rated Products' ), 
							'heading' => __( 'Widget title', DH_DOMAIN ), 
							'param_name' => 'title', 
							'description' => __( 
								'What text use as a widget title. Leave blank to use default widget title.', 
								DH_DOMAIN ) ), 
						array( 
							'type' => 'textfield', 
							'value' => 5, 
							'heading' => __( 'Number of products to show', DH_DOMAIN ), 
							'param_name' => 'number' ), 
						array( 
							'type' => 'textfield', 
							'heading' => __( 'Extra class name', DH_DOMAIN ), 
							'param_name' => 'el_class', 
							'description' => __( 
								'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 
								DH_DOMAIN ) ) ) ) );
		}

		public function add_params() {
			$params = array( 
				'vc_row' => array( 
					array( 
						'param_name' => 'inner_container', 
						'heading' => __( 'Inner Container', DH_DOMAIN ), 
						'description' => __( 'If checked, this row will be placed inside a container.', DH_DOMAIN ), 
						'type' => 'checkbox', 
						'holder' => $this->param_holder, 
						'value' => array( __( 'Yes,please' ) => 'yes' ) ), 
					array( 
						'type' => 'dropdown', 
						'param_name' => 'background_type', 
						'heading' => __( 'Background Type', DH_DOMAIN ), 
						'holder' => $this->param_holder, 
						'value' => array( 
							__( 'None', DH_DOMAIN ) => '', 
							__( 'Color', DH_DOMAIN ) => 'color', 
							__( 'Image', DH_DOMAIN ) => 'image', 
							__( 'Video', DH_DOMAIN ) => 'video' ) ), 
					array( 
						'param_name' => 'bg_color', 
						'heading' => __( 'Background Color', DH_DOMAIN ), 
						'type' => 'colorpicker', 
						'holder' => $this->param_holder, 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'color' ) ) ), 
					array( 
						"type" => "attach_image", 
						"heading" => __( "Image", DH_DOMAIN ), 
						"param_name" => "bg_image", 
						"value" => "", 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'image' ) ), 
						"description" => __( "Select image from media library.", DH_DOMAIN ) ), 
					array( 
						"type" => "checkbox", 
						"heading" => __( "Background Image Repeat", DH_DOMAIN ), 
						"param_name" => "bg_image_repeat", 
						"value" => array( __( 'Yes, please', DH_DOMAIN ) => 'yes' ), 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'image' ) ) ), 
					array( 
						"type" => "dropdown", 
						"heading" => __( "Background Style", DH_DOMAIN ), 
						"param_name" => "parallax_bg", 
						'value' => array(
							__( 'None', DH_DOMAIN ) => 'no',
							__( 'Parallax', DH_DOMAIN ) => 'yes',
							__( 'Fixed', DH_DOMAIN ) => 'fixed'
						),
						'dependency' => array( 'element' => "background_type", 'value' => array( 'image' ) ) ), 
					array( 
						'param_name' => 'parallax_bg_speed', 
						'heading' => __( 'Parallax Speed', DH_DOMAIN ), 
						'type' => 'textfield', 
						'holder' => $this->param_holder, 
						'value' => '0.1', 
						'description' => __( 'The movement speed, value should be between -1.0 and 1.0', DH_DOMAIN ), 
						'dependency' => array( 'element' => "parallax_bg", 'value' => array('yes') ) ), 
					array( 
						'param_name' => 'bg_video_src_mp4', 
						'heading' => __( 'Video background (.mp4)', DH_DOMAIN ), 
						'type' => 'textfield', 
						'holder' => $this->param_holder, 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'video' ) ) ), 
					array( 
						'param_name' => 'bg_video_src_ogv', 
						'heading' => __( 'Video background (.ogv,.ogg)', DH_DOMAIN ), 
						'type' => 'textfield', 
						'holder' => $this->param_holder, 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'video' ) ) ), 
					array( 
						'param_name' => 'bg_video_src_webm', 
						'heading' => __( 'Video background (.webm)', DH_DOMAIN ), 
						'type' => 'textfield', 
						'holder' => $this->param_holder, 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'video' ) ) ), 
					array( 
						'param_name' => 'bg_video_poster', 
						'heading' => __( 'Video Poster Image', DH_DOMAIN ), 
						'type' => 'attach_image', 
						'holder' => $this->param_holder, 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'video' ) ) ), 
					array( 
						'param_name' => 'bg_overlay', 
						'heading' => __( 'Background Color Overlay', DH_DOMAIN ), 
						'type' => 'colorpicker', 
						'holder' => $this->param_holder, 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'video', 'image' ) ) ), 
					array( 
						'param_name' => 'border', 
						'heading' => __( 'Border', DH_DOMAIN ), 
						'type' => 'dropdown', 
						'holder' => $this->param_holder, 
						'value' => array( 
							__( 'None', DH_DOMAIN ) => '', 
							__( 'Top', DH_DOMAIN ) => 'top', 
							__( 'Right', DH_DOMAIN ) => 'right', 
							__( 'Left', DH_DOMAIN ) => 'left', 
							__( 'Bottom', DH_DOMAIN ) => 'bottom', 
							__( 'Top - Right', DH_DOMAIN ) => 'top-right',
							__( 'Top - Left', DH_DOMAIN ) => 'top-left',
							__( 'Top - Bottom', DH_DOMAIN ) => 'vertical',
							__( 'Left - Right', DH_DOMAIN ) => 'horizontal',
							__( 'Bottom - Right', DH_DOMAIN ) => 'bottom-right',
							__( 'Bottom - Left', DH_DOMAIN ) => 'bottom-left',
							__( 'Top - Right - Bottom', DH_DOMAIN ) => 'top-right-bottom',
							__( 'Top - Left - Bottom', DH_DOMAIN ) => 'top-left-bottom',
							__( 'Top - Right - Left', DH_DOMAIN ) => 'top-right-left',
							__( 'Bottom - Right - left', DH_DOMAIN ) => 'bottom-right-left',
							__( 'All', DH_DOMAIN ) => 'all' ) ), 
					array( 
						'param_name' => 'padding_top', 
						'heading' => __( 'Padding Top (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '20', 
						'data_min' => '0', 
						'data_max' => '200' ), 
					array( 
						'param_name' => 'padding_bottom', 
						'heading' => __( 'Padding Bottom (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '20', 
						'data_min' => '0', 
						'data_max' => '200' ) ), 
				'vc_row_inner' => array( 
					array( 
						'param_name' => 'inner_container', 
						'heading' => __( 'Inner Container', DH_DOMAIN ), 
						'description' => __( 'If checked, this row will be placed inside a container.', DH_DOMAIN ), 
						'type' => 'checkbox', 
						'holder' => $this->param_holder, 
						'value' => array( __( 'Yes,please' ) => 'yes' ) ), 
					array( 
						'type' => 'dropdown', 
						'param_name' => 'background_type', 
						'heading' => __( 'Background Type', DH_DOMAIN ), 
						'holder' => $this->param_holder, 
						'value' => array( 
							__( 'None', DH_DOMAIN ) => '', 
							__( 'Color', DH_DOMAIN ) => 'color', 
							__( 'Image', DH_DOMAIN ) => 'image', 
							__( 'Video', DH_DOMAIN ) => 'video' ) ), 
					array( 
						'param_name' => 'bg_color', 
						'heading' => __( 'Background Color', DH_DOMAIN ), 
						'type' => 'colorpicker', 
						'holder' => $this->param_holder, 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'color' ) ) ), 
					array( 
						"type" => "attach_image", 
						"heading" => __( "Image", DH_DOMAIN ), 
						"param_name" => "bg_image", 
						"value" => "", 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'image' ) ), 
						"description" => __( "Select image from media library.", DH_DOMAIN ) ), 
					array( 
						"type" => "checkbox", 
						"heading" => __( "Background Image Repeat", DH_DOMAIN ), 
						"param_name" => "bg_image_repeat", 
						"value" => array( __( 'Yes, please', DH_DOMAIN ) => 'yes' ), 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'image' ) ) ), 
					array( 
						"type" => "dropdown", 
						"heading" => __( "Background Style", DH_DOMAIN ), 
						"param_name" => "parallax_bg", 
						'value' => array(
							__( 'None', DH_DOMAIN ) => 'no',
							__( 'Parallax', DH_DOMAIN ) => 'yes',
							__( 'Fixed', DH_DOMAIN ) => 'fixed'
						),
						'dependency' => array( 'element' => "background_type", 'value' => array( 'image' ) ) ), 
					array( 
						'param_name' => 'parallax_bg_speed', 
						'heading' => __( 'Parallax Speed', DH_DOMAIN ), 
						'type' => 'textfield', 
						'holder' => $this->param_holder, 
						'value' => '0.1', 
						'description' => __( 'The movement speed, value should be between -1.0 and 1.0', DH_DOMAIN ), 
						'dependency' => array( 'element' => "parallax_bg", 'value' => array('yes') ) ), 
					array( 
						'param_name' => 'bg_video', 
						'heading' => __( 'Video URL', DH_DOMAIN ), 
						'type' => 'textfield', 
						'holder' => $this->param_holder, 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'video' ) ) ), 
					array( 
						'param_name' => 'bg_video_poster', 
						'heading' => __( 'Video Poster Image', DH_DOMAIN ), 
						'type' => 'attach_image', 
						'holder' => $this->param_holder, 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'video' ) ) ), 
					array( 
						'param_name' => 'bg_overlay', 
						'heading' => __( 'Background Color Overlay', DH_DOMAIN ), 
						'type' => 'colorpicker', 
						'holder' => $this->param_holder, 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'video', 'image' ) ) ), 
					array( 
						'param_name' => 'border', 
						'heading' => __( 'Border', DH_DOMAIN ), 
						'type' => 'dropdown', 
						'holder' => $this->param_holder, 
						'value' => array( 
							__( 'None', DH_DOMAIN ) => '', 
							__( 'Top', DH_DOMAIN ) => 'top', 
							__( 'Right', DH_DOMAIN ) => 'right', 
							__( 'Left', DH_DOMAIN ) => 'left', 
							__( 'Bottom', DH_DOMAIN ) => 'bottom', 
							__( 'Top - Right', DH_DOMAIN ) => 'top-right',
							__( 'Top - Left', DH_DOMAIN ) => 'top-left',
							__( 'Top - Bottom', DH_DOMAIN ) => 'vertical',
							__( 'Left - Right', DH_DOMAIN ) => 'horizontal',
							__( 'Bottom - Right', DH_DOMAIN ) => 'bottom-right',
							__( 'Bottom - Left', DH_DOMAIN ) => 'bottom-left',
							__( 'Top - Right - Bottom', DH_DOMAIN ) => 'top-right-bottom',
							__( 'Top - Left - Bottom', DH_DOMAIN ) => 'top-left-bottom',
							__( 'Top - Right - Left', DH_DOMAIN ) => 'top-right-left',
							__( 'Bottom - Right - left', DH_DOMAIN ) => 'bottom-right-left',
							__( 'All', DH_DOMAIN ) => 'all' ) ),  
					array( 
						'param_name' => 'padding_top', 
						'heading' => __( 'Padding Top (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '20', 
						'data_min' => '0', 
						'data_max' => '200' ), 
					array( 
						'param_name' => 'padding_bottom', 
						'heading' => __( 'Padding Bottom (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '20', 
						'data_min' => '0', 
						'data_max' => '200' ) ), 
				'vc_column' => array( 
					array(
						'param_name' => 'border',
						'heading' => __( 'Border', DH_DOMAIN ),
						'type' => 'dropdown',
						'holder' => $this->param_holder,
						'value' => array(
							__( 'None', DH_DOMAIN ) => '', 
							__( 'Top', DH_DOMAIN ) => 'top', 
							__( 'Right', DH_DOMAIN ) => 'right', 
							__( 'Left', DH_DOMAIN ) => 'left', 
							__( 'Bottom', DH_DOMAIN ) => 'bottom', 
							__( 'Top - Right', DH_DOMAIN ) => 'top-right',
							__( 'Top - Left', DH_DOMAIN ) => 'top-left',
							__( 'Top - Bottom', DH_DOMAIN ) => 'vertical',
							__( 'Left - Right', DH_DOMAIN ) => 'horizontal',
							__( 'Bottom - Right', DH_DOMAIN ) => 'bottom-right',
							__( 'Bottom - Left', DH_DOMAIN ) => 'bottom-left',
							__( 'Top - Right - Bottom', DH_DOMAIN ) => 'top-right-bottom',
							__( 'Top - Left - Bottom', DH_DOMAIN ) => 'top-left-bottom',
							__( 'Top - Right - Left', DH_DOMAIN ) => 'top-right-left',
							__( 'Bottom - Right - left', DH_DOMAIN ) => 'bottom-right-left',
							__( 'All', DH_DOMAIN ) => 'all' ) ), 
					array( 
						'param_name' => 'fade', 
						'heading' => __( 'Fade Effect', DH_DOMAIN ), 
						'description' => __( 'Select to activate the fade effect.', DH_DOMAIN ), 
						'type' => 'checkbox', 
						'holder' => $this->param_holder, 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ) ), 
					array( 
						'param_name' => 'fade_animation', 
						'heading' => __( 'Fade Animation', DH_DOMAIN ), 
						'description' => __( 'Select the type of fade animation you want to use.', DH_DOMAIN ), 
						'type' => 'dropdown', 
						'holder' => $this->param_holder, 
						'value' => array( 
							__( 'In', DH_DOMAIN ) => 'in', 
							__( 'In From Top', DH_DOMAIN ) => 'in-from-top', 
							__( 'In From Left', DH_DOMAIN ) => 'in-from-left', 
							__( 'In From Right', DH_DOMAIN ) => 'in-from-right', 
							__( 'In From Bottom', DH_DOMAIN ) => 'in-from-bottom' ), 
						'dependency' => array( 'element' => "fade", 'not_empty' => true ) ), 
					array( 
						'param_name' => 'fade_offset', 
						'heading' => __( 'Fade Offset (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '80', 
						'data_min' => '0', 
						'data_max' => '200', 
						'data_step' => '10', 
						'dependency' => array( 'element' => "fade", 'not_empty' => true ) ), 
					array( 
						'param_name' => 'bg_color', 
						'heading' => __( 'Background Color', DH_DOMAIN ), 
						'type' => 'colorpicker', 
						'holder' => $this->param_holder ) ), 
				'vc_column_inner' => array( 
					array(
						'param_name' => 'border',
						'heading' => __( 'Border', DH_DOMAIN ),
						'type' => 'dropdown',
						'holder' => $this->param_holder,
						'value' => array(
							__( 'None', DH_DOMAIN ) => '', 
							__( 'Top', DH_DOMAIN ) => 'top', 
							__( 'Right', DH_DOMAIN ) => 'right', 
							__( 'Left', DH_DOMAIN ) => 'left', 
							__( 'Bottom', DH_DOMAIN ) => 'bottom', 
							__( 'Top - Right', DH_DOMAIN ) => 'top-right',
							__( 'Top - Left', DH_DOMAIN ) => 'top-left',
							__( 'Top - Bottom', DH_DOMAIN ) => 'vertical',
							__( 'Left - Right', DH_DOMAIN ) => 'horizontal',
							__( 'Bottom - Right', DH_DOMAIN ) => 'bottom-right',
							__( 'Bottom - Left', DH_DOMAIN ) => 'bottom-left',
							__( 'Top - Right - Bottom', DH_DOMAIN ) => 'top-right-bottom',
							__( 'Top - Left - Bottom', DH_DOMAIN ) => 'top-left-bottom',
							__( 'Top - Right - Left', DH_DOMAIN ) => 'top-right-left',
							__( 'Bottom - Right - left', DH_DOMAIN ) => 'bottom-right-left',
							__( 'All', DH_DOMAIN ) => 'all' ) ), 
					array( 
						'param_name' => 'fade', 
						'heading' => __( 'Fade Effect', DH_DOMAIN ), 
						'description' => __( 'Select to activate the fade effect.', DH_DOMAIN ), 
						'type' => 'checkbox', 
						'holder' => $this->param_holder, 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ) ), 
					array( 
						'param_name' => 'fade_animation', 
						'heading' => __( 'Fade Animation', DH_DOMAIN ), 
						'description' => __( 'Select the type of fade animation you want to use.', DH_DOMAIN ), 
						'type' => 'dropdown', 
						'holder' => $this->param_holder, 
						'value' => array( 
							__( 'In', DH_DOMAIN ) => 'in', 
							__( 'In From Top', DH_DOMAIN ) => 'in-from-top', 
							__( 'In From Left', DH_DOMAIN ) => 'in-from-left', 
							__( 'In From Right', DH_DOMAIN ) => 'in-from-right', 
							__( 'In From Bottom', DH_DOMAIN ) => 'in-from-bottom' ), 
						'dependency' => array( 'element' => "fade", 'not_empty' => true ) ), 
					array( 
						'param_name' => 'fade_offset', 
						'heading' => __( 'Fade Offset (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '80', 
						'data_min' => '0', 
						'data_max' => '200', 
						'data_step' => '10', 
						'dependency' => array( 'element' => "fade", 'not_empty' => true ) ), 
					array( 
						'param_name' => 'bg_color', 
						'heading' => __( 'Background Color', DH_DOMAIN ), 
						'type' => 'colorpicker', 
						'holder' => $this->param_holder ) ), 
				'dh_box' => array(
					array(
						'param_name' => 'border',
						'heading' => __( 'Border', DH_DOMAIN ),
						'type' => 'dropdown',
						'holder' => $this->param_holder,
						'value' => array(
							__( 'None', DH_DOMAIN ) => '', 
							__( 'Top', DH_DOMAIN ) => 'top', 
							__( 'Right', DH_DOMAIN ) => 'right', 
							__( 'Left', DH_DOMAIN ) => 'left', 
							__( 'Bottom', DH_DOMAIN ) => 'bottom', 
							__( 'Top - Right', DH_DOMAIN ) => 'top-right',
							__( 'Top - Left', DH_DOMAIN ) => 'top-left',
							__( 'Top - Bottom', DH_DOMAIN ) => 'vertical',
							__( 'Left - Right', DH_DOMAIN ) => 'horizontal',
							__( 'Bottom - Right', DH_DOMAIN ) => 'bottom-right',
							__( 'Bottom - Left', DH_DOMAIN ) => 'bottom-left',
							__( 'Top - Right - Bottom', DH_DOMAIN ) => 'top-right-bottom',
							__( 'Top - Left - Bottom', DH_DOMAIN ) => 'top-left-bottom',
							__( 'Top - Right - Left', DH_DOMAIN ) => 'top-right-left',
							__( 'Bottom - Right - left', DH_DOMAIN ) => 'bottom-right-left',
							__( 'All', DH_DOMAIN ) => 'all' ) ), 
					array( 
						'type' => 'dropdown', 
						'param_name' => 'background_type', 
						'heading' => __( 'Background Type', DH_DOMAIN ), 
						'holder' => $this->param_holder, 
						'value' => array( 
							__( 'None', DH_DOMAIN ) => '', 
							__( 'Color', DH_DOMAIN ) => 'color', 
							__( 'Image', DH_DOMAIN ) => 'image', 
							__( 'Video', DH_DOMAIN ) => 'video' ) ), 
					array( 
						'param_name' => 'bg_color', 
						'heading' => __( 'Background Color', DH_DOMAIN ), 
						'type' => 'colorpicker', 
						'holder' => $this->param_holder, 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'color' ) ) ), 
					array( 
						"type" => "attach_image", 
						"heading" => __( "Image", DH_DOMAIN ), 
						"param_name" => "bg_image", 
						"value" => "", 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'image' ) ), 
						"description" => __( "Select image from media library.", DH_DOMAIN ) ), 
					array( 
						"type" => "checkbox", 
						"heading" => __( "Background Image Repeat", DH_DOMAIN ), 
						"param_name" => "bg_image_repeat", 
						"value" => array( __( 'Yes, please', DH_DOMAIN ) => 'yes' ), 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'image' ) ) ), 
					array( 
						"type" => "dropdown", 
						"heading" => __( "Background Style", DH_DOMAIN ), 
						"param_name" => "parallax_bg", 
						'value' => array(
							__( 'None', DH_DOMAIN ) => 'no',
							__( 'Parallax', DH_DOMAIN ) => 'yes',
							__( 'Fixed', DH_DOMAIN ) => 'fixed'
						),
						'dependency' => array( 'element' => "background_type", 'value' => array( 'image' ) ) ), 
					array( 
						'param_name' => 'parallax_bg_speed', 
						'heading' => __( 'Parallax Speed', DH_DOMAIN ), 
						'type' => 'textfield', 
						'holder' => $this->param_holder, 
						'value' => '0.1', 
						'description' => __( 'The movement speed, value should be between -1.0 and 1.0', DH_DOMAIN ), 
						'dependency' => array( 'element' => "parallax_bg", 'value' => array('yes') ) ), 
					array( 
						'param_name' => 'bg_video', 
						'heading' => __( 'Video URL', DH_DOMAIN ), 
						'type' => 'textfield', 
						'holder' => $this->param_holder, 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'video' ) ) ), 
					array( 
						'param_name' => 'bg_video_poster', 
						'heading' => __( 'Video Poster Image', DH_DOMAIN ), 
						'type' => 'attach_image', 
						'holder' => $this->param_holder, 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'video' ) ) ), 
					array( 
						'param_name' => 'bg_overlay', 
						'heading' => __( 'Background Color Overlay', DH_DOMAIN ), 
						'type' => 'colorpicker', 
						'holder' => $this->param_holder, 
						'dependency' => array( 'element' => "background_type", 'value' => array( 'video', 'image' ) ) ), 
					array( 
						'param_name' => 'margin_top', 
						'heading' => __( 'Margin Top (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '0', 
						'data_min' => '0', 
						'data_max' => '200' ), 
					array( 
						'param_name' => 'margin_right', 
						'heading' => __( 'Margin Right (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '0', 
						'data_min' => '0', 
						'data_max' => '200' ), 
					array( 
						'param_name' => 'margin_bottom', 
						'heading' => __( 'Margin Bottom (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '0', 
						'data_min' => '0', 
						'data_max' => '200' ), 
					array( 
						'param_name' => 'margin_left', 
						'heading' => __( 'Margin Left (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '0', 
						'data_min' => '0', 
						'data_max' => '200' ), 
					array( 
						'param_name' => 'padding_top', 
						'heading' => __( 'Padding Top (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '0', 
						'data_min' => '0', 
						'data_max' => '200' ), 
					array( 
						'param_name' => 'padding_right', 
						'heading' => __( 'Padding Right (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '0', 
						'data_min' => '0', 
						'data_max' => '200' ), 
					array( 
						'param_name' => 'padding_bottom', 
						'heading' => __( 'Padding Bottom (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '0', 
						'data_min' => '0', 
						'data_max' => '200' ), 
					array( 
						'param_name' => 'padding_left', 
						'heading' => __( 'Padding Left (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '0', 
						'data_min' => '0', 
						'data_max' => '200' ) ), 
				'vc_column_text' => array(), 
				'dh_clearfix' => array( 
					array( 
						'param_name' => 'el_class', 
						'heading' => __( '(Optional) Extra class name', DH_DOMAIN ), 
						'type' => 'textfield', 
						'holder' => $this->param_holder, 
						"description" => __( 
							"If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 
							DH_DOMAIN ) ) ), 
				'dh_menu_anchor' => array(
					array(
						'param_name' => 'name',
						'heading' => __( 'Name Of Menu Anchor', DH_DOMAIN ),
						'type' => 'textfield',
						'admin_label' => true,
						'holder' => $this->param_holder,
						"description" => __(
							"This name will be the id you will have to use in your one page menu.",
							DH_DOMAIN ) ) ),
				'dh_animation' => array( 
					array( 
						'param_name' => 'animation', 
						'heading' => __( 'Select Animation', DH_DOMAIN ), 
						'description' => __( 'Choose animation effect for this column.', DH_DOMAIN ), 
						'type' => 'dropdown', 
						'holder' => $this->param_holder, 
						'std' => 'fadeIn', 
						'value' => array( 
							"None" => "", 
							"Flash" => "flash", 
							"Shake" => "shake", 
							"Bounce" => "bounce", 
							"Fade In" => "fadeIn", 
							"Fade In Up" => "fadeInUp", 
							"Fade In Down" => "fadeInDown", 
							"Fade In Left" => "fadeInLeft", 
							"Fade In Right" => "fadeInRight", 
							"Fade In Up Big" => "fadeInUpBig", 
							"Fade In Down Big" => 'fadeInDownBig', 
							'Fade In Left Big' => 'fadeInLeftBig', 
							'Fade In Right Big' => 'fadeInRightBig', 
							'Fade Out' => 'fadeOut', 
							'Fade Out Up' => 'fadeOutUp', 
							'Fade Out Down' => 'fadeOutDown', 
							'Fade Out Left' => 'fadeOutLeft', 
							'Fade Out Right' => 'fadeOutRight', 
							'Fade Out Up Big' => 'fadeOutUpBig', 
							'Fade Out Down Big' => 'fadeOutDownBig', 
							'Fade Out Left Big' => 'fadeOutLeftBig', 
							'Fade Out Right Big' => 'fadeOutRightBig', 
							'Slide In Down' => 'slideInDown', 
							'Slide In Left' => 'slideInLeft', 
							'Slide In Right' => 'slideInRight', 
							'Slide Out Up' => 'slideOutUp', 
							'Slide Out Left' => 'slideOutLeft', 
							'Slide Out Right' => 'slideOutRight', 
							'Bounce In' => 'bounceIn', 
							'Bounce In Up' => 'bounceInUp', 
							'Bounce In Down' => 'bounceInDown', 
							'Bounce In Left' => 'bounceInLeft', 
							'Bounce In Right' => 'bounceInRight', 
							'Bounce Out' => 'bounceOut', 
							'Bounce Out Up' => 'bounceOutUp', 
							'Bounce Out Down' => 'bounceOutDown', 
							'Bounce Out Left' => 'bounceOutLeft', 
							'Bounce Out Right' => 'bounceOutRight', 
							'Light Speed In' => 'lightSpeedIn', 
							'Light Speed Out' => 'lightSpeedOut' ) ), 
					
					array( 
						"type" => "dropdown", 
						"heading" => __( "Animation Transition", DH_DOMAIN ), 
						"param_name" => "animation_timing", 
						"value" => array( 
							'linear' => 'linear', 
							'ease' => 'ease', 
							'easeIn' => 'easeIn', 
							'easeOut' => 'easeOut', 
							'easeInOut' => 'easeInOut', 
							'easeInQuad' => 'easeInQuad', 
							'easeInCubic' => 'easeInCubic', 
							'easeInQuart' => 'easeInQuart', 
							'easeInQuint' => 'easeInQuint', 
							'easeInSine' => 'easeInSine', 
							'easeInExpo' => 'easeInExpo', 
							'easeInCirc' => 'easeInCirc', 
							'easeInBack' => 'easeInBack', 
							'easeOutQuad' => 'easeOutQuad', 
							'easeOutCubic' => 'easeOutCubic', 
							'easeOutQuart' => 'easeOutQuart', 
							'easeOutQuint' => 'easeOutQuint', 
							'easeOutSine' => 'easeOutSine', 
							'easeOutExpo' => 'easeOutExpo', 
							'easeOutCirc' => 'easeOutCirc', 
							'easeOutBack' => 'easeOutBack', 
							'easeInOutQuad' => 'easeInOutQuad', 
							'easeInOutCubic' => 'easeInOutCubic', 
							'easeInOutQuart' => 'easeInOutQuart', 
							'easeInOutQuint' => 'easeInOutQuint', 
							'easeInOutSine' => 'easeInOutSine', 
							'easeInOutExpo' => 'easeInOutExpo', 
							'easeInOutCirc' => 'easeInOutCirc', 
							'easeInOutBack' => 'easeInOutBack' ), 
						"description" => __( "Select the a animation transition.", DH_DOMAIN ) ), 
					array( 
						'param_name' => 'animation_duration', 
						'heading' => __( 'Animation Duration (ms)', DH_DOMAIN ), 
						'description' => __( 
							'Specifies how many milliseconds an animation takes to complete.', 
							DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '1000', 
						'data_min' => '0', 
						'data_max' => '3000', 
						'data_step' => '50', 
						'dependency' => array( 'element' => "animation", 'not_empty' => true ) ), 
					array( 
						'param_name' => 'animation_delay', 
						'heading' => __( 'Animation Delay (ms)', DH_DOMAIN ), 
						'description' => __( 'Specifies a delay before the animation will start.', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '0', 
						'data_min' => '0', 
						'data_max' => '3000', 
						'data_step' => '50', 
						'dependency' => array( 'element' => "animation", 'not_empty' => true ) ) ), 
				
				'vc_separator' => array( 
					array( 
						'param_name' => 'type', 
						'heading' => __( 'Type', DH_DOMAIN ), 
						'type' => 'dropdown', 
						'admin_label' => true, 
						'holder' => $this->param_holder, 
						'value' => array( 
							__( 'Line', DH_DOMAIN ) => "line", 
							__( 'Line with text', DH_DOMAIN ) => "line_text", 
							__( 'Line with icon', DH_DOMAIN ) => "line_icon" ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Icon', DH_DOMAIN ), 
						'param_name' => 'icon', 
						"param_holder_class" => 'dh-font-awesome-select', 
						"value" => dh_font_awesome_options(), 
						'dependency' => array( 'element' => "type", 'value' => array( 'line_icon' ) ), 
						'description' => __( 'Icon.', DH_DOMAIN ) ), 
					array( 
						'param_name' => 'icon_size', 
						'heading' => __( 'Icon font size', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '14', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "type", 'value' => array( 'line_icon' ) ), 
						'data_max' => '100' ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Icon position', DH_DOMAIN ), 
						'param_name' => 'icon_align', 
						'value' => array( 
							__( 'Align center', DH_DOMAIN ) => 'align-center', 
							__( 'Align left', DH_DOMAIN ) => 'align-left', 
							__( 'Align right', DH_DOMAIN ) => "align-right" ), 
						'description' => __( 'Select icon location.', DH_DOMAIN ), 
						'dependency' => array( 'element' => "type", 'value' => array( 'line_icon' ) ) ), 
					array( 
						'param_name' => 'title', 
						'heading' => __( 'Text', DH_DOMAIN ), 
						'type' => 'textfield', 
						'admin_label' => true, 
						'holder' => $this->param_holder, 
						'dependency' => array( 'element' => "type", 'value' => array( 'line_text' ) ) ), 
					array( 
						'param_name' => 'title_size', 
						'heading' => __( 'Title font size', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '20', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "type", 'value' => array( 'line_text' ) ), 
						'data_max' => '100' ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Title position', DH_DOMAIN ), 
						'param_name' => 'title_align', 
						'value' => array( 
							__( 'Align center', DH_DOMAIN ) => 'align-center', 
							__( 'Align left', DH_DOMAIN ) => 'align-left', 
							__( 'Align right', DH_DOMAIN ) => "align-right" ), 
						'description' => __( 'Select title location.', DH_DOMAIN ), 
						'dependency' => array( 'element' => "type", 'value' => array( 'line_text' ) ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Align', DH_DOMAIN ), 
						'param_name' => 'line_align', 
						'value' => array( 
							__( 'Align center', DH_DOMAIN ) => 'align-center', 
							__( 'Align left', DH_DOMAIN ) => 'align-left', 
							__( 'Align right', DH_DOMAIN ) => "align-right" ), 
						'description' => __( 'Select title location.', DH_DOMAIN ), 
						'dependency' => array( 'element' => "type", 'value' => array( 'line' ) ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Color', DH_DOMAIN ), 
						'param_name' => 'color', 
						'description' => __( 'Separator color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Style', DH_DOMAIN ), 
						'param_name' => 'style', 
						'value' => array( 
							__( 'Border', DH_DOMAIN ) => '', 
							__( 'Border 2', DH_DOMAIN ) => 'border-2', 
							__( 'Dashed', DH_DOMAIN ) => 'dashed', 
							__( 'Dotted', DH_DOMAIN ) => 'dotted', 
							__( 'Double', DH_DOMAIN ) => 'double' ), 
						'description' => __( 'Separator style.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Element Width Type', DH_DOMAIN ), 
						'param_name' => 'width_type', 
						'value' => array( __( 'Percent', DH_DOMAIN ) => 'percent', __( 'Fix', DH_DOMAIN ) => 'fix' ) ), 
					array( 
						'param_name' => 'el_with_px', 
						'heading' => __( 'Element width (Px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '100', 
						'data_min' => '100', 
						'data_step' => '10', 
						'dependency' => array( 'element' => "width_type", 'value' => array( 'fix' ) ), 
						'data_max' => '800' ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Element width', DH_DOMAIN ), 
						'param_name' => 'el_width', 
						'dependency' => array( 'element' => "width_type", 'value' => array( 'percents' ) ), 
						'value' => array( 
							'100%' => '', 
							'90%' => '90', 
							'80%' => '80', 
							'70%' => '70', 
							'60%' => '60', 
							'50%' => '50', 
							'40%' => '40', 
							'30%' => '30', 
							'20%' => '20', 
							'10%' => '10' ), 
						'description' => __( 'Separator element width in percents.', DH_DOMAIN ) ) ), 
				'vc_empty_space' => array( 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Height', DH_DOMAIN ), 
						'param_name' => 'height', 
						'admin_label' => true, 
						'value' => '40', 
						'description' => __( 'Enter empty space height (px).', DH_DOMAIN ) ) ), 
				'dh_post' => array( 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Layout', DH_DOMAIN ), 
						'param_name' => 'layout', 
						'admin_label' => true, 
						'value' => array( 
							__( 'Default', DH_DOMAIN ) => 'default', 
							__( 'Medium', DH_DOMAIN ) => 'medium', 
							__( 'Grid', DH_DOMAIN ) => 'grid', 
							__( 'Masonry', DH_DOMAIN ) => 'masonry', 
							__( 'Timeline', DH_DOMAIN ) => 'timeline',
						), 
						'std' => 'default', 
						'description' => __( 'Select the layout for the blog shortcode.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Columns', DH_DOMAIN ), 
						'param_name' => 'columns', 
						'std' => 3, 
						'value' => array( 
							__( '2', DH_DOMAIN ) => '2', 
							__( '3', DH_DOMAIN ) => '3', 
							__( '4', DH_DOMAIN ) => '4' ), 
						'dependency' => array( 'element' => "layout", 'value' => array( 'grid', 'masonry' ) ), 
						'description' => __( 'Select whether to display the layout in 2, 3 or 4 column.', DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Posts Per Page', DH_DOMAIN ), 
						'param_name' => 'posts_per_page', 
						'value' => 5, 
						'description' => __( 'Select number of posts per page.Set "-1" to display all', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Order by', DH_DOMAIN ), 
						'param_name' => 'orderby', 
						'value' => array( 
							__( 'Recent First', DH_DOMAIN ) => 'latest', 
							__( 'Older First', DH_DOMAIN ) => 'oldest', 
							__( 'Title Alphabet', DH_DOMAIN ) => 'alphabet', 
							__( 'Title Reversed Alphabet', DH_DOMAIN ) => 'ralphabet' ) ), 
					array( 
						'type' => 'post_category', 
						'heading' => __( 'Categories', DH_DOMAIN ), 
						'param_name' => 'categories', 
						'admin_label' => true, 
						'description' => __( 'Select a category or leave blank for all', DH_DOMAIN ) ), 
					array( 
						'type' => 'post_category', 
						'heading' => __( 'Exclude Categories', DH_DOMAIN ), 
						'param_name' => 'exclude_categories', 
						'description' => __( 'Select a category to exclude', DH_DOMAIN ) ), 
					
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Post Title', DH_DOMAIN ), 
						'param_name' => 'hide_post_title', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Hide the post title below the featured', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Link Title To Post', DH_DOMAIN ), 
						'param_name' => 'link_post_title', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes', __( 'No', DH_DOMAIN ) => 'no' ), 
						'description' => __( 
							'Choose if the title should be a link to the single post page.', 
							DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Thumbnail', DH_DOMAIN ), 
						'param_name' => 'hide_thumbnail', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Hide the post featured', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Excerpt', DH_DOMAIN ), 
						'param_name' => 'hide_excerpt', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'dependency' => array( 
							'element' => "layout", 
							'value' => array( 'default', 'medium', 'grid', 'masonry' ) ), 
						'description' => __( 'Hide excerpt', DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Number of words in Excerpt', DH_DOMAIN ), 
						'param_name' => 'excerpt_length', 
						'value' => 55, 
						'dependency' => array( 'element' => 'hide_excerpt', 'is_empty' => true ), 
						'description' => __( 'The number of words', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Date', DH_DOMAIN ), 
						'param_name' => 'hide_date', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Hide date in post meta info', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Timeline Month', DH_DOMAIN ), 
						'param_name' => 'hide_month', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'dependency' => array( 'element' => "layout", 'value' => array( 'timeline' ) ), 
						'description' => __( 'Hide timeline month', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Comment', DH_DOMAIN ), 
						'param_name' => 'hide_comment', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Hide comment in post meta info', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Category', DH_DOMAIN ), 
						'param_name' => 'hide_category', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Hide category in post meta info', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Author', DH_DOMAIN ), 
						'param_name' => 'hide_author', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'dependency' => array( 
							'element' => "layout", 
							'value' => array( 'default', 'medium', 'grid', 'masonry' ) ), 
						'description' => __( 'Hide author in post meta info', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Read More Link', DH_DOMAIN ), 
						'param_name' => 'hide_readmore', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'dependency' => array( 
							'element' => "layout", 
							'value' => array( 'default', 'medium', 'grid', 'masonry' ) ), 
						'description' => __( 'Choose to hide the link', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Show Tags', DH_DOMAIN ), 
						'param_name' => 'show_tag', 
						'value' => array( __( 'No', DH_DOMAIN ) => 'no', __( 'Yes', DH_DOMAIN ) => 'yes' ), 
						'dependency' => array( 
							'element' => "layout", 
							'value' => array( 'default', 'medium', 'grid', 'masonry' ) ), 
						'description' => __( 'Choose to show the tags', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Pagination', DH_DOMAIN ), 
						'param_name' => 'pagination', 
						'value' => array( 
							__( 'Page Number', DH_DOMAIN ) => 'page_num', 
							__( 'Load More Button', DH_DOMAIN ) => 'loadmore', 
							__( 'Infinite Scrolling', DH_DOMAIN ) => 'infinite_scroll', 
							__( 'No', DH_DOMAIN ) => 'no' ), 
						'description' => __( 'Choose pagination type.', DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Load More Button Text', DH_DOMAIN ), 
						'param_name' => 'loadmore_text', 
						'dependency' => array( 'element' => "pagination", 'value' => array( 'loadmore' ) ), 
						'value' => __( 'Load More', DH_DOMAIN ) ) ), 
				'dh_latestnews' => array( 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Title', DH_DOMAIN ), 
						'param_name' => 'title', 
						'description' => __( 
							'Enter text which will be used as widget title. Leave blank if no title is needed.', 
							DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Title Backgound', DH_DOMAIN ), 
						'param_name' => 'title_bg', 
						'description' => __( 'Select background color for title.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Title Icon', DH_DOMAIN ), 
						'param_name' => 'title_icon', 
						"param_holder_class" => 'dh-font-awesome-select', 
						"value" => dh_font_awesome_options(), 
						'description' => __( 'Title icon.', DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Posts Per Page', DH_DOMAIN ), 
						'param_name' => 'posts_per_page', 
						'value' => 5, 
						'admin_label' => true, 
						'description' => __( 'Select number of posts per page.Set "-1" to display all', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Order by', DH_DOMAIN ), 
						'param_name' => 'orderby', 
						'value' => array( 
							__( 'Recent First', DH_DOMAIN ) => 'latest', 
							__( 'Older First', DH_DOMAIN ) => 'oldest', 
							__( 'Most Commented', DH_DOMAIN ) => 'comment', 
							__( 'Title Alphabet', DH_DOMAIN ) => 'alphabet', 
							__( 'Title Reversed Alphabet', DH_DOMAIN ) => 'ralphabet' ) ), 
					array( 
						'type' => 'post_category', 
						'heading' => __( 'Categories', DH_DOMAIN ), 
						'param_name' => 'categories', 
						'single_select' => true, 
						'admin_label' => true, 
						'description' => __( 'Select a category or leave blank for all', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Style', DH_DOMAIN ), 
						'param_name' => 'style', 
						'admin_label' => true, 
						'value' => array( 
							__( 'Only this category', DH_DOMAIN ) => 'only', 
							__( 'Tabs with childrens', DH_DOMAIN ) => 'tab', 
							__( 'Carousel', DH_DOMAIN ) => 'carousel' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Transition', DH_DOMAIN ), 
						'param_name' => 'fx', 
						'dependency' => array( 'element' => "style", 'value' => array( 'carousel' ) ), 
						'std' => 'scroll', 
						'value' => array( 
							'Scroll' => 'scroll', 
							'Directscroll' => 'directscroll', 
							'Fade' => 'fade', 
							'Cross fade' => 'crossfade', 
							'Cover' => 'cover', 
							'Cover fade' => 'cover-fade', 
							'Uncover' => 'cover-fade', 
							'Uncover fade' => 'uncover-fade' ), 
						'description' => __( 'Indicates which effect to use for the transition.', DH_DOMAIN ) ), 
					array( 
						'param_name' => 'visible', 
						'dependency' => array( 'element' => "style", 'value' => array( 'carousel' ) ), 
						'heading' => __( 'The number of visible items', DH_DOMAIN ), 
						'type' => 'dropdown', 
						'std' => 4, 
						'value' => array( 2, 3, 4, 6 ) ), 
					
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Autoplay ?', DH_DOMAIN ), 
						'param_name' => 'auto_play', 
						'dependency' => array( 'element' => "style", 'value' => array( 'carousel' ) ), 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Slide Pagination ?', DH_DOMAIN ), 
						'param_name' => 'hide_pagination', 
						'dependency' => array( 'element' => "style", 'value' => array( 'carousel' ) ), 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Previous/Next Control ?', DH_DOMAIN ), 
						'param_name' => 'hide_control', 
						'dependency' => array( 'element' => "style", 'value' => array( 'carousel' ) ), 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Use category name as widget title', DH_DOMAIN ), 
						'param_name' => 'title_is_cat_name', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Intro Posts Thumb', DH_DOMAIN ), 
						'param_name' => 'intro_thumb', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Show thumbnail in intro post', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Excerpt', DH_DOMAIN ), 
						'param_name' => 'hide_excerpt', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Hide excerpt', DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Number of words in Excerpt', DH_DOMAIN ), 
						'param_name' => 'excerpt_length', 
						'value' => 20, 
						'dependency' => array( 'element' => 'hide_excerpt', 'is_empty' => true ), 
						'description' => __( 'The number of words', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Date', DH_DOMAIN ), 
						'param_name' => 'hide_date', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Hide date in post meta info', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Comment', DH_DOMAIN ), 
						'param_name' => 'hide_comment', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Hide comment in post meta info', DH_DOMAIN ) ) ), 
				
				'dh_post_thumbnail' => array( 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Title', DH_DOMAIN ), 
						'param_name' => 'title', 
						'description' => __( 
							'Enter text which will be used as widget title. Leave blank if no title is needed.', 
							DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Posts Per Page', DH_DOMAIN ), 
						'param_name' => 'posts_per_page', 
						'admin_label' => true, 
						'value' => 5, 
						'description' => __( 'Select number of posts per page.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Order by', DH_DOMAIN ), 
						'param_name' => 'orderby', 
						'admin_label' => true, 
						'value' => array( 
							__( 'Recent First', DH_DOMAIN ) => 'latest', 
							__( 'Recent Comment', DH_DOMAIN ) => 'comment' ) ), 
					
					array( 
						'type' => 'post_category', 
						'heading' => __( 'Categories', DH_DOMAIN ), 
						'param_name' => 'categories', 
						'admin_label' => true, 
						'description' => __( 'Select a category or leave blank for all', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Date', DH_DOMAIN ), 
						'param_name' => 'hide_date', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Hide date in post meta info', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Comment', DH_DOMAIN ), 
						'param_name' => 'hide_comment', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Hide comment in post meta info', DH_DOMAIN ) ) ), 
				'dh_mailchimp'=>array(
					array(
						'type' => 'textfield',
						'heading' => __( 'Title', DH_DOMAIN ),
						'param_name' => 'title',
						'description' => __(
							'Enter text which will be used as widget title. Leave blank if no title is needed.',
							DH_DOMAIN ) ),
				),
				'dh_portfolio' => array( 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Layout', DH_DOMAIN ), 
						'param_name' => 'layout', 
						'admin_label' => true, 
						'value' => array( 
							__( 'Masonry', DH_DOMAIN ) => 'masonry', 
							__( 'Grid', DH_DOMAIN ) => 'grid' ), 
						'std' => 'masonry', 
						'description' => __( 'Select layout for the portfolio shortcode.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Style', DH_DOMAIN ), 
						'param_name' => 'style', 
						'admin_label' => true, 
						'dependency' => array( 'element' => "layout", 'value' => array( 'masonry', 'grid' ) ), 
						'value' => array( 
							__( 'Link from bottom on hover + zoom button', DH_DOMAIN ) => 'one', 
							__( 'Title + zoom button on hover', DH_DOMAIN ) => 'two', 
							__( 'Link on hover + zoom button', DH_DOMAIN ) => 'three', 
							__( 'Lily', DH_DOMAIN ) => 'lily', 
							__( 'Marley', DH_DOMAIN ) => 'marley' ), 
						'std' => 'one', 
						'description' => __( 'Select style for the portfolio shortcode.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Columns', DH_DOMAIN ), 
						'param_name' => 'columns', 
						'std' => 3, 
						'dependency' => array( 'element' => "layout", 'value' => array( 'masonry', 'grid' ) ), 
						'value' => array( 
							__( '2', DH_DOMAIN ) => '2', 
							__( '3', DH_DOMAIN ) => '3', 
							__( '4', DH_DOMAIN ) => '4' ), 
						'dependency' => array( 'element' => "layout", 'value' => array( 'grid', 'masonry' ) ), 
						'description' => __( 'Select whether to display the layout in 2, 3 or 4 column.', DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Posts Per Page', DH_DOMAIN ), 
						'param_name' => 'posts_per_page', 
						'value' => 5, 
						'description' => __( 'Select number of posts per page.Set "-1" to display all', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Order by', DH_DOMAIN ), 
						'param_name' => 'orderby', 
						'value' => array( 
							__( 'Recent First', DH_DOMAIN ) => 'latest', 
							__( 'Older First', DH_DOMAIN ) => 'oldest', 
							__( 'Title Alphabet', DH_DOMAIN ) => 'alphabet', 
							__( 'Title Reversed Alphabet', DH_DOMAIN ) => 'ralphabet' ) ), 
					array( 
						'type' => 'portfolio_category', 
						'heading' => __( 'Categories', DH_DOMAIN ), 
						'param_name' => 'categories', 
						'admin_label' => true, 
						'description' => __( 'Select a category or leave blank for all', DH_DOMAIN ) ), 
					array( 
						'type' => 'portfolio_category', 
						'heading' => __( 'Exclude Categories', DH_DOMAIN ), 
						'param_name' => 'exclude_categories', 
						'description' => __( 'Select a category to exclude', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Gap', DH_DOMAIN ), 
						'param_name' => 'gap', 
						'dependency' => array( 'element' => "layout", 'value' => array( 'masonry', 'grid' ) ), 
						'value' => array( 
							__( 'No', DH_DOMAIN ) => '', 
							__( 'Yes', DH_DOMAIN ) => 'yes', 
							__( '1 px', DH_DOMAIN ) => 'onepx' ), 
						'description' => __( 'Enable gap in item', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Filter', DH_DOMAIN ), 
						'param_name' => 'hide_filter', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'dependency' => array( 'element' => "layout", 'value' => array( 'masonry' ) ), 
						'description' => __( 'Hide masonry filter', DH_DOMAIN ) ), 
					array(
						'type' => 'checkbox',
						'heading' => __( 'Hide Sorting', DH_DOMAIN ),
						'param_name' => 'hide_sorting',
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ),
						'dependency' => array( 'element' => "layout", 'value' => array( 'masonry' ) ),
						'description' => __( 'Hide masonry sorting', DH_DOMAIN ) ),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Filter Type', DH_DOMAIN ),
						'param_name' => 'filter_type',
						'value' => array(
							__( 'Default', DH_DOMAIN ) => 'default', 
							__( 'Center', DH_DOMAIN ) => 'center'
						), 
						'description'=>__('Chooso a Filter Type, the filter type Center only work when Hide Sorting',DH_DOMAIN),
						'dependency' => array( 'element' => "layout", 'value' => array( 'masonry' ) ),
						'description' => __( 'Hide masonry filter', DH_DOMAIN ) ),
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Wrap Masonry filter in a container', DH_DOMAIN ), 
						'param_name' => 'wrap_filter', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'dependency' => array( 'element' => "layout", 'value' => array( 'masonry' ) ), 
						'description' => __( 
							'If selected, filter box will be placed inside a container. <strong>Recommended use in full width page</strong>', 
							DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Action', DH_DOMAIN ), 
						'param_name' => 'hide_action', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Hide Popup and Link button icon', DH_DOMAIN ) ),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Action Link To', DH_DOMAIN ),
						'param_name' => 'portfolio_action_link_to',
						'sdt'=>'project_detail',
						'value' => array(
							__('View Single URL',DH_DOMAIN)=>'single_url',
							__('Project URL',DH_DOMAIN)=>'project_url',
						),
						'description' => __( 'Select Link URL for Link Button icon', DH_DOMAIN ) ),
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Pagination', DH_DOMAIN ), 
						'param_name' => 'pagination', 
						'value' => array( 
							__( 'Page Number', DH_DOMAIN ) => 'page_num', 
							__( 'Load More Button', DH_DOMAIN ) => 'loadmore', 
							__( 'Infinite Scrolling', DH_DOMAIN ) => 'infinite_scroll', 
							__( 'No', DH_DOMAIN ) => 'no' ), 
						'description' => __( 'Choose pagination type.', DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Load More Button Text', DH_DOMAIN ), 
						'param_name' => 'loadmore_text', 
						'dependency' => array( 'element' => "pagination", 'value' => array( 'loadmore' ) ), 
						'value' => __( 'Load More', DH_DOMAIN ) ) ), 
				'vc_progress_bar' => array( 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Styles', DH_DOMAIN ), 
						'param_name' => 'style', 
						'value' => array( 
							__( 'Label Above', DH_DOMAIN ) => 'label-above', 
							__( 'Label Inner', DH_DOMAIN ) => 'label-inner' ), 
						'admin_label' => true, 
						'description' => __( 'Choose progress stype.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Title Color', DH_DOMAIN ), 
						'param_name' => 'title_color', 
						'description' => __( 'Select color for title.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Label Color', DH_DOMAIN ), 
						'param_name' => 'label_color', 
						'description' => __( 'Select color for progress label.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Progress height', DH_DOMAIN ), 
						'param_name' => 'progress_height', 
						'value' => array( 
							__( 'Default', DH_DOMAIN ) => 'default', 
							__( 'Custom', DH_DOMAIN ) => 'custom' ), 
						'description' => __( 'Choose custom progress height.', DH_DOMAIN ) ), 
					array( 
						'param_name' => 'progress_height_value', 
						'heading' => __( 'Progress Height Value (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '20', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "progress_height", 'value' => array( 'custom' ) ), 
						'data_max' => '100' ) ), 
				'vc_accordion' => array( 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Style', DH_DOMAIN ), 
						'param_name' => 'style', 
						'value' => array( 
							__( 'Default', DH_DOMAIN ) => 'default', 
							__( 'Primary', DH_DOMAIN ) => 'primary', 
							__( 'Success', DH_DOMAIN ) => 'success', 
							__( 'Info', DH_DOMAIN ) => 'info', 
							__( 'Warning', DH_DOMAIN ) => 'warning', 
							__( 'Danger', DH_DOMAIN ) => 'danger', 
							__( 'Outline', DH_DOMAIN ) => 'outline',
							__( 'None', DH_DOMAIN ) => 'none' ), 
						'description' => __( 'Choose accordion stype.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Icon Position', DH_DOMAIN ), 
						'param_name' => 'icon_position', 
						'value' => array( __( 'Left', DH_DOMAIN ) => 'left', __( 'Right', DH_DOMAIN ) => 'right' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Icon Style', DH_DOMAIN ), 
						'param_name' => 'icon_style', 
						'value' => array( 
							__( 'None', DH_DOMAIN ) => 'none', 
							__( 'Circle', DH_DOMAIN ) => 'circle', 
							__( 'Square', DH_DOMAIN ) => 'square', 
							__( 'Square-O', DH_DOMAIN ) => 'square-o' ) ) ), 
				
				'vc_message' => array( 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Show dismiss button', DH_DOMAIN ), 
						'param_name' => 'dismissible', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ) ) ), 
				'dh_quote' => array( 
					array( 
						'type' => 'textarea_html', 
						'holder' => $this->param_holder, 
						'heading' => __( 'Content', DH_DOMAIN ), 
						'param_name' => 'content', 
						'value' => __( '<p>I am blockquote. Click edit button to change this text.</p>', DH_DOMAIN ) ), 
					array( 'type' => 'textfield', 'heading' => __( 'Cite', DH_DOMAIN ), 'param_name' => 'cite' )), 
				'dh_slider' => array(),
				'vc_button' => array( 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Style', DH_DOMAIN ), 
						"param_holder_class" => 'dh-btn-style-select', 
						'param_name' => 'style', 
						'value' => self::$button_styles, 
						'description' => __( 'Button style.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Size', DH_DOMAIN ), 
						'param_name' => 'size', 
						'std' => 'default', 
						'value' => array( 
							__( 'Default', DH_DOMAIN ) => 'default', 
							__( 'Large', DH_DOMAIN ) => 'lg', 
							__( 'Small', DH_DOMAIN ) => 'sm', 
							__( 'Extra small', DH_DOMAIN ) => 'xs', 
							__( 'Custom size', DH_DOMAIN ) => 'custom' ), 
						'description' => __( 'Button size.', DH_DOMAIN ) ), 
					array( 
						'param_name' => 'font_size', 
						'heading' => __( 'Font Size (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '14', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ), 
						'data_max' => '50' ), 
					array( 
						'param_name' => 'border_width', 
						'heading' => __( 'Border Width (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '1', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ), 
						'data_max' => '20' ), 
					array( 
						'param_name' => 'padding_top', 
						'heading' => __( 'Padding Top (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '6', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ), 
						'data_max' => '100' ), 
					array( 
						'param_name' => 'padding_right', 
						'heading' => __( 'Padding Right (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '30', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ), 
						'data_max' => '100' ), 
					array( 
						'param_name' => 'padding_bottom', 
						'heading' => __( 'Padding Bottom (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '6', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ), 
						'data_max' => '100' ), 
					array( 
						'param_name' => 'padding_left', 
						'heading' => __( 'Padding Right (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '30', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ), 
						'data_max' => '100' ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Color', DH_DOMAIN ), 
						'param_name' => 'color', 
						'value' => array( 
							__( 'Default', DH_DOMAIN ) => 'default', 
							__( 'Primary', DH_DOMAIN ) => 'primary', 
							__( 'Success', DH_DOMAIN ) => 'success', 
							__( 'Info', DH_DOMAIN ) => 'info', 
							__( 'Warning', DH_DOMAIN ) => 'warning', 
							__( 'Danger', DH_DOMAIN ) => 'danger', 
							__( 'Outlined White', DH_DOMAIN ) => 'white', 
							__( 'Custom', DH_DOMAIN ) => 'custom' ), 
						'description' => __( 'Button color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Background Color', DH_DOMAIN ), 
						'param_name' => 'background_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select background color for button.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Border Color', DH_DOMAIN ), 
						'param_name' => 'border_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select border color for button.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Text Color', DH_DOMAIN ), 
						'param_name' => 'text_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select text color for button.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Hover Background Color', DH_DOMAIN ), 
						'param_name' => 'hover_background_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select background color for button when hover.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Hover Border Color', DH_DOMAIN ), 
						'param_name' => 'hover_border_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select border color for button when hover.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Hover Text Color', DH_DOMAIN ), 
						'param_name' => 'hover_text_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select text color for button when hover.', DH_DOMAIN ) ), 
					
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Icon', DH_DOMAIN ), 
						'param_name' => 'icon', 
						"param_holder_class" => 'dh-font-awesome-select', 
						"value" => dh_font_awesome_options(), 
						'description' => __( 'Button icon.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Icon Postiton', DH_DOMAIN ), 
						'param_name' => 'icon_position', 
						'dependency' => array( 'element' => "icon", 'not_empty' => true ), 
						'value' => array( __( 'Left', DH_DOMAIN ) => 'left', __( 'Right', DH_DOMAIN ) => 'right' ) ), 
					array( 
						'type' => 'dropdown_group', 
						'heading' => __( 'Effect', DH_DOMAIN ), 
						'param_name' => 'effect', 
						"param_holder_class" => 'dh-btn-effect-select', 
						'value' => '', 
						'dependency' => array( 
							'element' => "color", 
							'value' => array( 'primary', 'success', 'info', 'warning', 'danger', 'white' ) ), 
						'optgroup' => array( 
							__( 'None', DH_DOMAIN ) => '', 
							__( 'Border Transitions', DH_DOMAIN ) => array( 
								__( 'Border Fade', DH_DOMAIN ) => 'border_fade', 
								__( 'Border Hollow', DH_DOMAIN ) => 'border_hollow', 
								__( 'Border Trim', DH_DOMAIN ) => 'border_trim', 
								__( 'Border Outline Outward', DH_DOMAIN ) => 'border_outline_outward', 
								__( 'Border Outline Inward', DH_DOMAIN ) => 'border_outline_inward', 
								__( 'Border Round Corners', DH_DOMAIN ) => 'border_round_corners' ), 
							__( 'Background Transitions', DH_DOMAIN ) => array( 
								__( 'Bg Darken Background', DH_DOMAIN ) => 'bg_darken', 
								__( 'Bg Fade In', DH_DOMAIN ) => 'bg_fade_in', 
								__( 'Bg Fade Out', DH_DOMAIN ) => 'bg_fade_out', 
								__( 'Bg From Top', DH_DOMAIN ) => 'bg_top', 
								__( 'Bg From Right', DH_DOMAIN ) => 'bg_right', 
								__( 'Bg From Center', DH_DOMAIN ) => 'bg_center', 
								__( 'Bg Skew Center', DH_DOMAIN ) => 'bg_skew_center', 
								__( 'Bg Form Horizontal Center', DH_DOMAIN ) => 'bg_horizontal_center' ), 
							__( 'For 3D button', DH_DOMAIN ) => array( __( 'Click state', DH_DOMAIN ) => 'click_state' ), 
							__( 'For Icon Buton', DH_DOMAIN ) => array( 
								__( 'Icon Slide In', DH_DOMAIN ) => 'icon_slide_in' ) ), 
						
						// __('Icon Slide Overlay',DH_DOMAIN)=>'icon_slide_overlay',
						'description' => __( 'Effect when hover button', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Text Uppercase', DH_DOMAIN ), 
						'param_name' => 'text_uppercase', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Text transform to uppercase', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Button Full Width', DH_DOMAIN ), 
						'param_name' => 'block_button', 
						'value' => array( __( 'Yes, please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Button full width of a parent', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Alignment', DH_DOMAIN ), 
						'param_name' => 'alignment', 
						'value' => array( 
							__( 'Left', DH_DOMAIN ) => 'left', 
							__( 'Center', DH_DOMAIN ) => 'center', 
							__( 'Right', DH_DOMAIN ) => 'right' ), 
						'description' => __( 'Button alignment (Not use for Button full width)', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Show Tooltip/Popover', DH_DOMAIN ), 
						'param_name' => 'tooltip', 
						'value' => array( 
							__( 'No', DH_DOMAIN ) => '', 
							__( 'Tooltip', DH_DOMAIN ) => 'tooltip', 
							__( 'Popover', DH_DOMAIN ) => 'popover' ), 
						'description' => __( 'Display a tooltip or popover with descriptive text.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Tip position', DH_DOMAIN ), 
						'param_name' => 'tooltip_position', 
						'value' => array( 
							__( 'Top', DH_DOMAIN ) => 'top', 
							__( 'Bottom', DH_DOMAIN ) => 'bottom', 
							__( 'Left', DH_DOMAIN ) => 'left', 
							__( 'Right', DH_DOMAIN ) => 'right' ), 
						'dependency' => array( 'element' => "tooltip", 'value' => array( 'tooltip', 'popover' ) ), 
						'description' => __( 'Choose the display position.', DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Popover Title', DH_DOMAIN ), 
						'param_name' => 'tooltip_title', 
						'dependency' => array( 'element' => "tooltip", 'value' => array( 'popover' ) ) ), 
					array( 
						'type' => 'textarea', 
						'heading' => __( 'Tip/Popover Content', DH_DOMAIN ), 
						'param_name' => 'tooltip_content', 
						'dependency' => array( 'element' => "tooltip", 'value' => array( 'tooltip', 'popover' ) ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Tip/Popover trigger', DH_DOMAIN ), 
						'param_name' => 'tooltip_trigger', 
						'value' => array( __( 'Hover', DH_DOMAIN ) => 'hover', __( 'Click', DH_DOMAIN ) => 'click' ), 
						'dependency' => array( 'element' => "tooltip", 'value' => array( 'tooltip', 'popover' ) ), 
						'description' => __( 'Choose action to trigger the tooltip.', DH_DOMAIN ) ) ), 
				'vc_cta_button' => array( 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Heading', DH_DOMAIN ), 
						'admin_label' => true, 
						'param_name' => 'heading', 
						'value' => __( 'Hey! I am first heading line feel free to change me', DH_DOMAIN ), 
						'description' => __( 'Text for the first heading line.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Heading Color', DH_DOMAIN ), 
						'param_name' => 'heading_color', 
						'description' => __( 'Custom the heading color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Heading second', DH_DOMAIN ), 
						'param_name' => 'second_heading', 
						'value' => '', 
						'description' => __( 'Optional text for the second heading line.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Heading Second Color', DH_DOMAIN ), 
						'param_name' => 'heading_second_color', 
						'description' => __( 'Custom the second heading color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'textarea_html', 
						'holder' => $this->param_holder, 
						'heading' => __( 'Promotional text', DH_DOMAIN ), 
						'param_name' => 'content', 
						'value' => __( 
							'I am promo text. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 
							DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'CTA style', DH_DOMAIN ), 
						'param_name' => 'style', 
						'value' => self::$cta_styles, 
						'description' => __( 'Call to action style.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Text align', DH_DOMAIN ), 
						'param_name' => 'txt_align', 
						'std' => 'center', 
						'value' => getVcShared( 'text align' ), 
						'description' => __( 'Text align in call to action block.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Background Color', DH_DOMAIN ), 
						'param_name' => 'background_color', 
						'description' => __( 'Select background color for your element.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Border Color', DH_DOMAIN ), 
						'param_name' => 'border_color', 
						'description' => __( 'Select border color for your element.', DH_DOMAIN ) ), 
					array( 
						'type' => 'href', 
						'heading' => __( 'URL (Link)', DH_DOMAIN ), 
						'param_name' => 'href', 
						'description' => __( 'Button link.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Target', DH_DOMAIN ), 
						'param_name' => 'target', 
						'value' => array( 
							__( 'Same window', DH_DOMAIN ) => '_self', 
							__( 'New window', DH_DOMAIN ) => "_blank" ), 
						'dependency' => array( 
							'element' => 'href', 
							'not_empty' => true, 
							'callback' => 'vc_button_param_target_callback' ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Text on the button', DH_DOMAIN ), 
						'param_name' => 'btn_title', 
						'value' => __( 'Click', DH_DOMAIN ), 
						'description' => __( 'Text on the button.', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Button Text Uppercase', DH_DOMAIN ), 
						'param_name' => 'btn_text_uppercase', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Text transform to uppercase', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Button Style', DH_DOMAIN ), 
						"param_holder_class" => 'dh-btn-style-select', 
						'param_name' => 'btn_style', 
						'value' => self::$button_styles, 
						'description' => __( 'Button style.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Button Size', DH_DOMAIN ), 
						'param_name' => 'btn_size', 
						'std' => 'default', 
						'value' => array( 
							__( 'Default', DH_DOMAIN ) => 'default', 
							__( 'Large', DH_DOMAIN ) => 'lg', 
							__( 'Small', DH_DOMAIN ) => 'sm', 
							__( 'Extra small', DH_DOMAIN ) => 'xs', 
							__( 'Custom size', DH_DOMAIN ) => 'custom' ), 
						'description' => __( 'Button size.', DH_DOMAIN ) ), 
					array( 
						'param_name' => 'btn_font_size', 
						'heading' => __( 'Button Font Size (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '14', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "btn_size", 'value' => array( 'custom' ) ), 
						'data_max' => '50' ), 
					array( 
						'param_name' => 'btn_border_width', 
						'heading' => __( 'Button Border Width (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '1', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "btn_size", 'value' => array( 'custom' ) ), 
						'data_max' => '20' ), 
					array( 
						'param_name' => 'btn_padding_top', 
						'heading' => __( 'Button Padding Top (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '6', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "btn_size", 'value' => array( 'custom' ) ), 
						'data_max' => '100' ), 
					array( 
						'param_name' => 'btn_padding_right', 
						'heading' => __( 'Button Padding Right (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '30', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "btn_size", 'value' => array( 'custom' ) ), 
						'data_max' => '100' ), 
					array( 
						'param_name' => 'btn_padding_bottom', 
						'heading' => __( 'Button Padding Bottom (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '6', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "btn_size", 'value' => array( 'custom' ) ), 
						'data_max' => '100' ), 
					array( 
						'param_name' => 'btn_padding_left', 
						'heading' => __( 'Button Padding Right (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '30', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "btn_size", 'value' => array( 'custom' ) ), 
						'data_max' => '100' ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Button Color', DH_DOMAIN ), 
						'param_name' => 'btn_color', 
						'value' => array( 
							__( 'Default', DH_DOMAIN ) => 'default', 
							__( 'Primary', DH_DOMAIN ) => 'primary', 
							__( 'Success', DH_DOMAIN ) => 'success', 
							__( 'Info', DH_DOMAIN ) => 'info', 
							__( 'Warning', DH_DOMAIN ) => 'warning', 
							__( 'Danger', DH_DOMAIN ) => 'danger', 
							__( 'Outlined White', DH_DOMAIN ) => 'white', 
							__( 'Custom', DH_DOMAIN ) => 'custom' ), 
						'description' => __( 'Button color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Button Background Color', DH_DOMAIN ), 
						'param_name' => 'btn_background_color', 
						'dependency' => array( 'element' => "btn_color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select background color for button.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Button Border Color', DH_DOMAIN ), 
						'param_name' => 'btn_border_color', 
						'dependency' => array( 'element' => "btn_color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select border color for button.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Button Text Color', DH_DOMAIN ), 
						'param_name' => 'btn_text_color', 
						'dependency' => array( 'element' => "btn_color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select text color for button.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Hover Background Color', DH_DOMAIN ), 
						'param_name' => 'btn_hover_background_color', 
						'dependency' => array( 'element' => "btn_color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select background color for button when hover.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Hover Border Color', DH_DOMAIN ), 
						'param_name' => 'btn_hover_border_color', 
						'dependency' => array( 'element' => "btn_color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select border color for button when hover.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Hover Text Color', DH_DOMAIN ), 
						'param_name' => 'btn_hover_text_color', 
						'dependency' => array( 'element' => "btn_color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select text color for button when hover.', DH_DOMAIN ) ), 
					
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Icon', DH_DOMAIN ), 
						'param_name' => 'btn_icon', 
						"param_holder_class" => 'dh-font-awesome-select', 
						"value" => dh_font_awesome_options(), 
						'description' => __( 'Button icon.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Button Icon Postiton', DH_DOMAIN ), 
						'param_name' => 'btn_icon_position', 
						'dependency' => array( 'element' => "btn_icon", 'not_empty' => true ), 
						'value' => array( __( 'Left', DH_DOMAIN ) => 'left', __( 'Right', DH_DOMAIN ) => 'right' ) ), 
					array( 
						'type' => 'dropdown_group', 
						'heading' => __( 'Button Effect', DH_DOMAIN ), 
						'param_name' => 'btn_effect', 
						"param_holder_class" => 'dh-btn-effect-select', 
						'value' => '', 
						'dependency' => array( 
							'element' => "btn_color", 
							'value' => array( 'primary', 'success', 'info', 'warning', 'danger', 'white' ) ), 
						'optgroup' => array( 
							__( 'None', DH_DOMAIN ) => '', 
							__( 'Border Transitions', DH_DOMAIN ) => array( 
								__( 'Border Fade', DH_DOMAIN ) => 'border_fade', 
								__( 'Border Hollow', DH_DOMAIN ) => 'border_hollow', 
								__( 'Border Trim', DH_DOMAIN ) => 'border_trim', 
								__( 'Border Outline Outward', DH_DOMAIN ) => 'border_outline_outward', 
								__( 'Border Outline Inward', DH_DOMAIN ) => 'border_outline_inward', 
								__( 'Border Round Corners', DH_DOMAIN ) => 'border_round_corners' ), 
							__( 'Background Transitions', DH_DOMAIN ) => array( 
								__( 'Bg Darken Background', DH_DOMAIN ) => 'bg_darken', 
								__( 'Bg Fade In', DH_DOMAIN ) => 'bg_fade_in', 
								__( 'Bg Fade Out', DH_DOMAIN ) => 'bg_fade_out', 
								__( 'Bg From Top', DH_DOMAIN ) => 'bg_top', 
								__( 'Bg From Right', DH_DOMAIN ) => 'bg_right', 
								__( 'Bg From Center', DH_DOMAIN ) => 'bg_center', 
								__( 'Bg Skew Center', DH_DOMAIN ) => 'bg_skew_center', 
								__( 'Bg Form Horizontal Center', DH_DOMAIN ) => 'bg_horizontal_center' ), 
							__( 'For 3D button', DH_DOMAIN ) => array( __( 'Click state', DH_DOMAIN ) => 'click_state' ), 
							__( 'For Icon Buton', DH_DOMAIN ) => array( 
								__( 'Icon Slide In', DH_DOMAIN ) => 'icon_slide_in' ) ), 
						
						// __('Icon Slide Overlay',DH_DOMAIN)=>'icon_slide_overlay',
						'description' => __( 'Effect when hover button', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Button position', DH_DOMAIN ), 
						'param_name' => 'buton_position', 
						'std' => 'bottom', 
						'value' => array( 
							__( 'Align right', DH_DOMAIN ) => 'right', 
							__( 'Align left', DH_DOMAIN ) => 'left', 
							__( 'Align bottom', DH_DOMAIN ) => 'bottom' ), 
						'description' => __( 'Select button postion.', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Button Full Width', DH_DOMAIN ), 
						'param_name' => 'block_button', 
						'dependency' => array( 'element' => "buton_position", 'value' => array( 'bottom' ) ), 
						'value' => array( __( 'Yes, please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Button full width of a parent', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Show Tooltip/Popover', DH_DOMAIN ), 
						'param_name' => 'tooltip', 
						'value' => array( 
							__( 'No', DH_DOMAIN ) => '', 
							__( 'Tooltip', DH_DOMAIN ) => 'tooltip', 
							__( 'Popover', DH_DOMAIN ) => 'popover' ), 
						'description' => __( 'Display a tooltip or popover with descriptive text.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Tip position', DH_DOMAIN ), 
						'param_name' => 'tooltip_position', 
						'value' => array( 
							__( 'Top', DH_DOMAIN ) => 'top', 
							__( 'Bottom', DH_DOMAIN ) => 'bottom', 
							__( 'Left', DH_DOMAIN ) => 'left', 
							__( 'Right', DH_DOMAIN ) => 'right' ), 
						'dependency' => array( 'element' => "tooltip", 'value' => array( 'tooltip', 'popover' ) ), 
						'description' => __( 'Choose the display position.', DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Popover Title', DH_DOMAIN ), 
						'param_name' => 'tooltip_title', 
						'dependency' => array( 'element' => "tooltip", 'value' => array( 'popover' ) ) ), 
					array( 
						'type' => 'textarea', 
						'heading' => __( 'Tip/Popover Content', DH_DOMAIN ), 
						'param_name' => 'tooltip_content', 
						'dependency' => array( 'element' => "tooltip", 'value' => array( 'tooltip', 'popover' ) ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Tip/Popover trigger', DH_DOMAIN ), 
						'param_name' => 'tooltip_trigger', 
						'value' => array( __( 'Hover', DH_DOMAIN ) => 'hover', __( 'Click', DH_DOMAIN ) => 'click' ), 
						'dependency' => array( 'element' => "tooltip", 'value' => array( 'tooltip', 'popover' ) ), 
						'description' => __( 'Choose action to trigger the tooltip.', DH_DOMAIN ) ) ), 
				'dh_carousel' => array( 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Carousel Title', DH_DOMAIN ), 
						'param_name' => 'title', 
						'description' => __( 
							'Enter text which will be used as widget title. Leave blank if no title is needed.', 
							DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Transition', DH_DOMAIN ), 
						'param_name' => 'fx', 
						'std' => 'scroll', 
						'value' => array( 
							'Scroll' => 'scroll', 
							'Directscroll' => 'directscroll', 
							'Fade' => 'fade', 
							'Cross fade' => 'crossfade', 
							'Cover' => 'cover', 
							'Cover fade' => 'cover-fade', 
							'Uncover' => 'cover-fade', 
							'Uncover fade' => 'uncover-fade' ), 
						'description' => __( 'Indicates which effect to use for the transition.', DH_DOMAIN ) ), 
					array( 
						'param_name' => 'visible', 
						'heading' => __( 'The number of visible items', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '1', 
						'data_min' => '1', 
						'data_max' => '6' ), 
					
					// array(
					// 'param_name' => 'scroll_item',
					// 'heading' => __( 'The number of items to scroll', DH_DOMAIN ),
					// 'type' => 'ui_slider',
					// 'holder' => $this->param_holder,
					// 'value' => '1',
					// 'data_min' => '1',
					// 'data_max' => '6',
					// ),
					array( 
						'param_name' => 'scroll_speed', 
						'heading' => __( 'Transition Scroll Speed (ms)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '700', 
						'data_min' => '100', 
						'data_step' => '100', 
						'data_max' => '3000' ), 
					
					array( 
						"type" => "dropdown", 
						"holder" => $this->param_holder, 
						"heading" => __( "Easing", DH_DOMAIN ), 
						"param_name" => "easing", 
						"value" => array( 
							'linear' => 'linear', 
							'swing' => 'swing', 
							'easeInQuad' => 'easeInQuad', 
							'easeOutQuad' => 'easeOutQuad', 
							'easeInOutQuad' => 'easeInOutQuad', 
							'easeInCubic' => 'easeInCubic', 
							'easeOutCubic' => 'easeOutCubic', 
							'easeInOutCubic' => 'easeInOutCubic', 
							'easeInQuart' => 'easeInQuart', 
							'easeOutQuart' => 'easeOutQuart', 
							'easeInOutQuart' => 'easeInOutQuart', 
							'easeInQuint' => 'easeInQuint', 
							'easeOutQuint' => 'easeOutQuint', 
							'easeInOutQuint' => 'easeInOutQuint', 
							'easeInExpo' => 'easeInExpo', 
							'easeOutExpo' => 'easeOutExpo', 
							'easeInOutExpo' => 'easeInOutExpo', 
							'easeInSine' => 'easeInSine', 
							'easeOutSine' => 'easeOutSine', 
							'easeInOutSine' => 'easeInOutSine', 
							'easeInCirc' => 'easeInCirc', 
							'easeOutCirc' => 'easeOutCirc', 
							'easeInOutCirc' => 'easeInOutCirc', 
							'easeInElastic' => 'easeInElastic', 
							'easeOutElastic' => 'easeOutElastic', 
							'easeInOutElastic' => 'easeInOutElastic', 
							'easeInBack' => 'easeInBack', 
							'easeOutBack' => 'easeOutBack', 
							'easeInOutBack' => 'easeInOutBack', 
							'easeInBounce' => 'easeInBounce', 
							'easeOutBounce' => 'easeOutBounce', 
							'easeInOutBounce' => 'easeInOutBounce' ), 
						"description" => __( 
							"Select the animation easing you would like for slide transitions <a href=\"http://jqueryui.com/resources/demos/effect/easing.html\" target=\"_blank\"> Click here </a> to see examples of these.", 
							DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Autoplay ?', DH_DOMAIN ), 
						'param_name' => 'auto_play', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Slide Pagination ?', DH_DOMAIN ), 
						'param_name' => 'hide_pagination', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Previous/Next Control ?', DH_DOMAIN ), 
						'param_name' => 'hide_control', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ) ) ), 
				
				'dh_carousel_item' => array( 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Title', DH_DOMAIN ), 
						'param_name' => 'title', 
						'description' => __( 'Item title.', DH_DOMAIN ) ) ), 
				'dh_testimonial' => array( 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Background Transparent?', DH_DOMAIN ), 
						'param_name' => 'background_transparent', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Color', DH_DOMAIN ), 
						'param_name' => 'color', 
						'description' => __( 'Custom color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Transition', DH_DOMAIN ), 
						'param_name' => 'fx', 
						'std' => 'scroll', 
						'value' => array( 
							'Scroll' => 'scroll', 
							'Directscroll' => 'directscroll', 
							'Fade' => 'fade', 
							'Cross fade' => 'crossfade', 
							'Cover' => 'cover', 
							'Cover fade' => 'cover-fade', 
							'Uncover' => 'cover-fade', 
							'Uncover fade' => 'uncover-fade' ), 
						'description' => __( 'Indicates which effect to use for the transition.', DH_DOMAIN ) ) ), 
				'dh_testimonial_item' => array( 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Title', DH_DOMAIN ), 
						'param_name' => 'title', 
						'description' => __( 'Item title.', DH_DOMAIN ) ), 
					array( 
						'type' => 'textarea_safe', 
						'holder' => 'div', 
						'heading' => __( 'Text', DH_DOMAIN ), 
						'param_name' => 'text', 
						'value' => __( 
							'I am testimonial. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 
							DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Author', DH_DOMAIN ), 
						'param_name' => 'author', 
						'description' => __( 'Testimonial author.', DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Company', DH_DOMAIN ), 
						'param_name' => 'company', 
						'description' => __( 'Author company.', DH_DOMAIN ) ), 
					array( 
						'type' => 'attach_image', 
						'heading' => __( 'Avatar', DH_DOMAIN ), 
						'param_name' => 'avatar', 
						'description' => __( 'Avatar author.', DH_DOMAIN ) ) ), 
				'dh_client' => array( 
					array( 
						'type' => 'attach_images', 
						'heading' => __( 'Images', DH_DOMAIN ), 
						'param_name' => 'images', 
						'value' => '', 
						'description' => __( 'Select images from media library.', DH_DOMAIN ) ), 
					array( 
						'type' => 'exploded_textarea', 
						'heading' => __( 'Custom links', DH_DOMAIN ), 
						'param_name' => 'custom_links', 
						'description' => __( 
							'Enter links for each image here. Divide links with linebreaks (Enter) . ', 
							DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Display type', DH_DOMAIN ), 
						'param_name' => 'display', 
						'value' => array( 
							__( 'Slider', DH_DOMAIN ) => 'slider', 
							__( 'Image grid', DH_DOMAIN ) => 'grid' ), 
						'description' => __( 'Select display type.', DH_DOMAIN ) ), 
					array( 
						'param_name' => 'visible', 
						'heading' => __( 'The number of visible items on a slide or on a grid row', DH_DOMAIN ), 
						'type' => 'dropdown', 
						'holder' => $this->param_holder, 
						'value' => array( 2, 3, 4, 6 ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Image style', DH_DOMAIN ), 
						'param_name' => 'style', 
						'value' => array( 
							__( 'Normal', DH_DOMAIN ) => 'normal', 
							__( 'Grayscale and Color on hover', DH_DOMAIN ) => 'grayscale' ), 
						'description' => __( 'Select image style.', DH_DOMAIN ) ) ), 
				'dh_counter' => array( 
					array( 
						'param_name' => 'speed', 
						'heading' => __( 'Counter Speed', DH_DOMAIN ), 
						'type' => 'textfield', 
						'holder' => $this->param_holder, 
						'value' => '2000' ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Number', DH_DOMAIN ), 
						'param_name' => 'number', 
						'description' => __( 'Enter the number.', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Format number displayed ?', DH_DOMAIN ), 
						'dependency' => array( 'element' => "number", 'not_empty' => true ), 
						'param_name' => 'format', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Thousand Separator', DH_DOMAIN ), 
						'param_name' => 'thousand_sep', 
						'dependency' => array( 'element' => "format", 'not_empty' => true ), 
						'value' => ',', 
						'description' => __( 'This sets the thousand separator of displayed number.', DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Decimal Separator', DH_DOMAIN ), 
						'param_name' => 'decimal_sep', 
						'dependency' => array( 'element' => "format", 'not_empty' => true ), 
						'value' => '.', 
						'description' => __( 'This sets the decimal separator of displayed number.', DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Number of Decimals', DH_DOMAIN ), 
						'param_name' => 'num_decimals', 
						'dependency' => array( 'element' => "format", 'not_empty' => true ), 
						'value' => 0, 
						'description' => __( 
							'This sets the number of decimal points shown in displayed number.', 
							DH_DOMAIN ) ), 
					
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Number Color', DH_DOMAIN ), 
						'param_name' => 'number_color', 
						'dependency' => array( 'element' => "number", 'not_empty' => true ), 
						'description' => __( 'Select color for number.', DH_DOMAIN ) ), 
					array( 
						'param_name' => 'number_font_size', 
						'heading' => __( 'Custom Number Font Size (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '40', 
						'data_min' => '10', 
						'dependency' => array( 'element' => "number", 'not_empty' => true ), 
						'data_max' => '120' ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Units', DH_DOMAIN ), 
						'param_name' => 'units', 
						'description' => __( 
							'Enter measurement units (if needed) Eg. %, px, points, etc. Graph value and unit will be appended to the graph title.', 
							DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Units Color', DH_DOMAIN ), 
						'param_name' => 'units_color', 
						'dependency' => array( 'element' => "units", 'not_empty' => true ), 
						'description' => __( 'Select color for number.', DH_DOMAIN ) ), 
					array( 
						'param_name' => 'units_font_size', 
						'heading' => __( 'Custom Units Font Size (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '30', 
						'data_min' => '10', 
						'dependency' => array( 'element' => "units", 'not_empty' => true ), 
						'data_max' => '120' ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Icon', DH_DOMAIN ), 
						'param_name' => 'icon', 
						"param_holder_class" => 'dh-font-awesome-select', 
						"value" => dh_font_awesome_options(), 
						'description' => __( 'Button icon.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Icon Color', DH_DOMAIN ), 
						'param_name' => 'icon_color', 
						'dependency' => array( 'element' => "icon", 'not_empty' => true ), 
						'description' => __( 'Select color for icon.', DH_DOMAIN ) ), 
					array( 
						'param_name' => 'icon_font_size', 
						'heading' => __( 'Custom Icon Size (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '40', 
						'data_min' => '10', 
						'dependency' => array( 'element' => "icon", 'not_empty' => true ), 
						'data_max' => '120' ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Icon Postiton', DH_DOMAIN ), 
						'param_name' => 'icon_position', 
						'dependency' => array( 'element' => "icon", 'not_empty' => true ), 
						'value' => array( __( 'Top', DH_DOMAIN ) => 'top', __( 'Left', DH_DOMAIN ) => 'left' ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Title', DH_DOMAIN ), 
						'param_name' => 'text', 
						'admin_label' => true ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Title Color', DH_DOMAIN ), 
						'param_name' => 'text_color', 
						'dependency' => array( 'element' => "text", 'not_empty' => true ), 
						'description' => __( 'Select color for title.', DH_DOMAIN ) ), 
					array( 
						'param_name' => 'text_font_size', 
						'heading' => __( 'Custom Title Font Size (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '18', 
						'data_min' => '10', 
						'dependency' => array( 'element' => "text", 'not_empty' => true ), 
						'data_max' => '120' ) ), 
				
				'vc_gmaps' => array( 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Map Center Point Latitude', DH_DOMAIN ), 
						'param_name' => 'center_latitude', 
						'description' => __( 'Please enter the latitude for the maps center point.', DH_DOMAIN ), 
						'value' => '40.71260803688496' ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Map Center Point Longitude', DH_DOMAIN ), 
						'param_name' => 'center_longitude', 
						'description' => __( 'Please enter the latitude for the maps center point.', DH_DOMAIN ), 
						'value' => '-74.00566577911377' ), 
					array( 
						'param_name' => 'zoom', 
						'heading' => __( 'Map zoom', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '16', 
						'data_min' => '1', 
						'data_max' => '20' ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Eanble Zoom In/Out ?', DH_DOMAIN ), 
						'param_name' => 'eanble_zoom', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ) ), 
					array( 
						'type' => 'attach_image', 
						'heading' => __( 'Marker Image', DH_DOMAIN ), 
						'param_name' => 'images', 
						'value' => '', 
						'description' => __( 'Select images from media library.', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Greyscale Color ?', DH_DOMAIN ), 
						'param_name' => 'greyscale_color', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ) ), 
					array( 
						'type' => 'exploded_textarea', 
						'heading' => __( 'Map Marker Locations', DH_DOMAIN ), 
						'param_name' => 'locations', 
						'description' => __( 'Input locations latitude|longitude|description here. Divide values with line breaks (Enter). Example: -25.28346|133.7660|Our Location', DH_DOMAIN ), 
						'value' => "40.711910818933525|-74.00399208068848|Come visit us at our location!,40.711325280360334|-74.01094436645508|Come visit us at our location 2 !" ) ), 
				
				'dh_iconbox' => array( 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Box Background ?', DH_DOMAIN ), 
						'param_name' => 'box_bg', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Use box style with background color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Icon', DH_DOMAIN ), 
						'param_name' => 'icon', 
						'admin_label' => true, 
						"param_holder_class" => 'dh-font-awesome-select', 
						"value" => dh_font_awesome_options( false ), 
						'description' => __( 'Select a icon.', DH_DOMAIN ) ), 
					array(
						'type' => 'href',
						'heading' => __( 'Icon Link', DH_DOMAIN ),
						'param_name' => 'link',
						'description' => __( 'Enter URL if you want this icon to have a link.', DH_DOMAIN ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Link Type', DH_DOMAIN ),
						'param_name' => 'link_type',
						'std'=>'text_link',
						'value' => array(
							__('With a Text Link',DH_DOMAIN)=>'text_link',
							__('For This Icon link',DH_DOMAIN)=>'icon_link'
						),
						'dependency' => array(
							'element' => 'link',
							'not_empty' => true
						)
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Link Text', DH_DOMAIN ),
						'param_name' => 'link_text',
						'value' => 'See More',
						'dependency' => array(
							'element' => 'link_type',
							'value' => array('text_link')
						)
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Link Target', DH_DOMAIN ),
						'param_name' => 'link_target',
						'value' => array(
							__( 'Same window', DH_DOMAIN ) => '_self',
							__( 'New window', DH_DOMAIN ) => "_blank"
						),
						'dependency' => array(
							'element' => 'link',
							'not_empty' => true
						)
					),
					array( 
						'type' => 'textfield', 
						'holder' => $this->param_holder, 
						'heading' => __( 'Title', DH_DOMAIN ), 
						'admin_label' => true, 
						'param_name' => 'title' ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Title Color', DH_DOMAIN ), 
						'param_name' => 'title_color', 
						'description' => __( 'Custom icon color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'textarea', 
						'holder' => $this->param_holder, 
						'heading' => __( 'Text', DH_DOMAIN ), 
						'param_name' => 'text' ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Text Color', DH_DOMAIN ), 
						'param_name' => 'text_color', 
						'description' => __( 'Custom Text color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Icon Postiton', DH_DOMAIN ), 
						'param_name' => 'icon_position', 
						'dependency' => array( 'element' => "icon", 'not_empty' => true ), 
						'value' => array( 
							__( 'Top', DH_DOMAIN ) => 'top', 
							__( 'Left', DH_DOMAIN ) => 'left', 
							__( 'Right', DH_DOMAIN ) => 'right' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Icon circle ?', DH_DOMAIN ), 
						'param_name' => 'is_circle', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Create icon circle.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Icon Size', DH_DOMAIN ), 
						'param_name' => 'size', 
						'std' => 'default', 
						'value' => array( 
							__( 'Default', DH_DOMAIN ) => 'default', 
							__( 'Large', DH_DOMAIN ) => 'lg', 
							__( 'Small', DH_DOMAIN ) => 'sm', 
							__( 'Custom size', DH_DOMAIN ) => 'custom' ), 
						'description' => __( 'Icon size.', DH_DOMAIN ) ), 
					array( 
						'param_name' => 'icon_size', 
						'heading' => __( 'Icon Box Size (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '50', 
						'data_min' => '1', 
						'data_max' => '500', 
						'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ), 
						'description' => __( 'Custom box icon size.', DH_DOMAIN ) ), 
					array( 
						'param_name' => 'icon_font_size', 
						'heading' => __( 'Icon Font Size (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '30', 
						'data_min' => '1', 
						'data_max' => '100', 
						'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ), 
						'description' => __( 'Custom font icon size.', DH_DOMAIN ) ), 
					array( 
						'param_name' => 'icon_border_width', 
						'heading' => __( 'Icon Border Width (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '1', 
						'data_min' => '1', 
						'data_max' => '20', 
						'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ), 
						'description' => __( 'Custom icon border width.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Icon Color', DH_DOMAIN ), 
						'param_name' => 'color', 
						'value' => array( 
							__( 'Default', DH_DOMAIN ) => 'default', 
							__( 'Primary', DH_DOMAIN ) => 'primary', 
							__( 'Success', DH_DOMAIN ) => 'success', 
							__( 'Info', DH_DOMAIN ) => 'info', 
							__( 'Warning', DH_DOMAIN ) => 'warning', 
							__( 'Danger', DH_DOMAIN ) => 'danger', 
							__( 'Custom', DH_DOMAIN ) => 'custom' ), 
						'description' => __( 'Icon color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Icon Color', DH_DOMAIN ), 
						'param_name' => 'icon_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Custom icon color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Icon Border Color', DH_DOMAIN ), 
						'param_name' => 'icon_border_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Custom icon border color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Icon Background Color', DH_DOMAIN ), 
						'param_name' => 'icon_background_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Custom icon background color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Hover Icon Color', DH_DOMAIN ), 
						'param_name' => 'hover_icon_hover_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Custom hover icon color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Hover Icon Border Color', DH_DOMAIN ), 
						'param_name' => 'hover_icon_border_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Custom hover icon border color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Hover Icon Background Color', DH_DOMAIN ), 
						'param_name' => 'hover_icon_background_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Custom hover icon background color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Hover effect', DH_DOMAIN ), 
						'param_name' => 'effect', 
						"param_holder_class" => 'dh-chosen-select', 
						'dependency' => array( 
							'element' => "color", 
							'value' => array( 'default', 'primary', 'success', 'info', 'warning', 'danger' ) ), 
						'value' => array( 
							__( 'None', DH_DOMAIN ) => '', 
							__( 'Effect 1', DH_DOMAIN ) => 'effect-1', 
							__( 'Effect 2', DH_DOMAIN ) => 'effect-2', 
							__( 'Effect 3', DH_DOMAIN ) => 'effect-3', 
							__( 'Effect 4', DH_DOMAIN ) => 'effect-4' ), 
						'description' => __( 'Hover icon effect.', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Disable Appear Animation', DH_DOMAIN ), 
						'param_name' => 'disable_appear_animate', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Disable animation when appear', DH_DOMAIN ) ) ), 
				'dh_lists' => array( 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Icon', DH_DOMAIN ), 
						'param_name' => 'icon', 
						'admin_label' => true, 
						"param_holder_class" => 'dh-font-awesome-select', 
						"value" => dh_font_awesome_options( true ), 
						'description' => __( 'Select a icon.', DH_DOMAIN ) ), 
					array( 
						'type' => 'exploded_textarea', 
						'heading' => __( 'List Items', DH_DOMAIN ), 
						'param_name' => 'items', 
						'description' => __( 
							'Input list item in here. Divide values with linebreaks (Enter).', 
							DH_DOMAIN ), 
						'value' => "Lorem ipsum dolor sit amet,Consectetur adipiscing elit" ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Icon Color', DH_DOMAIN ), 
						'param_name' => 'icon_color', 
						'description' => __( 'Custom icon color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Item Color', DH_DOMAIN ), 
						'param_name' => 'item_color', 
						'description' => __( 'Custom item color.', DH_DOMAIN ) ), 
					array( 
						'param_name' => 'text_size', 
						'heading' => __( 'Text font size (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '14', 
						'data_min' => '1', 
						'data_max' => '100', 
						'description' => __( 'Custom item font size.', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Disable Animation', DH_DOMAIN ), 
						'param_name' => 'disable_animation', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Disable animation when appear', DH_DOMAIN ) ) ), 
				'dh_modal' => array( 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Modal title', DH_DOMAIN ), 
						'param_name' => 'title', 
						'value' => 'My Modal', 
						'description' => __( 
							'Enter text which will be used as modal title. Leave blank if no title is needed.', 
							DH_DOMAIN ) ), 
					array( 
						'type' => 'textarea_html', 
						'holder' => 'div', 
						'heading' => __( 'Modal Content', DH_DOMAIN ), 
						'param_name' => 'content', 
						'value' => __( 
							'<p>I am modal. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 
							DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Modal Size', DH_DOMAIN ), 
						'param_name' => 'modal_size', 
						'std' => 'default', 
						'value' => array( 
							__( 'Default', DH_DOMAIN ) => 'default', 
							__( 'Large', DH_DOMAIN ) => 'lg', 
							__( 'Small', DH_DOMAIN ) => 'sm' ), 
						'description' => __( 'Button size.', DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Text on button', DH_DOMAIN ), 
						'param_name' => 'btn_title', 
						'admin_label' => true, 
						'value' => __( 'Open Modal', DH_DOMAIN ), 
						'description' => __( 'Text on the button.', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Button Text Uppercase', DH_DOMAIN ), 
						'param_name' => 'btn_text_uppercase', 
						'value' => array( __( 'Yes,please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Text transform to uppercase', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Button Style', DH_DOMAIN ), 
						"param_holder_class" => 'dh-btn-style-select', 
						'param_name' => 'btn_style', 
						'value' => self::$button_styles, 
						'description' => __( 'Button style.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Button Size', DH_DOMAIN ), 
						'param_name' => 'btn_size', 
						'std' => 'default', 
						'value' => array( 
							__( 'Default', DH_DOMAIN ) => 'default', 
							__( 'Large', DH_DOMAIN ) => 'lg', 
							__( 'Small', DH_DOMAIN ) => 'sm', 
							__( 'Extra small', DH_DOMAIN ) => 'xs', 
							__( 'Custom size', DH_DOMAIN ) => 'custom' ), 
						'description' => __( 'Button size.', DH_DOMAIN ) ), 
					array( 
						'param_name' => 'btn_font_size', 
						'heading' => __( 'Button Font Size (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '14', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "btn_size", 'value' => array( 'custom' ) ), 
						'data_max' => '50' ), 
					array( 
						'param_name' => 'btn_border_width', 
						'heading' => __( 'Button Border Width (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '1', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "btn_size", 'value' => array( 'custom' ) ), 
						'data_max' => '20' ), 
					array( 
						'param_name' => 'btn_padding_top', 
						'heading' => __( 'Button Padding Top (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '6', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "btn_size", 'value' => array( 'custom' ) ), 
						'data_max' => '100' ), 
					array( 
						'param_name' => 'btn_padding_right', 
						'heading' => __( 'Button Padding Right (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '30', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "btn_size", 'value' => array( 'custom' ) ), 
						'data_max' => '100' ), 
					array( 
						'param_name' => 'btn_padding_bottom', 
						'heading' => __( 'Button Padding Bottom (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '6', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "btn_size", 'value' => array( 'custom' ) ), 
						'data_max' => '100' ), 
					array( 
						'param_name' => 'btn_padding_left', 
						'heading' => __( 'Button Padding Right (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '30', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "btn_size", 'value' => array( 'custom' ) ), 
						'data_max' => '100' ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Button Color', DH_DOMAIN ), 
						'param_name' => 'btn_color', 
						'value' => array( 
							__( 'Default', DH_DOMAIN ) => 'default', 
							__( 'Primary', DH_DOMAIN ) => 'primary', 
							__( 'Success', DH_DOMAIN ) => 'success', 
							__( 'Info', DH_DOMAIN ) => 'info', 
							__( 'Warning', DH_DOMAIN ) => 'warning', 
							__( 'Danger', DH_DOMAIN ) => 'danger', 
							__( 'Outlined White', DH_DOMAIN ) => 'white', 
							__( 'Custom', DH_DOMAIN ) => 'custom' ), 
						'description' => __( 'Button color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Button Background Color', DH_DOMAIN ), 
						'param_name' => 'btn_background_color', 
						'dependency' => array( 'element' => "btn_color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select background color for button.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Button Border Color', DH_DOMAIN ), 
						'param_name' => 'btn_border_color', 
						'dependency' => array( 'element' => "btn_color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select border color for button.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Button Text Color', DH_DOMAIN ), 
						'param_name' => 'btn_text_color', 
						'dependency' => array( 'element' => "btn_color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select text color for button.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Hover Background Color', DH_DOMAIN ), 
						'param_name' => 'btn_hover_background_color', 
						'dependency' => array( 'element' => "btn_color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select background color for button when hover.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Hover Border Color', DH_DOMAIN ), 
						'param_name' => 'btn_hover_border_color', 
						'dependency' => array( 'element' => "btn_color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select border color for button when hover.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Hover Text Color', DH_DOMAIN ), 
						'param_name' => 'btn_hover_text_color', 
						'dependency' => array( 'element' => "btn_color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select text color for button when hover.', DH_DOMAIN ) ), 
					
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Icon', DH_DOMAIN ), 
						'param_name' => 'btn_icon', 
						"param_holder_class" => 'dh-font-awesome-select', 
						"value" => dh_font_awesome_options(), 
						'description' => __( 'Button icon.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Button Icon Postiton', DH_DOMAIN ), 
						'param_name' => 'btn_icon_position', 
						'dependency' => array( 'element' => "btn_icon", 'not_empty' => true ), 
						'value' => array( __( 'Left', DH_DOMAIN ) => 'left', __( 'Right', DH_DOMAIN ) => 'right' ) ), 
					array( 
						'type' => 'dropdown_group', 
						'heading' => __( 'Button Effect', DH_DOMAIN ), 
						'param_name' => 'btn_effect', 
						"param_holder_class" => 'dh-btn-effect-select', 
						'value' => '', 
						'dependency' => array( 
							'element' => "btn_color", 
							'value' => array( 'primary', 'success', 'info', 'warning', 'danger', 'white' ) ), 
						'optgroup' => array( 
							__( 'None', DH_DOMAIN ) => '', 
							__( 'Border Transitions', DH_DOMAIN ) => array( 
								__( 'Border Fade', DH_DOMAIN ) => 'border_fade', 
								__( 'Border Hollow', DH_DOMAIN ) => 'border_hollow', 
								__( 'Border Trim', DH_DOMAIN ) => 'border_trim', 
								__( 'Border Outline Outward', DH_DOMAIN ) => 'border_outline_outward', 
								__( 'Border Outline Inward', DH_DOMAIN ) => 'border_outline_inward', 
								__( 'Border Round Corners', DH_DOMAIN ) => 'border_round_corners' ), 
							__( 'Background Transitions', DH_DOMAIN ) => array( 
								__( 'Bg Darken Background', DH_DOMAIN ) => 'bg_darken', 
								__( 'Bg Fade In', DH_DOMAIN ) => 'bg_fade_in', 
								__( 'Bg Fade Out', DH_DOMAIN ) => 'bg_fade_out', 
								__( 'Bg From Top', DH_DOMAIN ) => 'bg_top', 
								__( 'Bg From Right', DH_DOMAIN ) => 'bg_right', 
								__( 'Bg From Center', DH_DOMAIN ) => 'bg_center', 
								__( 'Bg Skew Center', DH_DOMAIN ) => 'bg_skew_center', 
								__( 'Bg Form Horizontal Center', DH_DOMAIN ) => 'bg_horizontal_center' ), 
							__( 'For 3D button', DH_DOMAIN ) => array( __( 'Click state', DH_DOMAIN ) => 'click_state' ), 
							__( 'For Icon Buton', DH_DOMAIN ) => array( 
								__( 'Icon Slide In', DH_DOMAIN ) => 'icon_slide_in' ) ), 
						
						// __('Icon Slide Overlay',DH_DOMAIN)=>'icon_slide_overlay',
						'description' => __( 'Effect when hover button', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Button Full Width', DH_DOMAIN ), 
						'param_name' => 'block_button', 
						'value' => array( __( 'Yes, please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Button full width of a parent', DH_DOMAIN ) ) ), 
				'vc_pie' => array( 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Title Color', DH_DOMAIN ), 
						'param_name' => 'title_color', 
						'description' => __( 'Select title color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Style', DH_DOMAIN ), 
						'param_name' => 'style', 
						'value' => array( 
							__( 'Default', DH_DOMAIN ) => 'default', 
							__( 'Outlined', DH_DOMAIN ) => 'outlined' ) ), 
					array( 
						'param_name' => 'box_size', 
						'heading' => __( 'Box size (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '100', 
						'data_min' => '0', 
						'data_max' => '500' ), 
					array( 
						'param_name' => 'text_size', 
						'heading' => __( 'Label font size (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '18', 
						'data_min' => '0', 
						'data_max' => '300' ), 
					array( 
						'param_name' => 'border_size', 
						'heading' => __( 'Pie width (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '1', 
						'data_min' => '0', 
						'data_max' => '300' ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Color', DH_DOMAIN ), 
						'param_name' => 'color', 
						'value' => array( 
							__( 'Default', DH_DOMAIN ) => 'default', 
							__( 'Primary', DH_DOMAIN ) => 'primary', 
							__( 'Success', DH_DOMAIN ) => 'success', 
							__( 'Info', DH_DOMAIN ) => 'info', 
							__( 'Warning', DH_DOMAIN ) => 'warning', 
							__( 'Danger', DH_DOMAIN ) => 'danger', 
							__( 'Custom', DH_DOMAIN ) => 'custom' ), 
						'description' => __( 'Pie color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Background Color', DH_DOMAIN ), 
						'param_name' => 'background_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select background color for pie chart.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Border Color', DH_DOMAIN ), 
						'param_name' => 'border_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select border color for pie chart.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Label Color', DH_DOMAIN ), 
						'param_name' => 'text_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select text color for pie chart.', DH_DOMAIN ) ) ), 
				'dh_pricing_table' => array(), 
				
				'dh_pricing_table_item' => array( 
					array( 
						"type" => "checkbox", 
						"heading" => __( "Recommend", DH_DOMAIN ), 
						"param_name" => "recommend", 
						"value" => array( __( 'Yes, please', DH_DOMAIN ) => 'yes' ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Title', DH_DOMAIN ), 
						'param_name' => 'title', 
						'description' => __( 'Item title.', DH_DOMAIN ) ), 
					array( 'type' => 'textfield', 'heading' => __( 'Price', DH_DOMAIN ), 'param_name' => 'price' ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Currency Symbol', DH_DOMAIN ), 
						'param_name' => 'currency', 
						'description' => __( 'Enter the currency symbol that will display for your price', DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Units', DH_DOMAIN ), 
						'param_name' => 'units', 
						'description' => __( 'Enter measurement units (if needed) Eg. /month /year etc.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Color', DH_DOMAIN ), 
						'param_name' => 'color', 
						'value' => array( 
							__( 'Default', DH_DOMAIN ) => 'default', 
							__( 'Primary', DH_DOMAIN ) => 'primary', 
							__( 'Success', DH_DOMAIN ) => 'success', 
							__( 'Info', DH_DOMAIN ) => 'info', 
							__( 'Warning', DH_DOMAIN ) => 'warning', 
							__( 'Danger', DH_DOMAIN ) => 'danger', 
							__( 'Custom', DH_DOMAIN ) => 'custom' ), 
						'description' => __( 'color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Title Background Color', DH_DOMAIN ), 
						'param_name' => 'title_background_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Custom background color for title.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Title Color', DH_DOMAIN ), 
						'param_name' => 'title_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Custom color for title.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Price Background Color', DH_DOMAIN ), 
						'param_name' => 'price_background_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Custom background color for price.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Price Color', DH_DOMAIN ), 
						'param_name' => 'price_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Custom color for price.', DH_DOMAIN ) ), 
					array( 
						'type' => 'pricing_table_feature', 
						'heading' => __( 'Features', DH_DOMAIN ), 
						'param_name' => 'features' ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Features Alignment', DH_DOMAIN ), 
						'param_name' => 'features_alignment', 
						'value' => array( 
							__( 'Left', DH_DOMAIN ) => 'left', 
							__( 'Center', DH_DOMAIN ) => 'center', 
							__( 'Right', DH_DOMAIN ) => 'right' ) ), 
					array( 
						'type' => 'href', 
						'heading' => __( 'URL (Link)', DH_DOMAIN ), 
						'param_name' => 'href', 
						'description' => __( 'Button link.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Target', DH_DOMAIN ), 
						'param_name' => 'target', 
						'value' => array( 
							__( 'Same window', DH_DOMAIN ) => '_self', 
							__( 'New window', DH_DOMAIN ) => "_blank" ), 
						'dependency' => array( 
							'element' => 'href', 
							'not_empty' => true, 
							'callback' => 'vc_button_param_target_callback' ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Text on the button', DH_DOMAIN ), 
						'param_name' => 'btn_title', 
						'value' => __( 'Click', DH_DOMAIN ), 
						'description' => __( 'Text on the button.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Button Style', DH_DOMAIN ), 
						"param_holder_class" => 'dh-btn-style-select', 
						'param_name' => 'btn_style', 
						'value' => self::$button_styles, 
						'description' => __( 'Button style.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Button Size', DH_DOMAIN ), 
						'param_name' => 'btn_size', 
						'std' => 'default', 
						'value' => array( 
							__( 'Default', DH_DOMAIN ) => 'default', 
							__( 'Large', DH_DOMAIN ) => 'lg', 
							__( 'Small', DH_DOMAIN ) => 'sm', 
							__( 'Extra small', DH_DOMAIN ) => 'xs', 
							__( 'Custom size', DH_DOMAIN ) => 'custom' ), 
						'description' => __( 'Button size.', DH_DOMAIN ) ), 
					
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Button Icon', DH_DOMAIN ), 
						'param_name' => 'btn_icon', 
						"param_holder_class" => 'dh-font-awesome-select', 
						"value" => dh_font_awesome_options(), 
						'description' => __( 'Button icon.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown_group', 
						'heading' => __( 'Button Effect', DH_DOMAIN ), 
						'param_name' => 'btn_effect', 
						"param_holder_class" => 'dh-btn-effect-select', 
						'value' => '', 
						'dependency' => array( 
							'element' => "color", 
							'value' => array( 'primary', 'success', 'info', 'warning', 'danger' ) ), 
						'optgroup' => array( 
							__( 'None', DH_DOMAIN ) => '', 
							__( 'Border Transitions', DH_DOMAIN ) => array( 
								__( 'Border Fade', DH_DOMAIN ) => 'border_fade', 
								__( 'Border Hollow', DH_DOMAIN ) => 'border_hollow', 
								__( 'Border Trim', DH_DOMAIN ) => 'border_trim', 
								__( 'Border Outline Outward', DH_DOMAIN ) => 'border_outline_outward', 
								__( 'Border Outline Inward', DH_DOMAIN ) => 'border_outline_inward', 
								__( 'Border Round Corners', DH_DOMAIN ) => 'border_round_corners' ), 
							__( 'Background Transitions', DH_DOMAIN ) => array( 
								__( 'Bg Darken Background', DH_DOMAIN ) => 'bg_darken', 
								__( 'Bg Fade In', DH_DOMAIN ) => 'bg_fade_in', 
								__( 'Bg Fade Out', DH_DOMAIN ) => 'bg_fade_out', 
								__( 'Bg From Top', DH_DOMAIN ) => 'bg_top', 
								__( 'Bg From Right', DH_DOMAIN ) => 'bg_right', 
								__( 'Bg From Center', DH_DOMAIN ) => 'bg_center', 
								__( 'Bg Skew Center', DH_DOMAIN ) => 'bg_skew_center', 
								__( 'Bg Form Horizontal Center', DH_DOMAIN ) => 'bg_horizontal_center' ), 
							__( 'For 3D button', DH_DOMAIN ) => array( __( 'Click state', DH_DOMAIN ) => 'click_state' ), 
							__( 'For Icon Buton', DH_DOMAIN ) => array( 
								__( 'Icon Slide In', DH_DOMAIN ) => 'icon_slide_in' ) ), 
						
						// __('Icon Slide Overlay',DH_DOMAIN)=>'icon_slide_overlay',
						'description' => __( 'Effect when hover button', DH_DOMAIN ) ) ), 
				'vc_video' => array( 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Type', DH_DOMAIN ), 
						'param_name' => 'type', 
						'value' => array( __( 'Link', DH_DOMAIN ) => 'link', __( 'Self Hosted', DH_DOMAIN ) => 'hosted' ), 
						'admin_label' => true, 
						'description' => __( 'Pie color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Video link', DH_DOMAIN ), 
						'param_name' => 'link', 
						'dependency' => array( 'element' => "type", 'value' => array( 'link' ) ), 
						'description' => sprintf( 
							__( 'Link to the video. More about supported formats at %s.', DH_DOMAIN ), 
							'<a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">WordPress codex page</a>' ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'MP4 File URL', DH_DOMAIN ), 
						'param_name' => 'mp4', 
						'dependency' => array( 'element' => "type", 'value' => array( 'hosted' ) ), 
						'description' => __( 'Please enter in the URL to the .m4v video file.', DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'OGV/OGG File URL', DH_DOMAIN ), 
						'param_name' => 'ogv', 
						'dependency' => array( 'element' => "type", 'value' => array( 'hosted' ) ), 
						'description' => __( 'Please enter in the URL to the .ogv or .ogg video file.', DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'WEBM File URL', DH_DOMAIN ), 
						'param_name' => 'webm', 
						'dependency' => array( 'element' => "type", 'value' => array( 'hosted' ) ), 
						'description' => __( 'Please enter in the URL to the .webm video file.', DH_DOMAIN ) ), 
					array( 
						'type' => 'attach_image', 
						'heading' => __( 'Preview Image', DH_DOMAIN ), 
						'param_name' => 'preview', 
						'dependency' => array( 'element' => "type", 'value' => array( 'hosted' ) ), 
						'description' => __( 'Image should be at least 680px wide.', DH_DOMAIN ) ) ), 
				'vc_tabs' => array( 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Color', DH_DOMAIN ), 
						'param_name' => 'color', 
						'value' => array( 
							__( 'Default', DH_DOMAIN ) => 'default', 
							__( 'Primary', DH_DOMAIN ) => 'primary', 
							__( 'Success', DH_DOMAIN ) => 'success', 
							__( 'Info', DH_DOMAIN ) => 'info', 
							__( 'Warning', DH_DOMAIN ) => 'warning', 
							__( 'Danger', DH_DOMAIN ) => 'danger', 
							__( 'Custom', DH_DOMAIN ) => 'custom' ), 
						'description' => __( 'Tab color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Tab Control', DH_DOMAIN ), 
						'param_name' => 'control_position', 
						'value' => array( 
							__( 'Top', DH_DOMAIN ) => 'top', 
							__( 'Left', DH_DOMAIN ) => 'left', 
							__( 'Right', DH_DOMAIN ) => 'right', 
							__( 'Below', DH_DOMAIN ) => 'below',
						), 
						'description' => __( 'Tab Control.', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Control center', DH_DOMAIN ), 
						'param_name' => 'control_center', 
						'dependency' => array( 'element' => "control_position", 'value' => array( 'top', 'below' ) ), 
						'value' => array( __( 'Yes, please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Set tabs control horizontal center.', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Control full Width', DH_DOMAIN ), 
						'param_name' => 'control_fullwith', 
						'dependency' => array( 'element' => "control_position", 'value' => array( 'top', 'below' ) ), 
						'value' => array( __( 'Yes, please', DH_DOMAIN ) => 'yes' ), 
						'description' => __( 'Set full Width tabs control.', DH_DOMAIN ) ) ), 
				'vc_tab' => array( 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Title', DH_DOMAIN ), 
						'param_name' => 'title', 
						'description' => __( 'Tab title.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Icon', DH_DOMAIN ), 
						'param_name' => 'icon', 
						"param_holder_class" => 'dh-font-awesome-select', 
						"value" => dh_font_awesome_options(), 
						'description' => __( 'Title icon.', DH_DOMAIN ) ) ), 
				'dh_timeline' => array( 
					array( 
						'type' => 'dropdown', 
						'param_name' => 'type', 
						'heading' => __( 'Badge Type', DH_DOMAIN ), 
						'holder' => $this->param_holder, 
						'std' => 'none', 
						'value' => array( 
							__( 'None', DH_DOMAIN ) => 'none', 
							__( 'Icon', DH_DOMAIN ) => 'icon', 
							__( 'Image', DH_DOMAIN ) => 'image', 
							__( 'Text', DH_DOMAIN ) => 'text' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Columns', DH_DOMAIN ), 
						'param_name' => 'columns', 
						'value' => array( __( 'One', DH_DOMAIN ) => 'one', __( 'Two', DH_DOMAIN ) => 'two' ), 
						'description' => __( 'Select timeline columns.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Columns Align', DH_DOMAIN ), 
						'param_name' => 'columns_align', 
						'dependency' => array( 'element' => "columns", 'value' => array( 'one' ) ), 
						'value' => array( __( 'Left', DH_DOMAIN ) => 'left', __( 'Right', DH_DOMAIN ) => 'right' ), 
						'description' => __( 'Select timeline columns align.', DH_DOMAIN ) ), 
					
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Stype', DH_DOMAIN ), 
						'param_name' => 'stype', 
						'dependency' => array( 'element' => "type", 'value' => array( 'icon', 'image', 'text' ) ), 
						'value' => array( __( 'Solid', DH_DOMAIN ) => 'solid', __( 'Dotted', DH_DOMAIN ) => 'dotted' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Color', DH_DOMAIN ), 
						'param_name' => 'color', 
						'value' => array( 
							__( 'Default', DH_DOMAIN ) => 'default', 
							__( 'Primary', DH_DOMAIN ) => 'primary', 
							__( 'Success', DH_DOMAIN ) => 'success', 
							__( 'Info', DH_DOMAIN ) => 'info', 
							__( 'Warning', DH_DOMAIN ) => 'warning', 
							__( 'Danger', DH_DOMAIN ) => 'danger', 
							__( 'Custom', DH_DOMAIN ) => 'custom' ), 
						'description' => __( 'Timeline color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Line Color', DH_DOMAIN ), 
						'param_name' => 'line_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Custom color for line.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Badge Color', DH_DOMAIN ), 
						'param_name' => 'badge_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Custom color for badge.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Badge Border Color', DH_DOMAIN ), 
						'param_name' => 'badge_border_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Custom border color for badge.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Badge Background Color', DH_DOMAIN ), 
						'param_name' => 'badge_background_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Custom background color for badge.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Content Color', DH_DOMAIN ), 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'param_name' => 'content_color', 
						'description' => __( 'Custom color for content.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Content Border Color', DH_DOMAIN ), 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'param_name' => 'content_border_color', 
						'description' => __( 'Custom border color for content.', DH_DOMAIN ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Content Background Color', DH_DOMAIN ), 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'param_name' => 'content_background_color', 
						'description' => __( 'Custom background color for content.', DH_DOMAIN ) ) ), 
				'dh_timeline_item' => array( 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Title', DH_DOMAIN ), 
						'param_name' => 'title', 
						'description' => __( 'Item title.', DH_DOMAIN ) ), 
					array( 
						'type' => 'dropdown', 
						'param_name' => 'badge_type', 
						"param_holder_class" => 'timeline-item-badge-type-select', 
						'heading' => __( 'Badge Type', DH_DOMAIN ), 
						'holder' => $this->param_holder, 
						'value' => array( 
							__( 'None', DH_DOMAIN ) => 'none', 
							__( 'Icon', DH_DOMAIN ) => 'icon', 
							__( 'Image', DH_DOMAIN ) => 'image', 
							__( 'Text', DH_DOMAIN ) => 'text' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Icon', DH_DOMAIN ), 
						'param_name' => 'badge_icon', 
						"param_holder_class" => 'dh-font-awesome-select', 
						"value" => dh_font_awesome_options( false ), 
						'dependency' => array( 'element' => "badge_type", 'value' => array( 'icon' ) ), 
						'description' => __( 'Select a icon.', DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Badge Text', DH_DOMAIN ), 
						'param_name' => 'badge_text', 
						'dependency' => array( 'element' => "badge_type", 'value' => array( 'text' ) ) ), 
					array( 
						'type' => 'attach_image', 
						'heading' => __( 'Badge Image', DH_DOMAIN ), 
						'param_name' => 'badge_image', 
						'dependency' => array( 'element' => "badge_type", 'value' => array( 'image' ) ), 
						"description" => __( "Select image from media library.", DH_DOMAIN ) ), 
					array( 
						'type' => 'textarea_html', 
						'holder' => 'div', 
						'heading' => __( 'Content', DH_DOMAIN ), 
						'param_name' => 'content', 
						'value' => __( 
							'<p>I am timeline. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 
							DH_DOMAIN ) ) ), 
				'dh_member' => array( 
					array( 
						'type' => 'dropdown', 
						'param_name' => 'style', 
						'heading' => __( 'Style', DH_DOMAIN ), 
						'value' => array( 
							__( 'Meta below', DH_DOMAIN ) => 'below', 
							__( 'Meta overlay', DH_DOMAIN ) => 'overlay', 
							__( 'Meta right', DH_DOMAIN ) => 'right' ), 
						"description" => __( "Team Member Stlye.", DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Name', DH_DOMAIN ), 
						'param_name' => 'name', 
						'admin_label' => true, 
						"description" => __( "Enter the name of team member.", DH_DOMAIN ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Job Position', DH_DOMAIN ), 
						'param_name' => 'job', 
						"description" => __( "Enter the job position for team member.", DH_DOMAIN ) ), 
					array( 
						'type' => 'attach_image', 
						'heading' => __( 'Avatar', DH_DOMAIN ), 
						'param_name' => 'avatar', 
						"description" => __( "Select avatar from media library.", DH_DOMAIN ) ), 
					array( 
						'type' => 'textarea', 
						'heading' => __( 'Description', DH_DOMAIN ), 
						'param_name' => 'description', 
						"description" => __( "Enter the description for team member.", DH_DOMAIN ) ), 
					
					array( 'type' => 'href', 'heading' => __( 'Facebook URL', DH_DOMAIN ), 'param_name' => 'facebook' ), 
					
					array( 'type' => 'href', 'heading' => __( 'Twitter URL', DH_DOMAIN ), 'param_name' => 'twitter' ), 
					array( 'type' => 'href', 'heading' => __( 'Google+ URL', DH_DOMAIN ), 'param_name' => 'google' ), 
					array( 'type' => 'href', 'heading' => __( 'LinkedIn URL', DH_DOMAIN ), 'param_name' => 'linkedin' ) ), 
				
				'vc_single_image' => array( 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Image style', DH_DOMAIN ), 
						'param_name' => 'style', 
						'value' => array( 
							'Default' => 'default', 
							'Rounded' => 'rounded', 
							'Border' => 'border', 
							'Outline' => 'outline', 
							'Shadow' => 'shadow', 
							'Bordered shadow' => 'shadow-border', 
							'Circle' => 'circle',  // new
							'Circle Border' => 'border-circle',  // new
							'Circle Outline' => 'outline-circle',  // new
							'Circle Shadow' => 'shadow-circle',  // new
							'Circle Border Shadow' => 'shadow-border-circle' ) ),  // new
					array( 
						'param_name' => 'border_size', 
						'heading' => __( 'Border width (px)', DH_DOMAIN ), 
						'type' => 'ui_slider', 
						'holder' => $this->param_holder, 
						'value' => '1', 
						'data_min' => '0', 
						'data_max' => '50', 
						'dependency' => array( 
							'element' => 'style', 
							'value' => array( 'border', 'border-circle', 'outline', 'outline-circle' ) ) ), 
					
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Border color', DH_DOMAIN ), 
						'param_name' => 'border_color', 
						'dependency' => array( 
							'element' => 'style', 
							'value' => array( 'border', 'border-circle', 'outline', 'outline-circle' ) ), 
						'description' => __( 'Border color.', DH_DOMAIN ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Open image in popup?', DH_DOMAIN ), 
						'param_name' => 'img_link_large', 
						'description' => __( 'If selected, image will open in popup.', DH_DOMAIN ), 
						'value' => array( __( 'Yes, please', DH_DOMAIN ) => 'yes' ) ), 
					array( 
						'type' => 'href', 
						'heading' => __( 'Image link', DH_DOMAIN ), 
						'param_name' => 'link', 
						'description' => __( 'Enter URL if you want this image to have a link.', DH_DOMAIN ), 
						'dependency' => array( 
							'element' => 'img_link_large', 
							'is_empty' => true, 
							'callback' => 'wpb_single_image_img_link_dependency_callback' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Link Target', DH_DOMAIN ), 
						'param_name' => 'img_link_target', 
						'value' => array( 
							__( 'Same window', DH_DOMAIN ) => '_self', 
							__( 'New window', DH_DOMAIN ) => "_blank" ), 
						'dependency' => array( 'element' => 'link', 'not_empty' => true ) ) ), 
				'vc_custom_heading' => array( 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Inherit Style?', DH_DOMAIN ), 
						'param_name' => 'inherit_style', 
						'std' => 'yes', 
						'description' => __( 'Inherit Style in Theme.', DH_DOMAIN ), 
						'value' => array( __( 'Yes', DH_DOMAIN ) => 'yes', __( 'No', DH_DOMAIN ) => 'no' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Heading Type', DH_DOMAIN ), 
						'param_name' => 'heading_type', 
						'value' => array( 
							__( 'None', DH_DOMAIN ) => '', 
							__( 'Bold first word', DH_DOMAIN ) => 'bold_first_word', 
							__( 'Typed Effect', DH_DOMAIN ) => "typed_effect" ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Typed Color', DH_DOMAIN ), 
						'param_name' => 'heading_type_color', 
						'dependency' => array( 'element' => "heading_type", 'value' => array('typed_effect') ), 
						'description' => __( 'Custom Typed word color.', DH_DOMAIN ) 
					) 
				), 
				'vc_raw_html' => array(), 
				'vc_raw_js' => array() );
			$shortcode_optional_param = array( 
				'vc_row', 
				'vc_row_inner', 
				'vc_column', 
				'vc_column_inner', 
				'dh_box', 
				'vc_column_text', 
				'dh_animation', 
				'vc_separator', 
				'vc_empty_space', 
				'dh_post', 
				'dh_latestnews', 
				'dh_portfolio', 
				'vc_progress_bar', 
				'vc_accordion', 
				'vc_message', 
				'dh_quote', 
				'dh_slider', 
				'vc_button', 
				'vc_cta_button', 
				'dh_carousel', 
				'dh_testimonial', 
				'dh_client', 
				'dh_counter', 
				'vc_gmaps', 
				'dh_iconbox', 
				'dh_lists', 
				'dh_modal', 
				'vc_pie', 
				'dh_pricing_table', 
				'vc_video', 
				'vc_tabs', 
				'dh_timeline', 
				'dh_member', 
				'vc_single_image', 
				'vc_custom_heading', 
				'vc_raw_html', 
				'vc_raw_js' );
			foreach ( $params as $shortcode => $param ) {
				foreach ( $param as $attr ) {
					vc_add_param( $shortcode, $attr );
				}
				if ( in_array( $shortcode, $shortcode_optional_param ) ) {
					foreach ( (array) $this->_get_optional_param() as $optional_param ) {
						vc_add_param( $shortcode, $optional_param );
					}
				}
			}
			
			return;
		}

		public function remove_default_shortcodes() {
			// vc_remove_element( 'vc_column_text' );
			// vc_remove_element( 'vc_separator' );
// 			vc_remove_element( 'vc_text_separator' );
			// vc_remove_element( 'vc_message' );
// 			vc_remove_element( 'vc_facebook' );
// 			vc_remove_element( 'vc_tweetmeme' );
// 			vc_remove_element( 'vc_googleplus' );
// 			vc_remove_element( 'vc_pinterest' );
// 			vc_remove_element( 'vc_toggle' );
			// vc_remove_element( 'vc_single_image' );
// 			vc_remove_element( 'vc_gallery' );
// 			vc_remove_element( 'vc_images_carousel' );
			// vc_remove_element( 'vc_tabs' );
// 			vc_remove_element( 'vc_tour' );
			// vc_remove_element( 'vc_accordion' );
// 			vc_remove_element( 'vc_posts_grid' );
// 			vc_remove_element( 'vc_carousel' );
// 			vc_remove_element( 'vc_posts_slider' );
			// vc_remove_element( 'vc_widget_sidebar' );
			// vc_remove_element( 'vc_button' );
			// vc_remove_element( 'vc_cta_button' );
			// vc_remove_element( 'vc_video' );
			// vc_remove_element( 'vc_gmaps' );
			// vc_remove_element( 'vc_raw_html' );
			// vc_remove_element( 'vc_raw_js' );
// 			vc_remove_element( 'vc_flickr' );
			// vc_remove_element( 'vc_progress_bar' );
			// vc_remove_element( 'vc_pie' );
			// vc_remove_element( 'contact-form-7' );
			// vc_remove_element( 'rev_slider_vc' );
			// vc_remove_element( 'vc_wp_search' );
			// vc_remove_element( 'vc_wp_meta' );
			// vc_remove_element( 'vc_wp_recentcomments' );
			// vc_remove_element( 'vc_wp_calendar' );
			// vc_remove_element( 'vc_wp_pages' );
			// vc_remove_element( 'vc_wp_tagcloud' );
			// vc_remove_element( 'vc_wp_custommenu' );
			// vc_remove_element( 'vc_wp_text' );
			// vc_remove_element( 'vc_wp_posts' );
			// vc_remove_element( 'vc_wp_links' );
			// vc_remove_element( 'vc_wp_categories' );
			// vc_remove_element( 'vc_wp_archives' );
			// vc_remove_element( 'vc_wp_rss' );
// 			vc_remove_element( 'vc_button2' );
// 			vc_remove_element( 'vc_cta_button2' );
			// vc_remove_element( 'vc_empty_space' );
			// vc_remove_element( 'vc_custom_heading' );
			
			return;
		}

		public function remove_params() {
			// VC Row
			vc_remove_param( 'vc_row', 'css' );
			vc_remove_param( 'vc_row', 'el_class' );
			vc_remove_param( 'vc_row', 'font_color' );
			
			// VC Row Inner
			vc_remove_param( 'vc_row_inner', 'css' );
			vc_remove_param( 'vc_row_inner', 'el_class' );
			vc_remove_param( 'vc_row_inner', 'font_color' );
			
			// VC Column
			vc_remove_param( 'vc_column', 'el_class' );
			vc_remove_param( 'vc_column', 'font_color' );
			vc_remove_param( 'vc_column', 'css' );
			vc_remove_param( 'vc_column', 'width' );
			vc_remove_param( 'vc_column', 'offset' );
			
			// VC Column Inner
			vc_remove_param( 'vc_column_inner', 'font_color' );
			vc_remove_param( 'vc_column_inner', 'el_class' );
			vc_remove_param( 'vc_column_inner', 'css' );
			vc_remove_param( 'vc_column_inner', 'width' );
			
			// VC Column Text
			vc_remove_param( 'vc_column_text', 'css_animation' );
			vc_remove_param( 'vc_column_text', 'css' );
			vc_remove_param( 'vc_column_text', 'el_class' );
			
			// VC separator
			vc_remove_param( 'vc_separator', 'color' );
			vc_remove_param( 'vc_separator', 'accent_color' );
			vc_remove_param( 'vc_separator', 'style' );
			vc_remove_param( 'vc_separator', 'el_width' );
			vc_remove_param( 'vc_separator', 'el_class' );
			
			// VC Empty Space
			vc_remove_param( 'vc_empty_space', 'height' );
			vc_remove_param( 'vc_empty_space', 'el_class' );
			
			// vc_progress_bar
			vc_remove_param( 'vc_progress_bar', 'bgcolor' );
			vc_remove_param( 'vc_progress_bar', 'custombgcolor' );
			vc_remove_param( 'vc_progress_bar', 'el_class' );
			
			// vc_accordion
			vc_remove_param( 'vc_accordion', 'collapsible' );
			vc_remove_param( 'vc_accordion', 'el_class' );
			
			// css_animation
			vc_remove_param( 'vc_message', 'css_animation' );
			vc_remove_param( 'vc_message', 'el_class' );
			
			// vc_button
			vc_remove_param( 'vc_button', 'color' );
			vc_remove_param( 'vc_button', 'size' );
			vc_remove_param( 'vc_button', 'icon' );
			vc_remove_param( 'vc_button', 'el_class' );
			
			// vc_cta_button target
			vc_remove_param( 'vc_cta_button', 'call_text' );
			vc_remove_param( 'vc_cta_button', 'title' );
			vc_remove_param( 'vc_cta_button', 'href' );
			vc_remove_param( 'vc_cta_button', 'href' );
			vc_remove_param( 'vc_cta_button', 'target' );
			vc_remove_param( 'vc_cta_button', 'color' );
			vc_remove_param( 'vc_cta_button', 'icon' );
			vc_remove_param( 'vc_cta_button', 'size' );
			vc_remove_param( 'vc_cta_button', 'position' );
			vc_remove_param( 'vc_cta_button', 'css_animation' );
			vc_remove_param( 'vc_cta_button', 'el_class' );
			
			// vc_gmaps
			vc_remove_param( 'vc_gmaps', 'link' );
			vc_remove_param( 'vc_gmaps', 'el_class' );
			// vc_pie
			vc_remove_param( 'vc_pie', 'label_value' );
			vc_remove_param( 'vc_pie', 'color' );
			vc_remove_param( 'vc_pie', 'el_class' );
			
			// vc_video
			vc_remove_param( 'vc_video', 'title' );
			vc_remove_param( 'vc_video', 'css' );
			vc_remove_param( 'vc_video', 'link' );
			vc_remove_param( 'vc_video', 'el_class' );
			
			// vc_tabs
			vc_remove_param( 'vc_tabs', 'title' );
			vc_remove_param( 'vc_tabs', 'interval' );
			vc_remove_param( 'vc_tabs', 'el_class' );
			// vc_tab
			vc_remove_param( 'vc_tab', 'title' );
			// vc_single_image
			vc_remove_param( 'vc_single_image', 'title' );
			vc_remove_param( 'vc_single_image', 'style' );
			vc_remove_param( 'vc_single_image', 'border_color' );
			vc_remove_param( 'vc_single_image', 'img_link_large' );
			vc_remove_param( 'vc_single_image', 'link' );
			vc_remove_param( 'vc_single_image', 'img_link_target' );
			vc_remove_param( 'vc_single_image', 'css_animation' );
			vc_remove_param( 'vc_single_image', 'el_class' );
			vc_remove_param( 'vc_single_image', 'css' );
			// vc_custom_heading
			vc_remove_param( 'vc_custom_heading', 'el_class' );
			vc_remove_param( 'vc_custom_heading', 'css' );
			return;
		}

		public function update_params() {
			// vc_message style
			vc_update_shortcode_param( 
				'vc_message', 
				array( 
					'type' => 'dropdown', 
					'heading' => __( 'Style', DH_DOMAIN ), 
					'param_name' => 'style', 
					'value' => self::$button_styles, 
					'description' => __( 'Alert style.', DH_DOMAIN ) ) );
			
			vc_update_shortcode_param('vc_progress_bar',
				array(
					'type' => 'textfield',
					'std'=>'%',
					'heading' => __( 'Units', 'js_composer' ),
					'param_name' => 'units',
					'description' => __( 'Enter measurement units (if needed) Eg. %, px, points, etc. Graph value and unit will be appended to the graph title.', 'js_composer' )
				));
			
			vc_update_shortcode_param( 
				'vc_message', 
				array( 
					'type' => 'dropdown', 
					'heading' => __( 'Message box type', DH_DOMAIN ), 
					'param_name' => 'color', 
					'value' => array( 
						__( 'Default', DH_DOMAIN ) => 'default', 
						__( 'Primary', DH_DOMAIN ) => 'primary', 
						__( 'Success', DH_DOMAIN ) => 'success', 
						__( 'Info', DH_DOMAIN ) => 'info', 
						__( 'Warning', DH_DOMAIN ) => 'warning', 
						__( 'Danger', DH_DOMAIN ) => 'danger' ), 
					'admin_label' => true, 
					'description' => __( 'Select message type.', DH_DOMAIN ), 
					'param_holder_class' => $this->param_holder ) );
			vc_update_shortcode_param( 
				'vc_button', 
				array( 
					'type' => 'textfield', 
					'heading' => __( 'Title', DH_DOMAIN ), 
					'param_name' => 'title', 
					'admin_label' => true, 
					'value' => __( 'Button', DH_DOMAIN ), 
					'description' => __( 'Text on the button.', DH_DOMAIN ) ) );
		}

		public function remove_vc_teaser_meta_box() {
			$post_types = get_post_types( '', 'names' );
			foreach ( $post_types as $post_type ) {
				remove_meta_box( 'vc_teaser', $post_type, 'side' );
			}
			return;
		}

		protected function _get_optional_param() {
			$optional_param = array( 
				array( 
					'param_name' => 'visibility', 
					'heading' => __( 'Visibility', DH_DOMAIN ), 
					'type' => 'dropdown', 
					'holder' => $this->param_holder, 
					'value' => array( 
						__( 'All Devices', DH_DOMAIN ) => "all", 
						__( 'Hidden Phone', DH_DOMAIN ) => "hidden-phone", 
						__( 'Hidden Tablet', DH_DOMAIN ) => "hidden-tablet", 
						__( 'Hidden PC', DH_DOMAIN ) => "hidden-pc", 
						__( 'Visible Phone', DH_DOMAIN ) => "visible-phone", 
						__( 'Visible Tablet', DH_DOMAIN ) => "visible-tablet", 
						__( 'Visible PC', DH_DOMAIN ) => "visible-pc" ) ), 
				array( 
					'param_name' => 'el_class', 
					'heading' => __( '(Optional) Extra class name', DH_DOMAIN ), 
					'type' => 'textfield', 
					'holder' => $this->param_holder, 
					"description" => __( 
						"If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 
						DH_DOMAIN ) ) );
			return $optional_param;
		}

		public function portfolio_category_param( $settings, $value ) {
			$dependency = vc_generate_dependencies_attributes( $settings );
			$categories = (array) get_terms( 'portfolio_category', array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
			$class = 'dh-chosen-multiple-select';
			$selected_values = explode( ',', $value );
			$html = array();
			$html[] = '<div class="portfolio_category_param">';
			$html[] = '  <select id="' . $settings['param_name'] . '" multiple="true" class="' . $class . '" ' .
				 $dependency . '>';
			$r = array();
			$r['pad_counts'] = 1;
			$r['hierarchical'] = 1;
			$r['hide_empty'] = 1;
			$r['show_count'] = 0;
			$r['selected'] = $selected_values;
			$r['menu_order'] = false;
			$html[] = dh_walk_category_dropdown_tree( $categories, 0, $r );
			
			$html[] = '  </select>';
			$html[] = '<input id= "' . $settings['param_name'] .
				 '" type="hidden" class="wpb_vc_param_value dh-chosen-value wpb-textinput" name="' .
				 $settings['param_name'] . '" value="' . $value . '" />';
			$html[] = '</div>';
			
			return implode( "\n", $html );
		}

		public function pricing_table_feature_param( $settings, $value ) {
			$value_64 = base64_decode( $value );
			$value_arr = json_decode( $value_64 );
			if ( empty( $value_arr ) && ! is_array( $value_arr ) ) {
				for ( $i = 0; $i < 2; $i++ ) {
					$option = new stdClass();
					$option->content = '<i class="fa fa-check"></i> I am a feature';
					$value_arr[] = $option;
				}
			}
			$param_line = '';
			$param_line .= '<div class="pricing-table-feature-list clearfix">';
			$param_line .= '<table>';
			$param_line .= '<thead>';
			$param_line .= '<tr>';
			$param_line .= '<td>';
			$param_line .= __( 'Content (<em>Add Arbitrary text or HTML.</em>)', DH_DOMAIN );
			$param_line .= '</td>';
			$param_line .= '<td>';
			$param_line .= '</td>';
			$param_line .= '</tr>';
			$param_line .= '</thead>';
			$param_line .= '<tbody>';
			if ( is_array( $value_arr ) && ! empty( $value_arr ) ) {
				foreach ( $value_arr as $k => $v ) {
					$param_line .= '<tr>';
					$param_line .= '<td>';
					$param_line .= '<textarea id="content">' . esc_textarea( $v->content ) . '</textarea>';
					$param_line .= '</td>';
					$param_line .= '<td align="left" style="padding:5px;">';
					$param_line .= '<a href="#" class="pricing-table-feature-remove" onclick="return pricing_table_feature_remove(this);"  title="' .
						 __( 'Remove', DH_DOMAIN ) . '">-</a>';
					$param_line .= '</td>';
					$param_line .= '</tr>';
				}
			}
			$param_line .= '</tbody>';
			$param_line .= '<tfoot>';
			$param_line .= '<tr>';
			$param_line .= '<td colspan="3">';
			$param_line .= '<a href="#" onclick="return pricing_table_feature_add(this);" class="button" title="' .
				 __( 'Add', DH_DOMAIN ) . '">' . __( 'Add', DH_DOMAIN ) . '</a>';
			$param_line .= '</td>';
			$param_line .= '</tfoot>';
			$param_line .= '</table>';
			$param_line .= '<input type="hidden" name="' . $settings['param_name'] . '" class="wpb_vc_param_value' .
				 $settings['param_name'] . ' ' . $settings['type'] . '" value="' . $value . '">';
			$param_line .= '</div>';
			return $param_line;
		}

		public function post_category_param( $settings, $value ) {
			$dependency = vc_generate_dependencies_attributes( $settings );
			$categories = get_categories( array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
			
			$class = 'dh-chosen-multiple-select';
			$selected_values = explode( ',', $value );
			$html = array();
			$html[] = '<div class="post_category_param">';
			$html[] = '<select id="' . $settings['param_name'] . '" ' .
				 ( isset( $settings['single_select'] ) ? '' : 'multiple="multiple"' ) . ' class="' . $class . '" ' .
				 $dependency . '>';
			$r = array();
			
			$r['pad_counts'] = 1;
			$r['hierarchical'] = 1;
			$r['hide_empty'] = 1;
			$r['show_count'] = 0;
			$r['selected'] = $selected_values;
			$r['menu_order'] = false;
			$html[] = dh_walk_category_dropdown_tree( $categories, 0, $r );
			$html[] = '</select>';
			$html[] = '<input id= "' . $settings['param_name'] .
				 '" type="hidden" class="wpb_vc_param_value dh-chosen-value wpb-textinput" name="' .
				 $settings['param_name'] . '" value="' . $value . '" />';
			$html[] = '</div>';
			
			return implode( "\n", $html );
		}

		public function dropdown_group_param( $param, $param_value ) {
			$css_option = vc_get_dropdown_option( $param, $param_value );
			$param_line = '';
			$param_line .= '<select name="' . $param['param_name'] .
				 '" class="dh-chosen-select wpb_vc_param_value wpb-input wpb-select ' . $param['param_name'] . ' ' .
				 $param['type'] . ' ' . $css_option . '" data-option="' . $css_option . '">';
			foreach ( $param['optgroup'] as $text_opt => $opt ) {
				if ( is_array( $opt ) ) {
					$param_line .= '<optgroup label="' . $text_opt . '">';
					foreach ( $opt as $text_val => $val ) {
						if ( is_numeric( $text_val ) && ( is_string( $val ) || is_numeric( $val ) ) ) {
							$text_val = $val;
						}
						$selected = '';
						if ( $param_value !== '' && (string) $val === (string) $param_value ) {
							$selected = ' selected="selected"';
						}
						$param_line .= '<option class="' . $val . '" value="' . $val . '"' . $selected . '>' .
							 htmlspecialchars( $text_val ) . '</option>';
					}
					$param_line .= '</optgroup>';
				} elseif ( is_string( $opt ) ) {
					if ( is_numeric( $text_opt ) && ( is_string( $opt ) || is_numeric( $opt ) ) ) {
						$text_opt = $opt;
					}
					$selected = '';
					if ( $param_value !== '' && (string) $opt === (string) $param_value ) {
						$selected = ' selected="selected"';
					}
					$param_line .= '<option class="' . $opt . '" value="' . $opt . '"' . $selected . '>' .
						 htmlspecialchars( $text_opt ) . '</option>';
				}
			}
			$param_line .= '</select>';
			return $param_line;
		}

		public function nullfield_param( $settings, $value ) {
			return '';
		}

		public function product_attribute_param( $settings, $value ) {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) )
				return '';
			
			$output = '';
			$attributes = wc_get_attribute_taxonomies();
			$output .= '<select name= "' . $settings['param_name'] . '" data-placeholder="' .
				 __( 'Select Attibute', DH_DOMAIN ) .
				 '" class="dh-product-attribute dh-chosen-select wpb_vc_param_value wpb-input wpb-select ' .
				 $settings['param_name'] . ' ' . $settings['type'] . '">';
			if ( ! empty( $attributes ) ) {
				foreach ( $attributes as $attr ) :
					if ( taxonomy_exists( wc_attribute_taxonomy_name( $attr->attribute_name ) ) ) {
						if ( $name = wc_attribute_taxonomy_name( $attr->attribute_name ) ) {
							$output .= '<option value="' . esc_attr( $name ) . '"' . selected( $value, $name, false ) .
								 '>' . $attr->attribute_name . '</option>';
						}
					}
				endforeach
				;
			}
			$output .= '</select>';
			return $output;
		}

		public function product_attribute_filter_param( $settings, $value ) {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) )
				return '';
			
			$output = '';
			$args = array( 'orderby' => 'name', 'hide_empty' => false );
			$filter_ids = explode( ',', $value );
			$attributes = wc_get_attribute_taxonomies();
			$output .= '<select id= "' . $settings['param_name'] . '" multiple="multiple"  data-placeholder="' .
				 __( 'Select Attibute Filter', DH_DOMAIN ) .
				 '" class="dh-product-attribute-filter dh-chosen-multiple-select dh-chosen-select wpb_vc_param_value wpb-input wpb-select ' .
				 $settings['param_name'] . ' ' . $settings['type'] . '">';
			if ( ! empty( $attributes ) ) {
				foreach ( $attributes as $attr ) :
					if ( taxonomy_exists( wc_attribute_taxonomy_name( $attr->attribute_name ) ) ) {
						if ( $name = wc_attribute_taxonomy_name( $attr->attribute_name ) ) {
							$terms = get_terms( $name, $args );
							if ( ! empty( $terms ) ) {
								foreach ( $terms as $term ) {
									$v = $term->slug;
									$output .= '<option data-attr="' . esc_attr( $name ) . '" value="' . esc_attr( $v ) .
										 '"' . selected( in_array( $v, $filter_ids ), true, false ) . '>' .
										 esc_html( $term->name ) . '</option>';
								}
							}
						}
					}
				endforeach
				;
			}
			$output .= '</select>';
			$output .= '<input id= "' . $settings['param_name'] .
				 '" type="hidden" class="wpb_vc_param_value wpb-textinput" name="' . $settings['param_name'] .
				 '" value="' . $value . '" />';
			return $output;
		}

		public function product_brand_param( $settings, $value ) {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) )
				return '';
			$output = '';
			$brands_slugs = explode( ',', $value );
			$args = array( 'orderby' => 'name', 'hide_empty' => true );
			$brands = get_terms( 'product_brand', $args );
			$output .= '<select id= "' . $settings['param_name'] . '" multiple="multiple" data-placeholder="' .
				__( 'Select brands', DH_DOMAIN ) . '" class="dh-chosen-multiple-select dh-chosen-select-nostd ' .
				$settings['param_name'] . ' ' . $settings['type'] . '">';
			if ( ! empty( $brands ) ) {
				foreach ( $brands as $brand ) :
				$output .= '<option value="' . esc_attr( $brand->slug ) . '"' .
				selected( in_array( $brand->slug, $brands_slugs ), true, false ) . '>' . esc_html( $brand->name ) .
				'</option>';
				endforeach
				;
			}
			$output .= '</select>';
			$output .= '<input id= "' . $settings['param_name'] .
			'" type="hidden" class="wpb_vc_param_value wpb-textinput" name="' . $settings['param_name'] .
			'" value="' . $value . '" />';
			return $output;
		}
		
		public function product_category_param( $settings, $value ) {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) )
				return '';
			$output = '';
			$category_slugs = explode( ',', $value );
			$args = array( 'orderby' => 'name', 'hide_empty' => true );
			$categories = get_terms( 'product_cat', $args );
			$r['value'] = isset($settings['select_field']) ? 'id' :'slug';
			$output .= '<select id= "' . $settings['param_name'] . '" multiple="multiple" data-placeholder="' .
				 __( 'Select categories', DH_DOMAIN ) . '" class="dh-chosen-multiple-select dh-chosen-select-nostd ' .
				 $settings['param_name'] . ' ' . $settings['type'] . '">';
			if ( ! empty( $categories ) ) {
				foreach ( $categories as $cat ) :
					$s = isset($settings['select_field']) ? $cat->term_id : $cat->slug;
					$output .= '<option value="' . esc_attr( $s ) . '"' .
						 selected( in_array( $s, $category_slugs ), true, false ) . '>' . esc_html( $cat->name ) .
						 '</option>';
				endforeach
				;
			}
			$output .= '</select>';
			$output .= '<input id= "' . $settings['param_name'] .
				 '" type="hidden" class="wpb_vc_param_value wpb-textinput" name="' . $settings['param_name'] .
				 '" value="' . $value . '" />';
			return $output;
		}

		public function products_ajax_param( $settings, $value ) {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) )
				return '';
			
			$product_ids = array();
			if ( ! empty( $value ) )
				$product_ids = array_map( 'absint', explode( ',', $value ) );
			
			$output = '<select data-placeholder="' . __( 'Search for a product...', DH_DOMAIN ) . '" id= "' .
				 $settings['param_name'] . '" ' . ( isset( $settings['single_select'] ) ? '' : 'multiple="multiple"' ) .
				 ' class="dh-chosen-multiple-select dh-chosen-ajax-select-product ' . $settings['param_name'] . ' ' .
				 $settings['type'] . '">';
			if ( isset( $settings['single_select'] ) ) {
				$output .= '<option value=""></option>';
			}
			if ( ! empty( $product_ids ) ) {
				foreach ( $product_ids as $product_id ) {
					$product = get_product( $product_id );
					if ( $product->get_sku() ) {
						$identifier = $product->get_sku();
					} else {
						$identifier = '#' . $product->id;
					}
					
					$product_name = sprintf( __( '%s &ndash; %s', DH_DOMAIN ), $identifier, $product->get_title() );
					
					$output .= '<option value="' . esc_attr( $product_id ) . '" selected="selected">' .
						 esc_html( $product_name ) . '</option>';
				}
			}
			$output .= '</select>';
			$output .= '<input id= "' . $settings['param_name'] .
				 '" type="hidden" class="wpb_vc_param_value wpb-textinput" name="' . $settings['param_name'] .
				 '" value="' . $value . '" />';
			
			return $output;
		}

		public function ui_datepicker_param( $param, $param_value ) {
			$param_line = '';
			$value = $param_value;
			$value = htmlspecialchars( $value );
			$param_line .= '<input id="' . $param['param_name'] . '" name="' . $param['param_name'] .
				 '" class="wpb_vc_param_value wpb-textinput ' . $param['param_name'] . ' ' . $param['type'] .
				 '" type="text" value="' . $value . '"/>';
			if ( ! defined( 'DH_UISLDER_PARAM' ) ) {
				define( 'DH_UISLDER_PARAM', 1 );
				$param_line .= '<link media="all" type="text/css" href="' . DHINC_ASSETS_URL .
					 '/vendor/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.css?ver=1.10.0" rel="stylesheet" />';
			}
			$param_line .= '<script>
					jQuery(function() {
					jQuery( "#' . $param['param_name'] . '" ).datepicker({showButtonPanel: true});
					});</script>	
				';
			return $param_line;
		}

		public function ui_slder_param( $settings, $value ) {
			$data_min = ( isset( $settings['data_min'] ) && ! empty( $settings['data_min'] ) ) ? 'data-min="' .absint( $settings['data_min'] ) . '"' : 'data-min="0"';
			$data_max = ( isset( $settings['data_max'] ) && ! empty( $settings['data_max'] ) ) ? 'data-max="' . absint( $settings['data_max'] ) . '"' : 'data-max="100"';
			$data_step = ( isset( $settings['data_step'] ) && ! empty( $settings['data_step'] ) ) ? 'data-step="' .absint( $settings['data_step'] ) . '"' : 'data-step="1"';
			$html = array();
			if ( ! defined( 'DH_UISLDER_PARAM' ) ) {
				define( 'DH_UISLDER_PARAM', 1 );
				$html[] = '<link media="all" type="text/css" href="' . DHINC_ASSETS_URL .'/vendor/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.css?ver=1.10.0" rel="stylesheet" />';
			}
			$html[] = '	<div class="ui_slider-param dh-clearfix" style="padding-top: 10px;">';
			$html[] = '		<input type="text" id="' . $settings['param_name'] . '" name="' . $settings['param_name'] .
				 '" class="wpb_vc_param_value wpb-input ' . $settings['param_name'] . ' ' . $settings['type'] .
				 '_field" value="' . $value . '" ' . $data_min . ' ' . $data_max . ' ' . $data_step .
				 ' style="width: 10%;margin:0 20px 0 0; display: inline-block;"/>';
			$html[] = '	</div>';
			$html[] = '<script>';
			$html[] = 'jQuery("#' . $settings['param_name'] . '").each(function() {';
			$html[] = '	var $this = jQuery(this);';
			$html[] = '	var $slider = jQuery(\'<div style="width: 80%; display: inline-block; margin: 0">\', {id: $this.attr("id") + "-slider"}).insertAfter($this);';
			$html[] = '	$slider.slider(';
			$html[] = '	{';
			$html[] = '		range: "min",';
			$html[] = '		value: $this.val() || $this.data("min") || 0,';
			$html[] = '		min: $this.data("min") || 0,';
			$html[] = '		max: $this.data("max") || 100,';
			$html[] = '		step: $this.data("step") || 1,';
			$html[] = '		slide: function(event, ui) {';
			$html[] = '			$this.val(ui.value).attr("value", ui.value);';
			$html[] = '		}';
			$html[] = '	}';
			$html[] = '	);';
			$html[] = '	$this.change(function() {';
			$html[] = '		$slider.slider( "option", "value", $this.val() );';
			$html[] = '	});';
			$html[] = '});';
			$html[] = '</script>';
			
			return implode( "\n", $html );
		}

		public function enqueue_scripts() {
			$pricing_table_feature_tmpl = '';
			$pricing_table_feature_tmpl .= '<tr><td><textarea id="content"></textarea></td><td align="left" style="padding:5px;"><a href="#" class="pricing-table-feature-remove" onclick="return pricing_table_feature_remove(this);"  title="' .
				 __( 'Remove', DH_DOMAIN ) . '">-</a></td></tr>';
			wp_enqueue_style( 
				'dh-vc-admin', 
				DHINC_ASSETS_URL . '/css/vc-admin.css', 
				array( 'vendor-font-awesome', 'vendor-elegant-icon', 'vendor-chosen' ), 
				DHINC_VERSION );
			wp_register_script( 
				'dh-vc-custom', 
				DHINC_ASSETS_URL . '/js/vc-custom.js', 
				array( 'jquery', 'jquery-ui-slider', 'jquery-ui-datepicker', 'wpb_js_composer_js_custom_views' ), 
				DHINC_VERSION, 
				true );
			$dhvcL10n = array( 
				'pricing_table_max_item_msg' => __( 'Pricing Table element only support display 5 item', DH_DOMAIN ), 
				'item_title' => DHVC_ITEM_TITLE, 
				'add_item_title' => DHVC_ADD_ITEM_TITLE, 
				'move_title' => DHVC_MOVE_TITLE, 
				'pricing_table_feature_tmpl' => $pricing_table_feature_tmpl );
			wp_localize_script( 'dh-vc-custom', 'dhvcL10n', $dhvcL10n );
			wp_enqueue_script( 'dh-vc-custom' );
		}
	}
	new DHVisualComposer();

	function dh_vc_el_increment() {
		static $count = 0;
		$count++;
		return $count;
	}






endif;