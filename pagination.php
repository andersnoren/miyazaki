<?php

// Get the global $wp_query
global $wp_query;

// Combine the query with the query_vars into a single array
$query_args = array_merge( $wp_query->query, $wp_query->query_vars );

// If max_num_pages is not already set, add it
if ( ! array_key_exists( 'max_num_pages', $query_args ) ) {
	$query_args['max_num_pages'] = $wp_query->max_num_pages;
}

// If post_status is not already set, add it
if ( ! array_key_exists( 'post_status', $query_args ) ) {
	$query_args['post_status'] = 'publish';
}

// Make sure the paged value exists and is at least 1
if ( ! array_key_exists( 'paged', $query_args ) || 0 == $query_args['paged'] ) {

	// The page that will be loaded
	$query_args['paged'] = 1;

}

// Only show if we have more pages to load
if ( $query_args['max_num_pages'] > $query_args['paged'] ) :

	// Encode our modified query
	$json_query_args = wp_json_encode( $query_args ); ?>

	<section class="pagination-wrapper mpad-u-0 mpad-d-80 tpad-d-100 dpad-d-180">

		<div id="pagination" data-query-args="<?php echo esc_attr( $json_query_args ); ?>" data-load-more-target=".load-more-target">

			<button id="load-more" class="title-with-arrow">
				<span class="text"><?php _e( 'Load More', 'miyazaki' ); ?></span>
				<img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/arrow-down.svg" />
			</button>

			<p class="out-of-posts"><?php _e( 'Nothing more to load.', 'miyazaki' ); ?></p>

			<div class="loading-icon">
				<?php miyazaki_loading_indicator(); ?>
			</div>

			<?php

			$has_previous_link = get_previous_posts_link();
			$has_next_link = get_next_posts_link();

			if ( $has_previous_link || $has_next_link ) :

				if ( ! $has_previous_link ) {
					$pagination_class = ' only-next';
				} else {
					$pagination_class = '';
				}

				?>

				<nav class="link-pagination<?php echo $pagination_class; ?>">

					<?php if ( get_previous_posts_link() ) : ?>
						<?php previous_posts_link( __( 'Previous Page', 'miyazaki' ) ); ?>
					<?php endif; ?>

					<?php if ( get_next_posts_link() ) : ?>
						<?php next_posts_link( __( 'Next Page', 'miyazaki' ) ); ?>
					<?php endif; ?>

				</nav><!-- .posts-pagination -->

			<?php endif; ?>

		</div><!-- #oa-paging -->

	</section><!-- .pagination-wrapper -->

<?php endif; ?>
