/**
 * Vape & Smoke custom gutenberg block.
 */
const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;
const { InspectorControls, MediaUpload } = wp.blockEditor;
const {
	PanelBody,
	PanelRow,
	TextControl,
	TextareaControl,
	Button,
} = wp.components;
const { Fragment } = wp.element;
import ServerSideRender from '@wordpress/server-side-render';

registerBlockType( 'vape-smoke/about-us', {
	title: __( 'About Us', 'vape-smoke' ),
	description: __(
		'Custom gutenberg block built for Vape & Smoke, showing the about us section.',
		'vape-smoke'
	),
	icon: 'money',
	example: {},
	category: 'vs-cgb',
	attributes: {
		sectionHeading: {
			type: 'string',
			default: __( 'About Us', 'vape-smoke' ),
		},
		aboutUsDescription: {
			type: 'string',
			default: __( 'About us description goes here…', 'vape-smoke' ),
		},
		readMoreButtonText: {
			type: 'string',
			default: __( 'Read More', 'vape-smoke' ),
		},
		readMoreButtonLink: {
			type: 'string',
		},
		aboutUsImage: {
			type: 'string',
		},
	},
	edit: ( props ) => {
		const {
			attributes: {
				sectionHeading,
				aboutUsImage,
				aboutUsDescription,
				readMoreButtonText,
				readMoreButtonLink,
			},
			setAttributes,
		} = props;

		const selectImage = ( value ) => {
			setAttributes( {
				aboutUsImage: value.sizes.full.url,
			} );
		};

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody
						title={ __( 'Block Settings', 'vape-smoke' ) }
						initialOpen={ true }
					>
						<PanelRow>
							<TextControl
								label={ __( 'Section Heading', 'vape-smoke' ) }
								value={ props.attributes.sectionHeading }
								onChange={ ( value ) => {
									setAttributes( { sectionHeading: value } );
								} }
							/>
						</PanelRow>
						<PanelRow>
							<MediaUpload
								onSelect={ selectImage }
								render={ ( { open } ) => {
									return (
										<div className="vsau-image-block">
											<Button
												className="button"
												onClick={ open }
											>
												{ __(
													'Select Image',
													'vape-smoke'
												) }
											</Button>
											<img
												src={
													props.attributes
														.aboutUsImage
												}
												alt="about us img"
											/>
										</div>
									);
								} }
							/>
						</PanelRow>
						<PanelRow>
							<TextareaControl
								label={ __( 'Description', 'vape-smoke' ) }
								value={ props.attributes.aboutUsDescription }
								onChange={ ( value ) => {
									setAttributes( {
										aboutUsDescription: value,
									} );
								} }
							/>
						</PanelRow>
					</PanelBody>

					<PanelBody
						title={ __( 'Read More Button', 'vape-smoke' ) }
						initialOpen={ false }
					>
						<PanelRow>
							<TextControl
								label={ __( 'Button Text', 'vape-smoke' ) }
								value={ props.attributes.readMoreButtonText }
								onChange={ ( value ) => {
									setAttributes( {
										readMoreButtonText: value,
									} );
								} }
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label={ __( 'Button Link', 'vape-smoke' ) }
								value={ props.attributes.readMoreButtonLink }
								onChange={ ( value ) => {
									setAttributes( {
										readMoreButtonLink: value,
									} );
								} }
							/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>
				<ServerSideRender
					block="vape-smoke/about-us"
					attributes={ {
						sectionHeading,
						aboutUsImage,
						aboutUsDescription,
						readMoreButtonText,
						readMoreButtonLink,
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
