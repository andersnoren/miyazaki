<?php get_header(); ?>

<main id="site-content">

	<div class="error-404-content">

		<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/404.svg" class="404-image" />

		<div class="section-inner">

			<p><?php printf( __( "We could not find the page you are looking for. Do you want to go back to the %s?", 'miyazaki' ), '<a href="' . esc_url( home_url() ) . '">' . __( 'front page', 'miyazaki' ) . '</a>' ); ?></p>

		</div><!-- .section-inner -->

	</div><!-- .error-404-content -->

</main>

<?php get_footer(); ?>
