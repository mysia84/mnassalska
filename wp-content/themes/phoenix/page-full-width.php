<?php
/*
Template Name: Full Width 100%
*/
?>
<?php 
$no_padding = dh_get_post_meta('no_padding');
?>
<?php get_header() ?>
	<div class="content-container<?php echo (!empty($no_padding) ? ' no-padding':'') ?>">
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
					</div>
				</div>
			</div>
		</div>
	</div>
<?php get_footer() ?>