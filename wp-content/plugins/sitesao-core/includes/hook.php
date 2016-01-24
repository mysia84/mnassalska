<?php
if (! class_exists ( 'DHHook' )) :
	class DHHook {
		public function __construct(){
			if(!is_admin()){
				add_action('init', array(&$this,'init'));
				//custom body class
				add_filter('body_class', array(&$this,'body_class'));
				//add search form
				if( dh_get_theme_option('header-style','default') != 'toggle-offcanvas')
					add_filter( 'wp_nav_menu_items', array(&$this,'navbar_search_form'), 11, 2 );
				//Offcanvas open button
				//allow shortcodes in text widget
				add_filter('widget_text', 'do_shortcode');
				
				add_filter( 'wp_list_categories', array(&$this,'remove_category_list_rel') );
				add_filter( 'the_category', array(&$this,'remove_category_list_rel') );
				
				//user
				add_action('wp_footer', array(&$this,'user_login_modal'));
				add_action('wp_footer', array(&$this,'facebook_init'));
				add_action('login_form', array(&$this,'facebook_login_button'));
				add_action('login_footer',array(&$this,'facebook_init') );
				add_action('login_enqueue_scripts', array(&$this,'custom_login_css'));
				
				//newsletter
				if(dh_get_theme_option('popup_newsletter',1)){
					add_action('wp_footer', array(&$this,'newsletter_modal'));
				}
				//Go to Top 
				add_action('wp_footer', array(&$this,'gototop'));
				//excerpt length
				add_filter('excerpt_length', array(&$this,'excerpt_length'));
				//custom excerpt ending
				add_filter('excerpt_more', array(&$this,'excerpt_more'));
				
				//Theme option menu
				add_action('admin_bar_menu', array(&$this,'admin_bar_menu'), 10000);
				
				//video transparent
				global $wp_embed;
				add_filter('dh_embed_video',array($wp_embed,'autoembed'),8);
				
				//Custom css
				add_action( 'wp_head', array(&$this,'custom_css_output'), 10000, 0 );
			}
		}
		
		
		public function init(){
			
		}
		
		public function admin_bar_menu($admin_bar){
			if ( is_super_admin() && ! is_admin() ) {
				$admin_bar->add_menu( array(
					'id'    => 'theme-options',
					'title' => __('Theme options',DH_DOMAIN),
					'href'  => get_admin_url().'admin.php?page=theme-options',
					'meta'  => array(
							'title' => __('Theme options',DH_DOMAIN),
							'target' => '_blank'
					),
				));
			}
		
		}
		
		public function offcanvas_open_btn($items,$args){
			if ($args->theme_location == 'primary'){
				//$search_form = '';
				$items .= '<li class="navbar-offcanvas-btn"><div class="offcanvas-open-btn"><svg xml:space="preserve" enable-background="new 0 0 24 24" viewBox="0 0 24 24" height="24px" width="24px" y="0px" x="0px" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" version="1.1"><rect height="2" width="24" y="5"/><rect height="2" width="24" y="11"/><rect height="2" width="24" y="17"/></svg></div></li>';
			}
			return $items;
		}
		
		public function navbar_search_form($items,$args){
			if ($args->theme_location == 'primary' && dh_get_theme_option('ajaxsearch',1)){
				$search_form = dh_get_search_form();
				$search_form = '<div class="search-form-wrap show-popup hide">'.$search_form.'</div>';
				//$search_form = '';
				$items .= '<li class="navbar-search"><a class="navbar-search-button" href="#"><i class="fa fa-search"></i></a>'.$search_form.'</li>';
			}
			return $items;
		}
		
		public function body_class($classes){
			if(!is_page())
				$classes[] = dh_get_main_class(true);
			return $classes;	
		}
		
		public function gototop(){
			if(dh_get_theme_option('back-to-top',1)){
				echo '<a href="#" class="go-to-top"><i class="fa fa-angle-up"></i></a>';
			}
			return '';
		}
		
		public function custom_login_css() {
			wp_enqueue_script('jquery');
			$logo_url = dh_get_theme_option('logo');
			echo "\n<style>";
			echo 'html,body{background:#262626 !important;}.login h1 a { background-image: url("'.esc_url($logo_url).'");background-size: contain;min-height: 88px;width:auto;}';
			echo "</style>\n";
		}
		
		public function facebook_init(){
			if(is_user_logged_in() || !get_option('users_can_register'))
				return;
			
			if(dh_get_theme_option('facebook_login',0)):
			?>
			<div id="fb-root"></div>
			<script type="text/javascript">
	        window.fbAsyncInit = function() {
	            FB.init({
	                appId      : '<?php echo dh_get_theme_option('facebook_app_id'); ?>',
	                version    : 'v2.1',
	                status     : true,
	                cookie     : true,
	                xfbml      : true,
	                oauth      : true
	            });
	            jQuery('#fb-root').trigger('facebook:init');
	        };
	        (function(d, s, id) {
	            var js, fjs = d.getElementsByTagName(s)[0];
	            if (d.getElementById(id)) return;
	            js = d.createElement(s); js.id = id;
	            js.src = "//connect.facebook.net/<?php echo apply_filters('dh_facebook_js_locale', 'en_US'); ?>/sdk.js";
	            fjs.parentNode.insertBefore(js, fjs);
	        }(document, 'script', 'facebook-jssdk'));
	        
	        jQuery(document).ready(function() {
	            jQuery('.btn-login-facebook').click(function(e) {
	            	e.stopPropagation();
					e.preventDefault();
	                if (navigator.userAgent.match('CriOS')) {
	                    window.open('https://www.facebook.com/dialog/oauth?client_id=<?php echo dh_get_theme_option('facebook_app_id'); ?>&redirect_uri=' + document.location.href + '&scope=email,public_profile&response_type=token', '', null);
	                } else {
	                    FB.login(function(fb_response){
	                            if (fb_response.authResponse) {
	                            	facebookInit(fb_response, '');
	                            }
	                        },
	                        {
	                            scope: 'email',
	                            auth_type: 'rerequest',
	                            return_scopes: true
	                        });
	                }
	            });

	            jQuery("#fb-root").bind("facebook:init", function() {
	            	var getUrlVars = function(){
		                var vars = [], hash;
		                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		                for(var i = 0; i < hashes.length; i++)
		                {
		                    hash = hashes[i].split('=');
		                    vars.push(hash[0]);
		                    vars[hash[0]] = hash[1];
		                }
		                return vars;
		            };
		            var getUrlVar = function(name){
		                return getUrlVars()[name];
		            };
	                var token = getUrlVar('#access_token');
	                if (token) {
	                    var fb_data = { scopes: "email" };
	                    facebookInit(fb_data, token);
	                }
	            });

	        });

	    	function facebookInit(fb_response, token){
	    		FB.api( '/me', 'GET', {
	                    fields : 'id,email,verified,name',
	                    access_token : token
	                },
	                function(fb_user){
	                    jQuery.ajax({
	                        type: 'POST',
	                        url: '<?php echo admin_url( 'admin-ajax.php', 'relative' ) ?>',
	                        data: {"action": "dh_facebook_init", "fb_user": fb_user, "fb_response": fb_response},
	                        success: function(user){
	                            if( user.error ) {
	                                alert( user.error );
	                            }
	                            else if( user.loggedin ) {
	                                jQuery('.user-login-modal-result').html(user.message);
	                                if( user.type === 'login' ) {
	                                    if(window.location.href.indexOf("wp-login.php") > -1) {
	                                      window.location = user.siteUrl;
	                                    } else {
	                                      window.location.reload();
	                                    }
	                                }
	                                else if( user.type === 'register' ) {
	                                    window.location = user.url;
	                                }
	                            }
	                        }
	                    });
	                }
	    		);
	    	}
	    	</script>
			<?php
			endif;
		}
		
		public function facebook_login_button(){
			if(is_user_logged_in() || !get_option('users_can_register'))
				return;
			ob_start();
			if(dh_get_theme_option('facebook_login',0)):
			?>
			<div class="user-login-facebook">
				<style type="text/css" scoped>
					.user-login-facebook {
					   margin-bottom: 15px;
					}
					.btn-login-facebook{
						  display: inline-block;
						  margin-bottom: 0;
						  font-weight: 400;
						  text-align: center;
						  vertical-align: middle;
						  cursor: pointer;
						  background-image: none;
						  border: 1px solid transparent;
						  white-space: nowrap;
						  padding: 0.7517241379310344rem 0.9655172413793104rem;
						  font-size: 14.5px;
						  line-height: 1.1;
						  -webkit-transition: background-color 0.3s,border-color 0.3s;
						  -o-transition: background-color 0.3s,border-color 0.3s;
						  transition: background-color 0.3s,border-color 0.3s;
						  -webkit-border-radius: 3px;
						  border-radius: 3px;
						  -webkit-user-select: none;
						  -moz-user-select: none;
						  -ms-user-select: none;
						  user-select: none;
						  outline: none;
						  background: none repeat scroll 0 0 #3b5998;
					      border-width: 0;
					      color: #fff;
					}
					.btn-login-facebook i{
						margin-right: 10px;
					}
				</style>
				<button class="btn-login-facebook" type="button"><i class="fa fa-facebook"></i><?php _e('Sign in with Facebook',DH_DOMAIN)?></button>
			</div>
			<?php
			endif;
			echo ob_get_clean();
		}
		
		public function newsletter_modal(){
			$cookie_key = 'dh_phoenix_newsletter_modal';
			if(absint(dh_get_theme_option('popup_newsletter_interval',1)) && isset($_COOKIE[$cookie_key]))
				return;
			ob_start();
			?>
			<div class="modal fade newsletter-modal" data-interval="<?php echo esc_attr(absint(dh_get_theme_option('popup_newsletter_interval',1)))?>" id="newsletterModal" tabindex="-1" role="dialog" aria-labelledby="newsletterModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-center__">
					<div class="modal-content">
						<form method="post" id="newsletterModalForm">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title hide" id="newsletterModalLabel"><?php _e('Newsletter')?></h4>
							</div>
							<div class="modal-body">
								<?php wp_nonce_field( 'mailchimp_subscribe_nonce', '_subscribe_nonce' ); ?>
								<?php do_action('dh_before_user_login_modal');?>
								<h2 class="newsletter-modal-title"><?php _e('Newsletter',DH_DOMAIN)?></h2>
								<div class="newsletter-modal-desc"><?php _e('Get timely updates from your favorite products',DH_DOMAIN)?></div>
								<div class="form-group">
									<label for="log" class="sr-only"><?php _e('Email',DH_DOMAIN)?></label>
								    <input type="text" id="newsletter-modal-email" name="email" autofocus required class="form-control" value="" placeholder="<?php esc_attr_e('Your email',DH_DOMAIN)?>">
								 </div>
								 <?php do_action('dh_after_user_login_modal')?>
								 <div class="ajax-modal-result"></div>
							</div>
							<div class="modal-footer text-center">
					        	<button type="submit" class="btn btn-primary"><?php _e('Submit',DH_DOMAIN)?></button>
					        </div>
				        </form>
					</div>
				</div>
			</div>
			<?php
			echo apply_filters('dh_newsletter_modal', ob_get_clean());
		}
		
		public function user_login_modal(){
			if(is_user_logged_in())
				return;
			?>
			<div class="modal fade user-login-modal" id="userloginModal" tabindex="-1" role="dialog" aria-labelledby="userloginModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-center__">
					<div class="modal-content">
						<form action="<?php echo wp_login_url(apply_filters('dh_login_redirect', '','modal')  ); ?>" method="post" id="userloginModalForm">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title" id="userloginModalLabel"><?php _e('Login to your account')?></h4>
							</div>
							<div class="modal-body">
								<?php wp_nonce_field( 'dh-ajax-login-nonce', 'security' ); ?>
								<?php do_action('dh_before_user_login_modal');?>
								<?php if(dh_get_theme_option('facebook_login',0) && get_option('users_can_register')):?>
								<?php $this->facebook_login_button()?>
								<div class="user-login-or"><span><?php _e('or',DH_DOMAIN)?></span></div>
								<?php endif;?>
								<div class="form-group">
									<label for="log"><?php _e('Username',DH_DOMAIN)?></label>
								    <input type="text" id="username" name="log" autofocus required class="form-control" value="" placeholder="<?php esc_attr_e( "Username", DH_DOMAIN );?>">
								 </div>
								 <div class="form-group">
								    <label for="password"><?php _e('Password',DH_DOMAIN)?></label>
								    <input type="password" id="password" required value="" name="pwd" class="form-control" placeholder="<?php esc_attr_e( "Password", DH_DOMAIN );?>">
								  </div>
								  <div class="checkbox clearfix">
								    <label class="form-flat-checkbox">
								      <input type="checkbox" name="rememberme" id="rememberme" value="forever"><i></i>&nbsp;<?php _e('Remember Me'); ?>
								    </label>
								    <span class="pull-right">
								    	<a href="#lostpasswordModal" rel="lostpasswordModal"><?php _e('Lost your password?',DH_DOMAIN)?></a>
								    </span>
								  </div>
								  <?php do_action('dh_after_user_login_modal')?>
								  <div class="user-modal-result"></div>
							</div>
							<div class="modal-footer">
								<?php if(get_option('users_can_register')) : ?>
								<span class="user-login-modal-register pull-left"><a rel="registerModal" href="<?php echo apply_filters('dh_register_url', get_bloginfo('url')."/wp-login.php?action=register",'modal') ?>"><?php _e('Not a Member yet?')?></a></span>
					        	<?php endif;?>
					        	<button type="submit" class="btn btn-primary"><?php _e('Sign in',DH_DOMAIN)?></button>
					        </div>
				        </form>
					</div>
				</div>
			</div>
			<?php if(get_option('users_can_register')) : ?>
			<div class="modal fade user-register-modal" id="userregisterModal" tabindex="-1" role="dialog" aria-labelledby="userregisterModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-center__">
					<div class="modal-content">
						<form action="<?php echo wp_registration_url()?>" method="post" id="userregisterModalForm">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title" id="userregisterModalLabel"><?php _e('Register account')?></h4>
							</div>
							<div class="modal-body">
								<?php wp_nonce_field( 'dh-ajax-register-nonce', 'security' ); ?>
								<?php do_action('dh_before_user_register_modal');?>
								<?php if(dh_get_theme_option('facebook_login',0) && get_option('users_can_register')):?>
								<?php $this->facebook_login_button()?>
								<div class="user-login-or"><span><?php _e('or',DH_DOMAIN)?></span></div>
								<?php endif;?>
								<div class="form-group">
									<label for="user_login"><?php _e('Username',DH_DOMAIN)?></label>
								    <input type="text" id="user_login" name="user_login" autofocus required class="form-control" value="" placeholder="<?php esc_attr_e( "Username", DH_DOMAIN );?>">
								 </div>
								 <div class="form-group">
									<label for="user_email"><?php _e('Email',DH_DOMAIN)?></label>
								    <input type="email" id="user_email" name="user_email" autofocus required class="form-control" value="" placeholder="<?php esc_attr_e( "Email", DH_DOMAIN );?>">
								 </div>
								 <div class="form-group">
								    <label for="user_password"><?php _e('Password',DH_DOMAIN)?></label>
								    <input type="password" id="user_password" required value="" name="user_password" class="form-control" placeholder="<?php esc_attr_e( "Password", DH_DOMAIN );?>">
								  </div>
								  <div class="form-group">
								    <label for="user_password"><?php _e('Retype password',DH_DOMAIN)?></label>
								    <input type="password" id="cuser_password" required value="" name="cuser_password" class="form-control" placeholder="<?php esc_attr_e( "Retype password", DH_DOMAIN );?>">
								  </div>
								  <?php do_action('dh_after_user_register_modal')?>
								  <div class="user-modal-result"></div>
							</div>
							<div class="modal-footer">
								<span class="user-login-modal-link pull-left"><a rel="loginModal" href="#loginModal"><?php _e('Already have an account?')?></a></span>
					        	<button type="submit" class="btn btn-primary"><?php _e('Register',DH_DOMAIN)?></button>
					        </div>
				        </form>
					</div>
				</div>
			</div>
			<?php endif;?>
			<div class="modal fade user-lostpassword-modal" id="userlostpasswordModal" tabindex="-1" role="dialog" aria-labelledby="userlostpasswordModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-center__">
					<div class="modal-content">
						<form action="<?php echo wp_lostpassword_url(apply_filters('dh_lostpassword_redirect', '','modal')  ); ?>" method="post" id="userlostpasswordModalForm">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title" id="userlostpasswordModalLabel"><?php _e('Forgot your details?')?></h4>
							</div>
							<div class="modal-body">
								<?php wp_nonce_field( 'dh-ajax-lostpassword-nonce', 'security' ); ?>
								<?php do_action('dh_before_user_lostpassword_modal');?>
								<div class="form-group">
									<label for="user_login"><?php _e('Username or E-mail:',DH_DOMAIN)?></label>
								    <input type="text" id="user_login" name="user_login" autofocus required class="form-control" value="" placeholder="<?php esc_attr_e( "Username or E-mail", DH_DOMAIN );?>">
								 </div>
								  <?php do_action('dh_after_user_lostpassword_modal')?>
								  <div class="user-modal-result"></div>
							</div>
							<div class="modal-footer">
								<span class="user-login-modal-link pull-left"><a rel="loginModal" href="#loginModal"><?php _e('Already have an account?')?></a></span>
					        	<button type="submit" class="btn btn-primary"><?php _e('Sign in',DH_DOMAIN)?></button>
					        </div>
				        </form>
					</div>
				</div>
			</div>
			<?php
		}
		
		public  function excerpt_length( $length ) {
			$excerpt_length = dh_get_theme_option('excerpt-length',60);
		    return (empty($excerpt_length) ? 60 : $excerpt_length); 
		}
		
		public function excerpt_more( $more ) {
			return '...';
		}
		
		public function remove_category_list_rel( $output ) {
			// Remove rel attribute from the category list
			return str_replace( ' rel="category tag"', '', $output );
		}
		
		public function custom_css_output(){
			ob_start();
			require_once( DHINC_DIR . '/custom-css/brand-primary.php' );
			require_once( DHINC_DIR . '/custom-css/brand-success.php' );
			require_once( DHINC_DIR . '/custom-css/brand-info.php' );
			require_once( DHINC_DIR . '/custom-css/brand-warning.php' );
			require_once( DHINC_DIR . '/custom-css/brand-danger.php' );
			require_once( DHINC_DIR . '/custom-css/style.php' );
			$custom_css = ob_get_clean();
			$custom_css = dh_css_minify($custom_css);
			echo '<style id="'.basename(get_template_directory()).'-custom-css" type="text/css">'."\n" . $custom_css ."\n".'</style>'."\n";
			return;
		}
		
	}
	new DHHook ();

endif;