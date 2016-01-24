<div class="author-info">
	<div class="author-avatar">
		<?php
		$author_bio_avatar_size = 100;
		echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
		?>
	</div>
	<div class="author-description">
		<h2 class="author-title"><?php printf( __( 'About %s', DH_THEME_DOMAIN ), get_the_author() ); ?></h2>
		<p class="author-bio">
			<?php the_author_meta( 'description' ); ?>
		</p>
	</div>
</div>