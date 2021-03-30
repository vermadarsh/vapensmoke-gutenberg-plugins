/**
 * Contact Us gutenberg block.
 */
const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, PanelRow, SelectControl } = wp.components;
const { Fragment } = wp.element;
import ServerSideRender from '@wordpress/server-side-render';

registerBlockType( 'vape-smoke/contact-us', {
	title: __( 'Contact Us', 'vape-smoke' ),
	description: __(
		'Custom gutenberg block built for Vape & Smoke, showing the contact form on contact us page.',
		'vape-smoke'
	),
	icon: 'email',
	category: 'vs-cgb',
	example: {},
	attributes: {
		contactForm: {
			type: 'string',
		},
		contactForms: {
			type: 'array',
		},
	},
	edit: ( props ) => {
		const {
			attributes: { contactForm },
			setAttributes,
		} = props;

		fetch( window.location.origin + '/wp-json/wp/v2/wpcf7_contact_forms' )
			.then( ( response ) => {
				return response.json();
			} )
			.then( ( json ) => {
				const cf7Arr = [];
				for ( const i in json ) {
					cf7Arr.push( {
						label: json[ i ].title.rendered,
						value: json[ i ].id,
					} );
				}
				props.setAttributes( { contactForms: cf7Arr } );
			} );

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody
						title={ __( 'Block Settings', 'vape-smoke' ) }
						initialOpen={ true }
					>
						<PanelRow>
							<SelectControl
								label={ __( 'Contact Form', 'vape-smoke' ) }
								value={ props.attributes.contactForm }
								onChange={ ( value ) => {
									setAttributes( { contactForm: value } );
								} }
								options={ props.attributes.contactForms }
							/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>
				<ServerSideRender
					block="vape-smoke/contact-us"
					attributes={ {
						contactForm,
					} }
				/>
			</Fragment>
		);
	},
	save: () => {
		// Rendering in PHP.
		return null;
	},
} );
