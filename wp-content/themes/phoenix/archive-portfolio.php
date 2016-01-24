<?php 
$main_class = dh_get_main_class();
$layout = dh_get_theme_option('portfolio-layout','grid');
$style = dh_get_theme_option('portfolio-stype','one');
$columns = dh_get_theme_option('portfolio-columns',3);
$categories = '';
$gap = dh_get_theme_option('portfolio-gap','1');
$hide_filter = dh_get_theme_option('portfolio-show-filter',1) == 1 ? '' : 'yes';
$wrap_filter = dh_get_theme_option('portfolio-wrap-filter',0) == '1' ? 'yes':'';
$hide_sorting = dh_get_theme_option('portfolio-show-sorting',1) == '1' ? '' : 'yes';
$hide_action = dh_get_theme_option('portfolio-show-action',1) == '1' ? '' : 'yes';
$portfolio_action_link_to = (dh_get_theme_option('portfolio-action-link-to','single_url') == 'single_url') ? false : true;
$pagination = dh_get_theme_option('portfolio-pagination','infinite_scroll');
$loadmore_text = dh_get_theme_option('portfolio-loadmore-text',__('Load More',DH_THEME_DOMAIN));
$gap_class=' no-gap'; 
if(!empty($gap)){
	if($gap === 'onepx'){
		$gap_class =' onepx';
	}else{
		$gap_class =' gap';
	}
}
if($layout == 'masonry'){
	/**
	 * script
	 * {{
	 */
	wp_enqueue_script('vendor-isotope');
}
if($pagination === 'infinite_scroll'){
	/**
	 * script
	 * {{
	 */
	wp_enqueue_script('vendor-infinitescroll');
}
global $wp_query;
?>
<?php get_header() ?>
	<div class="content-container">
		<div class="<?php dh_container_class() ?>">
			<div class="row">
				<?php do_action('dh_left_sidebar')?>
				<?php do_action('dh_left_sidebar_extra')?>
				<div class="<?php echo esc_attr($main_class) ?>" role="main">
					<div class="main-content">
						<?php if ( have_posts() ) : ?>
						<div class="portfolio portfolio-style-<?php echo esc_attr($style) ?> <?php echo (($pagination === 'loadmore') ? ' loadmore':''); ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll':'') ?><?php echo (($layout === 'masonry') ? ' masonry':'') ?><?php echo esc_attr($gap_class) ?>" data-paginate="<?php echo esc_attr($pagination) ?>" data-layout="<?php echo esc_attr($layout) ?>" <?php echo ($layout === 'masonry') ? ' data-masonry-column="'.$columns.'"':''?>>
							<?php if(($layout === 'masonry') && (empty($hide_filter) || empty($hide_sorting))):?>
							<?php if(!empty($wrap_filter)):?>
							<div class="container">
							<?php endif;?>
								<div class="portfolio-filter">
									<div class="filter-heaeding">
										<h3><?php echo esc_html_e('All',DH_THEME_DOMAIN)?></h3>
									</div>
									<div class="filter-action <?php echo (empty($hide_sorting) ? 'sorting':'no-sorting');?>">
										<?php if(empty($hide_sorting)):?>
											<a href="#"  data-sort="date" class="sort-btn current reversed"><?php esc_html_e('Date',DH_THEME_DOMAIN) ?> <i class="fa fa-sort"></i></a>
											<a href="#"  data-sort="title" class="sort-btn"><?php esc_html_e('Name',DH_THEME_DOMAIN) ?> <i class="fa fa-sort"></i></a>
										<?php endif;?>
										<?php if(empty($hide_filter)):?>
											<?php if(empty($hide_sorting)):?>
											<a href="#" class="filter-btn"><?php esc_html_e('Filters',DH_THEME_DOMAIN) ?> <i class="fa fa-bars"></i></a>
											<?php endif;?>
											<ul data-filter-key="filter">
												<li>
													<a class="selected" href="#" data-filter-value= "*"><?php echo esc_html_e('All',DH_THEME_DOMAIN) ?></a>
												</li>
												<?php $category_arr = explode(',', $categories); ?>
												<?php $category_arr = array_filter($category_arr)?>
												<?php if(is_array($category_arr) && count($category_arr) > 0):?>
													<?php foreach ($category_arr as $cat):?>
														<?php if($cat):?>
															<?php $category = get_term_by('slug',$cat, 'portfolio_category'); ?>
															<?php if($category): ?>
															<li>
																<a href="#" data-filter-value= ".<?php echo esc_attr($category->slug) ?>"><?php echo esc_html($category->name); ?></a>
															</li>
															<?php endif;?>
														<?php endif;?>
													<?php endforeach;?>
												<?php else:?>
													<?php foreach ((array) get_terms('portfolio_category') as $category):?>
														<?php 
															if ( empty($category->slug ) )
																continue;
														?>
														<li>
															<a href="#" data-filter-value= ".<?php echo esc_attr($category->slug) ?>"><?php echo esc_html($category->name); ?></a>
														</li>
													<?php endforeach;?>
												<?php endif;?>
											</ul>
										<?php endif;?>
									</div>
								</div>
							<?php if(!empty($wrap_filter)):?>
							</div>
							<?php endif;?>
							<?php endif;?>
							<div class="portfolio-wrap<?php echo (($pagination === 'loadmore') ? ' loadmore-wrap':'') ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll-wrap':'') ?><?php echo (($layout === 'masonry') ? ' masonry-wrap':'') ?> portfolio-layout-<?php echo esc_attr($layout)?><?php if($layout == 'grid' || $layout == 'masonry') echo' row' ?>">
								<?php 
								$post_class = 'portfolio-item';
								$post_class .= (($pagination === 'infinite_scroll') ? ' infinite-scroll-item':'');
								$post_class .= (($pagination === 'loadmore') ? ' loadmore-item':'');
								
									
								if($layout == 'masonry'){
									$post_class .=' masonry-item ';
								}
								?>
								<?php $i = 0;$w=0;?>
								<?php while (have_posts()):the_post(); global $post; $i++;$format = dh_get_post_meta('portfolio_format'); ?>
									<?php 
									//action link
									$view_action = get_the_permalink();
									$target='';
									if($portfolio_action_link_to && ($project_url = dh_get_post_meta('url'))){
										$view_action = $project_url;
										$target = '  target="_blank"';
									}
									
									$post_class_masonry_size =' '.dh_get_post_meta('masonry_size','','normal');
									
									$post_col = '';
									if($style != 'lily'){
											if(dh_get_post_meta('masonry_size',$post->ID,'normal') === 'double' || dh_get_post_meta('masonry_size',$post->ID,'normal') === 'wide_tall'):
												if($layout == 'grid'):
													$post_col = ' col-md-'.(12/$columns).' col-sm-6';
												elseif ($layout == 'masonry'):
													$post_col = ' col-md-'.((12/$columns) * 2).' col-sm-6';
												endif;
											else :
												$post_col = ' col-md-'.(12/$columns).' col-sm-6';
											endif;
										}else{
											$post_col = ' col-md-'.(12/$columns).' col-sm-6';
										}
									$post_col = apply_filters('dh_portfolio_item_col', $post_col, $columns);
									?>
									<?php 
									$cat_class = array();
									if($layout == 'masonry'){
										if ( is_object_in_taxonomy( $post->post_type, 'portfolio_category' ) ) {
											foreach ( (array) get_the_terms($post->ID,'portfolio_category') as $cat ) {
												if ( empty($cat->slug ) )
													continue;
												$cat_class[] =  sanitize_html_class($cat->slug, $cat->term_id);
											}
										}
									}
									?>
									<article data-title="<?php echo esc_attr(get_the_title())?>" data-date="<?php echo esc_attr(get_the_time('YmdHis'))?>"<?php if($accent_color = dh_get_post_meta('accent_color',$post->ID)):?> data-accent-color="<?php echo esc_attr($accent_color) ?>"<?php endif;?> id="portfolio-<?php the_ID(); ?>" class="<?php echo esc_attr($post_class) ?><?php echo esc_attr($post_class_masonry_size)?><?php echo esc_attr($post_col) ?><?php echo (!empty($cat_class) ? ' '.implode(' ', $cat_class):'')?>">
										<div class="portfolio-item-wrap">
											<?php 
											$entry_featured_class = '';
											?>
											<?php $thumb_img = '';?>
											<div class="portfolio-featured-wrap<?php echo esc_attr($entry_featured_class)?>">
												<?php dh_portfolio_featured('','',true,$hide_action,$layout,$portfolio_action_link_to); ?>
												<?php if($hide_action && $style === 'three'):?>
												<div class="portfolio-overlay"></div>
												<?php endif;?>
											</div>
											<div class="portfolio-caption">
												<div class="portfolio-caption-wrap">
													<h2 class="portfolio-title">
														<a href="<?php echo esc_url($view_action)?>" <?php echo ($target)?> title="<?php echo esc_attr(get_the_title())?>">
															<?php 
															if($style == 'lily' || $style == 'marley')
																dh_nth_word(get_the_title());
															else 
																the_title();
															?>
														</a>
													</h2>
													<div class="portfolio-meta">
														<?php echo get_the_date()?>
													</div>
												</div>
											</div>
										</div>
									</article>
								<?php endwhile;?>
							</div>
							<?php if($pagination === 'loadmore' && 1 < $wp_query->max_num_pages ):?>
							<div class="loadmore-action">
								<div class="loadmore-loading"><div class="fade-loading"><i></i><i></i><i></i><i></i></div></div>
								<button type="button" class="btn-loadmore"><?php echo esc_html($loadmore_text) ?></button>
							</div>
							<?php endif;?>
							<?php 
							$paginate_args = array();
							if($pagination === 'infinite_scroll' || $pagination === 'loadmore'){
								$paginate_args = array('show_all'=>true);
							}
							?>
							<?php if($pagination != 'no')dh_paginate_links($paginate_args);?>
						</div>
						<?php endif;?>
					</div>
				</div>
				<?php do_action('dh_right_sidebar_extra')?>
				<?php do_action('dh_right_sidebar')?>
			</div>
		</div>
	</div>
<?php get_footer() ?>