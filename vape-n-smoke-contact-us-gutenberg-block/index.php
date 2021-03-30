<?php
/**
 * The plugin bootstrap file.
 *
 * @link        https://github.com/vermadarsh/
 * @since       1.0.0
 * @package     Vape_And_Smoke_Contact_Us_Gutenberg_Block
 *
 * Plugin Name: Vaps & Smoke Contact Us Gutenberg Block
 * Author:      Adarsh Verma
 * Author URI:  https://github.com/vermadarsh/
 * Version:     1.0.0
 * Description: This plugin creates custom gutenberg block for showing contact us page.
 * Text Domain: vape-smoke
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'VSCU_PLUGIN_URL' ) ) {
	define( 'VSCU_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

add_filter( 'block_categories', 'vscu_block_categories_callback' );
add_action( 'init', 'vscu_init_callback' );
add_filter( 'register_post_type_args', 'vscu_register_post_type_args_callback', 10, 2 );

/**
 * Adding a custom category for Gutenberg blocks.
 *
 * @param array $categories Block category pre-defined array.
 *
 * @return array
 */
function vscu_block_categories_callback( $categories = array() ) {

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
function vscu_init_callback() {
	/* Register Javascript File build/index.js */
	wp_register_script(
		'vs-contact-us-js',
		plugins_url( 'build/index.js', __FILE__ ),
		array( 'wp-blocks', 'wp-editor', 'wp-element', 'wp-i18n', 'wp-data', 'wp-components' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' ),
		true
	);

	/* Register Editor Style: src/editor.css */
	wp_register_style(
		'vs-contact-us-editor-css',
		plugins_url( 'src/editor.css', __FILE__ ),
		array( 'wp-edit-blocks' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'src/editor.css' )
	);

	register_block_type(
		'vape-smoke/contact-us',
		array(
			'editor_script'   => 'vs-contact-us-js',
			'editor_style'    => 'vs-contact-us-editor-css',
			'style'           => 'vs-contact-us-frontend-css',
			'render_callback' => 'vscu_block_render',
			'attributes'      => array(
				'contactForm' => array(
					'type' => 'string',
				),
			),
		)
	);
}

/**
 * Render the block at server end.
 *
 * @param array $attributes Holds the block data attributes.
 */
function vscu_block_render( $attributes ) {
	$contact_form = ( ! empty( $attributes['contactForm'] ) ) ? $attributes['contactForm'] : '';
	ob_start();
	?>
	<section class="contact_us_main">
		<div class="container">
			<div class="contact-us-content">
				<div class="row">
					<div class="col-md-7 ml-auto mr-auto">
						<div class="col-md-12">
							<?php echo do_shortcode( '[contact-form-7 id="' . $contact_form . '"]' ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Modify CPT arguments for Rest API.
 *
 * @param array  $args     CPT arguments that define it.
 * @param string $posttype CPT title.
 *
 * @return array
 */
function vscu_register_post_type_args_callback( $args, $posttype ) {

	if ( 'wpcf7_contact_form' === $posttype ) {
		$args['show_in_rest']          = true;
		$args['rest_base']             = 'wpcf7_contact_forms';
		$args['rest_controller_class'] = 'WP_REST_Posts_Controller';
	}

	return $args;
}
