<?php

/* ---------------------------------------------------------------------------------------------
   THEME SETUP
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_setup' ) ) :
	function miyazaki_setup() {

		// Automatic feed
		add_theme_support( 'automatic-feed-links' );

		// Custom background color
		add_theme_support( 'custom-background' );

		// Set content-width
		global $content_width;
		$content_width = 520;

		// Post thumbnails
		add_theme_support( 'post-thumbnails' );

		// Set post thumbnail size
		set_post_thumbnail_size( 1870, 9999 );

		// Add image sizes
		add_image_size( 'miyazaki_preview_image_low_resolution', 400, 9999, false );
		add_image_size( 'miyazaki_preview_image_high_resolution', 800, 9999, false );

		// Custom logo
		add_theme_support( 'custom-logo', array(
			'height'      => 240,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array( 'site-title', 'site-description' ),
		) );

		// Title tag
		add_theme_support( 'title-tag' );

		// Add nav menu
		register_nav_menu( 'primary-menu', __( 'Primary Menu', 'miyazaki' ) );
		register_nav_menu( 'mobile-menu', __( 'Mobile Menu', 'miyazaki' ) );
		register_nav_menu( 'footer-menu', __( 'Footer Menu', 'miyazaki' ) );

		// HTML5 semantic markup
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

		// Make the theme translation ready
		load_theme_textdomain( 'miyazaki', get_template_directory() . '/languages' );

	}
	add_action( 'after_setup_theme', 'miyazaki_setup' );
endif;


/*	-----------------------------------------------------------------------------------------------
	REQUIRED FILES
	Include required files
--------------------------------------------------------------------------------------------------- */

// Custom Customizer control for multiple checkboxes
require get_template_directory() . '/inc/classes/class-miyazaki-customize-control-checkbox-multiple.php';

// Handle Customizer settings
require get_template_directory() . '/inc/classes/class-miyazaki-customize.php';


/* ---------------------------------------------------------------------------------------------
   ENQUEUE STYLES
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_load_style' ) ) :
	function miyazaki_load_style() {

		$dependencies = array();

		wp_register_style( 'miyazaki-google-fonts', get_theme_file_uri( '/assets/css/fonts.css' ) );
		$dependencies[] = 'miyazaki-google-fonts';

		wp_enqueue_style( 'miyazaki-style', get_template_directory_uri() . '/style.css', $dependencies, wp_get_theme( 'miyazaki' )->get( 'Version' ) );

	}
	add_action( 'wp_enqueue_scripts', 'miyazaki_load_style' );
endif;


/* ---------------------------------------------------------------------------------------------
   ADD EDITOR STYLES
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_add_editor_styles' ) ) :
	function miyazaki_add_editor_styles() {

		add_editor_style( array( '/assets/css/miyazaki-classic-editor-style.css', '/assets/css/fonts.css' ) );

	}
	add_action( 'init', 'miyazaki_add_editor_styles' );
endif;


/* ---------------------------------------------------------------------------------------------
   ENQUEUE SCRIPTS
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_enqueue_scripts' ) ) :
	function miyazaki_enqueue_scripts() {

		wp_enqueue_script( 'miyazaki_construct', get_template_directory_uri() . '/assets/js/construct.js', array( 'jquery', 'imagesloaded', 'masonry' ), wp_get_theme()->get( 'Version' ), true );

		if ( ( ! is_admin() ) && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		$ajax_url = admin_url( 'admin-ajax.php' );

		// AJAX Load More
		wp_localize_script( 'miyazaki_construct', 'miyazaki_ajax_load_more', array(
			'ajaxurl'   => esc_url( $ajax_url ),
		) );

	}
	add_action( 'wp_enqueue_scripts', 'miyazaki_enqueue_scripts' );
endif;

/* ---------------------------------------------------------------------------------------------
   INCLUDE THEME WIDGETS
   --------------------------------------------------------------------------------------------- */

require_once( get_template_directory() . '/widgets/recent-comments.php' );
require_once( get_template_directory() . '/widgets/recent-posts.php' );


/* ---------------------------------------------------------------------------------------------
	REGISTER AND DEREGISTER THEME WIDGETS
	--------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_register_widgets' ) ) {
	function miyazaki_register_widgets() {

		// Register widgets
		register_widget( 'Miyazaki_Recent_Comments' );
		register_widget( 'Miyazaki_Recent_Posts' );

		// Deregister core widgets replaced by theme widgets
		unregister_widget( 'WP_Widget_Recent_Comments' );
		unregister_widget( 'WP_Widget_Recent_Posts' );

	}
	add_action( 'widgets_init', 'miyazaki_register_widgets' );
}


/* ---------------------------------------------------------------------------------------------
   POST CLASSES
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_post_classes' ) ) :
	function miyazaki_post_classes( $classes ) {

		global $post;

		// Class indicating presence/lack of post thumbnail
		$classes[] = ( has_post_thumbnail() ? 'has-thumbnail' : 'missing-thumbnail' );

		return $classes;

	}
	add_action( 'post_class', 'miyazaki_post_classes' );
endif;


/* ---------------------------------------------------------------------------------------------
   BODY CLASSES
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_body_classes' ) ) :
	function miyazaki_body_classes( $classes ) {

		global $post;

		// Determine type of infinite scroll
		$pagination_type = get_theme_mod( 'miyazaki_pagination_type' ) ? get_theme_mod( 'miyazaki_pagination_type' ) : 'button';
		switch ( $pagination_type ) {
			case 'button' :
				$classes[] = 'pagination-type-button';
				break;
			case 'scroll' :
				$classes[] = 'pagination-type-scroll';
				break;
			case 'links' :
				$classes[] = 'pagination-type-links';
				break;
		}

		// Add class for front page title styling
		if ( is_home() && is_front_page() && ! get_theme_mod( 'miyazaki_disable_front_page_title' ) && ! get_theme_mod( 'custom_logo' ) ) {
			$classes[] = 'has-front-header';
		}

		// Add class for overlay logo
		if ( get_theme_mod( 'miyazaki_overlay_logo' ) ) {
			$classes[] = 'has-overlay-logo';
		}

		// Check for post thumbnail
		if ( is_singular() && has_post_thumbnail() ) {
			$classes[] = 'has-post-thumbnail';
		} elseif ( is_singular() ) {
			$classes[] = 'missing-post-thumbnail';
		}

		// Check whether we're in the customizer preview
		if ( is_customize_preview() ) {
			$classes[] = 'customizer-preview';
		}

		// Slim page template class names (class = name - file suffix)
		if ( is_page_template() ) {
			$classes[] = basename( get_page_template_slug(), '.php' );
		}

		return $classes;

	}
	add_action( 'body_class', 'miyazaki_body_classes' );
endif;


/* ---------------------------------------------------------------------------------------------
   ADD HTML CLASS IF THERE'S JAVASCRIPT
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_has_js' ) ) :
	function miyazaki_has_js() {

		?>
		<script>document.documentElement.className = document.documentElement.className.replace( 'no-js', 'js' );</script>
		<?php

	}
	add_action( 'wp_head', 'miyazaki_has_js' );
endif;


/* ---------------------------------------------------------------------------------------------
   CUSTOM LOGO OUTPUT
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_custom_logo' ) ) :
	function miyazaki_custom_logo( $logo_theme_mod = 'custom_logo' ) {

		// Get the attachment for the specified logo
		$logo_id = get_theme_mod( $logo_theme_mod );
		$logo = wp_get_attachment_image_src( $logo_id, 'full' );

		if ( $logo ) {

			// For clarity
			$logo_url = esc_url( $logo[0] );
			$logo_width = esc_attr( $logo[1] );
			$logo_height = esc_attr( $logo[2] );

			// If the retina logo setting is active, reduce the width/height by half
			if ( get_theme_mod( 'miyazaki_retina_logo' ) ) {
				$logo_width = floor( $logo_width / 2 );
				$logo_height = floor( $logo_height / 2 );
			}

			// CSS friendly class
			$logo_theme_mod_class = str_replace( '_', '-', $logo_theme_mod );

			?>

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php bloginfo( 'name' ); ?>" class="custom-logo-link <?php echo $logo_theme_mod_class; ?>">
				<img src="<?php echo esc_url( $logo_url ); ?>" width="<?php echo esc_attr( $logo_width ); ?>" height="<?php echo esc_attr( $logo_height ); ?>" />
			</a>

			<?php
		}

	}
endif;


/* ---------------------------------------------------------------------------------------------
   REGISTER WIDGET AREAS
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_widget_areas' ) ) :
	function miyazaki_widget_areas() {

		$shared_args = array(
			'before_title' 	=> '<h2 class="widget-title">',
			'after_title' 	=> '</h2>',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-content">',
			'after_widget' 	=> '</div></div>',
		);

		register_sidebar( array_merge( $shared_args, array(
			'name' 			=> __( 'Footer #1', 'miyazaki' ),
			'id' 			=> 'footer-one',
			'description' 	=> __( 'Widgets in this area will be shown in the first footer column.', 'miyazaki' ),
		) ) );

		register_sidebar( array_merge( $shared_args, array(
			'name' 			=> __( 'Footer #2', 'miyazaki' ),
			'id' 			=> 'footer-two',
			'description' 	=> __( 'Widgets in this area will be shown in the second footer column.', 'miyazaki' ),
		) ) );

		register_sidebar( array_merge( $shared_args, array(
			'name' 			=> __( 'Footer #3', 'miyazaki' ),
			'id' 			=> 'footer-three',
			'description' 	=> __( 'Widgets in this area will be shown in the third footer column.', 'miyazaki' ),
		) ) );

	}
	add_action( 'widgets_init', 'miyazaki_widget_areas' );
endif;


/* ---------------------------------------------------------------------------------------------
   REMOVE ARCHIVE PREFIXES
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_remove_archive_title_prefix' ) ) :
	function miyazaki_remove_archive_title_prefix( $title ) {

		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_author() ) {
			$title = '<span class="vcard">' . get_the_author() . '</span>';
		} elseif ( is_year() ) {
			$title = get_the_date( 'Y' );
		} elseif ( is_month() ) {
			$title = get_the_date( 'F Y' );
		} elseif ( is_day() ) {
			$title = get_the_date( get_option( 'date_format' ) );
		} elseif ( is_tax( 'post_format' ) ) {
			if ( is_tax( 'post_format', 'post-format-aside' ) ) {
				$title = _x( 'Asides', 'post format archive title', 'miyazaki' );
			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				$title = _x( 'Galleries', 'post format archive title', 'miyazaki' );
			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				$title = _x( 'Images', 'post format archive title', 'miyazaki' );
			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				$title = _x( 'Videos', 'post format archive title', 'miyazaki' );
			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				$title = _x( 'Quotes', 'post format archive title', 'miyazaki' );
			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				$title = _x( 'Links', 'post format archive title', 'miyazaki' );
			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				$title = _x( 'Statuses', 'post format archive title', 'miyazaki' );
			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				$title = _x( 'Audio', 'post format archive title', 'miyazaki' );
			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				$title = _x( 'Chats', 'post format archive title', 'miyazaki' );
			}
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );
		} elseif ( is_search() ) {
			$title = '&lsquo;' . get_search_query() . '&rsquo;';
		} else {
			$title = __( 'Archives', 'miyazaki' );
		} // End if().
		return $title;

	}
	add_filter( 'get_the_archive_title', 'miyazaki_remove_archive_title_prefix' );
endif;


/* ---------------------------------------------------------------------------------------------
   GET ARCHIVE PREFIX
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_get_archive_title_prefix' ) ) :
	function miyazaki_get_archive_title_prefix() {

		if ( is_category() ) {
			$title_prefix = __( 'Category', 'miyazaki' );
		} elseif ( is_tag() ) {
			$title_prefix = __( 'Tag', 'miyazaki' );
		} elseif ( is_author() ) {
			$title_prefix = __( 'Author', 'miyazaki' );
		} elseif ( is_year() ) {
			$title_prefix = __( 'Year', 'miyazaki' );
		} elseif ( is_month() ) {
			$title_prefix = __( 'Month', 'miyazaki' );
		} elseif ( is_day() ) {
			$title_prefix = __( 'Day', 'miyazaki' );
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( get_queried_object()->taxonomy );
			$title_prefix = $tax->labels->singular_name;
		} elseif ( is_search() ) {
			$title_prefix = __( 'Search', 'miyazaki' );
		} else {
			$title_prefix = __( 'Archives', 'miyazaki' );
		}
		return $title_prefix;

	}
endif;


/* ---------------------------------------------------------------------------------------------
   GET FALLBACK IMAGE
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_get_fallback_image_url' ) ) :
	function miyazaki_get_fallback_image_url() {

		$disable_fallback_image = get_theme_mod( 'miyazaki_disable_fallback_image' );

		if ( $disable_fallback_image ) {
			return '';
		}

		$fallback_image_id = get_theme_mod( 'miyazaki_fallback_image' );

		if ( $fallback_image_id ) {
			$fallback_image = wp_get_attachment_image_src( $fallback_image_id, 'full' );
		}

		$fallback_image_url = isset( $fallback_image ) ? esc_url( $fallback_image[0] ) : esc_url( get_template_directory_uri() ) . '/assets/images/default-fallback-image.png';

		return $fallback_image_url;

	}
endif;


/* ---------------------------------------------------------------------------------------------
   OUTPUT FALLBACK IMAGE
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_the_fallback_image' ) ) :
	function miyazaki_the_fallback_image() {

		$fallback_image_url = miyazaki_get_fallback_image_url();

		if ( ! $fallback_image_url ) return;

		echo '<img src="' . $fallback_image_url . '" class="fallback-featured-image" />';

	}
endif;


/* ---------------------------------------------------------------------------------------------
   GET THE IMAGE SIZE OF PREVIEWS
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_get_preview_image_size' ) ) :
	function miyazaki_get_preview_image_size() {

		// Check if low-resolution images are activated in the customizer
		$low_res_images = get_theme_mod( 'miyazaki_activate_low_resolution_images' );

		// If they are, we're using the low resolution image size
		if ( $low_res_images ) {
			return 'miyazaki_preview_image_low_resolution';

		// If not, we're using the high resolution image size
		} else {
			return 'miyazaki_preview_image_high_resolution';
		}

	}
endif;


/* ---------------------------------------------------------------------------------------------
   OUTPUT POST META
   If it's a single post, output the post meta values specified in the Customizer settings.

   @param	$post_id int		The ID of the post for which the post meta should be output
   @param	$location string	Which post meta location to output – single or preview
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_the_post_meta' ) ) :
	function miyazaki_the_post_meta( $post_id = null, $location = 'single-top' ) {

		echo miyazaki_get_post_meta( $post_id, $location );

	}
endif;


/* ---------------------------------------------------------------------------------------------
   GET THE POST META
   If the provided ID is for a single post, return the post meta values specified in the Customizer settings.

   @param	$post_id int		The ID of the post for which the post meta should be output
   @param	$location string	Which post meta location to output – single or preview
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_get_post_meta' ) ) :
	function miyazaki_get_post_meta( $post_id = null, $location = 'single-top' ) {

		// Require post ID
		if ( ! $post_id ) {
			return;
		}

		// Check that the post type should be able to output post meta
		$allowed_post_types = apply_filters( 'miyazaki_allowed_post_types_for_meta_output', array( 'post' ) );
		if ( ! in_array( get_post_type( $post_id ), $allowed_post_types ) ) {
			return;
		}

		$post_meta_wrapper_classes = '';
		$post_meta_classes = '';

		// Get the post meta settings for the location specified
		if ( 'preview' === $location ) {
			$post_meta = get_theme_mod( 'miyazaki_post_meta_preview' );

			$post_meta_wrapper_classes = ' post-meta-preview';

			// Empty = use default
			if ( ! $post_meta ) {
				$post_meta = array();
			}
		} elseif ( 'single-top' === $location ) {
			$post_meta = get_theme_mod( 'miyazaki_post_meta_single_top' );

			$post_meta_wrapper_classes = ' post-meta-single post-meta-single-top';

			// Empty = use default
			if ( ! $post_meta ) {
				$post_meta = array(
					'post-date',
					'categories',
				);
			}
		} elseif ( 'single-bottom' === $location ) {
			$post_meta = get_theme_mod( 'miyazaki_post_meta_single_bottom' );

			$post_meta_wrapper_classes = ' post-meta-single post-meta-single-bottom';

			// Empty = use default
			if ( ! $post_meta ) {
				$post_meta = array(
					'tags',
				);
			}
		}

		// If the post meta setting has the value 'empty', it's explicitly empty and the default post meta shouldn't be output
		if ( $post_meta && ! in_array( 'empty', $post_meta ) ) :

			ob_start();

			setup_postdata( $post_id );

			?>

			<div class="post-meta-wrapper<?php echo $post_meta_wrapper_classes; ?>">

				<ul class="post-meta<?php echo $post_meta_classes; ?>">

					<?php

					// Post date
					if ( in_array( 'post-date', $post_meta ) ) : ?>
						<li class="post-date">
							<a class="meta-wrapper" href="<?php the_permalink(); ?>">
								<span class="screen-reader-text"><?php _e( 'Post date', 'miyazaki' ); ?></span>
								<?php the_time( get_option( 'date_format' ) ); ?>
							</a>
						</li>
					<?php endif;

					// Author
					if ( in_array( 'author', $post_meta ) ) : ?>
						<li class="post-author meta-wrapper">
								<?php printf( _x( 'By %s', '%s = author name', 'miyazaki' ), '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '">' . get_the_author_meta( 'nickname' ) , '</a>' ); ?>
							</a>
						</li>
						<?php
					endif;

					// Categories
					if ( in_array( 'categories', $post_meta ) ) : ?>
						<li class="post-categories meta-wrapper">
							<?php _e( 'In', 'miyazaki' ); ?> <?php the_category( ', ' ); ?>
						</li>
						<?php
					endif;

					// Tags
					if ( in_array( 'tags', $post_meta ) && has_tag() ) : ?>
						<li class="post-tags meta-wrapper">
							<div class="post-tags-inner">
								<?php the_tags( '<span class="post-tags-title">' . __( 'Tags', 'miyazaki' ) . '</span>', '', '' ); ?>
							</div><!-- .post-tags-inner -->
						</li>
						<?php
					endif;

					// Comments link
					if ( in_array( 'comments', $post_meta ) && comments_open() ) : ?>
						<li class="post-comment-link">
							<a class="meta-wrapper" href="<?php echo esc_url( get_comments_link( $post_id ) ); ?>">
								<?php comments_popup_link(); ?>
							</a>
						</li>
						<?php
					endif;

					// Sticky
					if ( in_array( 'sticky', $post_meta ) && is_sticky() ) : ?>
						<li class="post-sticky meta-wrapper">
							<?php _e( 'Sticky post', 'miyazaki' ); ?>
						</li>
					<?php endif;

					// Edit link
					if ( in_array( 'edit-link', $post_meta ) && current_user_can( 'edit_post', get_the_ID() ) ) : ?>
						<li class="edit-post">
							
							<?php
							// Make sure we display something in the customizer, as edit_post_link() doesn't output anything there
							if ( is_customize_preview() ) { ?>
								<a href="#" class="meta-wrapper">
									<?php _e( 'Edit', 'miyazaki' ); ?>
								</a>
								<?php
							} else {
								echo '<a href="' . esc_url( get_edit_post_link() ) . '" class="meta-wrapper">' . __( 'Edit', 'miyazaki' ) . '</span>' . '</a>';
							}
							?>

						</li>
					<?php endif; ?>

				</ul><!-- .post-meta -->

			</div><!-- .post-meta-wrapper -->

			<?php

			// Get the contents of the buffer
			$post_meta_contents = ob_get_clean();

			wp_reset_postdata();

			// And return them
			return $post_meta_contents;

		endif;

		// If we've reached this point, there's nothing to return, so let's return nothing
		return;

	}
endif;


/* ---------------------------------------------------------------------------------------------
   FILTER COMMENT TEXT TO OUTPUT "BY POST AUTHOR" TEXT
------------------------------------------------------------------------------------------------ */

if ( ! function_exists( 'miyazaki_loading_indicator' ) ) :
	function miyazaki_filter_comment_text( $comment_text, $comment, $args ) {

		$comment_author_user_id = $comment->user_id;
		$post_author_user_id = get_post_field( 'post_author', $comment->comment_post_ID );

		if ( $comment_author_user_id === $post_author_user_id ) {
			$comment_text .= '<div class="by-post-author-wrapper">' . __( 'By post author', 'miyazaki' ) . '</div>';
		}

		return $comment_text;

	}
	add_filter( 'comment_text', 'miyazaki_filter_comment_text', 10, 3 );
endif;


/* ---------------------------------------------------------------------------------------------
   OUTPUT LOADING INDICATOR
------------------------------------------------------------------------------------------------ */

if ( ! function_exists( 'miyazaki_loading_indicator' ) ) :
	function miyazaki_loading_indicator() {

		echo '<div class="loader"></div>';

	}
endif;


/* ---------------------------------------------------------------------------------------------
	AJAX LOAD MORE
	Called in construct.js when the user has clicked the load more button
--------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_ajax_load_more' ) ) :
	function miyazaki_ajax_load_more() {

		$query_args = json_decode( wp_unslash( $_POST['json_data'] ), true );

		$ajax_query = new WP_Query( $query_args );

		// Determine which preview to use based on the post_type
		$post_type = $ajax_query->get( 'post_type' );

		// Default to the "post" post type for previews
		if ( is_array( $post_type ) ) {
			$post_type = 'post';
		}

		if ( $ajax_query->have_posts() ) :

			while ( $ajax_query->have_posts() ) : $ajax_query->the_post();

				get_template_part( 'preview', $post_type );

			endwhile;

		endif;

		die();

	}
	add_action( 'wp_ajax_nopriv_miyazaki_ajax_load_more', 'miyazaki_ajax_load_more' );
	add_action( 'wp_ajax_miyazaki_ajax_load_more', 'miyazaki_ajax_load_more' );
endif;


/* ---------------------------------------------------------------------------------------------
   SPECIFY BLOCK EDITOR SUPPORT
------------------------------------------------------------------------------------------------ */

if ( ! function_exists( 'miyazaki_add_block_editor_features' ) ) :
	function miyazaki_add_block_editor_features() {

		/* Block editor Feature Opt-Ins --------------------------------------- */

		add_theme_support( 'align-wide' );

		/* Block editor Palette --------------------------------------- */

		add_theme_support( 'editor-color-palette', array(
			array(
				'name' 	=> _x( 'Accent', 'Name of the accent color in the block editor palette', 'miyazaki' ),
				'slug' 	=> 'accent',
				'color' => '#F9423A',
			),
			array(
				'name' 	=> _x( 'Black', 'Name of the black color in the block editor palette', 'miyazaki' ),
				'slug' 	=> 'black',
				'color' => '#000000',
			),
			array(
				'name' 	=> _x( 'Dark gray', 'Name of the dark gray color in the block editor palette', 'miyazaki' ),
				'slug' 	=> 'dark-gray',
				'color' => '#333333',
			),
			array(
				'name' 	=> _x( 'Gray', 'Name of the gray color in the block editor palette', 'miyazaki' ),
				'slug' 	=> 'gray',
				'color' => '#767676',
			),
			array(
				'name' 	=> _x( 'Light gray', 'Name of the light gray color in the block editor palette', 'miyazaki' ),
				'slug' 	=> 'light-gray',
				'color' => '#DDDDDD',
			),
			array(
				'name' 	=> _x( 'White', 'Name of the white color in the block editor palette', 'miyazaki' ),
				'slug' 	=> 'white',
				'color' => '#FFF',
			),
		) );

		/* Block editor Font Sizes --------------------------------------- */

		add_theme_support( 'editor-font-sizes', array(
			array(
				'name' 		=> _x( 'Small', 'Name of the small font size in the block editor', 'miyazaki' ),
				'shortName' => _x( 'S', 'Short name of the small font size in the block editor.', 'miyazaki' ),
				'size' 		=> 16,
				'slug' 		=> 'small',
			),
			array(
				'name' 		=> _x( 'Regular', 'Name of the regular font size in the block editor', 'miyazaki' ),
				'shortName' => _x( 'M', 'Short name of the regular font size in the block editor.', 'miyazaki' ),
				'size' 		=> 20,
				'slug' 		=> 'regular',
			),
			array(
				'name' 		=> _x( 'Large', 'Name of the large font size in the block editor', 'miyazaki' ),
				'shortName' => _x( 'L', 'Short name of the large font size in the block editor.', 'miyazaki' ),
				'size' 		=> 24,
				'slug' 		=> 'large',
			),
			array(
				'name' 		=> _x( 'Larger', 'Name of the larger font size in the block editor', 'miyazaki' ),
				'shortName' => _x( 'XL', 'Short name of the larger font size in the block editor.', 'miyazaki' ),
				'size' 		=> 32,
				'slug' 		=> 'larger',
			),
		) );

	}
	add_action( 'after_setup_theme', 'miyazaki_add_block_editor_features' );
endif;


/* ---------------------------------------------------------------------------------------------
   BLOCK EDITOR STYLES
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_block_editor_styles' ) ) :
	function miyazaki_block_editor_styles() {

		wp_register_style( 'miyazaki-block-editor-styles-font', get_theme_file_uri( '/assets/css/fonts.css' ) );
		wp_enqueue_style( 'miyazaki-block-editor-styles', get_template_directory_uri() . '/assets/css/miyazaki-block-editor-style.css', array( 'miyazaki-block-editor-styles-font' ), wp_get_theme( 'miyazaki' )->get( 'Version' ) );

	}
	add_action( 'enqueue_block_editor_assets', 'miyazaki_block_editor_styles', 1 );
endif;


/* ---------------------------------------------------------------------------------------------
   BLOCK EDITOR OPTIONS
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'miyazaki_block_editor_options' ) ) :
	function miyazaki_block_editor_options() {

		wp_enqueue_script( 'miyazaki-block-editor-options', get_template_directory_uri() . '/assets/js/block-editor-options.js', array( 'wp-blocks', 'wp-dom' ) );

	}
	add_action( 'enqueue_block_editor_assets', 'miyazaki_block_editor_options' );
endif;
