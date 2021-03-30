/**
 * Store Location Gutenberg Block.
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const {
	Button,
	PanelBody,
	PanelRow,
	TextControl,
	TextareaControl,
} = wp.components;
const { InspectorControls } = wp.editor;
const { Fragment } = wp.element;
import ServerSideRender from '@wordpress/server-side-render';

registerBlockType( 'vape-smoke/store-locations', {
	title: __( 'Store Locations', 'vape-smoke' ),
	description: __(
		'Custom gutenberg block built for Vape & Smoke, showing the store locations.',
		'vape-smoke'
	),
	icon: 'admin-multisite',
	example: {},
	category: 'vs-cgb',
	attributes: {
		locations: {
			type: 'array',
			default: [],
		},
	},
	keywords: [
		__( 'Gutenberg Repeater Field', 'vape-smoke' ),
		__( 'Repeatable', 'vape-smoke' ),
	],
	edit: ( props ) => {
		const {
			attributes: { locations },
			setAttributes,
		} = props;

		const addNewLocation = () => {
			const locations = [ ...props.attributes.locations ];
			locations.push( {
				title: '',
				description: '',
				address: '',
				iframe_src: '',
			} );
			setAttributes( { locations } );
		};

		const removeLocation = ( index ) => {
			const locations = [ ...props.attributes.locations ];
			locations.splice( index, 1 );
			setAttributes( { locations } );
		};

		/**
		 * Update location title.
		 *
		 * @param {*} value
		 * @param {*} index
		 */
		const changeLocationTitle = ( value, index ) => {
			if ( undefined === value ) {
				return;
			}

			const locations = [ ...props.attributes.locations ];

			locations[ index ] = {
				title: value || '',
				description: locations[ index ].description || '',
				address: locations[ index ].address || '',
				iframe_src: locations[ index ].iframe_src || '',
			};
			props.setAttributes( { locations } );
		};

		/**
		 * Update location description.
		 *
		 * @param {*} value
		 * @param {*} index
		 */
		const changeLocationDescription = ( value, index ) => {
			if ( undefined === value ) {
				return;
			}

			const locations = [ ...props.attributes.locations ];

			locations[ index ] = {
				title: locations[ index ].title || '',
				description: value || '',
				address: locations[ index ].address || '',
				iframe_src: locations[ index ].iframe_src || '',
			};
			props.setAttributes( { locations } );
		};

		/**
		 * Update location address.
		 *
		 * @param {*} value
		 * @param {*} index
		 */
		const changeLocationAddress = ( value, index ) => {
			if ( undefined === value ) {
				return;
			}

			const locations = [ ...props.attributes.locations ];

			locations[ index ] = {
				title: locations[ index ].title || '',
				description: locations[ index ].description || '',
				address: value || '',
				iframe_src: locations[ index ].iframe_src || '',
			};
			props.setAttributes( { locations } );
		};

		/**
		 * Update location iFrame Src.
		 *
		 * @param {*} value
		 * @param {*} index
		 */
		const changeLocationIframeSrc = ( value, index ) => {
			if ( undefined === value ) {
				return;
			}

			const locations = [ ...props.attributes.locations ];

			locations[ index ] = {
				title: locations[ index ].title || '',
				description: locations[ index ].description || '',
				address: locations[ index ].address || '',
				iframe_src: value || '',
			};
			props.setAttributes( { locations } );
		};

		const locationFields = props.attributes.locations.map(
			( location, index ) => {
				return (
					<PanelBody
						title={
							__( 'Location - ', 'vape-smoke' ) + location.title
						}
						initialOpen={ true }
						key={ index }
					>
						<PanelRow>
							<TextControl
								label={ __( 'Title', 'vape-smoke' ) }
								value={ location.title }
								onChange={ ( value ) => {
									changeLocationTitle( value, index );
								} }
							/>
						</PanelRow>
						<PanelRow>
							<TextareaControl
								label={ __( 'Description', 'vape-smoke' ) }
								value={ location.description }
								onChange={ ( value ) => {
									changeLocationDescription( value, index );
								} }
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label={ __( 'Address', 'vape-smoke' ) }
								value={ location.address }
								onChange={ ( value ) => {
									changeLocationAddress( value, index );
								} }
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label={ __( 'iFrame Src', 'vape-smoke' ) }
								value={ location.iframe_src }
								onChange={ ( value ) => {
									changeLocationIframeSrc( value, index );
								} }
							/>
						</PanelRow>
						<PanelRow>
							<Button
								className="button"
								onClick={ () => removeLocation( index ) }
							>
								{ __( 'Delete location', 'vape-smoke' ) }
							</Button>
						</PanelRow>
					</PanelBody>
				);
			}
		);

		return (
			<Fragment>
				<InspectorControls>
					{ locationFields }
					<PanelBody
						title={ __( 'Add New Location', 'vape-smoke' ) }
						initialOpen={ true }
					>
						<PanelRow>
							<Button
								isSecondary
								onClick={ addNewLocation.bind( this ) }
							>
								{ __( 'Add Location', 'vape-smoke' ) }
							</Button>
						</PanelRow>
					</PanelBody>
				</InspectorControls>
				<ServerSideRender
					block="vape-smoke/store-locations"
					attributes={ {
						locations,
					} }
				/>
			</Fragment>
		);
	},
	save: () => {
		// Render in PHP.
		return null;
	},
} );
