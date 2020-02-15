<?php get_header(); ?>

<main id="site-content">

	<div class="section-inner">

		<?php 
		
		if ( is_archive() || is_search() ) : 

			$has_description = get_the_archive_description();

			$results_count = $wp_query->found_posts;
			$results_strlen = strlen( $results_count );
		
			?>

			<header class="archive-header <?php if ( $has_description ) echo ' has-description'; ?>">

				<div class="archive-header-titles">

					<h3 class="archive-title-prefix"><?php echo miyazaki_get_archive_title_prefix(); ?></h3>
					
					<h1 class="archive-title">
						<?php the_archive_title(); ?>
						<?php if ( $results_count ) : ?>
							<div class="results-count length-<?php echo $results_strlen; ?>"><?php echo $results_count; ?></div>
						<?php endif; ?>
					</h1>

				</div><!-- .header-titles -->

				<?php if ( $has_description ) : ?>

					<div class="archive-header-text">

						<div class="archive-description intro-text">
							<?php the_archive_description(); ?>
						</div><!-- .archive-description -->

					</div><!-- .header-text -->

				<?php endif; ?>

			</header><!-- .archive-header -->

		<?php endif; ?>

		<?php if ( have_posts() ) : ?>

			<div class="posts load-more-target" id="posts">

				<div class="grid-sizer"></div>

				<?php

				while ( have_posts() ) : the_post();

					get_template_part( 'preview', get_post_type() );

				endwhile;

				?>

			</div><!-- .posts -->

			<?php get_template_part( 'pagination' ); ?>

		<?php elseif ( is_search() ) : ?>

			<p class="no-search-results"><?php _e( "We could not find any matching search results, but feel free to try again with different words.", "miyazaki" ); ?></p>

		<?php endif; ?>

	</div><!-- .section-inner -->

</main><!-- #site-content -->

<?php get_footer(); ?>
