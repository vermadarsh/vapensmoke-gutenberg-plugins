<?php
/**
 * The plugin bootstrap file.
 *
 * @link        https://github.com/vermadarsh/
 * @since       1.0.0
 * @package     Vape_And_Smoke_About_Us_Gutenberg_Block
 *
 * Plugin Name: Vaps & Smoke About Us Gutenberg Block
 * Author:      Adarsh Verma
 * Author URI:  https://github.com/vermadarsh/
 * Version:     1.0.0
 * Description: This plugin creates custom gutenberg block for showing about us section.
 * Text Domain: vape-smoke
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'VSAU_PLUGIN_URL' ) ) {
	define( 'VSAU_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

add_filter( 'block_categories', 'vsau_block_categories_callback' );
add_action( 'init', 'vsau_init_callback' );

/**
 * Adding a custom category for Gutenberg blocks.
 *
 * @param array $categories Block category pre-defined array.
 *
 * @return array
 */
function vsau_block_categories_callback( $categories = array() ) {

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
function vsau_init_callback() {

	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	/* Register Javascript File build/index.js */
	wp_register_script(
		'vs-about-us-js',
		plugins_url( 'build/index.js', __FILE__ ),
		array( 'wp-blocks', 'wp-editor', 'wp-element', 'wp-i18n', 'wp-data', 'wp-components' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' ),
		true
	);

	/* Register Editor Style: src/editor.css */
	wp_register_style(
		'vs-about-us-editor-css',
		plugins_url( 'src/editor.css', __FILE__ ),
		array( 'wp-edit-blocks' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'src/editor.css' )
	);

	register_block_type(
		'vape-smoke/about-us',
		array(
			'editor_script'   => 'vs-about-us-js',
			'editor_style'    => 'vs-about-us-editor-css',
			'style'           => 'vs-about-us-frontend-css',
			'render_callback' => 'vsau_block_render',
			'attributes'      => array(
				'sectionHeading'      => array(
					'type' => 'string'
				),
				'aboutUsImage'         => array(
					'type' => 'string'
				),
				'aboutUsDescription' => array(
					'type' => 'string'
				),
				'readMoreButtonText' => array(
					'type' => 'string'
				),
				'readMoreButtonLink' => array(
					'type' => 'string'
				),
			)
		)
	);
}

/**
 * Render the block at server end.
 *
 * @param array $attributes Holds the block data attributes.
 */
function vsau_block_render( $attributes ) {
	$aboutUsImage  = ( ! empty( $attributes['aboutUsImage'] ) ) ? $attributes['aboutUsImage'] : '';
	ob_start();
	?>
	<section class="about-us">
		<div class="mission-area">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center">
						<h2 class="section-heading"><?php echo wp_kses_post( ( ! empty( $attributes['sectionHeading'] ) ) ? $attributes['sectionHeading'] : __( 'About', 'vape-smoke' ) ); ?></h2>
					</div>
				</div>
				<div class="mission-area-content">
					<div class="row">
						<div class="col-md-6 pr-md-0">
							<img src="<?php echo esc_url( $aboutUsImage ); ?>" alt="abt us image" class="w-100">
						</div>
						<div class="mission-area-text col-md-6">
							<p><?php echo esc_html( ( ! empty( $attributes['aboutUsDescription'] ) ) ? $attributes['aboutUsDescription'] : '' ); ?></p>
							<a href="<?php echo esc_url( ( ! empty( $attributes['readMoreButtonLink'] ) ) ? $attributes['readMoreButtonLink'] : '' ); ?>">
								<button class="btn"><?php echo esc_html( ( ! empty( $attributes['readMoreButtonText'] ) ) ? $attributes['readMoreButtonText'] : __( 'Read More', 'vape-smoke' ) ); ?></button>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}