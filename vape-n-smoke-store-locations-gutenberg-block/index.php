<?php
/**
 * The plugin bootstrap file.
 *
 * @link        https://github.com/vermadarsh/
 * @since       1.0.0
 * @package     Vape_And_Smoke_Store_Locations_Gutenberg_Block
 *
 * Plugin Name: Vaps & Smoke Store Locations Gutenberg Block
 * Author:      Adarsh Verma
 * Author URI:  https://github.com/vermadarsh/
 * Version:     1.0.0
 * Description: This plugin creates custom gutenberg block for showing store locations repeater section.
 * Text Domain: vape-smoke
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'VSSL_PLUGIN_URL' ) ) {
	define( 'VSSL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

add_filter( 'block_categories', 'vssl_block_categories_callback' );
add_action( 'init', 'vssl_init_callback' );

/**
 * Adding a custom category for Gutenberg blocks.
 *
 * @param array $categories Block category pre-defined array.
 *
 * @return array
 */
function vssl_block_categories_callback( $categories = array() ) {

	$category_slug  = 'vs-cgb';
	$category_slugs = wp_list_pluck( $categories, 'slug' );

	return in_array( $category_slug, $category_slugs, true ) ? $categories : array_merge(
		$categories,
		array(
			array(
				'slug'  => $category_slug,
				'title' => apply_filters( 'vscgb_gutenberg_block_category', __( 'Vape & Smoke', 'vape-smoke' ) ),
				'icon'  => 'smiley',
			),
		)
	);

}

/**
 * This function helps in registering block and it's corresponding scripts.
 */
function vssl_init_callback() {

	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	/* Register Javascript File build/index.js */
	wp_register_script(
		'vs-store-locations-js',
		plugins_url( 'build/index.js', __FILE__ ),
		array( 'wp-blocks', 'wp-editor', 'wp-element', 'wp-i18n', 'wp-data', 'wp-components' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' ),
		true
	);

	/* Register Editor Style: src/editor.css */
	wp_register_style(
		'vs-store-locations-editor-css',
		plugins_url( 'src/editor.css', __FILE__ ),
		array( 'wp-edit-blocks' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'src/editor.css' )
	);

	register_block_type(
		'vape-smoke/store-locations',
		array(
			'editor_script'   => 'vs-store-locations-js',
			'editor_style'    => 'vs-store-locations-editor-css',
			'style'           => 'vs-store-locations-frontend-css',
			'attributes'      => array(
				'locations' => array(
					'type' => 'array',
				),
			),
			'render_callback' => 'vssl_block_render_callback',
		)
	);
}

/**
 * Render store location block.
 *
 * @param array $attributes Holds the attributes data array.
 * @return array
 */
function vssl_block_render_callback( $attributes ) {
	ob_start();
	?>
	<section class="store_location">
        <div class="container">
			<div class="store-location-content">
				<div class="row">
					<?php if ( ! empty( $attributes['locations'] ) && is_array( $attributes['locations'] ) ) { ?>
						<?php foreach ( $attributes['locations'] as $location ) { ?>
							<div class="col-md-6" style="padding: 15px;">
								<div class="store-location-content-box d-flex flex-md-row flex-column align-items-stretch justify-content-center">
									<div class="map-area">
										<iframe src="<?php echo esc_url( ( ! empty( $location['iframe_src'] ) ) ? $location['iframe_src'] : '' ); ?>"
												frameborder="0" style="border:0" allowfullscreen></iframe>
									</div>
									<div class="map-area-detail">
										<h4><?php echo esc_html( ( ! empty( $location['title'] ) ) ? $location['title'] : '' ); ?></h4>
										<p><?php echo esc_html( ( ! empty( $location['description'] ) ) ? $location['description'] : '' ); ?></p>
										<div class="address-area d-flex">
											<i class="fas fa-map-marker-alt"></i>
											<p class="mb-0"><?php echo esc_html( ( ! empty( $location['address'] ) ) ? $location['address'] : '' ); ?></p>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
