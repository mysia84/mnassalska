<?php
$output = '';
extract(shortcode_atts(array(
	'title'				=>'',
	'title_bg'		    =>'',
	'title_icon'		=>'',
	'posts_per_page'	=>'5',
	'orderby'			=>'',
	'categories'		=>'',
	'style'				=>'',
	'fx'				=>'scroll',
	'visible'			=>'4',
	'auto_play'			=>'',
	'hide_pagination'	=>'',
	'hide_control'		=>'',
	'title_is_cat_name' =>'',
	'intro_thumb'		=>'',
	'hide_excerpt'		=>'',
	'excerpt_length'	=>'',
	'hide_date'			=>'',
	'hide_comment'		=>'',
	'visibility'		=>'',
	'el_class'			=>'',
), $atts));

$show_date = empty($hide_date) ? true : false;
$show_comment = empty($hide_comment) ? true : false;

$class          = !empty($el_class) ?  ' '.esc_attr( $el_class ) : '';
switch ($visibility) {
	case 'hidden-phone':
		$class .= ' hidden-xs';
		break;
	case 'hidden-tablet':
		$class .= ' hidden-sm hidden-md';
		break;
	case 'hidden-pc':
		$class .= ' hidden-lg';
		break;
	case 'visible-phone':
		$class .= ' visible-xs-inline';
		break;
	case 'visible-tablet':
		$class .= ' visible-sm-inline visible-md-inline';
		break;
	case 'visible-pc':
		$class .= ' visible-lg-inline';
		break;
}
$order = 'DESC';
switch ($orderby) {
	case 'latest':
		$orderby = 'date';
		break;

	case 'oldest':
		$orderby = 'date';
		$order = 'ASC';
		break;
	case 'comment':
		$orderby = 'comment_count';
		$order = 'DESC';
	break;
	case 'alphabet':
		$orderby = 'title';
		$orderby = 'ASC';
		break;

	case 'ralphabet':
		$orderby = 'title';
		break;

	default:
		$orderby = 'date';
		break;
}
$args = array(
		'orderby'         => $orderby,
		'order'           => $order,
		'post_type'       => "post",
);
if(!empty($posts_per_page))
	$args['posts_per_page'] = $posts_per_page;

if(!empty($categories)){
	$args['category_name'] = $categories;
}
$r = new WP_Query($args);
if($r->have_posts()):
	$output .='<div class="latestnews latestnews-'.$style.$class.'">';
		$sub_cats = array();
		$tab = '';
		$id = uniqid('latestnews_');
		
		if($style ==='tab' && !empty($categories) && ($category = get_category_by_slug($categories))):
			$sub_cats = get_categories(array('child_of' => $category->term_id, 'number' => 3, 'hierarchical' => false));
			if(!empty($sub_cats)):
				$tab .='<ul class="sub-cat">';
				$tab .='<li class="active"><a data-toggle="tab" href="#'.$id.'0">'.__('All',DH_THEME_DOMAIN).'</a></li>';
				foreach ((array)$sub_cats as $cat):
					$tab .='<li><a data-toggle="tab" href="#'.$id.$cat->term_id.'">'.$cat->name.'</a></li>';
				endforeach;
				$tab .='</ul>';
			endif;
		endif;
		
		$title_bg = dh_format_color($title_bg);
		if(!empty($categories) && !empty($title_is_cat_name)):
			$output .='<div class="latestnews-title"'.(!empty($title_bg) ? ' style="border-color:'.$title_bg.'"':'').'>';
			$category = get_category_by_slug($categories);
			$output .= '<h3 class="el-heading"'.(!empty($title_bg) ? ' style="background-color:'.$title_bg.'"':'').'><a href="'.esc_url(get_category_link($category)).'">'.(!empty($title_icon)?'<i class="'.esc_attr($title_icon).'"></i>':'').$category->name.'</a></h3>';
			$output .=$tab;
			$output .='</div>';
		elseif (!empty($title)):
			$output .='<div class="latestnews-title"'.(!empty($title_bg) ? ' style="border-color:'.$title_bg.'"':'').'>';
			$output .= '<h3 class="el-heading"'.(!empty($title_bg) ? ' style="background-color:'.$title_bg.'"':'').'><span>'.(!empty($title_icon)?'<i class="'.esc_attr($title_icon).'"></i>':'').$title.'</span></h3>';
			$output .= $tab;
			$output .='</div>';
		endif;
		
		$output .='<div class="latestnews-content">';
		if($style === 'carousel'):
			/**
			 * script
			 * {{
			 */
			wp_enqueue_script('vendor-carouFredSel');
			$output .='<div class="caroufredsel" data-visible-min="1" data-visible-max="'.$visible.'" data-scroll-fx="'.$fx.'" data-speed="7000" data-responsive="1" data-infinite="1" data-autoplay="'.(!empty($auto_play)?'1':'0').'">';
			$output .='<div class="caroufredsel-wrap">';
			$output .='<ul class="caroufredsel-items row">';
			while ($r->have_posts()): $r->the_post(); global $post;
				$output .='<li class="caroufredsel-item col-md-'.(12/$visible).'">';
				$output .='<article itemtype="'.dh_get_protocol().'://schema.org/Article" itemscope="" class="latestnews-leading">';
				$output .='<div class="latestnews-thumb">';
				if(has_post_thumbnail()){
					$output .='<a href="'.esc_url(get_the_permalink()).'" title="'.esc_attr(get_the_title()).'">';
					$output .=get_the_post_thumbnail(null,'dh-thumbnail-square',array('data-itemprop'=>'image','title' => esc_attr(get_the_title())));
					if(get_post_format() == 'video'){
						$output .='<i class="featured-play"></i>';
					}
					$output .='</a>';
				}else{
					$output .='<a href="'.esc_url(get_the_permalink()).'" title="'.esc_attr(get_the_title()).'">';
					$output .='<img src="'.get_template_directory_uri().'/assets/images/noo-thumb_650x450.png" alt="'.get_the_title().'">';
					if(get_post_format() == 'video'){
						$output .='<i class="featured-play"></i>';
					}
					$output .='</a>';
				}
				$output .='</div>';
				$output .='<h2 data-itemprop="name"'.(!empty($title_bg) ? ' style="border-color:'.$title_bg.'"':'').'>';
				$output .='<a data-itemprop="url" title="'.esc_attr(get_the_title()).'" href="'.esc_url(get_the_permalink()).'">'.get_the_title().'</a>';
				$output .='</h2>';
				if($show_comment || $show_date):
					$output .='<div class="entry-meta">';
					$output .= dh_post_meta($show_date,$show_comment,false,false,false,'|','M j, Y');
					$output .='</div>';
				endif;
				
				if(empty($hide_excerpt)){
					$output .='<div class="excerpt">';
					$excerpt = $post->post_excerpt;
					if(empty($excerpt))
						$excerpt = $post->post_content;
						
					$excerpt = strip_shortcodes($excerpt);
					$excerpt = wp_trim_words($excerpt,$excerpt_length,'...');
					$output .= '<p>' . $excerpt . '</p>';
					$output .='</div>';
				}
				$output .='<meta content="UserComments:'.get_comments_number().'" data-itemprop="interactionCount">';
				$output .='<meta content="'.get_the_author().'" itemprop="author">';
				$output .='</article>';
				$output .='</li>';
			endwhile;
			$output .='</ul>';
			if(empty($hide_control)){
				$output .='<a href="#" class="caroufredsel-prev"></a>';
				$output .='<a href="#" class="caroufredsel-next"></a>';
			}
			$output .='</div>';
			if(empty($hide_pagination)){
				$output .='<div class="caroufredsel-pagination">';
				$output .='</div>';
			}
			$output .='</div>';
		else:
			$highlighted = false;
			if($style ==='tab' && !empty($sub_cats)):
			$output .='<div id="'.$id.'0" class="latestnews-tab-content fade active in">';
			endif;
			while ($r->have_posts()): $r->the_post(); global $post;
				if(!$highlighted){
					$highlighted = true;
					$output .='<article itemtype="'.dh_get_protocol().'://schema.org/Article" itemscope="" class="latestnews-leading">';
					$output .='<div class="latestnews-thumb">';
					if(has_post_thumbnail()){
						$output .='<a href="'.esc_url(get_the_permalink()).'" title="'.esc_attr(get_the_title()).'">';
						$output .=get_the_post_thumbnail(null,'dh-thumbnail-square',array('data-itemprop'=>'image','title' => esc_attr(get_the_title())));
						if(get_post_format() == 'video'){
							$output .='<i class="featured-play"></i>';
						}
						$output .='</a>';
					}else{
						$output .='<a href="'.esc_url(get_the_permalink()).'" title="'.esc_attr(get_the_title()).'">';
						$output .='<img src="'.get_template_directory_uri().'/assets/images/noo-thumb_650x450.png" alt="'.get_the_title().'">';
						if(get_post_format() == 'video'){
							$output .='<i class="featured-play"></i>';
						}
						$output .='</a>';
					}
					$output .='</div>';
					$output .='<h2 itemprop="name"'.(!empty($title_bg) ? ' style="border-color:'.$title_bg.'"':'').'>';
					$output .='<a itemprop="url" title="'.esc_attr(get_the_title()).'" href="'.esc_url(get_the_permalink()).'">'.get_the_title().'</a>';
					$output .='</h2>';
					if($show_comment || $show_date):
						$output .='<div class="entry-meta">';
						$output .= dh_post_meta($show_date,$show_comment,false,false,false);
						$output .='</div>';
					endif;
					if(empty($hide_excerpt)){
						$output .='<div class="excerpt">';
						$excerpt = $post->post_excerpt;
						if(empty($excerpt))
							$excerpt = $post->post_content;
							
						$excerpt = strip_shortcodes($excerpt);
						$excerpt = wp_trim_words($excerpt,$excerpt_length,'...');
						$output .= '<p>' . $excerpt . '</p>';
						$output .='</div>';
					}
					$output .='<meta content="UserComments:'.get_comments_number().'" itemprop="interactionCount">';
					$output .='<meta content="'.get_the_author().'" itemprop="author">';
					$output .='</article>';
					if($r->post_count > 1):
					$output .='<ul class="latestnews-intro'.(!empty($intro_thumb) ? ' intro-thumbnail':'').'">';
					endif;
					continue;
				}
				$output .='<li>';
				if(!empty($intro_thumb)):
					$output .='<div class="intro-thumbnail-image'.(has_post_thumbnail() ? '':' intro-thumbnail-no-image').'">';
					$output .='<a href="'.esc_url(get_the_permalink()).'" title="'.esc_attr(get_the_title()).'">';
					$output .=get_the_post_thumbnail(null,'dh-thumbnail-square',array('title' => esc_attr(get_the_title())));
					$output .='</a>';
					$output .='</div>';
				endif;
				$output .='<div class="intro-content">';
				$output .='<h3>';
				$post_format_icon = '';
				$post_format = get_post_format();
				if($post_format == 'video')
					$post_format_icon = '<i class="format-icon format-video"></i>';
				elseif ($post_format == 'gallery')
					$post_format_icon = '<i class="format-icon format-gallery"></i>';
				$output .=$post_format_icon.'<a class="intro-content-title" title="'.esc_attr(get_the_title()).'" href="'.esc_url(get_the_permalink()).'">'.get_the_title().'</a>';
				$output .='</h3>';
				
				$comment_title = '';
				$comment_number = '';
				if (get_comments_number() == 0) {
					$comment_title = sprintf(__('Leave a comment on: &ldquo;%s&rdquo;', DH_THEME_DOMAIN) , get_the_title());
					$comment_number = __(' Leave a Comment', DH_THEME_DOMAIN);
				} else if (get_comments_number() == 1) {
					$comment_title = sprintf(__('View a comment on: &ldquo;%s&rdquo;', DH_THEME_DOMAIN) , get_the_title());
					$comment_number = ' 1 ' . __('Comment', DH_THEME_DOMAIN);
				} else {
					$comment_title = sprintf(__('View all comments on: &ldquo;%s&rdquo;', DH_THEME_DOMAIN) , get_the_title());
					$comment_number =  ' ' . get_comments_number() . ' ' . __('Comments', DH_THEME_DOMAIN);
				}
				if($show_date)
					$output .='<time datetime="'.get_the_date('Y-m-d\TH:i:sP').'">'.get_the_date('M j, Y').'</time>';
				if($show_comment)
					$output .='<span class="comment-count"><a title="'.esc_attr($comment_title).'" href="'.esc_url(get_comments_link()).'">'.$comment_number.'</a></span>';
		
				$output .='</div>';
				$output .='</li>';
				//
				//
			endwhile;
			if($r->post_count > 1):
				$output .='</ul>';
			endif;
			if($style ==='tab' && !empty($sub_cats)):
				wp_reset_postdata();
				$output .='</div>';
				foreach ($sub_cats as $cat):
					$args['category_name'] = $cat->slug;
					$cat_r = new WP_Query($args);
					if($cat_r->have_posts()):
						$output .='<div id="'.$id.$cat->term_id.'" class="latestnews-tab-content fade">';
						$highlighted = false;
						while ($cat_r->have_posts()): $cat_r->the_post(); global $post;
							if(!$highlighted){
								$highlighted = true;
								$output .='<article itemtype="'.dh_get_protocol().'://schema.org/Article" itemscope="" class="latestnews-leading">';
								$output .='<div class="latestnews-thumb">';
								$output .='<a href="'.esc_url(get_the_permalink()).'" title="'.esc_attr(get_the_title()).'">';
								$output .=get_the_post_thumbnail(null,'dh-thumbnail',array('data-itemprop'=>'image','title' => esc_attr(get_the_title())));
								$output .='</a>';
								$output .='</div>';
								$output .='<h2 data-itemprop="name">';
								$output .='<a data-itemprop="url" title="'.esc_attr(get_the_title()).'" href="'.esc_url(get_the_permalink()).'">'.get_the_title().'</a>';
								$output .='</h2>';
								if($show_comment || $show_date):
								$output .='<div class="entry-meta">';
								$output .= dh_post_meta($show_date,$show_comment,false,false,false,'|','M j,Y');
								$output .='</div>';
								endif;
								if(empty($hide_excerpt)){
									$output .='<div class="excerpt">';
									$excerpt = $post->post_excerpt;
									if(empty($excerpt))
										$excerpt = $post->post_content;
										
									$excerpt = strip_shortcodes($excerpt);
									$excerpt = wp_trim_words($excerpt,$excerpt_length,'...');
									$output .= '<p>' . $excerpt . '</p>';
									$output .='</div>';
								}
								$output .='<meta content="UserComments:'.get_comments_number().'" itemprop="interactionCount">';
								$output .='<meta content="'.get_the_author().'" itemprop="author">';
								$output .='</article>';
								if($r->post_count > 1):
								$output .='<ul class="latestnews-intro'.(!empty($intro_thumb) ? ' intro-thumbnail':'').'">';
								endif;
								continue;
							}
							$output .='<li>';
							if(!empty($intro_thumb)):
							$output .='<div class="intro-thumbnail-image">';
							$output .='<a href="'.esc_url(get_the_permalink()).'" title="'.esc_attr(get_the_title()).'">';
							$output .=get_the_post_thumbnail(null,'dh-thumbnail-square',array('title' => esc_attr(get_the_title())));
							$output .='</a>';
							$output .='</div>';
							endif;
							$output .='<div class="intro-content">';
							$output .='<h3><a class="intro-content-title" title="'.esc_attr(get_the_title()).'" href="'.esc_url(get_the_permalink()).'">'.get_the_title().'</a></h3>';
							
							$comment_title = '';
							$comment_number = '';
							if (get_comments_number() == 0) {
								$comment_title = sprintf(__('Leave a comment on: &ldquo;%s&rdquo;', DH_THEME_DOMAIN) , get_the_title());
								$comment_number = __(' Leave a Comment', DH_THEME_DOMAIN);
							} else if (get_comments_number() == 1) {
								$comment_title = sprintf(__('View a comment on: &ldquo;%s&rdquo;', DH_THEME_DOMAIN) , get_the_title());
								$comment_number = ' 1 ' . __('Comment', DH_THEME_DOMAIN);
							} else {
								$comment_title = sprintf(__('View all comments on: &ldquo;%s&rdquo;', DH_THEME_DOMAIN) , get_the_title());
								$comment_number =  ' ' . get_comments_number() . ' ' . __('Comments', DH_THEME_DOMAIN);
							}
							if($show_date){
								$output .='<time datetime="'.get_the_date('Y-m-d\TH:i:sP').'">'.get_the_date('M j, Y').'</time>';
							}
							if($show_comment){
								$output .='<span class="comment-count"><a title="'.esc_attr($comment_title).'" href="'.esc_url(get_comments_link()).'">'.$comment_number.'</a></span>';
							}
							$output .='</div>';
							$output .='</li>';
						endwhile;
						if($cat_r->post_count > 1):
							$output .='</ul>';
						endif;
					$output .='</div>';
					endif;
					wp_reset_postdata();
				endforeach;
			endif;
		endif;
		$output .='</div>';
	$output .='</div>';
endif;
wp_reset_postdata();
echo $output;