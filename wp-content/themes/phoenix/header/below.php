<?php 
$login_url = wp_login_url();
$logout_url = wp_logout_url();
$register_url = wp_registration_url();
if(defined('WOOCOMMERCE_VERSION')){
	$login_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
	$logout_url = wp_logout_url( get_permalink( woocommerce_get_page_id( 'myaccount' ) ) );
}
?>
<header id="header" class="header-container header-type-below header-navbar-below<?php if($menu_transparent):?> header-absolute header-transparent<?php endif?>" itemscope="itemscope" itemtype="<?php echo dh_get_protocol()?>://schema.org/Organization" role="banner">
	<?php if(dh_get_theme_option('show-topbar',1)):?>
	<div class="topbar">
		<div class="<?php dh_container_class() ?>">
				<div class="row">
					<div class="col-md-5 col-sm-6">
						<div class="left-topbar">
	            			<?php
	            			$left_topbar_content = dh_get_theme_option('left-topbar-content','info');
	            			if($left_topbar_content === 'info'): 
	            				echo '<div class="topbar-info">';
	            				echo '<a href="#"><i class="fa fa-phone"></i> '.esc_html(dh_get_theme_option('left-topbar-phone','(123) 456 789')).'</a>';
	            				echo '<a href="mailto:'.esc_attr(dh_get_theme_option('left-topbar-email','info@domain.com')).'"><i class="fa fa-envelope-o"></i> '.esc_html(dh_get_theme_option('left-topbar-email','info@domain.com')).'</a>';
	            				echo '<a href="skype:'.esc_attr(dh_get_theme_option('left-topbar-skype','skype.name')).'?call"><i class="fa fa-skype"></i> '.esc_html(dh_get_theme_option('left-topbar-skype','skype.name')).'</a>';
	            				echo '</div>';
	            			elseif ($left_topbar_content === 'custom'):
	            				echo dhecho(dh_get_theme_option('left-topbar-custom-content',''));
	            			endif;
	            			?>
	            			<?php 
	            			if(($left_topbar_content == 'menu_social')):
	            			echo '<div class="topbar-social">';
	            			dh_social(dh_get_theme_option('left-topbar-social',array('facebook','twitter','google-plus','pinterest','rss','instagram')),true);
	            			echo '</div>';
	            			endif;
	            			?>
					</div>
				</div>
				<div class="col-md-7 col-sm-6">
					<div class="right-topbar">
						<?php
	            			$right_topbar_content = dh_get_theme_option('right-topbar-content','menu_social');
	            			if($right_topbar_content == 'menu' ){
	            				?>
	            				<div class="topbar-nav">
		            				<ul class="nav top-nav">
		            				<?php
	            				if ( has_nav_menu( 'top' ) ) :
		            				wp_nav_menu( array(
			            				'theme_location'    => 'top',
			            				'depth'             => 2,
			            				'container'         => false,
		            					'items_wrap' 		=> '%3$s',
			            				'walker'            => new DHWalker
			            			));
	            				endif;
	            				if(dh_get_theme_option('right-topbar-account',0)){
	            				
	            				?>
		            				<li class="menu-item<?php if(is_user_logged_in()):?> menu-item-has-children dropdown<?php endif;?>">
										<a <?php if(!is_user_logged_in()): ?> rel="loginModal" <?php endif;?> href="<?php echo esc_url($login_url) ?>">
											<?php _e('My Account', DH_THEME_DOMAIN); ?>
											<?php if(is_user_logged_in()):?>
											<span class="caret"></span>
											<?php endif;?>
										</a>
										<?php if(is_user_logged_in()):?>
										<ul class="dropdown-menu" role="menu">
											<li class="menu-item">
												<a href="<?php echo esc_url($logout_url) ?>"><?php _e('Logout', DH_THEME_DOMAIN); ?></a>
											</li>
										</ul>
										<?php endif;?>
									</li>
								<?php } ?>
								<?php do_action('dh_right_topbar_menu')?>
		            				</ul>
	            				</div>
	            				<?php
	            			}elseif ($right_topbar_content == 'custom'){
	            				echo  dhecho(dh_get_theme_option('right-topbar-custom-content',''));
	            			}
	            			?>
	            			<?php
	            			$topbar_social = dh_get_theme_option('topbar-social','social_button');
	            			if(($right_topbar_content == 'menu_social')): 
	            				echo '<div class="topbar-social">';
	            				dh_social(dh_get_theme_option('topbar-social',array('facebook','twitter','google-plus','pinterest','rss','instagram')),true);
	            				echo '</div>';
	            			endif;
	            			?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif;?>
	<div class="navbar-header">
		<div class="<?php dh_container_class() ?>">
			<div class="navbar-header-left">
				<button data-target=".primary-navbar-collapse" data-toggle="collapse" type="button" class="navbar-toggle">
					<span class="sr-only"><?php echo __('Toggle navigation',DH_THEME_DOMAIN)?></span>
					<span class="icon-bar bar-top"></span> 
					<span class="icon-bar bar-middle"></span> 
					<span class="icon-bar bar-bottom"></span>
				</button>
			     <?php if(defined('WOOCOMMERCE_VERSION') && dh_get_theme_option('woo-cart-mobile',1)):?>
			     	<?php 
			     	if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {
			     		$cart_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_cart_url() );
			     	}else{
			     		$cart_url = esc_url( $woocommerce->cart->get_cart_url() );
			     	}
			     	?>
					<a class="cart-icon-mobile" href="<?php echo esc_url($cart_url) ?>"><i class="minicart-icon-svg elegant_icon_cart_alt"></i></a>
				<?php endif;?>
				<a class="navbar-brand" data-itemprop="url" title="<?php esc_attr(bloginfo( 'name' )); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php if($logo_url = dh_get_theme_option('logo')):?>
						<img class="logo" alt="<?php bloginfo( 'name' ); ?>" src="<?php echo esc_url($logo_url)?>">
					<?php else:?>
						<?php echo bloginfo( 'name' ) ?>
					<?php endif;?>
					<img class="logo-fixed" alt="<?php bloginfo( 'name' ); ?>" src="<?php echo esc_url($logo_fixed_url)?>">
					<img class="logo-mobile" alt="<?php bloginfo( 'name' ); ?>" src="<?php echo esc_url($logo_mobile_url)?>">
				</a>
				<div class="navuser">
					<div class="navuser-wrap">
						<?php if(is_user_logged_in()):?>
							<div class="navuser-nav">
								<a href="<?php echo esc_url($login_url)?>" title="<?php esc_attr_e('My Account',DH_THEME_DOMAIN)?>"><?php _e('My Account',DH_THEME_DOMAIN)?></a>
								<div class="navuser-dropdown">
									<ul>
										<li>
											<a href="<?php echo esc_url($logout_url) ?>"><i class="fa fa-sign-out"></i><?php _e('Logout', DH_THEME_DOMAIN); ?></a>
										</li>
									</ul>
								</div>
							</div>
						<?php else:?>
						<a rel="registerModal" href="<?php echo esc_url($register_url)?>" title="<?php esc_attr_e('Sign Up',DH_THEME_DOMAIN)?>"><?php _e('Sign Up',DH_THEME_DOMAIN)?></a> / <a title="<?php esc_attr_e('LogIn',DH_THEME_DOMAIN)?>" rel="loginModal" href="<?php echo esc_url($login_url)?>"><?php _e('LogIn',DH_THEME_DOMAIN)?></a>
						<?php endif;?>
					</div>
				</div>
			</div>
			<div class="navbar-header-center">
				<a class="navbar-brand" data-itemprop="url" title="<?php esc_attr(bloginfo( 'name' )); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php if($logo_url = dh_get_theme_option('logo')):?>
						<img class="logo" alt="<?php bloginfo( 'name' ); ?>" src="<?php echo esc_url($logo_url)?>">
					<?php else:?>
						<?php echo bloginfo( 'name' ) ?>
					<?php endif;?>
					<img class="logo-fixed" alt="<?php bloginfo( 'name' ); ?>" src="<?php echo esc_url($logo_fixed_url)?>">
					<img class="logo-mobile" alt="<?php bloginfo( 'name' ); ?>" src="<?php echo esc_url($logo_mobile_url)?>">
					<meta itemprop="name" content="<?php bloginfo('name')?>">
				</a>
			</div>
			<div class="navbar-header-right">
				 <?php if(defined('WOOCOMMERCE_VERSION') && dh_get_theme_option('woo-cart-nav',1)):?>
			     	<?php 
			     	global $woocommerce;
			     	$cart_total = $woocommerce->cart->get_cart_total();
			     	if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {
			     		$cart_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_cart_url() );
			     	}else{
			     		$cart_url = esc_url( $woocommerce->cart->get_cart_url() );
			     	}
			     	?>
					<div class="navcart">
						<div class="navcart-wrap">
							<?php 
							echo (class_exists('DHWoocommerce') ? DHWoocommerce::instance()->get_minicart():'');
							?>
						</div>
					</div>
				<?php else:?>
					<?php 
					if(is_active_sidebar('sidebar-header'))
						dynamic_sidebar('sidebar-header')
					?>
				<?php endif;?>
				
			</div>
		</div>
	</div>
	<div class="navbar-container">
		<div class="navbar navbar-default <?php if(dh_get_theme_option('sticky-menu',1)):?> navbar-scroll-fixed<?php endif;?>">
			<div class="navbar-primary-nav">
				<div class="<?php dh_container_class() ?>">
					<div class="navbar-wrap">
						<nav class="collapse navbar-collapse primary-navbar-collapse" itemtype="<?php echo dh_get_protocol() ?>://schema.org/SiteNavigationElement" itemscope="itemscope" role="navigation">
							<?php
							$page_menu = '' ;
							if(is_page() && ($selected_page_menu = dh_get_post_meta('main_menu'))){
								$page_menu = $selected_page_menu;
							}
							if(has_nav_menu('primary') || !empty($page_menu)):
								wp_nav_menu( array(
									'theme_location'    => 'primary',
									'container'         => false,
									'depth'				=> 3,
									'menu'				=> $page_menu,
									'menu_class'        => 'nav navbar-nav primary-nav',
									'walker' 			=> new DHMegaWalker
								) );
							else:
								echo '<ul class="nav navbar-nav primary-nav"><li><a href="' . home_url( '/' ) . 'wp-admin/nav-menus.php">' . __( 'No menu assigned!', DH_THEME_DOMAIN ) . '</a></li></ul>';
							endif;
							?>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>