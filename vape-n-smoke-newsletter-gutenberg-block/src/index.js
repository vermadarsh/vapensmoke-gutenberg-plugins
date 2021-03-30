const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, PanelRow, TextControl, TextareaControl, SelectControl } = wp.components;
const { Fragment } = wp.element;
import ServerSideRender from '@wordpress/server-side-render';

registerBlockType( 'vape-smoke/newsletter', {
	'title': __( 'Newsletter', 'vape-smoke' ),
	'icon': 'tag',
	'category': 'vs-cgb',
	'attributes': {
		section_heading: {
			type: 'string',
			default: __( 'Newsletter', 'vape-smoke' ),
		},
		section_tagline: {
			type: 'string',
		},
		section_icon_class: {
			type: 'string',
		},
		newsletter_form: {
			type: 'string',
		},
		contact_forms: {
			type: 'array',
		}
	},
	edit: ( props ) => {
		const {
			attributes: {
				section_heading,
				section_tagline,
				section_icon_class,
				newsletter_form
			},
			setAttributes
		} = props;

		var currentOrigin = window.location.origin
		var apiURL = currentOrigin + '/wp-json/wp/v2/wpcf7_contact_forms'
		fetch( apiURL )
			.then( ( response ) => {
				return response.json()
			} )
			.then( ( json ) => {
				var cf7Arr = []
				for ( var i in json ) {
					cf7Arr.push(
						{
							label: json[i].title.rendered,
							value: json[i].id
						}
					);
				}
				props.setAttributes( { contact_forms: cf7Arr } )
		} );

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody
						title={
							__( 'Newsletter Section Settings', 'vape-smoke' )
						}
						initialOpen={ true }
					>
						<PanelRow>
							<TextControl
								label={
									__( 'Heading', 'vape-smoke' )
								}
								value={ props.attributes.section_heading }
								onChange={
									( value ) => {
										setAttributes( { section_heading: value } )
									}
								}
							/>
						</PanelRow>
						<PanelRow>
							<TextareaControl
								label={
									__( 'Tagline', 'vape-smoke' )
								}
								value={ props.attributes.section_tagline }
								onChange={
									( value ) => {
										setAttributes( { section_tagline: value } )
									}
								}
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label={
									__( 'Icon', 'vape-smoke' )
								}
								value={ props.attributes.section_icon_class }
								onChange={
									( value ) => {
										setAttributes( { section_icon_class: value } )
									}
								}
							/>
						</PanelRow>
						<PanelRow>
							<SelectControl
								label={
									__( 'Contact Form', 'vape-smoke' )
								}
								value={ props.attributes.newsletter_form }
								onChange={
									( value ) => {
										setAttributes( { newsletter_form: value } )
									}
								}
								options={ props.attributes.contact_forms }
							/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>
				<ServerSideRender
					block="vape-smoke/newsletter"
					attributes={
						{
							section_heading,
							section_tagline,
							section_icon_class,
							newsletter_form
						}
					}
				/>
			</Fragment>
		);
	},
	save: () => {
		// Rendering in PHP.
		return null;
	}
} );
