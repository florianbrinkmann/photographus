/**
 * Scripts for the editor view of the block editor.
 *
 * @package photographus
 */

// Remove block style from pull quote.
// phpcs:disable
wp.domReady( () => {
	wp.blocks.unregisterBlockStyle( 'core/pullquote', 'default' );
	wp.blocks.unregisterBlockStyle( 'core/pullquote', 'solid-color' );
} );
// phpcs:enable
