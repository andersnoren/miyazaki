<article <?php post_class( 'preview preview-' . get_post_type() . ' do-spot' ); ?> id="post-<?php the_ID(); ?>">

	<div class="preview-wrapper">

		<?php

		$fallback_image_url = miyazaki_get_fallback_image_url();

		if ( ( has_post_thumbnail() && ! post_password_required() ) || $fallback_image_url ) : ?>

			<a href="<?php the_permalink(); ?>" class="preview-image">

				<?php
				if ( has_post_thumbnail() && ! post_password_required() ) {
					$image_size = miyazaki_get_preview_image_size();
					the_post_thumbnail( $image_size );
				} else {
					echo '<img class="fallback-image" src="' . $fallback_image_url . '" />';
				}
				?>
				
			</a>

		<?php endif; ?>

		<h3 class="preview-title"><a href="<?php the_permalink(); ?>"><span><?php the_title(); ?></span></a></h3>

		<?php

		// Output the post meta
		miyazaki_the_post_meta( $post->ID, 'preview' ); ?>

	</div><!-- .preview-wrapper -->

</article>
