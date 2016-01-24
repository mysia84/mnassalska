<?php
if ( post_password_required() )
	return;
?>

<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
		<div class="title-sep-wrap commentst-title">
			<h3 class="title-sep-text">
				<?php comments_number(__('No Comments', DH_THEME_DOMAIN), __('One Comment', DH_THEME_DOMAIN), '% '.__('Comments', DH_THEME_DOMAIN));?>
			</h3>
			<span class="title-sep"><span></span></span>
		</div>
		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'callback'	 => 'dh_list_comments',
				'style'      => 'ol',
				'avatar_size'=> 60,
			) );
			?>
		</ol>
		<?php
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
		?>
		<div class="paginate comment-paginate">
			<div class="paginate_links">
				<?php paginate_comments_links()?>
			</div>
		</div>
		<?php endif; ?>

		<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="no-comments"><?php _e( 'Comments are closed.' , DH_THEME_DOMAIN ); ?></p>
		<?php endif; ?>
	<?php endif; ?>
	<?php dh_comment_form(); ?>
</div>