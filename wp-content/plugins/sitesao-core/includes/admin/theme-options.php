<?php

if(!class_exists('DHThemeOptions')):
class DHThemeOptions {
	
	protected  $_sections            = array(); // Sections and fields
	protected static $_option_name;
	
	public function __construct(){
		$this->_sections = $this->get_sections();
		
		self::$_option_name = dh_get_theme_option_name();
		
		add_action('admin_init', array(&$this,'admin_init'));
		add_action( 'admin_menu', array(&$this,'admin_menu') );
		//Download theme option
		add_action("wp_ajax_dh_download_theme_option",          array(&$this, "download_theme_option"));
			
	}
	
	public static function get_options($key,$default = null){
		global $dh_theme_options;
		if ( empty( $dh_theme_options ) ) {
			$dh_theme_options = get_option(self::$_option_name);
		}
		if(isset($dh_theme_options[$key]) && $dh_theme_options[$key] !== ''){
			return $dh_theme_options[$key];
		}else{
			return $default;
		}
	}
	
	public function admin_init(){
		register_setting(self::$_option_name,self::$_option_name,array(&$this,'register_setting_callback'));
		$_opions = get_option(self::$_option_name);
		if(empty($_opions)){
			$default_options = array();
			foreach ($this->_sections as $key=>$sections){
				if(is_array($sections['fields']) && !empty($sections['fields'])){
					foreach ($sections['fields'] as $field){
						if(isset($field['name']) && isset($field['value'])){
							$default_options[$field['name']] = $field['value'];
						}
					}
				}
			}
			if(!empty($default_options)){
				$options = array();
				foreach($default_options as $key => $value) {
					$options[$key] = $value;
				}
			}
			$r  = update_option(self::$_option_name, $options);
		}
	}
	
	protected static function getFileSystem( $url = '' ) {
		if ( empty( $url ) ) {
			$url = wp_nonce_url( 'admin.php?page=theme-options', 'register_setting_callback' );
		}
		if ( false === ( $creds = request_filesystem_credentials( $url, '', false, false, null ) ) ) {
			_e( 'This is required to enable file writing', DH_DOMAIN );
			exit(); // stop processing here
		}
		$assets_dir = get_template_directory();
		if ( ! WP_Filesystem( $creds, $assets_dir ) ) {
			request_filesystem_credentials( $url, '', true, false, null );
			_e( 'This is required to enable file writing', DH_DOMAIN );
			exit();
		}
	}
	
	public function register_setting_callback($options){
		$less_flag = false;
		
		do_action('dh_theme_option_before_setting_callback',$options);
		
		$update_options = array();
		foreach ($this->_sections as $key=>$sections){
			if(is_array($sections['fields']) && !empty($sections['fields'])){
				foreach ($sections['fields'] as $field){
					if(isset($field['name']) && isset($options[$field['name']])){
						$option_value = $options[$field['name']];
						$option_value = wp_unslash($option_value);
						if(is_array($option_value)){
							$option_value = array_filter( array_map( 'sanitize_text_field', (array) $option_value ) );
						}else{
							if($field['type']=='textarea'){
								$option_value = wp_kses_post(trim($option_value));
							}elseif($field['type'] == 'ace_editor'){
								$option_value = $option_value;
							}else{
								$option_value =  wp_kses_post(trim($option_value));
							}
						}
						$update_options[$field['name']] = $option_value;
					}
				}
			}
		}
		if(!empty($update_options)){
			foreach($update_options as $key => $value) {
				$options[$key] = $value;
			}
		}
		
		if(isset($options['dh_opt_import'])){
			$import_code = $options['import_code'];
			if(!empty($import_code)){
				$imported_options = json_decode($import_code,true);
				if( !empty( $imported_options ) && is_array( $imported_options )){
					foreach($imported_options as $key => $value) {
						$options[$key] = $value;
					}
				}
			}
		}
		if(isset($options['dh_opt_reset'])){
			$default_options = array();
			foreach ($this->_sections as $key=>$sections){
				if(is_array($sections['fields']) && !empty($sections['fields'])){
					foreach ($sections['fields'] as $field){
						if(isset($field['name']) && isset($field['value'])){
							$default_options[$field['name']] = $field['value'];
						}
					}
				}
			}
			if(!empty($default_options)){
				$options = array();
				foreach($default_options as $key => $value) {
					$options[$key] = $value;
				}
			}
		}
		
		unset($options['import_code']);
		do_action('dh_theme_option_after_setting_callback',$options);
		return $options;
	}
	
	public function get_default_option(){
		return apply_filters('dh_theme_option_default','');
	}
	
	public function option_page(){
		?>
		<div class="clear"></div>
		<div class="wrap">
			<input type="hidden" id="security" name="security" value="<?php echo wp_create_nonce( 'dh_theme_option_ajax_security' ) ?>" />
			<form method="post" action="options.php" enctype="multipart/form-data">
				<?php settings_fields( self::$_option_name ); ?>
				<div class="dh-opt-header">
					<div class="dh-opt-heading"><h2><?php echo DH_THEME_NAME?> <span><?php echo DH_THEME_VERSION?></span></h2> <a target="_blank" href="http://sitesao.com/<?php echo basename(get_template_directory())?>/document"><?php _e('Online Document',DH_DOMAIN)?></a></div>
				</div>
				<div class="clear"></div>
				<div class="dh-opt-actions">
					<em style="float: left; margin-top: 5px;"><?php echo esc_html('Theme customizations are done here. Happy styling!',DH_DOMAIN)?></em>
					<button id="dh-opt-submit" name="dh_opt_save" class="button-primary" type="submit"><?php echo __('Save All Change',DH_DOMAIN) ?></button>
				</div>
				<div class="clear"></div>
				<div id="dh-opt-tab" class="dh-opt-wrap">
					<div class="dh-opt-sidebar">
						<ul id="dh-opt-menu" class="dh-opt-menu">
							<?php $i = 0;?>
							<?php foreach ((array) $this->_sections as $key=>$sections):?>
								<li<?php echo ($i == 0 ? ' class="current"': '')?>>
									<a href="#<?php echo esc_attr($key)?>" title="<?php echo esc_attr($sections['title']) ?>"><?php echo (isset($sections['icon']) ? '<i class="'.$sections['icon'].'"></i> ':'')?><?php echo esc_html($sections['title']) ?></a>
								</li>
							<?php $i++?>
							<?php endforeach;?>
						</ul>
					</div>
					<div id="dh-opt-content" class="dh-opt-content">
						<?php $i = 0;?>
						<?php foreach ((array) $this->_sections as $key=>$sections):?>
							<div id=<?php echo esc_attr($key)?> class="dh-opt-section" <?php echo ($i == 0 ? ' style="display:block"': '') ?>>
								<h3><?php echo esc_html($sections['title']) ?></h3>
								<?php if(isset($sections['desc'])):?>
								<div class="dh-opt-section-desc">
									<?php echo dhecho($sections['desc'])?>
								</div>
								<?php endif;?>
								<table class="form-table">
									<tbody>
										<?php foreach ( (array) $sections['fields'] as $field ) { ?>
										<tr>
											<?php if ( !empty($field['label']) ): ?>
											<th scope="row">
												<div class="dh-opt-label">
													<?php echo esc_html($field['label'])?>
													<?php if ( !empty($field['desc']) ): ?>
													<span class="description"><?php echo dhecho($field['desc'])?></span>
													<?php endif;?>
												</div>
											</th>
											<?php endif;?>
											<td <?php if(empty($field['label'])):?>colspan="2" <?php endif;?>>
												<div class="dh-opt-field-wrap">
													<?php 
													if(isset($field['callback']))
														call_user_func($field['callback'], $field);
													?>
													<?php echo dhecho($this->_render_field($field))?>
												</div>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php $i++?>
						<?php endforeach;?>
					</div>
				</div>
				<div class="clear"></div>
				<div class="dh-opt-actions2">
					<button id="dh-opt-submit2" name="dh_opt_save" class="button-primary" type="submit"><?php echo __('Save All Change',DH_DOMAIN) ?></button>
					<button id="dh-opt-reset" name="<?php echo self::$_option_name?>[dh_opt_reset]" class="button" type="submit"><?php echo __('Reset Options',DH_DOMAIN) ?></button>
				</div>
				<div class="clear"></div>
			</form>
		</div>
		<?php
	}
	
	public function _render_field($field = array()){
		if(!isset($field['type']))
			echo '';
		
		$field['name']          = isset( $field['name'] ) ? esc_attr($field['name']) : '';
		
		$value = self::get_options($field['name']);
		$field['value']         = isset( $field['value'] ) ? $field['value'] : '';
		
		$field['value'] = apply_filters('dh_theme_option_field_std',$field['value'],$field);
		$field['default_value'] = $field['value'];
		if($value !== '' && $value !== null && $value !== array() && $value !== false){
			$field['value']         = $value;
		}
		
		$field['id'] 			= isset( $field['id'] ) ? esc_attr($field['id']) : $field['name'];
		$field['desc'] 	= isset($field['desc']) ? $field['desc'] : '';
		$field['label'] 		= isset( $field['label'] ) ? $field['label'] : '';
		$field['placeholder']   = isset( $field['placeholder'] ) ? esc_attr($field['placeholder']) : esc_attr($field['label']);
		
		
		$data_name = ' data-name="'.$field['name'].'"';
		$field_name = self::$_option_name.'['.$field['name'].']';
		
		$dependency_cls = isset($field['dependency']) ? ' dh-dependency-field':'';
		$dependency_data = '';
		if(!empty($dependency_cls)){
			$dependency_default = array('element'=>'','value'=>array());
			$field['dependency'] = wp_parse_args($field['dependency'],$dependency_default);
			if(!empty($field['dependency']['element']) && !empty($field['dependency']['value']))
				$dependency_data = ' data-master="'.esc_attr($field['dependency']['element']).'" data-master-value="'.esc_attr(implode(',',$field['dependency']['value'])).'"';
		}
		
		if(isset($field['field-label'])){
			echo '<p class="field-label">'.$field['field-label'].'</p>';
		}
		
		switch ($field['type']){
			case 'heading':
				echo '<h4>'.(isset($field['text']) ? $field['text'] : '').'</h4>';
			break;
			case 'hr':
				echo '<hr/>';
			break;
			case 'datetimepicker':
				wp_enqueue_script('vendor-datetimepicker');
				wp_enqueue_style('vendor-datetimepicker');
				echo '<div class="dh-field-text ' . $field['id']  . '-field'.$dependency_cls.'"'.$dependency_data.'>';
				echo '<input type="text" readonly class="dh-opt-value input_text" name="' . $field_name . '" id="' .  $field['id'] . '" value="' . esc_attr( $field['value'] ) . '" placeholder="' .  $field['placeholder'] . '" style="width:99%" /> ';
				echo '</div>';
				?>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#<?php echo esc_attr($field['id']); ?>').datetimepicker({
						 scrollMonth: false,
						 scrollTime: false,
						 scrollInput: false,
						 step:15,
						 format:'m/d/Y H:i'
						});
					});
				</script>
				<?php
			break;
			case 'image':
				if(function_exists( 'wp_enqueue_media' )){
					wp_enqueue_media();
				}else{
					wp_enqueue_style('thickbox');
					wp_enqueue_script('media-upload');
					wp_enqueue_script('thickbox');
				}
				
				$image = $field['value'];
				$output = !empty( $image ) ? '<img src="'.$image.'" with="200">' : '';
				
				$btn_text = !empty( $image_id ) ? __( 'Change Image', DH_DOMAIN ) : __( 'Select Image', DH_DOMAIN );
				echo '<div  class="dh-field-image ' . $field['id'] . '-field'.$dependency_cls.'"'.$dependency_data.'>';
					echo '<div class="dh-field-image-thumb">' . $output . '</div>';
					echo '<input type="hidden" class="dh-opt-value" name="' . $field_name . '" id="' . $field['id'] . '" value="' . esc_attr($field['value']) . '" />';
					echo '<input type="button" class="button button-primary" id="' . $field['id'] . '_upload" value="' . $btn_text . '" /> ';
					echo '<input type="button" class="button" id="' . $field['id'] . '_clear" value="' . __( 'Clear Image', DH_DOMAIN ) . '" '.(empty($field['value']) ? ' style="display:none"':'').' />';
				?>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#<?php echo esc_attr($field['id']); ?>_upload').on('click', function(event) {
							event.preventDefault();
							var $this = $(this);
	
							// if media frame exists, reopen
							if(dh_meta_image_frame) {
								dh_meta_image_frame.open();
				                return;
				            }
	
							// create new media frame
							// I decided to create new frame every time to control the selected images
							var dh_meta_image_frame = wp.media.frames.wp_media_frame = wp.media({
								title: "<?php echo __( 'Select or Upload your Image', DH_DOMAIN ); ?>",
								button: {
									text: "<?php echo __( 'Select', DH_DOMAIN ); ?>"
								},
								library: { type: 'image' },
								multiple: false
							});
	
							// when image selected, run callback
							dh_meta_image_frame.on('select', function(){
								var attachment = dh_meta_image_frame.state().get('selection').first().toJSON();
								$this.closest('.dh-field-image').find('input#<?php echo esc_attr($field['id']); ?>').val(attachment.url);
								var thumbnail = $this.closest('.dh-field-image').find('.dh-field-image-thumb');
								thumbnail.html('');
								thumbnail.append('<img src="' + attachment.url + '" alt="" />');
	
								$this.attr('value', '<?php echo __( 'Change Image', DH_DOMAIN ); ?>');
								$('#<?php echo esc_attr($field['id']); ?>_clear').css('display', 'inline-block');
							});
	
							// open media frame
							dh_meta_image_frame.open();
						});
	
						$('#<?php echo esc_attr($field['id']); ?>_clear').on('click', function(event) {
							var $this = $(this);
							$this.hide();
							$('#<?php echo esc_attr($field['id']); ?>_upload').attr('value', '<?php echo __( 'Select Image', DH_DOMAIN ); ?>');
							$this.closest('.dh-field-image').find('input#<?php echo esc_attr($field['id']); ?>').val('');
							$this.closest('.dh-field-image').find('.dh-field-image-thumb').html('');
						});
					});
				</script>
							
				<?php
				echo '</div>';
			break;
			case 'color':
				wp_enqueue_style( 'wp-color-picker');
				wp_enqueue_script( 'wp-color-picker');
			
				echo '<div  class="dh-field-color ' . $field['id']  . '-field'.$dependency_cls.'"'.$dependency_data.'>';
				echo '<input type="text" class="dh-opt-value" name="' . $field_name . '" id="' . $field['id'] . '" value="' . esc_attr( $field['value'] ) . '" placeholder="' .  $field['placeholder'] . '" /> ';
				echo '<script type="text/javascript">
					jQuery(document).ready(function($){
					    $("#'. ( $field['id'] ).'").wpColorPicker();
					});
				 </script>
				';
				echo '</div>';
			break;
			case 'muitl-select':
			case 'select':
				if($field['type'] == 'muitl-select'){
					
					$field_name = $field_name.'[]';
				}
				$field['options']       = isset( $field['options'] ) ? $field['options'] : array();
				echo '<div  class="dh-field-select ' .  $field['id'] . '-field'.$dependency_cls.'"'.$dependency_data.'>';
				echo '<select '.($field['type'] == 'muitl-select' ? 'multiple="multiple"': $data_name).' data-placeholder="' . $field['label'] . '" class="dh-opt-value dh-chosen-select"  id="' . $field['id'] . '" name="' . $field_name . '">';
				foreach ( $field['options'] as $key => $value ) {
					if($field['type'] == 'muitl-select'){
						echo '<option value="' . esc_attr( $key ) . '" ' . ( in_array(esc_attr($key), $field['value']) ? 'selected="selected"':''). '>' . esc_html( $value ) . '</option>';
					}else{
						echo '<option value="' . esc_attr( $key ) . '" ' . selected( ( $field['value'] ), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
					}
				}
				echo '</select> ';
				echo '</div>';
			break;
			case 'textarea':
				echo '<div class="dh-field-textarea ' .  $field['id']  . '-field'.$dependency_cls.'"'.$dependency_data.'>';
				echo '<textarea class="dh-opt-value" name="' . $field_name . '" id="' .  $field['id']  . '" placeholder="' . $field['placeholder'] . '" rows="5" cols="20" style="width: 99%;">' . esc_textarea( $field['value'] ) . '</textarea> ';
				echo '</div>';
			break;
			case 'ace_editor':
				echo '<div class="dh-field-textarea ' .  $field['id']  . '-field'.$dependency_cls.'"'.$dependency_data.'>';
				echo '<pre id="' .  $field['id']  . '-editor" class="dh-opt-value" style="height: 205px;border:1px solid #ccc">'. $field['value'].'</pre>';
				echo '<textarea class="dh-opt-value" id="' .  $field['id']  . '" name="' . $field_name . '" placeholder="' . $field['placeholder'] . '" style="width: 99%;display:none">' .  $field['value'] . '</textarea> ';
				echo '</div>';
			break;
			case 'switch':
				$cb_enabled = $cb_disabled = '';//no errors, please
				if ( (int) $field['value'] == 1 ){
					$cb_enabled = ' selected';
				}else {
					$field['value'] = 0;
					$cb_disabled = ' selected';
				}
				//Label On
				if(!isset($field['on'])){
					$on = __('On',DH_DOMAIN);
				}else{
					$on = $field['on'];
				}
				
				//Label OFF
				if(!isset($field['off'])){
					$off = __('Off',DH_DOMAIN);
				} else{
					$off = $field['off'];
				}
				
				echo '<div class="dh-field-switch ' .  $field['id']  . '-field'.$dependency_cls.'"'.$dependency_data.'>';
				echo '<label class="cb-enable'. $cb_enabled .'" data-id="'.$field['id'].'">'. $on .'</label>';
				echo '<label class="cb-disable'. $cb_disabled .'" data-id="'.$field['id'].'">'. $off .'</label>';
				echo '<input '.$data_name.' type="hidden"  class="dh-opt-value switch-input" id="'.$field['id'].'" name="' . $field_name .'" value="'.esc_attr($field['value']).'" />';
				echo '</div>';
			break;
			case 'buttonset':
				$field['options']       = isset( $field['options'] ) ? $field['options'] : array();
				echo '<div class="dh-field-buttonset ' .  $field['id']  . '-field'.$dependency_cls.'"'.$dependency_data.'>';
				echo '<div class="dh-buttonset">';
				foreach ( $field['options'] as $key => $value ) {
					echo '<input '.$data_name.' name="' . $field_name  . '"
								id="' . esc_attr($field['name'].'-'.$key)  . '"
				        		value="' . esc_attr( $key ) . '"
				        		type="radio"
								class="dh-opt-value"
				        		' . checked(  $field['value'], esc_attr( $key ), false ) . '
				        		/><label for="'.esc_attr($field['name'].'-'.$key).'">' . esc_html( $value ) . '</label>';
				}
				echo '</div>';
				echo '</div>';
			break;
			case 'radio':
				$field['options']       = isset( $field['options'] ) ? $field['options'] : array();
				echo '<div class="dh-field-radio ' .  $field['id'] . '-field'.$dependency_cls.'"'.$dependency_data.'>';
				echo '<ul>';
				foreach ( $field['options'] as $key => $value ) {
					echo '<li><label><input
				        		name="' . $field_name . '"
				        		value="' . esc_attr( $key ) . '"
				        		type="radio"
								'.$data_name.'
								class="dh-opt-value radio"
				        		' . checked( esc_attr( $field['value'] ), esc_attr( $key ), false ) . '
				        		/> ' . esc_html( $value ) . '</label>
				    	</li>';
				}
				echo '</ul>';
				echo '</div>';
			break;
			case 'text':
				echo '<div class="dh-field-text ' . $field['id']  . '-field'.$dependency_cls.'"'.$dependency_data.'>';
				echo '<input type="text" class="dh-opt-value input_text" name="' . $field_name . '" id="' .  $field['id'] . '" value="' . esc_attr( $field['value'] ) . '" placeholder="' .  $field['placeholder'] . '" style="width:99%" /> ';
				echo '</div>';
			break;
			case 'background':
				wp_enqueue_style( 'wp-color-picker');
				wp_enqueue_script( 'wp-color-picker');
				if(function_exists( 'wp_enqueue_media' )){
					wp_enqueue_media();
				}else{
					wp_enqueue_style('thickbox');
					wp_enqueue_script('media-upload');
					wp_enqueue_script('thickbox');
				}
				$value_default = array(
						'background-color'      => '',
						'background-repeat'     => '',
						'background-attachment' => '',
						'background-position'   => '',
						'background-image'      => '',
						'background-clip'       => '',
						'background-origin'     => '',
						'background-size'       => '',
						'media' => array(),
				);
				$values = wp_parse_args( $field['value'], $value_default );
				echo '<div class="dh-field-background ' . $field['id']  . '-field'.$dependency_cls.'"'.$dependency_data.'>';
				//background color
				echo '<div  class="dh-background-color">';
				echo '<input type="text" class="dh-opt-value" name="' .  $field_name . '[background-color]" id="' .  $field['id'] . '_background_color" value="' . esc_attr( $values['background-color'] ) . '" /> ';
				echo '<script type="text/javascript">
					jQuery(document).ready(function($){
					    $("#'. ( $field['id'] ).'_background_color").wpColorPicker();
					});
				 </script>
				';
				echo '</div>';
				echo '<br>';
				//background repeat
				echo '<div  class="dh-background-repeat">';
				$bg_repeat_options = array('no-repeat'=>'No Repeat','repeat'=>'Repeat All','repea-x'=>'Repeat Horizontally','repeat-y'=>'Repeat Vertically','inherit'=>'Inherit');
				echo '<select class="dh-opt-value dh-chosen-select-nostd" id="' . $field['id'] . '_background_repeat" data-placeholder="' . __( 'Background Repeat', DH_DOMAIN ) . '" name="' . $field_name . '[background-repeat]">';
				echo '<option value=""></option>';
				foreach ( $bg_repeat_options as $key => $value ) {
					echo '<option value="' . esc_attr( $key ) . '" ' . selected( $values['background-repeat'] , esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
				}
				echo '</select> ';
				echo '</div>';
				//background size
				echo '<div  class="dh-background-size">';
				$bg_size_options = array('inherit'=>'Inherit','cover'=>'Cover','contain'=>'Contain');
				echo '<select class="dh-opt-value dh-chosen-select-nostd" id="' . $field['id'] . '_background_size" data-placeholder="' . __( 'Background Size', DH_DOMAIN ) . '" name="' . $field_name . '[background-size]">';
				echo '<option value=""></option>';
				foreach ( $bg_size_options as $key => $value ) {
					echo '<option value="' . esc_attr( $key ) . '" ' . selected( $values['background-size'] , esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
				}
				echo '</select> ';
				echo '</div>';
				//background attachment
				echo '<div  class="dh-background-attachment">';
				$bg_attachment_options = array('fixed'=>'Fixed','scroll'=>'Scroll','inherit'=>'Inherit');
				echo '<select class="dh-opt-value dh-chosen-select-nostd" id="' . $field['id'] . '_background_attachment" data-placeholder="' . __( 'Background Attachment', DH_DOMAIN ) . '"  name="' . $field_name . '[background-attachment]">';
				echo '<option value=""></option>';
				foreach ( $bg_attachment_options as $key => $value ) {
					echo '<option value="' . esc_attr( $key ) . '" ' . selected( $values['background-attachment'] , esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
				}
				echo '</select> ';
				echo '</div>';
				//background position
				echo '<div  class="dh-background-position">';
				$bg_position_options = array(
					'left top' => 'Left Top',
                    'left center' => 'Left center',
                    'left bottom' => 'Left Bottom',
                    'center top' => 'Center Top',
                    'center center' => 'Center Center',
                    'center bottom' => 'Center Bottom',
                    'right top' => 'Right Top',
                    'right center' => 'Right center',
                    'right bottom' => 'Right Bottom'
				);
				echo '<select class="dh-opt-value dh-chosen-select-nostd"  id="' . $field['id'] . '_background_position" data-placeholder="' . __( 'Background Position', DH_DOMAIN ) . '" name="' . $field_name . '[background-position]">';
				echo '<option value=""></option>';
				foreach ( $bg_position_options as $key => $value ) {
					echo '<option value="' . esc_attr( $key ) . '" ' . selected( $values['background-position'] , esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
				}
				echo '</select> ';
				echo '</div>';
				//background image
				
				$image = $values['background-image'];
				$output = !empty( $image ) ? '<img src="'.$image.'" with="100">' : '';
				$btn_text = !empty( $image_id ) ? __( 'Change Image', DH_DOMAIN ) : __( 'Select Image', DH_DOMAIN );
				echo '<br>';
				echo '<div  class="dh-background-image">';
				echo '<div class="dh-field-image-thumb">' . $output . '</div>';
				echo '<input type="hidden" class="dh-opt-value" name="' . $field_name . '[background-image]" id="' . $field['id'] . '_background_image" value="' . esc_attr($values['background-image']) . '" />';
				echo '<input type="button" class="button button-primary" id="' . $field['id'] . '_background_image_upload" value="' . $btn_text . '" /> ';
				echo '<input type="button" class="button" id="' . $field['id'] . '_background_image_clear" value="' . __( 'Clear Image', DH_DOMAIN ) . '" '.(empty($field['value']) ? ' style="display:none"':'').' />';
				?>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#<?php echo esc_attr($field['id']); ?>_background_image_upload').on('click', function(event) {
							event.preventDefault();
							var $this = $(this);
	
							// if media frame exists, reopen
							if(dh_meta_image_frame) {
								dh_meta_image_frame.open();
				                return;
				            }
	
							// create new media frame
							// I decided to create new frame every time to control the selected images
							var dh_meta_image_frame = wp.media.frames.wp_media_frame = wp.media({
								title: "<?php echo __( 'Select or Upload your Image', DH_DOMAIN ); ?>",
								button: {
									text: "<?php echo __( 'Select', DH_DOMAIN ); ?>"
								},
								library: { type: 'image' },
								multiple: false
							});
	
							// when image selected, run callback
							dh_meta_image_frame.on('select', function(){
								var attachment = dh_meta_image_frame.state().get('selection').first().toJSON();
								$this.closest('.dh-background-image').find('input#<?php echo esc_attr($field['id']); ?>_background_image').val(attachment.url);
								var thumbnail = $this.closest('.dh-background-image').find('.dh-field-image-thumb');
								thumbnail.html('');
								thumbnail.append('<img src="' + attachment.url + '" alt="" />');
	
								$this.attr('value', '<?php echo __( 'Change Image', DH_DOMAIN ); ?>');
								$('#<?php echo esc_attr($field['id']); ?>_background_image_clear').css('display', 'inline-block');
							});
	
							// open media frame
							dh_meta_image_frame.open();
						});
	
						$('#<?php echo esc_attr($field['id']); ?>_background_image_clear').on('click', function(event) {
							var $this = $(this);
							$this.hide();
							$('#<?php echo esc_attr($field['id']); ?>_background_image_upload').attr('value', '<?php echo __( 'Select Image', DH_DOMAIN ); ?>');
							$this.closest('.dh-background-image').find('input#<?php echo esc_attr($field['id']); ?>_background_image').val('');
							$this.closest('.dh-background-image').find('.dh-field-image-thumb').html('');
						});
					});
				</script>
							
				<?php
				echo '</div>';
				echo '</div>';
			break;
			case 'custom_font':
				$value_default = array(
						'font-family'      		=> '',
						'font-size'     		=> '',
						'font-style'      		=> '',
						'text-transform'   		=> '',
						'letter-spacing'      	=> '',
						'subset'       			=> '',
				);
				$values = wp_parse_args( $field['value'], $value_default );
				global $google_fonts;
				if(empty($google_fonts))
					include_once (DHINC_DIR . '/lib/google-fonts.php');
				
				$google_fonts_object = json_decode($google_fonts);
				$google_faces = array();
				foreach($google_fonts_object as $obj => $props) {
					$google_faces[$props->family] =  $props->family;
				}
				echo '<div class="dh-field-custom-font ' . ( $field['id'] ) . '-field'.$dependency_cls.'"'.$dependency_data.'>';
				//font-family
				echo '<div  class="custom-font-family">';
				echo '<select data-placeholder="' . __('Select a font family',DH_DOMAIN) . '" class="dh-opt-value dh-chosen-select-nostd"  id="' . $field['id'] . '" name="' . $field_name  . '[font-family]">';
				echo '<option value=""></option>';
				foreach ( $google_faces as $key => $value ) {
					echo '<option value="' . ( $key ) . '" ' . selected( ( $values['font-family'] ), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
				}
				echo '</select> ';
				echo '</div>';
				//font-size
				echo '<div  class="custom-font-size">';
				echo '<select data-placeholder="' . __('Font size',DH_DOMAIN) . '" class="dh-opt-value dh-chosen-select-nostd"  id="' . $field['id'] . '" name="' . $field_name  . '[font-size]">';
				echo '<option value=""></option>';
				foreach ( (array) dh_custom_font_size(true) as $key => $value ) {
					echo '<option value="' . ( $key ) . '" ' . selected( ( $values['font-size'] ), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
				}
				echo '</select> ';
				echo '</div>';
				//font-style
				echo '<div  class="custom-font-style">';
				echo '<select data-placeholder="' . __('Font style',DH_DOMAIN) . '" class="dh-opt-value dh-chosen-select-nostd"  id="' . $field['id'] . '" name="' . $field_name  . '[font-style]">';
				echo '<option value=""></option>';
				foreach ( (array) dh_custom_font_style(true) as $key => $value ) {
					echo '<option value="' . ( $key ) . '" ' . selected( ( $values['font-style'] ), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
				}
				echo '</select> ';
				echo '</div>';
				
				//subset
				$subset = array(
					"latin" => "Latin",
				    "latin-ext" => "Latin Ext",
				    "cyrillic" => "Cyrillic",
				    "cyrillic-ext" => "Cyrillic Ext",
				    "greek" => "Greek",
				    "greek-ext" => "Greek Ext",
				    "vietnamese" => "Vietnamese"
				);
				echo '<div  class="custom-font-subset">';
				echo '<select data-placeholder="' . __('Subset',DH_DOMAIN) . '" class="dh-opt-value dh-chosen-select-nostd"  id="' . $field['id'] . '" name="' . $field_name  . '[subset]">';
				echo '<option value=""></option>';
				foreach ( (array) $subset as $key => $value ) {
					echo '<option value="' . ( $key ) . '" ' . selected( ( $values['subset'] ), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
				}
				echo '</select> ';
				echo '</div>';
				
				echo '</div>';
			break;
			case 'list_color':
				wp_enqueue_style( 'wp-color-picker');
				wp_enqueue_script( 'wp-color-picker');
				echo '<div class="dh-field-list-color ' . ( $field['id'] ) . '-field'.$dependency_cls.'"'.$dependency_data.'>';
				$field['options']       = isset( $field['options'] ) ? $field['options'] : array();
				foreach ($field['options'] as $key=>$label){
					$values[$key] = isset($field['value'][$key]) ? $field['value'][$key] : '';
					echo '<div>'.$label.'<br>';
					echo '<input type="text" class="dh-opt-value" name="' .  $field_name . '['.$key.']" id="' . $field['id'] . '_'.$key .'" value="' . esc_attr( $values[$key] ) . '" /> ';
					echo '<script type="text/javascript">
						jQuery(document).ready(function($){
						    $("#'. $field['id'] . '_'.$key.'").wpColorPicker();
						});
					 </script>
					';
					echo '</div>';
				}
				echo '</div>';
			break;
			case 'image_select':
				$field['options']       = isset( $field['options'] ) ? $field['options'] : array();
				echo '<div class="dh-field-image-select ' . ( $field['id'] ) . '-field'.$dependency_cls.'"'.$dependency_data.'>';
				echo '<ul class="dh-image-select">';
				foreach ( $field['options'] as $key => $value ) {
					echo '<li'.($field['value'] == $key ? ' class="selected"':'').'><label for="' . esc_attr( $key ) . '"><input
			        		name="' . $field_name . '"
							id="' . esc_attr( $key ) . '"
			        		value="' . esc_attr( $key ) . '"
			        		type="radio"
							'.$data_name.'
							class="dh-opt-value"
			        		' . checked( $field['value'], esc_attr( $key ), false ) . '
			        		/><img title="'.esc_attr(@$value['alt']).'" alt="'.esc_attr(@$value['alt']).'" src="'.esc_url(@$value['img']).'"></label>
				    </li>';
				}
				echo '</ul>';
				echo '</div>';
			break;
			case 'import':
				echo '<div class="dh-field-import ' .  $field['id']  . '-field'.$dependency_cls.'"'.$dependency_data.'>';
				echo '<textarea id="' .  $field['id']  . '" name="' .  self::$_option_name  . '[import_code]" placeholder="' . $field['placeholder'] . '" rows="5" cols="20" style="width: 99%;"></textarea><br><br>';
				echo '<button id="dh-opt-import" class="button-primary" name="'.self::$_option_name.'[dh_opt_import]" type="submit">'.__('Import',DH_DOMAIN).'</button>';
				echo ' <em style="font-size:11px;color:#f00">'.esc_html__('WARNING! This will overwrite all existing option values, please proceed with caution!',DH_DOMAIN).'</em>';
				echo '</div>';
			break;
			case 'export':
				$secret = md5( AUTH_KEY . SECURE_AUTH_KEY );
				$link = admin_url('admin-ajax.php?action=dh_download_theme_option&secret=' . $secret);
				echo '<a id="dh-opt-export" class="button-primary" href="'.esc_url($link).'">'.__('Export',DH_DOMAIN).'</a>';
			break;
			default:
			break;
		}
		
	}
	
	public function get_sections(){
		$section = array(
			'general' => array (
					'icon' => 'fa fa-home',
					'title' => __ ( 'General', DH_DOMAIN ),
					'desc' => __ ( '<p class="description">Here you will set your site-wide preferences.</p>', DH_DOMAIN ),
					'fields' => array (
							array (
									'name' => 'logo',
									'type' => 'image',
									'value'=>get_template_directory_uri().'/assets/images/logo.png',
									'label' => __ ( 'Logo', DH_DOMAIN ),
									'desc' => __ ( 'Upload your own logo.', DH_DOMAIN ),
							),
							array (
									'name' => 'logo-fixed',
									'type' => 'image',
									'value'=>get_template_directory_uri().'/assets/images/logo.png',
									'label' => __ ( 'Fixed Menu Logo', DH_DOMAIN ),
									'desc' => __ ( 'Upload your own logo.This is optional use when fixed menu', DH_DOMAIN ),
							),
							array (
								'name' => 'logo-transparent',
								'type' => 'image',
								'value'=>get_template_directory_uri().'/assets/images/logo-dark.png',
								'label' => __ ( 'Transparent Menu Logo', DH_DOMAIN ),
								'desc' => __ ( 'Upload your own logo.This is optional use for menu transparent', DH_DOMAIN ),
							),
							array (
								'name' => 'logo-mobile',
								'type' => 'image',
								'value'=>get_template_directory_uri().'/assets/images/logo-mobile.png',
								'label' => __ ( 'Mobile Version Logo', DH_DOMAIN ),
								'desc' => __ ( 'Use this option to change your logo for mobile devices if your logo width is quite long to fit in mobile device screen.', DH_DOMAIN ),
							),
							array (
									'name' => 'favicon',
									'type' => 'image',
									'value'=>get_template_directory_uri().'/assets/images/favicon.png',
									'label' => __ ( 'Favicon', DH_DOMAIN ),
									'desc' => __ ( 'Image that will be used as favicon (32px32px).', DH_DOMAIN ),
							),
							array (
									'name' => 'apple57',
									'type' => 'image',
									'label' => __ ( 'Apple Iphone Icon', DH_DOMAIN ),
									'desc' => __ ( 'Apple Iphone Icon (57px 57px).', DH_DOMAIN ),
							),	
							array (
									'name' => 'apple72',
									'type' => 'image',
									'label' => __ ( 'Apple iPad Icon', DH_DOMAIN ),
									'desc' => __ ( 'Apple Iphone Retina Icon (72px 72px).', DH_DOMAIN ),
							),
							array (
									'name' => 'apple114',
									'type' => 'image',
									'label' => __ ( 'Apple Retina Icon', DH_DOMAIN ),
									'desc' => __ ( 'Apple iPad Retina Icon (144px 144px).', DH_DOMAIN ),
							),
							array (
								'name' => 'back-to-top',
								'type' => 'switch',
								'on'	=> __('Show',DH_DOMAIN),
								'off'	=> __('Hide',DH_DOMAIN),
								'label' => __ ( 'Back To Top Button', DH_DOMAIN ),
								'value'	=> 1,
								'desc' => __ ( 'Toggle whether or not to enable a back to top button on your pages.', DH_DOMAIN ),
							),
							array (
								'name' => 'popup_newsletter',
								'type' => 'switch',
								'on'	=> __('Show',DH_DOMAIN),
								'off'	=> __('Hide',DH_DOMAIN),
								'label' => __ ( 'Newsletter', DH_DOMAIN ),
								'value'	=> 1,
								'desc' => __ ( 'Toggle whether or not to enable modal Newsletter in your site.', DH_DOMAIN ),
							),
							array(
								'name' => 'popup_newsletter_interval',
								'type' => 'text',
								'dependency' => array('element'=>'popup_newsletter','value'=>array('1')),
								'label' => __('Newsletter refresh interval',DH_DOMAIN),
								'value'=>'1',
								'desc'=>__('Enter day number to refresh newsletter. value 0 will be shown every page',DH_DOMAIN)
							),
					)
			),
			'design_layout' => array(
					'icon' => 'fa fa-columns',
					'title' => __ ( 'Design and Layout', DH_DOMAIN ),
					'desc' => __ ( '<p class="description">Customize Design and Layout.</p>', DH_DOMAIN ),
					'fields'=>array(
						array (
								'name' => 'site-style',
								'type' => 'buttonset',
								'label' => __ ( 'Site Layout', DH_DOMAIN ),
								'desc'=>__('Select between wide or boxed site layout',DH_DOMAIN),
								'value'=>'default',
								'options'=>array(
									'default'=> __('Default',DH_DOMAIN),
									'creative'=> __('Creative',DH_DOMAIN)
								)
						),
						array (
							'name' => 'site-layout',
							'type' => 'buttonset',
							'label' => __ ( 'Site Layout', DH_DOMAIN ),
							'desc'=>__('Select between wide or boxed site layout',DH_DOMAIN),
							'value'=>'wide',
							'options'=>array(
								'wide'=> __('Wide',DH_DOMAIN),
								'boxed'=> __('Boxed',DH_DOMAIN)
							)
						),
						array(
							'name'=>'body-bg',
							'type' => 'background',
							'dependency' => array('element'=>'site-layout','value'=>array('boxed')),
							'label' => __('Background', DH_DOMAIN),
							'desc'=> __('Select your boxed background', DH_DOMAIN),
							'value' => array('background-color'=>'#f3f3f3','background-image'=>get_template_directory_uri().'/assets/images/bg-body.png', 'background-repeat' => 'repeat' ),
						),
					)
			),
			'color_typography' => array(
					'icon' => 'fa fa-font',
					'title' => __ ( 'Color and Typography', DH_DOMAIN ),
					'desc' => __ ( '<p class="description">Customize Color and Typography.</p>', DH_DOMAIN ),
					'fields'=>array(
								array(
										'name' => 'brand-primary',
										'type' => 'color',
										'label' => __('Brand primary',DH_DOMAIN),
										'value'=>'#262626'
								),
								array(
										'name' => 'brand-success',
										'type' => 'color',
										'label' => __('Brand success',DH_DOMAIN),
										'value'=>'#57bb58',
								),
								array(
										'name' => 'brand-info',
										'type' => 'color',
										'label' => __('Brand info',DH_DOMAIN),
										'value'=>'#5788bb'
								),
								array(
										'name' => 'brand-warning',
										'type' => 'color',
										'label' => __('Brand warning',DH_DOMAIN),
										'value'=>'#f0ad4e'
								),
								array(
										'name' => 'brand-danger',
										'type' => 'color',
										'label' => __('Brand danger',DH_DOMAIN),
										'value'=>'#bb5857',
								),
								array(
										'name' => 'text-color',
										'type' => 'color',
										'label' => __('Text color',DH_DOMAIN),
										'value'=>'#525252',
								),
								array(
										'name' => 'link-color',
										'type' => 'color',
										'label' => __('Link color',DH_DOMAIN),
										'value'=>'#262626'
								),
								array(
										'name' => 'link-hover-color',
										'type' => 'color',
										'label' => __('Link hover color',DH_DOMAIN),
										'value'=>'#262626',
								),
								array(
										'name' => 'headings-color',
										'type' => 'color',
										'label' => __('Headings Color',DH_DOMAIN),
										'value'=>'#262626',
								),
								array(
										'name' => 'body-typography',
										'type' => 'custom_font',
										'field-label' => __('Body',DH_DOMAIN),
										'value' => array()
								),
								array(
										'name' => 'navbar-typography',
										'type' => 'custom_font',
										'field-label' => __('Navigation',DH_DOMAIN),
										'value' => array()
								),
								array(
										'name' => 'h1-typography',
										'type' => 'custom_font',
										'field-label' => __('Heading H1',DH_DOMAIN),
										'value' => array()
								),
								array(
										'name' => 'h2-typography',
										'type' => 'custom_font',
										'field-label' => __('Heading H2',DH_DOMAIN),
										'value' => array()
								),
								array(
										'name' => 'h3-typography',
										'type' => 'custom_font',
										'field-label' => __('Heading H3',DH_DOMAIN),
										'value' => array()
								),
								array(
										'name' => 'h4-typography',
										'type' => 'custom_font',
										'field-label' => __('Heading H4',DH_DOMAIN),
										'value' => array()
								),
								array(
										'name' => 'h5-typography',
										'type' => 'custom_font',
										'field-label' => __('Heading H5',DH_DOMAIN),
										'value' => array()
								),
								array(
										'name' => 'h6-typography',
										'type' => 'custom_font',
										'field-label' => __('Heading H6',DH_DOMAIN),
										'value' => array()
								),
					)
			),
			'header'=>array(
					'icon' => 'fa fa-header',
					'title' => __ ( 'Header', DH_DOMAIN ),
					'desc' => __ ( '<p class="description">Customize Header.</p>', DH_DOMAIN ),
					'fields'=>array(
							array(
									'name' => 'header-style',
									'type' => 'select',
									'label' => __('Header Style', DH_DOMAIN),
									'desc' => __('Please select your header style here.', DH_DOMAIN),
									'options' => array(
											'center'=>__('Center',DH_DOMAIN),
											'below'=>__('Below',DH_DOMAIN)
									),
									'value'=>'below'
							),
							array(
								'name' => 'topbar_setting',
								'type' => 'heading',
								'text' => __('Topbar Settings',DH_DOMAIN),
							),
							array(
									'name' => 'show-topbar',
									'type' => 'switch',
									'on'	=> __('Show',DH_DOMAIN),
									'off'	=> __('Hide',DH_DOMAIN),
									'label' => __('Display top bar', DH_DOMAIN),
									'desc' => __('Enable or disable the top bar.<br> See Social icons tab to enable the social icons inside it.<br> Set a Top menu from  Appearance - Menus ', DH_DOMAIN),
									'value' => '0' // 1 = checked | 0 = unchecked
							),
							array(
									'name' => 'left-topbar-content',
									'type' => 'buttonset',
									'dependency' => array('element'=>'show-topbar','value'=>array('1')),
									'label' => __('Left topbar content', DH_DOMAIN),
									'options' => array(
										'none'=>__('None',DH_DOMAIN),
										'menu_social'=>__('Social',DH_DOMAIN),
										'info'=>__('Site Info',DH_DOMAIN),
										'custom'=>__('Custom HTML',DH_DOMAIN),
									),
									'value'=>'info'
							),
							array(
								'name' => 'left-topbar-social',
								'type' => 'muitl-select',
								'label' => __('Top Social Icon',DH_DOMAIN),
								'dependency' => array('element'=>'left-topbar-content','value'=>array('menu_social')),
								'value' => array('facebook','twitter','google-plus','pinterest','rss','instagram'),
								'options'=>array(
									'facebook'=>'Facebook',
									'twitter'=>'Twitter',
									'google-plus'=>'Google Plus',
									'pinterest'=>'Pinterest',
									'linkedin'=>'Linkedin',
									'rss'=>'Rss',
									'instagram'=>'Instagram',
									'github'=>'Github',
									'behance'=>'Behance',
									'stack-exchange'=>'Stack Exchange',
									'tumblr'=>'Tumblr',
									'soundcloud'=>'SoundCloud',
									'dribbble'=>'Dribbble'
								),
							),
							array(
								'name' => 'left-topbar-phone',
								'type' => 'text',
								'dependency' => array('element'=>'left-topbar-content','value'=>array('info')),
								'label' => __('Phone number',DH_DOMAIN),
								'value'=>'(123) 456 789'
							),
							array(
								'name' => 'left-topbar-email',
								'type' => 'text',
								'dependency' => array('element'=>'left-topbar-content','value'=>array('info')),
								'label' => __('Email',DH_DOMAIN),
								'value'=>'info@domain.com'
							),
							array(
								'name' => 'left-topbar-skype',
								'type' => 'text',
								'dependency' => array('element'=>'left-topbar-content','value'=>array('info')),
								'label' => __('Skype',DH_DOMAIN),
								'value'=>'skype.name'
							),
							array(
									'name' => 'left-topbar-custom-content',
									'type' => 'textarea',
									'dependency' => array('element'=>'left-topbar-content','value'=>array('custom')),
									'label' => __('Left Topbar Content Custom HTML', DH_DOMAIN),
							
							),
							array(
									'name' => 'right-topbar-content',
									'type' => 'buttonset',
									'dependency' => array('element'=>'show-topbar','value'=>array('1')),
									'label' => __('Right topbar content', DH_DOMAIN),
									'options' => array(
											'none'=>__('None',DH_DOMAIN),
											'menu'=>__('Navigation',DH_DOMAIN),
											'menu_social'=>__('Social',DH_DOMAIN),
											'custom'=>__('Custom HTML',DH_DOMAIN),
									),
									'value'=>'menu'	
							),
							array(
								'name' => 'right-topbar-account',
								'type' => 'switch',
								'label' => __('Use Account Url', DH_DOMAIN),
								'dependency' => array('element'=>'right-topbar-content','value'=>array('menu')),
								'desc' => __('Use account url in right topbar menu', DH_DOMAIN),
								'value' => '0' // 1 = checked | 0 = unchecked
							),
							array(
								'name' => 'topbar-social',
								'type' => 'muitl-select',
								'label' => __('Top Social Icon',DH_DOMAIN),
								'dependency' => array('element'=>'right-topbar-content','value'=>array('menu_social')),
								'value' => array('facebook','twitter','google-plus','pinterest','rss','instagram'),
								'options'=>array(
									'facebook'=>'Facebook',
									'twitter'=>'Twitter',
									'google-plus'=>'Google Plus',
									'pinterest'=>'Pinterest',
									'linkedin'=>'Linkedin',
									'rss'=>'Rss',
									'instagram'=>'Instagram',
									'github'=>'Github',
									'behance'=>'Behance',
									'stack-exchange'=>'Stack Exchange',
									'tumblr'=>'Tumblr',
									'soundcloud'=>'SoundCloud',
									'dribbble'=>'Dribbble'
								),
							),
							array(
									'name' => 'right-topbar-custom-content',
									'type' => 'textarea',
									'dependency' => array('element'=>'right-topbar-content','value'=>array('custom')),
									'label' => __('Right Topbar Content Custom HTML', DH_DOMAIN),
										
							),
							array(
								'name' => 'main_navbar_setting',
								'type' => 'heading',
								'text' => __('Main Navbar Settings',DH_DOMAIN),
							),
							array(
									'name' => 'sticky-menu',
									'type' => 'switch',
									'label' => __('Sticky Top menu', DH_DOMAIN),
									'desc' => __('Enable or disable the sticky menu.', DH_DOMAIN),
									'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
									'name' => 'custom-sticky-color',
									'type' => 'switch',
									'label' => __('Custom Sticky Color', DH_DOMAIN),
									'dependency' => array('element'=>'sticky-menu','value'=>array('1')),
									'desc' => __('Custom sticky menu color scheme ?', DH_DOMAIN),
									'value' => '0' // 1 = checked | 0 = unchecked
							),
							array(
									'name' => 'sticky-menu-bg',
									'type' => 'color',
									'label' => __('Sticky menu background', DH_DOMAIN),
									'dependency' => array('element'=>'custom-sticky-color','value'=>array('1')),
									'value' => ''
							),
							array(
									'name' => 'sticky-menu-color',
									'type' => 'color',
									'label' => __('Sticky menu color', DH_DOMAIN),
									'dependency' => array('element'=>'custom-sticky-color','value'=>array('1')),
									'value' => ''
							),
							array(
								'name' => 'sticky-menu-hover-color',
								'type' => 'color',
								'label' => __('Sticky menu hover color', DH_DOMAIN),
								'dependency' => array('element'=>'custom-sticky-color','value'=>array('1')),
								'value' => ''
							),
							array(
									'name' => 'menu-transparent',
									'type' => 'switch',
									'label' => __('Transparent Main Menu', DH_DOMAIN),
									'desc' => __('Enable or disable main menu background transparency', DH_DOMAIN),
									'value' => '0' // 1 = checked | 0 = unchecked
							),
							array(
									'name' => 'ajaxsearch',
									'type' => 'switch',
									'label' => __('Ajax Search in menu',DH_DOMAIN),
									'desc' => __('Enable or disable ajax search in menu.', DH_DOMAIN),
									'value' => '1' // 1 = checked | 0 = unchecked
							),
							array (
								'name' => 'heading-bg',
								'type' => 'image',
								'desc'=>__('Change Heading background',DH_DOMAIN),
								'label' => __ ( 'Heading background', DH_DOMAIN ),
							),
							array(
									'name' => 'breadcrumb',
									'type' => 'switch',
									'on'	=> __('Show',DH_DOMAIN),
									'off'	=> __('Hide',DH_DOMAIN),
									'label' => __('Show breadcrumb',DH_DOMAIN),
									'desc' => __('Enable or disable the site path under the page title.', DH_DOMAIN),
									'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
								'name' => 'header_color_setting',
								'type' => 'heading',
								'text' => __('Header Color Scheme',DH_DOMAIN),
							),
							array(
									'name' => 'header-color',
									'type' => 'select',
									'label' => __('Header Color Scheme', DH_DOMAIN),
									'desc' => __('Custom Topbar and Main menu color scheme .', DH_DOMAIN),
									'options' => array(
										'default'=>__('Default',DH_DOMAIN),
										'custom'=>__('Custom',DH_DOMAIN)
									),
									'value'=>'default'
							),
							array(
									'name' => 'header-custom-color',
									'type' => 'list_color',
									'dependency' => array('element'=>'header-color','value'=>array('custom')),
									'options' => array(
										'topbar-bg'=>__('Topbar Background',DH_DOMAIN),
										'topbar-font'=>__('Topbar Color',DH_DOMAIN),
										'topbar-link'=>__('Topbar Link Color',DH_DOMAIN),
										'header-bg'=>__('Header Background',DH_DOMAIN),
										'header-color'=>__('Header Color',DH_DOMAIN),
										'header-hover-color'=>__('Header Hover Color',DH_DOMAIN),
										'navbar-bg'=>__('Navbar Background',DH_DOMAIN),
										'navbar-font'=>__('Navbar Color',DH_DOMAIN),
										'navbar-font-hover'=>__('Navbar Color Hover',DH_DOMAIN),
										'navbar-dd-bg'=>__('Navbar Dropdown Background',DH_DOMAIN),
										'navbar-dd-hover-bg'=>__('Navbar Dropdown Hover Background',DH_DOMAIN),
										'navbar-dd-font'=>__('Navbar Dropdown Color',DH_DOMAIN),
										'navbar-dd-font-hover'=>__('Navbar Dropdown Color Hover',DH_DOMAIN),
										'navbar-dd-mm-title'=>__('Navbar Dropdown Mega Menu Title',DH_DOMAIN),
									)
							),
					)
			),
			'footer'=>array(
					'icon' => 'fa fa-list-alt',
					'title' => __ ( 'Footer', DH_DOMAIN ),
					'desc' => __ ( '<p class="description">Customize Footer.</p>', DH_DOMAIN ),
					'fields'=>array(
						array(
								'name' => 'footer-area',
								'type' => 'switch',
								'on'	=> __('Show',DH_DOMAIN),
								'off'	=> __('Hide',DH_DOMAIN),
								'label' => __('Footer Widget Area',DH_DOMAIN),
								'desc' => __('Do you want use the main footer that contains all the widgets areas.', DH_DOMAIN),
								'value' => '1' // 1 = checked | 0 = unchecked
						),
						array(
								'name' => 'footer-area-columns',
								'type' => 'image_select',
								'label' => __('Footer Area Columns', DH_DOMAIN),
								'desc' => __('Please select the number of columns you would like for your footer.', DH_DOMAIN),
								'dependency' => array('element'=>'footer-area','value'=>array('1')),
								'options' => array(
										'2' => array('alt' => '2 Column', 'img' => DHINC_ASSETS_URL.'/images/2col.png'),
										'3' => array('alt' => '3 Column', 'img' => DHINC_ASSETS_URL.'/images/3col.png'),
										'4' => array('alt' => '4 Column', 'img' => DHINC_ASSETS_URL.'/images/4col.png'),
										'5' => array('alt' => '5 Column', 'img' => DHINC_ASSETS_URL.'/images/5col.png'),
								),
								'value' => '5'
						),
						array(
							'name' => 'footer-featured',
							'type' => 'switch',
							'on'	=> __('Show',DH_DOMAIN),
							'off'	=> __('Hide',DH_DOMAIN),
							'label' => __('Footer Featured',DH_DOMAIN),
							'desc' => __('Do you want show featured in footer.', DH_DOMAIN),
							'value' => '1' // 1 = checked | 0 = unchecked
						),
						array(
							'name' => 'footer-featured-1',
							'type' => 'textarea',
							'dependency' => array('element'=>'footer-featured','value'=>array('1')),
							'label' => __('Footer Featured Column 1',DH_DOMAIN),
							'desc'=>__('Footer featured column 1 text.',DH_DOMAIN),
							'value'=>'<h4 class="footer-featured-title">FREE UK STANDARD DELIVERY</h4>'."\n".'on order over $ 85 - use code ukfre75',
						),
						array(
							'name' => 'footer-featured-2',
							'type' => 'textarea',
							'dependency' => array('element'=>'footer-featured','value'=>array('1')),
							'label' => __('Footer Featured Column 2',DH_DOMAIN),
							'desc'=>__('Footer featured column 2 text.',DH_DOMAIN),
							'value'=>'<h4 class="footer-featured-title">COLLECT FROM STORE</h4>'."\n".'$2 next day delivery at over 250 store',
						),
						array(
							'name' => 'footer-featured-3',
							'type' => 'textarea',
							'dependency' => array('element'=>'footer-featured','value'=>array('1')),
							'label' => __('Footer Featured Column 3',DH_DOMAIN),
							'desc'=>__('Footer featured column 3 text.',DH_DOMAIN),
							'value'=>'<h4 class="footer-featured-title">FREE INTERNATIONAL DELIVERY</h4>'."\n".'onorder over $100 - use code free100',
						),
						array (
							'name' => 'footer-logo',
							'type' => 'image',
							'value'=>get_template_directory_uri().'/assets/images/logo.png',
							'label' => __ ( 'Footer Logo', DH_DOMAIN ),
							'desc' => __ ( 'Upload your footer logo.', DH_DOMAIN ),
						),
						array(
							'name' => 'footer-info',
							'type' => 'textarea',
							'on'	=> __('Show',DH_DOMAIN),
							'off'	=> __('Hide',DH_DOMAIN),
							'label' => __('Footer Info',DH_DOMAIN),
							'value' => 'Copyright© 2014 the Phoenix  Store. All Rights Reserved.'."\n".'Mobile: (00) 123 456 789'."\n".'Email: <a href="maito:thephoenix@info.com">thephoenix@info.com</a>'
						),
						array(
							'name' => 'footer-menu',
							'type' => 'switch',
							'on'	=> __('Show',DH_DOMAIN),
							'off'	=> __('Hide',DH_DOMAIN),
							'label' => __('Footer Menu',DH_DOMAIN),
							'desc' => __('Do you want use menu in main footer.', DH_DOMAIN),
							'value' => '1' // 1 = checked | 0 = unchecked
						),
						array(
							'name' => 'footer-copyright',
							'type' => 'text',
							'label' => __('Footer Copyright Text',DH_DOMAIN),
							'desc'=>__('Please enter the copyright section text.',DH_DOMAIN),
							'value'=>'Copyright 2012 - Powered by <a href="http://wordpress.org">WordPress</a>'
						),
						array(
							'name' => 'footer_color_setting',
							'type' => 'heading',
							'text' => __('Footer Color Scheme',DH_DOMAIN),
						),
						array(
								'name' => 'footer-color',
								'type' => 'switch',
								'label' => __('Custom Footer Color Scheme',DH_DOMAIN),
								'value' => '0' // 1 = checked | 0 = unchecked
						),
						array(
								'name' => 'footer-custom-color',
								'type' => 'list_color',
								'dependency' => array('element'=>'footer-color','value'=>array('1')),
								'options' => array(
										'footer-widget-bg'=>__('Footer Widget Area Background',DH_DOMAIN),
										'footer-widget-color'=>__('Footer Widget Area Color',DH_DOMAIN),
										'footer-widget-link'=>__('Footer Widget Area Link',DH_DOMAIN),
										'footer-widget-link-hover'=>__('Footer Widget Area Link Hover',DH_DOMAIN),
										'footer-bg'=>__('Footer Copyright Background',DH_DOMAIN),
										'footer-color'=>__('Footer Copyright Color',DH_DOMAIN),
										'footer-link'=>__('Footer Copyright Link',DH_DOMAIN),
										'footer-link-hover'=>__('Footer Copyright Link Hover',DH_DOMAIN),
								)
						),
					)
			),
			'blog'=>array(
					'icon' => 'fa fa-pencil',
					'title' => __ ( 'Blog', DH_DOMAIN ),
					'desc' => __ ( '<p class="description">Customize Blog.</p>', DH_DOMAIN ),
					'fields'=>array(
							array(
								'name' => 'list_blog_setting',
								'type' => 'heading',
								'text' => __('List Blog Settings',DH_DOMAIN),
							),
							array(
									'name' => 'blog-layout',
									'type' => 'image_select',
									'label' => __('Main Blog Layout', DH_DOMAIN),
									'desc' => __('Select main blog layout. Choose between 1, 2 or 3 column layout.', DH_DOMAIN),
									'options' => array(
											'full-width' => array('alt' => 'No sidebar', 'img' => DHINC_ASSETS_URL.'/images/1col.png'),
											'left-sidebar' => array('alt' => '2 Column Left', 'img' => DHINC_ASSETS_URL.'/images/2cl.png'),
											'right-sidebar' => array('alt' => '2 Column Right', 'img' => DHINC_ASSETS_URL.'/images/2cr.png'),
									),
									'value' => 'right-sidebar'
							),
							array(
									'name' => 'archive-layout',
									'type' => 'image_select',
									'label' => __('Archive Layout', DH_DOMAIN),
									'desc' => __('Select Archive layout. Choose between 1, 2 or 3 column layout.', DH_DOMAIN),
									'options' => array(
											'full-width' => array('alt' => 'No sidebar', 'img' => DHINC_ASSETS_URL.'/images/1col.png'),
											'left-sidebar' => array('alt' => '2 Column Left', 'img' => DHINC_ASSETS_URL.'/images/2cl.png'),
											'right-sidebar' => array('alt' => '2 Column Right', 'img' => DHINC_ASSETS_URL.'/images/2cr.png'),
									),
									'value' => 'right-sidebar'
							),
							array(
									'name' => 'blogs-style',
									'type' => 'select',
									'label' => __('Style', DH_DOMAIN),
									'desc' => __('How your blog posts will display.', DH_DOMAIN),
									'options' => array(
											'default'=>__('Default',DH_DOMAIN),
											'medium'=>__('Medium',DH_DOMAIN),
											'grid'=>__('Grid',DH_DOMAIN),
											'masonry'=>__('Masonry',DH_DOMAIN),
											'timeline'=>__('Timeline',DH_DOMAIN),
									),
									'value' => 'default'
							),
							array(
								'name' => 'blogs-columns',
								'type' => 'image_select',
								'label' => __('Blogs Columns', DH_DOMAIN),
								'desc' => __('Select blogs columns.', DH_DOMAIN),
								'dependency' => array('element'=>'blog-style','value'=>array('grid','masonry')),
								'options' => array(
									'2' => array('alt' => '2 Column', 'img' => DHINC_ASSETS_URL.'/images/2col.png'),
									'3' => array('alt' => '3 Column', 'img' => DHINC_ASSETS_URL.'/images/3col.png'),
									'4' => array('alt' => '4 Column', 'img' => DHINC_ASSETS_URL.'/images/4col.png'),
								),
								'value' => '3'
							),
							array(
									'type' => 'select',
									'label' => __( 'Pagination', DH_DOMAIN ),
									'name' => 'blogs-pagination',
									'options'		=>array(
											'page_num'=>__('Page Number',DH_DOMAIN),
											'loadmore'=>__('Load More Button',DH_DOMAIN),
											'infinite_scroll'=>__('Infinite Scrolling',DH_DOMAIN),
											'no'=>__('No',DH_DOMAIN),
									),
									'value'=>'page_num',
									'desc' => __( 'Choose pagination type.', DH_DOMAIN ),
							),
							array(
									'type' => 'text',
									'label' => __( 'Load More Button Text', DH_DOMAIN ),
									'name' => 'blogs-loadmore-text',
									'dependency'  => array( 'element' => "blog-pagination", 'value' => array( 'loadmore' ) ),
									'value'		=>__('Load More',DH_DOMAIN)
							),
							
							array(
									'name' => 'blogs-show-post-title',
									'type' => 'switch',
									'on'	=> __('Show',DH_DOMAIN),
									'off'	=> __('Hide',DH_DOMAIN),
									'label' => __('Show/Hide Title',DH_DOMAIN),
									'desc'=>__('In Archive Blog. Show/Hide the post title below the featured',DH_DOMAIN),
									'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
									'name' => 'blogs-link-post-title',
									'type' => 'switch',
									'label' => __('Link Title To Post',DH_DOMAIN),
									'desc'=>__('In Archive Blog. Choose if the title should be a link to the single post page.',DH_DOMAIN),
									'dependency' => array('element'=>'blogs-show-post-title','value'=>array('1')),
									'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
									'name' => 'blogs-show-featured',
									'type' => 'switch',
									'on'	=> __('Show',DH_DOMAIN),
									'off'	=> __('Hide',DH_DOMAIN),
									'label' => __('Show Featured Image',DH_DOMAIN),
									'desc'=>__('In Archive Blog. Show/Hide the post featured Image',DH_DOMAIN),
									'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
								'name' => 'blogs-excerpt-length',
								'type' => 'text',
								'label' => __('Excerpt Length',DH_DOMAIN),
								'dependency' => array( 'element' => "blog-style", 'value' => array( 'default','medium','grid','masonry' ) ),
								'desc'=>__('In Archive Blog. Enter the number words excerpt',DH_DOMAIN),
								'value' => 30
							),
							array(
									'name' => 'blogs-show-date',
									'type' => 'switch',
									'on'	=> __('Show',DH_DOMAIN),
									'off'	=> __('Hide',DH_DOMAIN),
									'label' => __('Date Meta',DH_DOMAIN),
									'desc'=>__('In Archive Blog. Show/Hide the date meta',DH_DOMAIN),
									'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
									'name' => 'blogs-show-comment',
									'type' => 'switch',
									'on'	=> __('Show',DH_DOMAIN),
									'off'	=> __('Hide',DH_DOMAIN),
									'label' => __('Comment Meta',DH_DOMAIN),
									'desc'=>__('In Archive Blog. Show/Hide the comment meta',DH_DOMAIN),
									'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
									'name' => 'blogs-show-category',
									'type' => 'switch',
									'label' => __('Show/Hide Category',DH_DOMAIN),
									'desc'=>__('In Archive Blog. Show/Hide the category meta',DH_DOMAIN),
									'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
								'name' => 'blogs-show-author',
								'type' => 'switch',
								'on'	=> __('Show',DH_DOMAIN),
								'off'	=> __('Hide',DH_DOMAIN),
								'dependency' => array( 'element' => "blog-style", 'value' => array( 'default','medium','grid','masonry' ) ),
								'label' => __('Author Meta',DH_DOMAIN),
								'desc'=>__('In Archive Blog. Show/Hide the author meta',DH_DOMAIN),
								'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
									'name' => 'blogs-show-tag',
									'type' => 'switch',
									'on'	=> __('Show',DH_DOMAIN),
									'off'	=> __('Hide',DH_DOMAIN),
									'dependency' => array( 'element' => "blog-style", 'value' => array( 'default','medium','grid','masonry' ) ),
									'label' => __('Tags',DH_DOMAIN),
									'desc'=>__('In Archive Blog. If enabled it will show tag.',DH_DOMAIN),
									'value' => '0' // 1 = checked | 0 = unchecked
							),
							
							array(
									'name' => 'blogs-show-readmore',
									'type' => 'switch',
									'on'	=> __('Show',DH_DOMAIN),
									'off'	=> __('Hide',DH_DOMAIN),
									'dependency' => array( 'element' => "blog-style", 'value' => array( 'default','medium','grid','masonry' ) ),
									'label' => __('Show/Hide Readmore',DH_DOMAIN),
									'desc'=>__('In Archive Blog. Show/Hide the post readmore',DH_DOMAIN),
									'value' => '1' // 1 = checked | 0 = unchecked
							),
									
							array(
								'name' => 'single_blog_setting',
								'type' => 'heading',
								'text' => __('Single Blog Settings',DH_DOMAIN),
							),
							array(
								'name' => 'single-layout',
								'type' => 'image_select',
								'label' => __('Single Blog Layout', DH_DOMAIN),
								'desc' => __('Select single content and sidebar alignment. Choose between 1, 2 or 3 column layout.', DH_DOMAIN),
								'options' => array(
									'full-width' => array('alt' => 'No sidebar', 'img' => DHINC_ASSETS_URL.'/images/1col.png'),
									'left-sidebar' => array('alt' => '2 Column Left', 'img' => DHINC_ASSETS_URL.'/images/2cl.png'),
									'right-sidebar' => array('alt' => '2 Column Right', 'img' => DHINC_ASSETS_URL.'/images/2cr.png'),
								),
								'value' => 'right-sidebar'
							),
						//as---
							array(
								'name' => 'blog-show-date',
								'type' => 'switch',
								'on'	=> __('Show',DH_DOMAIN),
								'off'	=> __('Hide',DH_DOMAIN),
								'label' => __('Date Meta',DH_DOMAIN),
								'desc'=>__('In Single Blog. Show/Hide the date meta',DH_DOMAIN),
								'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
								'name' => 'blog-show-comment',
								'type' => 'switch',
								'on'	=> __('Show',DH_DOMAIN),
								'off'	=> __('Hide',DH_DOMAIN),
								'label' => __('Comment Meta',DH_DOMAIN),
								'desc'=>__('In Single Blog. Show/Hide the comment meta',DH_DOMAIN),
								'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
								'name' => 'blog-show-category',
								'type' => 'switch',
								'label' => __('Show/Hide Category',DH_DOMAIN),
								'desc'=>__('In Single Blog. Show/Hide the category',DH_DOMAIN),
								'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
								'name' => 'blog-show-author',
								'type' => 'switch',
								'on'	=> __('Show',DH_DOMAIN),
								'off'	=> __('Hide',DH_DOMAIN),
								'label' => __('Author Meta',DH_DOMAIN),
								'desc'=>__('In Single Blog. Show/Hide the author meta',DH_DOMAIN),
								'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
								'name' => 'blog-show-tag',
								'type' => 'switch',
								'on'	=> __('Show',DH_DOMAIN),
								'off'	=> __('Hide',DH_DOMAIN),
								'label' => __('Show/Hide Tag',DH_DOMAIN),
								'desc'=>__('In Single Blog. If enabled it will show tag.',DH_DOMAIN),
								'value' => '1' // 1 = checked | 0 = unchecked
							),
						//as--
							array(
									'name' => 'show-authorbio',
									'type' => 'switch',
									'on'	=> __('Show',DH_DOMAIN),
									'off'	=> __('Hide',DH_DOMAIN),
									'label' => __('Show Author Bio',DH_DOMAIN),
									'desc'=>__('Display the author bio at the bottom of post on single post page ?',DH_DOMAIN),
									'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
									'name' => 'show-postnav',
									'type' => 'switch',
									'on'	=> __('Show',DH_DOMAIN),
									'off'	=> __('Hide',DH_DOMAIN),
									'label' => __('Show Next/Prev Post Link On Single Post Page',DH_DOMAIN),
									'desc'=>__('Using this will add a link at the bottom of every post page that leads to the next/prev post.',DH_DOMAIN),
									'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
									'name' => 'show-related',
									'type' => 'switch',
									'on'	=> __('Show',DH_DOMAIN),
									'off'	=> __('Hide',DH_DOMAIN),
									'label' => __('Show Related Post On Single Post Page',DH_DOMAIN),
									'desc'=>__('Display related post the bottom of posts?',DH_DOMAIN),
									'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
									'name' => 'show-post-share',
									'type' => 'switch',
									'on'	=> __('Show',DH_DOMAIN),
									'off'	=> __('Hide',DH_DOMAIN),
									'label' => __('Show Sharing Button',DH_DOMAIN),
									'desc'=>__('Activate this to enable social sharing buttons on single post page.',DH_DOMAIN),
									'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
									'name' => 'post-fb-share',
									'type' => 'switch',
									'dependency' => array('element'=>'show-post-share','value'=>array('1')),
									'label' => __('Share on Facebook',DH_DOMAIN),
									'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
									'name' => 'post-tw-share',
									'type' => 'switch',
									'dependency' => array('element'=>'show-post-share','value'=>array('1')),
									'label' => __('Share on Twitter',DH_DOMAIN),
									'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
									'name' => 'post-go-share',
									'type' => 'switch',
									'dependency' => array('element'=>'show-post-share','value'=>array('1')),
									'label' => __('Share on Google+',DH_DOMAIN),
									'value' => '1' // 1 = checked | 0 = unchecked
							),
							array(
									'name' => 'post-pi-share',
									'type' => 'switch',
									'dependency' => array('element'=>'show-post-share','value'=>array('1')),
									'label' => __('Share on Pinterest',DH_DOMAIN),
									'value' => '0' // 1 = checked | 0 = unchecked
							),
							array(
									'name' => 'post-li-share',
									'type' => 'switch',
									'dependency' => array('element'=>'show-post-share','value'=>array('1')),
									'label' => __('Share on LinkedIn',DH_DOMAIN),
									'value' => '1' // 1 = checked | 0 = unchecked
							),
					)
			),
		);
		if(defined('WOOCOMMERCE_VERSION')){
			$section['woocommerce'] = array(
				'icon' => 'fa fa-shopping-cart',
				'title' => __ ( 'Woocommerce', DH_DOMAIN ),
				'desc' => __ ( '<p class="description">Customize Woocommerce.</p>', DH_DOMAIN ),
				'fields'=>array(
						array(
							'name' => 'woo-cart-nav',
							'type' => 'switch',
							'on'	=> __('Show',DH_DOMAIN),
							'off'	=> __('Hide',DH_DOMAIN),
							'label' => __('Cart In header',DH_DOMAIN),
							'desc'=>__('This will show cat in header.',DH_DOMAIN),
							'value' => '1' // 1 = checked | 0 = unchecked
						),
						array(
							'name' => 'woo-cart-mobile',
							'type' => 'switch',
							'on'	=> __('Show',DH_DOMAIN),
							'off'	=> __('Hide',DH_DOMAIN),
							'label' => __('Mobile Cart Icon',DH_DOMAIN),
							'desc'=>__('This will show on mobile menu a shop icon with the number of cart items.',DH_DOMAIN),
							'value' => '1' // 1 = checked | 0 = unchecked
						),
						array(
							'name' => 'list_product_setting',
							'type' => 'heading',
							'text' => __('List Product Settings',DH_DOMAIN),
						),
						array(
								'name' => 'woo-shop-layout',
								'type' => 'image_select',
								'label' => __('Shop Layout', DH_DOMAIN),
								'desc' => __('Select shop layout.', DH_DOMAIN),
								'options' => array(
										'full-width' => array('alt' => 'No sidebar', 'img' => DHINC_ASSETS_URL.'/images/1col.png'),
										'left-sidebar' => array('alt' => '2 Column Left', 'img' => DHINC_ASSETS_URL.'/images/2cl.png'),
										'right-sidebar' => array('alt' => '2 Column Right', 'img' => DHINC_ASSETS_URL.'/images/2cr.png'),
								),
								'value' => 'right-sidebar'
						),	
						array(
								'name' => 'woo-category-layout',
								'type' => 'image_select',
								'label' => __('Product Category Layout', DH_DOMAIN),
								'desc' => __('Select product category layout.', DH_DOMAIN),
								'options' => array(
										'full-width' => array('alt' => 'No sidebar', 'img' => DHINC_ASSETS_URL.'/images/1col.png'),
										'left-sidebar' => array('alt' => '2 Column Left', 'img' => DHINC_ASSETS_URL.'/images/2cl.png'),
										'right-sidebar' => array('alt' => '2 Column Right', 'img' => DHINC_ASSETS_URL.'/images/2cr.png'),
								),
								'value' => 'right-sidebar'
						),
						array (
							'name' => 'dh_woocommerce_view_mode',
							'type' => 'buttonset',
							'label' => __ ( 'Default View Mode', DH_DOMAIN ),
							'desc'=>__('Select default view mode',DH_DOMAIN),
							'value'=>'grid',
							'options'=>array(
								'grid'=> __('Grid',DH_DOMAIN),
								'list'=> __('List',DH_DOMAIN)
							)
						),
						array(
								'name' => 'woo-per-page',
								'type' => 'text',
								'value'=>12,	
								'label' => __('Number of Products per Page',DH_DOMAIN),
								'desc'=>__('Enter the products of posts to display per page.',DH_DOMAIN)
						),
						array(
							'name' => 'single_product_setting',
							'type' => 'heading',
							'text' => __('Single Product Settings',DH_DOMAIN),
						),
						array(
							'name' => 'woo-product-layout',
							'type' => 'image_select',
							'label' => __('Single Product Layout', DH_DOMAIN),
							'desc' => __('Select single product layout.', DH_DOMAIN),
							'options' => array(
								'full-width' => array('alt' => 'No sidebar', 'img' => DHINC_ASSETS_URL.'/images/1col.png'),
								'left-sidebar' => array('alt' => '2 Column Left', 'img' => DHINC_ASSETS_URL.'/images/2cl.png'),
								'right-sidebar' => array('alt' => '2 Column Right', 'img' => DHINC_ASSETS_URL.'/images/2cr.png'),
							),
							'value' => 'right-sidebar'
						),
						array(
								'name' => 'show-woo-share',
								'type' => 'switch',
								'label' => __('Show Sharing Button',DH_DOMAIN),
								'desc'=>__('Activate this to enable social sharing buttons.',DH_DOMAIN),
								'value' => '1' // 1 = checked | 0 = unchecked
						),
						array(
								'name' => 'woo-fb-share',
								'type' => 'switch',
								'on'	=> __('Show',DH_DOMAIN),
								'off'	=> __('Hide',DH_DOMAIN),
								'dependency' => array('element'=>'show-woo-share','value'=>array('1')),
								'label' => __('Share on Facebook',DH_DOMAIN),
								'value' => '1' // 1 = checked | 0 = unchecked
						),
						array(
								'name' => 'woo-tw-share',
								'type' => 'switch',
								'dependency' => array('element'=>'show-woo-share','value'=>array('1')),
								'label' => __('Share on Twitter',DH_DOMAIN),
								'value' => '1' // 1 = checked | 0 = unchecked
						),
						array(
								'name' => 'woo-go-share',
								'type' => 'switch',
								'dependency' => array('element'=>'show-woo-share','value'=>array('1')),
								'label' => __('Share on Google+',DH_DOMAIN),
								'value' => '1' // 1 = checked | 0 = unchecked
						),
						array(
								'name' => 'woo-pi-share',
								'type' => 'switch',
								'dependency' => array('element'=>'show-woo-share','value'=>array('1')),
								'label' => __('Share on Pinterest',DH_DOMAIN),
								'value' => '0' // 1 = checked | 0 = unchecked
						),
						array(
								'name' => 'woo-li-share',
								'type' => 'switch',
								'dependency' => array('element'=>'show-woo-share','value'=>array('1')),
								'label' => __('Share on LinkedIn',DH_DOMAIN),
								'value' => '1' // 1 = checked | 0 = unchecked
						),
				)
			);
		}
		$section['social_api'] = array(
			'icon' => 'fa fa-cloud-upload',
			'title' => __ ( 'Social API', DH_DOMAIN ),
			'desc' => __ ( '<p class="description">Social API', DH_DOMAIN ),
			'fields'=>array(
				array(
					'name' => 'facebbook_login_heading',
					'type' => 'heading',
					'text' => __('Facebook Login Settings',DH_DOMAIN),
				),
				array(
					'name' => 'facebook_login',
					'type' => 'switch',
					'value'=>'0',
					'label' => __('Use Facebook login',DH_DOMAIN),
					'desc'=>__('Enable or disable Login/Register with Facebook',DH_DOMAIN)
				),
				array(
					'name' => 'facebook_app_id',
					'type' => 'text',
					'dependency' => array('element'=>'facebook_login','value'=>array('1')),
					'label' => __('Facebook App ID',DH_DOMAIN),
					'desc'=>sprintf(__('Use Facebook login you need to enter your Facebook App ID. If you don\'t have one, you can create it from: <a target="_blank" href="%s">Here</a>',DH_DOMAIN),'https://developers.facebook.com/apps')
				),
				array(
					'name' => 'mailchimp_heading',
					'type' => 'heading',
					'text' => __('MailChimp Settings',DH_DOMAIN),
				),
				array(
					'name' => 'mailchimp_api',
					'type' => 'text',
					'label' => __('MailChimp API Key',DH_DOMAIN),
					'desc'=>sprintf(__('Enter your API Key.<a target="_blank" href="%s">Get your API key</a>',DH_DOMAIN),'http://admin.mailchimp.com/account/api-key-popup')
				),
				array(
					'name' => 'mailchimp_list',
					'type' => 'select',
					'options'=>dh_get_mailchimplist(),
					'label' => __('MailChimp List',DH_DOMAIN),
					'desc'=>__('After you add your MailChimp API Key above and save it this list will be populated.',DH_DOMAIN)
				),
				array(
					'name' => 'mailchimp_opt_in',
					'type' => 'switch',
					'label' => __('Enable Double Opt-In',DH_DOMAIN),
					'desc'=>sprintf(__('Learn more about <a target="_blank" href="%s">Double Opt-in</a>.',DH_DOMAIN),'http://kb.mailchimp.com/article/how-does-confirmed-optin-or-double-optin-work')
				),
				array(
					'name' => 'mailchimp_welcome_email',
					'type' => 'switch',
					'label' => __('Send Welcome Email',DH_DOMAIN),
					'desc'=>sprintf(__('If your Double Opt-in is false and this is true, MailChimp will send your lists Welcome Email if this subscribe succeeds - this will not fire if MailChimp ends up updating an existing subscriber. If Double Opt-in is true, this has no effect. Learn more about <a target="_blank" href="%s">Welcome Emails</a>.',DH_DOMAIN),'http://blog.mailchimp.com/sending-welcome-emails-with-mailchimp/')
				),
				array(
					'name' => 'mailchimp_group_name',
					'type' => 'text',
					'label' => __('MailChimp Group Name',DH_DOMAIN),
					'desc'=>sprintf(__('Optional: Enter the name of the group. Learn more about <a target="_blank" href="%s">Groups</a>',DH_DOMAIN),'http://mailchimp.com/features/groups/')
				),
				array(
					'name' => 'mailchimp_group',
					'type' => 'text',
					'label' => __('MailChimp Group',DH_DOMAIN),
					'desc'=>__('Optional: Comma delimited list of interest groups to add the email to.',DH_DOMAIN)
				),
				array(
					'name' => 'mailchimp_replace_interests',
					'type' => 'switch',
					'label' => __('MailChimp Replace Interests',DH_DOMAIN),
					'desc'=>__('Whether MailChimp will replace the interest groups with the groups provided or add the provided groups to the member interest groups.',DH_DOMAIN)
				),
				array(
					'name' => 'mailchimp_hr',
					'type' => 'hr',
				)
			)
		);
		$section['social'] = array(
			'icon' => 'fa fa-twitter',
			'title' => __ ( 'Social Profile', DH_DOMAIN ),
			'desc' => __ ( '<p class="description">Enter in your profile media locations here.<br><strong>Remember to include the "http://" in all URLs!</strong></p>', DH_DOMAIN ),
			'fields'=>array(
				array(
						'name' => 'facebook-url',
						'type' => 'text',
						'label' => __('Facebook URL',DH_DOMAIN),
				),
				array(
						'name' => 'twitter-url',
						'type' => 'text',
						'label' => __('Twitter URL',DH_DOMAIN),
				),
				array(
						'name' => 'google-plus-url',
						'type' => 'text',
						'label' => __('Google+ URL',DH_DOMAIN),
				),
				array(
						'name' => 'pinterest-url',
						'type' => 'text',
						'label' => __('Pinterest URL',DH_DOMAIN),
				),
				array(
						'name' => 'linkedin-url',
						'type' => 'text',
						'label' => __('LinkedIn URL',DH_DOMAIN),
				),
				array(
						'name' => 'rss-url',
						'type' => 'text',
						'label' => __('RSS URL',DH_DOMAIN),
				),
				array(
						'name' => 'instagram-url',
						'type' => 'text',
						'label' => __('Instagram URL',DH_DOMAIN),
				),
				array(
						'name' => 'github-url',
						'type' => 'text',
						'label' => __('GitHub URL',DH_DOMAIN),
				),		
				array(
						'name' => 'behance-url',
						'type' => 'text',
						'label' => __('Behance URL',DH_DOMAIN),
				),
				array(
						'name' => 'stack-exchange-url',
						'type' => 'text',
						'label' => __('Stack Exchange URL',DH_DOMAIN),
				),
				array(
						'name' => 'tumblr-url',
						'type' => 'text',
						'label' => __('Tumblr URL',DH_DOMAIN),
				),
				array(
						'name' => 'soundcloud-url',
						'type' => 'text',
						'label' => __('SoundCloud URL',DH_DOMAIN),
				),
				array(
						'name' => 'dribbble-url',
						'type' => 'text',
						'label' => __('Dribbble URL',DH_DOMAIN),
				),
			)
		);
		$section['import_export'] = array(
				'icon' => 'fa fa-refresh',
				'title' => __ ( 'Import and Export', DH_DOMAIN ),
				'fields'=>array(
					array(
							'name' => 'import',
							'type' => 'import',
							'field-label'=>__('Input your backup file below and hit Import to restore your sites options from a backup.',DH_DOMAIN),
					),
					array(
							'name' => 'export',
							'type' => 'export',
							'field-label'=>__('Here you can download your current option settings.You can use it to restore your settings on this site (or any other site).',DH_DOMAIN),
					),
				)
		);
		$section['custom_code'] = array(
				'icon' => 'fa fa-code',
				'title' => __ ( 'Custom Code', DH_DOMAIN ),
				'fields'=>array(
					array(
							'name' => 'custom-css',
							'type' => 'ace_editor',
							'label' => __('Custom Style',DH_DOMAIN),
							'desc'=>__('Place you custom style here',DH_DOMAIN),
					),
// 					array(
// 							'name' => 'custom-js',
// 							'type' => 'ace_editor',
// 							'label' => __('Custom Javascript',DH_DOMAIN),
// 							'desc'=>__('Place you custom javascript here',DH_DOMAIN),
// 					),
				)
		);
		return apply_filters('dh_theme_option_sections', $section);
	}
	
	public function enqueue_scripts(){
		wp_enqueue_style('vendor-chosen');
		wp_enqueue_style('vendor-font-awesome');
		wp_enqueue_style('vendor-jquery-ui-bootstrap');
		wp_enqueue_style('dh-theme-options',DHINC_ASSETS_URL.'/css/theme-options.css',null,DHINC_VERSION);
		wp_register_script('dh-theme-options',DHINC_ASSETS_URL.'/js/theme-options.js',array('jquery','underscore','jquery-ui-button','jquery-ui-tooltip','vendor-chosen','vendor-ace-editor'),DHINC_VERSION,true);
		$dhthemeoptionsL10n = array(
			'reset_msg'=>esc_js('You want reset all options ?',DH_DOMAIN)
		);
		wp_localize_script('dh-theme-options', 'dhthemeoptionsL10n', $dhthemeoptionsL10n);
		wp_enqueue_script('dh-theme-options');
	}
	
	public function admin_menu(){
		$option_page = add_theme_page( __('Theme Options',DH_DOMAIN), __('Theme Options',DH_DOMAIN), 'edit_theme_options', 'theme-options', array(&$this,'option_page'));
		
		// Add framework functionaily to the head individually
		//add_action("admin_print_scripts-$option_page", array(&$this,'enqueue_scripts'));
		add_action("admin_print_styles-$option_page", array(&$this,'enqueue_scripts'));
	}
	
	public function admin_bar_render(){
		global $wp_admin_bar;
		$wp_admin_bar->add_menu( array(
				'parent' => 'site-name', // use 'false' for a root menu, or pass the ID of the parent menu
				'id' => 'dh_theme_options', // link ID, defaults to a sanitized title value
				'title' => __('Theme Options', DH_DOMAIN), // link title
				'href' => admin_url( 'themes.php?page=theme-option'), // name of file
				'meta' => false // array of any of the following options: array( 'html' => '', 'class' => '', 'onclick' => '', target => '', title => '' );
		));
	}
	public function download_theme_option(){
		if( !isset( $_GET['secret'] ) || $_GET['secret'] != md5( AUTH_KEY . SECURE_AUTH_KEY ) ) {
			wp_die( 'Invalid Secret for options use' );
			exit;
		}
		$options = get_option(self::$_option_name);
		$content = json_encode($options);
		header( 'Content-Description: File Transfer' );
		header( 'Content-type: application/txt' );
		header( 'Content-Disposition: attachment; filename="' . self::$_option_name . '_backup_' . date( 'd-m-Y' ) . '.json"' );
		header( 'Content-Transfer-Encoding: binary' );
		header( 'Expires: 0' );
		header( 'Cache-Control: must-revalidate' );
		header( 'Pragma: public' );
		echo $content;
		exit;
	}
}
new DHThemeOptions();
endif;