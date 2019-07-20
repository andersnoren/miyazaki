wp.domReady( () => {

	// Disable the squared button style, as square is default in Miyazaki
	wp.blocks.unregisterBlockStyle( 'core/button', 'squared' );

} );