<?php $unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo $unique_id; ?>">
		<span class="screen-reader-text"><?php echo _x( 'Search for:', 'Label', 'miyazaki' ); ?></span>
		<img aria-hidden src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/icons/spyglass-white.svg" />
	</label>
	<input type="search" id="<?php echo $unique_id; ?>" class="search-field" placeholder="<?php echo esc_attr_x( 'Search for&hellip;', 'Placeholder', 'miyazaki' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<button type="submit" class="search-submit"><?php echo _x( 'Search', 'Submit button', 'miyazaki' ); ?></button>
</form>
