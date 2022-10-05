<!DOCTYPE html>

<html class="no-js" <?php language_attributes(); ?>>

	<head>

		<meta http-equiv="content-type" content="<?php bloginfo( 'html_type' ); ?>" charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >

		<link rel="profile" href="http://gmpg.org/xfn/11">

		<?php wp_head(); ?>

	</head>

	<body <?php body_class(); ?>>

		<?php 
		if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open(); 
		}
		?>

		<a class="faux-button skip-link" href="#site-content"><?php _e( 'Skip to the content', 'miyazaki' ); ?></a>

		<header id="site-header">

			<div class="section-inner">

				<div class="header-left">

					<?php

					$has_logo = ( function_exists( 'the_custom_logo' ) && get_theme_mod( 'custom_logo' ) );
					$show_big_front_title = ( ( is_home() && is_front_page() ) && ! $has_logo && ! get_theme_mod( 'miyazaki_disable_front_page_title' ) );

					if ( ! $show_big_front_title ) :

						if ( $has_logo ) : ?>

							<div class="site-logo">

								<?php 

								// Display the regular logo
								miyazaki_custom_logo();

								// Display the overlay logo, if it's set
								miyazaki_custom_logo( 'miyazaki_overlay_logo' );

								?>

							</div><!-- .header-logo -->

							<?php
	
						elseif ( is_front_page() ) : ?>
	
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
	
						<?php else : ?>

							<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></p>

						<?php endif; ?>

					<?php endif; ?>

					<ul class="main-menu header-menu reset-list-style">
						<?php
						if ( has_nav_menu( 'primary-menu' ) ) {
							wp_nav_menu( array(
								'container' 		=> '',
								'items_wrap' 		=> '%3$s',
								'theme_location' 	=> 'primary-menu',
							) );
						} else {
							wp_list_pages( array(
								'container' => '',
								'title_li' 	=> '',
							) );
						}
						?>
					</ul><!-- .main-menu -->

				</div><!-- .header-left -->

				<div class="header-right">

					<button class="toggle nav-toggle" data-toggle-target=".mobile-menu-wrapper" data-toggle-scroll-lock="true">
						<label>
							<span class="show"><?php _e( 'Menu', 'miyazaki' ); ?></span>
							<span class="hide"><?php _e( 'Close', 'miyazaki' ); ?></span>
						</label>
						<div class="bars">
							<div class="bar"></div>
							<div class="bar"></div>
							<div class="bar"></div>
						</div><!-- .bars -->
					</button><!-- .nav-toggle -->

					<?php

					$disable_search = get_theme_mod( 'miyazaki_disable_search' );

					if ( ! $disable_search ) : ?>

						<button class="toggle search-toggle" data-toggle-target=".search-overlay" data-toggle-scroll-lock="true" data-set-focus=".search-overlay .search-field">
							<?php _e( 'Search', 'miyazaki' ); ?>
						</button><!-- .search-toggle -->

					<?php endif; ?>

				</div><!-- .header-right -->

			</div><!-- .section-inner -->

		</header><!-- #site-header -->

		<div class="mobile-menu-wrapper cover-modal" data-untoggle-above="1020" data-modal-target-string=".mobile-menu-wrapper">

			<div class="mobile-menu-container section-inner">

				<div class="mobile-menu-top">

					<ul class="mobile-menu header-menu reset-list-style">
						<?php
						if ( has_nav_menu( 'mobile-menu' ) ) {
							wp_nav_menu( array(
								'container' 		=> '',
								'items_wrap' 		=> '%3$s',
								'theme_location' 	=> 'mobile-menu',
							) );
						} else {
							wp_list_pages( array(
								'container' => '',
								'title_li' 	=> '',
							) );
						}
						?>
					</ul>

				</div><!-- .mobile-menu-top -->

				<?php if ( ! $disable_search ) : ?>

					<div class="overlay-search-form">
						<?php echo get_search_form(); ?>
					</div><!-- .overlay-search-form -->

				<?php endif; ?>

			</div><!-- .mobile-menu -->

		</div><!-- .mobile-menu-wrapper -->

		<?php if ( ! $disable_search ) : ?>

			<div class="search-overlay cover-modal" data-untoggle-below="1020" data-modal-target-string=".search-overlay">

				<div class="section-inner overlay-search-form search-overlay-form-wrapper">
					<?php echo get_search_form(); ?>
				</div><!-- .section-inner -->

			</div><!-- .search-overlay -->

		<?php endif; ?>

		<?php if ( $show_big_front_title ) : ?>

			<div class="front-title-wrapper section-inner">

				<h1 class="front-title site-title"><?php bloginfo( 'name' ); ?></h1>

			</div>

		<?php endif; ?>