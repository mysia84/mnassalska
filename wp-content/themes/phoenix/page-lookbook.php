<?php
/*
Template Name: Lookbook
*/
?>
<?php 
global $woocommerce;
$no_padding = dh_get_post_meta('no_padding');
?>
<?php get_header() ?>
	<div class="content-container no-padding">
		<div class="container-full">
			<div class="row">
				<div class="col-md-12 main-wrap" data-itemprop="mainContentOfPage" role="main">
					<div class="main-content">
						<?php if ( have_posts() ) : ?>
							<?php 
							 while (have_posts()): the_post();
								the_content();
							 endwhile;
							 ?>
						<?php endif;?>
						<?php if($lookbooks = get_terms('product_lookbook',array('hide_empty'=>0,'orderby'=>'name','menu_order'=>'DESC'))):?>
						<div class="lookbooks">
							<?php foreach ((array)$lookbooks as $lookbook):?>
								<?php
								$thumbnail_id  			= get_woocommerce_term_meta( $lookbook->term_id, 'thumbnail_id', true  );
								$thumbnail_align 		= get_woocommerce_term_meta( $lookbook->term_id, 'thumbnail_align', true  );
								$small_title 			= get_woocommerce_term_meta( $lookbook->term_id, 'small_title', true  );
								?>
								<div class="lookbook clearfix">
								<?php if($thumbnail_id):?>
									<?php if($thumbnail_align=='left'):?>
										<?php 
											$image = wp_get_attachment_image_src( $thumbnail_id, 'wide'  ); 
											$image = $image[0];
										?>
										<div class="lookbook-<?php echo esc_attr($thumbnail_align) ?> clearfix">
											<div class="lookbook-thumb">
												<a href="<?php echo get_term_link($lookbook,'product_lookbook') ?>">
													<img src="<?php echo esc_url($image)?>" alt="<?php echo esc_attr($lookbook->name)?>">
												</a>
											</div>
											<div class="lookbook-info">
												<div class="lookbook-info-wrap">
													<span class="lookbook-small-title"><?php echo esc_html($small_title )?></span>
													<h3>
														<a href="<?php echo get_term_link($lookbook,'product_lookbook') ?>">
															<?php echo dh_nth_word(esc_html($lookbook->name),'first',false)?>
														</a>
													</h3>
													<?php if($description = $lookbook->description):?>
													<div class="lookbook-description"><?php echo ($description)?></div>
													<?php endif;?>
													<a class="btn btn-primary btn-icon-left btn-uppercase btn-style-outlined btn-effect-icon-slide-in btn-align-left lookbook-action" href="<?php echo get_term_link($lookbook,'product_lookbook') ?>"><span><?php esc_html_e('Shop Now',DH_DOMAIN)?></span> <i class="fa fa-long-arrow-right"></i></a>
												</div>	
											</div>
										</div>
									<?php else:?>
										<?php 
											$image = wp_get_attachment_image_src( $thumbnail_id, 'wide'  ); 
											$image = $image[0];
										?>
										<div class="lookbook-<?php echo esc_attr($thumbnail_align) ?> clearfix">
											<div class="lookbook-thumb">
												<a href="<?php echo get_term_link($lookbook,'product_lookbook') ?>">
													<img src="<?php echo esc_url($image)?>" alt="<?php echo esc_attr($lookbook->name)?>">
												</a>
											</div>
											<div class="lookbook-info">
												<div class="lookbook-info-wrap">
													<span class="lookbook-small-title"><?php echo esc_html($small_title )?></span>
													<h3>
														<a href="<?php echo get_term_link($lookbook,'product_lookbook') ?>">
															<?php echo dh_nth_word(esc_html($lookbook->name),'first',false)?>
														</a>
													</h3>
													<?php if($description = $lookbook->description):?>
													<div class="lookbook-description"><?php echo ($description)?></div>
													<?php endif;?>
													<a class="btn btn-primary btn-icon-left btn-uppercase btn-style-outlined btn-effect-icon-slide-in btn-align-left lookbook-action" href="<?php echo get_term_link($lookbook,'product_lookbook') ?>"><span><?php esc_html_e('Shop Now',DH_DOMAIN)?></span> <i class="fa fa-long-arrow-right"></i></a>
												</div>
											</div>
										</div>
									<?php endif;?>
								<?php endif;?>
								</div>
							<?php endforeach;?>
						</div>
						<?php endif;?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php get_footer() ?>