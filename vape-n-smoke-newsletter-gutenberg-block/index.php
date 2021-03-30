<?php
/**
 * The plugin bootstrap file.
 *
 * @link        https://github.com/vermadarsh/
 * @since       1.0.0
 * @package     Vape_And_Smoke_Newsletter_Gutenberg_Block
 *
 * Plugin Name: Vaps & Smoke Newsletter Gutenberg Block
 * Author:      Adarsh Verma
 * Author URI:  https://github.com/vermadarsh/
 * Version:     1.0.0
 * Description: This plugin creates custom gutenberg block for showing newsletter section.
 * Text Domain: vape-smoke
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'VSNL_PLUGIN_URL' ) ) {
	define( 'VSNL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

add_filter( 'block_categories', 'vsnl_block_categories_callback' );
add_action( 'init', 'vsnl_init_callback' );

/**
 * Adding a custom category for Gutenberg blocks.
 *
 * @param array $categories Block category pre-defined array.
 *
 * @return array
 */
function vsnl_block_categories_callback( $categories = array() ) {

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
function vsnl_init_callback() {
	/* Register Javascript File build/index.js */
	wp_register_script(
		'vs-newsletter-js',
		plugins_url( 'build/index.js', __FILE__ ),
		array( 'wp-blocks', 'wp-editor', 'wp-element', 'wp-i18n', 'wp-data', 'wp-components' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' ),
		true
	);

	/* Register Editor Style: src/editor.css */
	wp_register_style(
		'vs-newsletter-editor-css',
		plugins_url( 'src/editor.css', __FILE__ ),
		array( 'wp-edit-blocks' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'src/editor.css' )
	);

	/* Register Frontend Style: src/style.css */
	wp_register_style(
		'vs-newsletter-frontend-css',
		plugins_url( 'src/style.css', __FILE__ ),
		array( 'wp-edit-blocks' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'src/style.css' )
	);

	register_block_type(
		'vape-smoke/newsletter',
		array(
			'editor_script'   => 'vs-newsletter-js',
			'editor_style'    => 'vs-newsletter-editor-css',
			'style'           => 'vs-newsletter-frontend-css',
			'render_callback' => 'vsnl_block_render',
			'attributes'      => array(
				'section_heading' => array(
					'type'    => 'string'
				),
				'section_tagline' => array(
					'type'    => 'string'
				),
				'section_icon_class' => array(
					'type'    => 'string'
				),
				'newsletter_form' => array(
					'type'    => 'string'
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
function vsnl_block_render( $attributes ) {
	// echo '<pre>'; print_r( $attributes ); echo '</pre>'; die;
	$section_heading  = ( ! empty( $attributes['section_heading'] ) ) ? $attributes['section_heading'] : __( 'Newsletter', 'vape-smoke' );
	$section_tagline  = ( ! empty( $attributes['section_tagline'] ) ) ? $attributes['section_tagline'] : '';
	$section_icon     = ( ! empty( $attributes['section_icon_class'] ) ) ? $attributes['section_icon_class'] : '';
	$newsletter_form  = ( ! empty( $attributes['newsletter_form'] ) ) ? $attributes['newsletter_form'] : '';
	ob_start();
	?>
	<section class="newsletter">
		<div class="container">
			<div class="newsletter-details">
				<div class="row">
					<div class="col-lg-6">
						<div class="newsletter-text d-flex align-items-center">
							<div class="icon-part mr-3">
								<i class="far <?php echo esc_attr( $section_icon ); ?>"></i>
							</div>
							<div class="text-part d-flex flex-column justify-content-end align-items-start">
								<h1><?php echo esc_html( $section_heading ); ?></h1>
								<p><?php echo esc_html( $section_tagline ); ?></p>
							</div>

						</div>
					</div>
					<div class="col-lg-6">
						<?php echo do_shortcode( '[contact-form-7 id="' . $newsletter_form . '"]' ); ?>
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
function vsnl_register_post_type_args_callback( $args, $posttype ) {

	if ( 'wpcf7_contact_form' === $posttype ) {
		$args['show_in_rest']          = true;
		$args['rest_base']             = 'wpcf7_contact_forms';
		$args['rest_controller_class'] = 'WP_REST_Posts_Controller';
	}

	return $args;
}

add_filter( 'register_post_type_args', 'vsnl_register_post_type_args_callback', 10, 2 );
