<?php get_header()?>
<div class="content-container">
	<div class="container">
		<div class="row">
			<div class="col-md-12" role="main">
				<div class="main-content">
					<div class="not-found-wrapper">
						<span class="not-found-title"><?php _e('WHOOPS!', DH_THEME_DOMAIN); ?></span>
						<span class="not-found-subtitle"><?php _e('404', DH_THEME_DOMAIN); ?></span>
						<div class="widget widget_search">
							<p><?php _e('It looks like you are lost! Try searching here', DH_THEME_DOMAIN); ?></p>
							<?php get_search_form()?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer()?>
