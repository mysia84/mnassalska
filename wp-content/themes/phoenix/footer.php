	<?php if(dh_get_theme_option('footer-area',1)):?>
	<div class="footer-widget">
		<div class="<?php dh_container_class() ?>">
			<div class="footer-widget-wrap">
				<div class="row">
					<?php 
					$area_columns = dh_get_theme_option('footer-area-columns',4);
					if($area_columns == '5'):
						?>
						<?php if(is_active_sidebar('sidebar-footer-1')):?>
						<div class="footer-widget-col col-md-4 col-sm-6">
							<?php dynamic_sidebar('sidebar-footer-1')?>
						</div>
						<?php endif;?>
						<?php if(is_active_sidebar('sidebar-footer-2')):?>
						<div class="footer-widget-col col-md-2 col-sm-6">
							<?php dynamic_sidebar('sidebar-footer-2')?>
						</div>
						<?php endif;?>
						<?php if(is_active_sidebar('sidebar-footer-3')):?>
						<div class="footer-widget-col col-md-2 col-sm-6">
							<?php dynamic_sidebar('sidebar-footer-3')?>
						</div>
						<?php endif;?>
						<?php if(is_active_sidebar('sidebar-footer-4')):?>
						<div class="footer-widget-col col-md-2 col-sm-6">
							<?php dynamic_sidebar('sidebar-footer-4')?>
						</div>
						<?php endif;?>
						<?php if(is_active_sidebar('sidebar-footer-5')):?>
						<div class="footer-widget-col col-md-2 col-sm-6">
							<?php dynamic_sidebar('sidebar-footer-5')?>
						</div>
						<?php endif;?>
						<?php
					else:
					$area_class = '';
						if($area_columns == '2'){
							$area_class = 'col-md-6 col-sm-6';
						}elseif ($area_columns == '3'){
							$area_class = 'col-md-4 col-sm-6';
						}elseif ($area_columns == '4'){
							$area_class = 'col-md-3 col-sm-6';
						}
						?>
						<?php for ( $i = 1; $i <= $area_columns ; $i ++ ) :?>
							<?php if(is_active_sidebar('sidebar-footer-'.$i)):?>
							<div class="footer-widget-col <?php echo esc_attr($area_class) ?>">
								<?php dynamic_sidebar('sidebar-footer-'.$i)?>
							</div>
							<?php endif;?>
						<?php endfor;?>
					<?php endif;?>
				</div>
			</div>
		</div>
	</div>
	<?php endif;?>
	<?php if(dh_get_post_meta('footer_info',get_the_ID(),1)):?>
		<?php if(dh_get_theme_option('footer-featured',1)):?>
		<div class="footer-featured">
			<div class="<?php dh_container_class() ?>">
				<div class="footer-featured-wrap">
					<div class="row">
						<div class="footer-widget-col col-md-4 col-sm-6">
							<?php echo dhecho(do_shortcode(dh_get_theme_option('footer-featured-1')));?>
						</div>
						<div class="footer-widget-col footer-featured-border col-md-4 col-sm-6">
							<?php echo dhecho(do_shortcode(dh_get_theme_option('footer-featured-2')));?>
						</div>
						<div class="footer-widget-col col-md-4 col-sm-6">
							<?php echo dhecho(do_shortcode(dh_get_theme_option('footer-featured-3')));?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endif;?>
		<footer id="footer" class="footer" role="contentinfo">
			<div class="footer-info">
				<div class="<?php dh_container_class() ?>">
					<div class="footer-info-wrap">
						<div class="row">
							
							<div class="col-sm-6">
								<div class="footer-info-logo">
									<a title="<?php esc_attr(bloginfo( 'name' )); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">
										<?php if($footer_logo = dh_get_theme_option('footer-logo')):?>
											<img alt="<?php bloginfo( 'name' ); ?>" src="<?php echo esc_url($footer_logo)?>">
										<?php endif;?>
									</a>
								</div>
								<?php if($footer_info = dh_get_theme_option('footer-info')):?>
								<div class="footer-info-text"><?php echo wpautop($footer_info) ?></div>
								<?php endif;?>
			            	</div>
			            	<div class="col-sm-6">
			            		<?php if(dh_get_theme_option('footer-menu',1)):?>
									<div class="footer-menu">
										<?php 
										if(has_nav_menu('footer')):
											wp_nav_menu( array(
													'theme_location'    => 'footer',
													'container'         => false,
													'depth'				=> 1,
													'menu_class'        => 'footer-nav',
													'items_wrap'	 	=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
													'walker' 			=> new DHWalker
											) );
										endif;
										?>
									</div>
								<?php endif;?>
								<?php if($footer_copyright = dh_get_theme_option('footer-copyright')):?>
									<div class="copyright"><?php echo dhecho($footer_copyright) ?></div>
				            	<?php endif;?>
			            	</div>
			            </div>
			         </div>
				</div>
			</div>
		</footer>
	<?php endif;?>
</div>
<?php wp_footer(); ?>
</body>
</html>