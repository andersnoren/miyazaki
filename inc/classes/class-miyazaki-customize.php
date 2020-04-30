<?php

/* ---------------------------------------------------------------------------------------------
   CUSTOMIZER SETTINGS
   --------------------------------------------------------------------------------------------- */

if ( ! class_exists( 'Miyazaki_Customize' ) ) :
	class Miyazaki_Customize {

		public static function miyazaki_register( $wp_customize ) {

			/* Overlay Logo ----------------------------- */

			$wp_customize->add_setting( 'miyazaki_overlay_logo', array(
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback' => 'absint',
			) );

			$wp_customize->add_control( 
				new WP_Customize_Media_Control( 
					$wp_customize, 
					'miyazaki_overlay_logo', 
					array(
						'label'      	=> __( 'Overlay Logo', 'miyazaki' ),
						'section' 		=> 'title_tagline',
						'settings'    	=> 'miyazaki_overlay_logo',
						'mime_type' 	=> 'image',
						'priority'		=> 10,
						'description' 	=> __( 'Select a logo to display in the menu overlay on mobile. It should have the same dimensions as the regular logo.', 'miyazaki' ),
					) 
				) 
			);

			/* 2X Header Logo ----------------------------- */

			$wp_customize->add_setting( 'miyazaki_retina_logo', array(
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback' => 'miyazaki_sanitize_checkbox',
				'transport'			=> 'postMessage',
			) );

			$wp_customize->add_control( 'miyazaki_retina_logo', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'title_tagline',
				'priority'		=> 10,
				'label' 		=> __( 'Retina logo', 'miyazaki' ),
				'description' 	=> __( 'Scales the logo to half its uploaded size, making it sharp on high-res screens.', 'miyazaki' ),
			) );

			/* Disable Big Front Page Title ----------------------------- */

			$wp_customize->add_setting( 'miyazaki_disable_front_page_title', array(
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback' => 'miyazaki_sanitize_checkbox',
			) );

			$wp_customize->add_control( 'miyazaki_disable_front_page_title', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'title_tagline',
				'priority'		=> 20,
				'label' 		=> __( 'Disable Big Front Page Title', 'miyazaki' ),
				'description' 	=> __( 'Check to remove the big site title on the front page. Only applies if you haven\'t uploaded a logo.', 'miyazaki' ),
			) );

			/* ------------------------------------------------------------------------
			 * Fallback Image Options
			 * ------------------------------------------------------------------------ */

			$wp_customize->add_section( 'miyazaki_image_options', array(
				'title' 		=> __( 'Images', 'miyazaki' ),
				'priority' 		=> 40,
				'capability' 	=> 'edit_theme_options',
				'description' 	=> __( 'Settings for images in Miyazaki.', 'miyazaki' ),
			) );

			// Activate low-resolution images setting
			$wp_customize->add_setting( 'miyazaki_activate_low_resolution_images', array(
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback' => 'miyazaki_sanitize_checkbox'
			) );

			$wp_customize->add_control( 'miyazaki_activate_low_resolution_images', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'miyazaki_image_options',
				'priority'		=> 5,
				'label' 		=> __( 'Use Low-Resolution Images', 'miyazaki' ),
				'description'	=> __( 'Checking this will decrease load times, but also make images look less sharp on high-resolution screens.', 'miyazaki' ),
			) );

			// Fallback image setting
			$wp_customize->add_setting( 'miyazaki_fallback_image', array(
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback' => 'absint'
			) );

			$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'miyazaki_fallback_image', array(
				'label'			=> __( 'Fallback Image', 'miyazaki' ),
				'description'	=> __( 'The selected image will be used when a post is missing a featured image. A default fallback image included in the theme will be used if no image is set.', 'miyazaki' ),
				'priority'		=> 10,
				'mime_type'		=> 'image',
				'section' 		=> 'miyazaki_image_options',
			) ) );

			// Disable fallback image setting
			$wp_customize->add_setting( 'miyazaki_disable_fallback_image', array(
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback' => 'miyazaki_sanitize_checkbox'
			) );

			$wp_customize->add_control( 'miyazaki_disable_fallback_image', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'miyazaki_image_options',
				'priority'		=> 15,
				'label' 		=> __( 'Disable Fallback Image', 'miyazaki' )
			) );

			/* ------------------------------------------------------------------------
			 * Post Meta Options
			 * ------------------------------------------------------------------------ */

			$wp_customize->add_section( 'miyazaki_post_meta_options', array(
				'title' 		=> __( 'Post Meta', 'miyazaki' ),
				'priority' 		=> 41,
				'capability' 	=> 'edit_theme_options',
				'description' 	=> __( 'Choose which meta information to display in Miyazaki.', 'miyazaki' ),
			) );

			/* Post Meta Setting ----------------------------- */

			$post_meta_choices = apply_filters( 'miyazaki_post_meta_choices_in_the_customizer', array(
				'author'		=> __( 'Author', 'miyazaki' ),
				'categories'	=> __( 'Categories', 'miyazaki' ),
				'comments'		=> __( 'Comments', 'miyazaki' ),
				'edit-link'		=> __( 'Edit link (for logged in users)', 'miyazaki' ),
				'post-date'		=> __( 'Post date', 'miyazaki' ),
				'sticky'		=> __( 'Sticky status', 'miyazaki' ),
				'tags'			=> __( 'Tags', 'miyazaki' ),
			) );

			// Post Meta Single Top Setting
			$wp_customize->add_setting( 'miyazaki_post_meta_single_top', array(
				'capability' 		=> 'edit_theme_options',
				'default'           => array( 'post-date', 'categories' ),
				'sanitize_callback' => 'miyazaki_sanitize_multiple_checkboxes',
			) );

			$wp_customize->add_control( new Miyazaki_Customize_Control_Checkbox_Multiple( $wp_customize, 'miyazaki_post_meta_single_top', array(
				'section' 		=> 'miyazaki_post_meta_options',
				'label'   		=> __( 'Post Meta on Single Posts (top):', 'miyazaki' ),
				'description'	=> __( 'Select the post meta to display above the content on single posts.', 'miyazaki' ),
				'choices' 		=> $post_meta_choices,
			) ) );

			// Post Meta Single Bottom Setting
			$wp_customize->add_setting( 'miyazaki_post_meta_single_bottom', array(
				'capability' 		=> 'edit_theme_options',
				'default'           => array( 'tags' ),
				'sanitize_callback' => 'miyazaki_sanitize_multiple_checkboxes',
			) );

			$wp_customize->add_control( new Miyazaki_Customize_Control_Checkbox_Multiple( $wp_customize, 'miyazaki_post_meta_single_bottom', array(
				'section' 		=> 'miyazaki_post_meta_options',
				'label'   		=> __( 'Post Meta on Single Posts (bottom):', 'miyazaki' ),
				'description'	=> __( 'Select the post meta to display below the content on single posts.', 'miyazaki' ),
				'choices' 		=> $post_meta_choices,
			) ) );

			// Post Meta Preview Setting
			$wp_customize->add_setting( 'miyazaki_post_meta_preview', array(
				'capability' 		=> 'edit_theme_options',
				'default'           => array( '' ),
				'sanitize_callback' => 'miyazaki_sanitize_multiple_checkboxes',
			) );

			$wp_customize->add_control( new Miyazaki_Customize_Control_Checkbox_Multiple( $wp_customize, 'miyazaki_post_meta_preview', array(
				'section' 		=> 'miyazaki_post_meta_options',
				'label'   		=> __( 'Post Meta In Previews:', 'miyazaki' ),
				'description'	=> __( 'Select the post meta to display in post previews on archive pages.', 'miyazaki' ),
				'choices' 		=> $post_meta_choices,
			) ) );

			/* ------------------------------------------------------------------------
			 * Pagination Options
			 * ------------------------------------------------------------------------ */

			$wp_customize->add_section( 'miyazaki_pagination_options', array(
				'title' 		=> __( 'Pagination', 'miyazaki' ),
				'priority' 		=> 45,
				'capability' 	=> 'edit_theme_options',
				'description' 	=> __( 'Choose which type of pagination to display.', 'miyazaki' ),
			) );

			/* Pagination Type Setting ----------------------------- */

			$wp_customize->add_setting( 'miyazaki_pagination_type', array(
				'capability' 		=> 'edit_theme_options',
				'default'           => 'button',
				'sanitize_callback' => 'miyazaki_sanitize_radio',
			) );

			$wp_customize->add_control( 'miyazaki_pagination_type', array(
				'type'			=> 'radio',
				'section' 		=> 'miyazaki_pagination_options',
				'label'   		=> __( 'Pagination Type:', 'miyazaki' ),
				'choices' 		=> array(
					'button'		=> __( 'Load more on button click', 'miyazaki' ),
					'scroll'		=> __( 'Load more on scroll', 'miyazaki' ),
					'links'			=> __( 'Previous and next page links', 'miyazaki' ),
				),
			) );

			/* ------------------------------------------------------------------------
			 * Search Options
			 * ------------------------------------------------------------------------ */

			$wp_customize->add_section( 'miyazaki_search_options', array(
				'title' 		=> __( 'Search', 'miyazaki' ),
				'priority' 		=> 60,
				'capability' 	=> 'edit_theme_options',
				'description' 	=> '',
			) );

			/* Disable Related Posts Setting ----------------------------- */

			$wp_customize->add_setting( 'miyazaki_disable_search', array(
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback' => 'miyazaki_sanitize_checkbox',
			) );

			$wp_customize->add_control( 'miyazaki_disable_search', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'miyazaki_search_options',
				'priority'		=> 10,
				'label' 		=> __( 'Disable Search', 'miyazaki' ),
				'description' 	=> __( 'Check to hide the search toggle and search form in the header and mobile menu.', 'miyazaki' ),
			) );

			/* ------------------------------------------------------------------------
			 * Related Posts Options
			 * ------------------------------------------------------------------------ */

			$wp_customize->add_section( 'miyazaki_related_posts_options', array(
				'title' 		=> __( 'Related Posts', 'miyazaki' ),
				'priority' 		=> 60,
				'capability' 	=> 'edit_theme_options',
				'description' 	=> '',
			) );

			/* Disable Related Posts Setting ----------------------------- */

			$wp_customize->add_setting( 'miyazaki_disable_related_posts', array(
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback' => 'miyazaki_sanitize_checkbox',
			) );

			$wp_customize->add_control( 'miyazaki_disable_related_posts', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'miyazaki_related_posts_options',
				'priority'		=> 10,
				'label' 		=> __( 'Disable Related Posts', 'miyazaki' ),
				'description' 	=> __( 'Check to hide the related posts section on single posts.', 'miyazaki' ),
			) );



			/* Sanitation functions ----------------------------- */

			// Sanitize boolean for checkbox
			function miyazaki_sanitize_checkbox( $checked ) {
				return ( ( isset( $checked ) && true == $checked ) ? true : false );
			}

			// Sanitize booleans for multiple checkboxes
			function miyazaki_sanitize_multiple_checkboxes( $values ) {
				$multi_values = ! is_array( $values ) ? explode( ',', $values ) : $values;
				return ! empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
			}

			function miyazaki_sanitize_radio( $input, $setting ) {
				$input = sanitize_key( $input );
				$choices = $setting->manager->get_control( $setting->id )->choices;
				return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
			}

		}

		// Initiate the customize controls js
		public static function miyazaki_customize_controls() {
			wp_enqueue_script( 'miyazaki-customize-controls', get_template_directory_uri() . '/assets/js/customize-controls.js', array( 'jquery', 'customize-controls' ), '', true );
		}

	}

	// Setup the Theme Customizer settings and controls
	add_action( 'customize_register', array( 'Miyazaki_Customize', 'miyazaki_register' ) );

	// Enqueue customize controls javascript in Theme Customizer admin screen
	add_action( 'customize_controls_init', array( 'Miyazaki_Customize', 'miyazaki_customize_controls' ) );

endif;
