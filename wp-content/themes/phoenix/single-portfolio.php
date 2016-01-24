<?php 
$main_class = dh_get_main_class();
$single_modern = 0;
if(dh_get_theme_option('single-portfolio-style','modern') == 'modern')
	$single_modern = 1;
?>
<?php get_header() ?>
	<div class="content-container">
		<div class="<?php dh_container_class() ?>">
			<div class="row">
				<?php do_action('dh_left_sidebar')?>
				<?php do_action('dh_left_sidebar_extra')?>
				<div class="<?php echo esc_attr($main_class) ?>" data-itemprop="mainContentOfPage" role="main">
					<div class="main-content">
					<?php if ( have_posts() ) : ?>
						<?php 
						 while (have_posts()): the_post(); global $post;
						 ?>
						 <article id="portfolio-<?php the_ID(); ?>" class="portfolio type-portfolio">
							<div class="portfolio-wrap">
								<?php if(!$single_modern):?>
								<div class="row">
									<div class="col-md-12">
										<div class="portfolio-title-wrap">
											<span class="portfolio-all"><a href="<?php echo get_post_type_archive_link(get_post_type()) ?>" title="<?php echo esc_attr(__('See All Portfolio',DH_THEME_DOMAIN))?>"><i class="dh-icon-column"></i></a></span>
											<h1 class="entry-title portfolio-title"><?php the_title()?></h1>
											<nav class="portfolio-navigation" role="navigation">
												<?php echo get_previous_post_link( '%link','<i class="dh-icon-chevron-left"></i>')?>
												<?php echo get_next_post_link( '%link','<i class="dh-icon-chevron-right"></i>')?>
											</nav>
										</div>
									</div>
								</div>
								<?php endif;?>
								<div class="row portfolio-summary">
									<div class="col-md-8">
										<?php dh_portfolio_featured(get_the_ID(),'',false,true); ?>
									</div>
									<div class="col-md-4">
										<div class="portfolio-meta-wrap">
											<div class="portfolio-meta">
												<?php if($project_url = dh_get_post_meta('url')):?>
													<div class="portfolio-link">
														<a target="_blank" href="<?php echo esc_attr($project_url)?>" class="btn btn-lg btn-primary"><?php echo esc_html('Launch Project',DH_THEME_DOMAIN)?></a>
													</div>
												<?php endif;?>
												<?php if($excerpt = get_the_excerpt()):?>
												<div class="portfolio-excerpt">
													<?php echo get_the_excerpt(); ?>
												</div>
												<?php endif;?>
												<div class="portfolio-attributes">
													<div class="row">
														<?php 
														$attributes = get_the_terms(get_the_ID(),'portfolio_attribute');
														if ( !is_wp_error( $attributes ) && !empty($attributes)):
															foreach ($attributes as $attribute):
															?>
															<div class="col-md-12 col-sm-6">
																<span class="portfolio-attribute"><i class="fa fa-check-circle"></i><?php echo esc_html($attribute->name)?></span>
															</div>
															<?php 
															endforeach;
														endif;
														?>
													</div>
												</div>
												<?php if(dh_get_theme_option('show-portfolio-share',1)):?>
												<div class="portfolio-share">
													<?php dh_share('',dh_get_theme_option('portfolio-fb-share',1),dh_get_theme_option('portfolio-tw-share',1),dh_get_theme_option('portfolio-go-share',1),dh_get_theme_option('portfolio-pi-share',0),dh_get_theme_option('portfolio-li-share',1));?>
												</div>
												<?php endif;?>
											</div>
										</div>
									</div>
								</div>
								<?php if(get_the_content()):?>
								<div class="portfolio-content">
									<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', DH_THEME_DOMAIN ) ) ?>
									<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', DH_THEME_DOMAIN ), 'after' => '</div>' ) ); ?>
								</div>
								<?php endif;?>
								<div class="portfolio-footer">
									<?php if(has_tag()):?>
									<div class="entry-tags">
										<?php the_tags(sprintf('<span>%s</span>',__('Tags: ',DH_THEME_DOMAIN)),'')?>
									</div>
									<?php endif;?>
								</div>
							</div>
						</article>
						 <?php
						 endwhile;
						 ?>
					<?php endif;?>
					<?php if(comments_open()):?>
						 <?php comments_template( '', true ); ?>
					<?php endif;?>
					</div>
				</div>
				<?php do_action('dh_right_sidebar_extra')?>
				<?php do_action('dh_right_sidebar')?>
			</div>
		</div>
	</div>
<?php get_footer() ?>