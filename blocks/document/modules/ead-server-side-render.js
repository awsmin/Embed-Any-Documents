import icon from './icon';

const { __, sprintf } = wp.i18n;
const { Component, createRef } = wp.element;
const apiFetch = wp.apiFetch;
const { addQueryArgs } = wp.url;
const { Placeholder, Spinner } = wp.components;

const { isEqual, debounce } = lodash;

export function rendererPath( block, attributes = null, urlQueryArgs = {} ) {
	return addQueryArgs( `/wp/v2/block-renderer/${ block }`, {
		context: 'edit',
		...( null !== attributes ? { attributes } : {} ),
		...urlQueryArgs,
	} );
}

class EadServerSideRender extends Component {
	constructor( props ) {
		super( props );
		this.state = {
			response: null,
		};
		this.eadRef = createRef();
	}

	componentDidMount() {
		this.isStillMounted = true;
		this.fetch( this.props );
		// Only debounce once the initial fetch occurs to ensure that the first
		// renders show data as soon as possible.
		this.fetch = debounce( this.fetch, 500 );
	}

	componentWillUnmount() {
		this.isStillMounted = false;
	}

	componentDidUpdate( prevProps, prevState ) {
		if ( ! isEqual( prevProps, this.props ) ) {
			this.fetch( this.props );
		}

		if ( this.state.response !== prevState.response && null !== this.eadRef.current ) {
			const { attributes = null } = this.props;

			if ( ( attributes !== null && attributes ) && ( attributes.viewer === 'google' || attributes.viewer === 'browser' || attributes.viewer === 'built-in' ) ) {
				let viewer = attributes.viewer;
				let currentRef = this.eadRef.current;
				let documentWrapper = jQuery(currentRef).find('.ead-document');
				let iframe = documentWrapper.find('.ead-iframe');

				if ( viewer === 'google' || viewer === 'browser' ) {
					if ( viewer === 'google' ) {
						iframe.css('visibility', 'visible');
					}
					iframe.on('load', function() {
						jQuery(this).parents('.ead-document').find('.ead-document-loading').css('display', 'none');
					});
				}

				if ( viewer === 'browser' || viewer === 'built-in' ) {
					let src = documentWrapper.data('pdfSrc');
					viewer = typeof src !== 'undefined' && src.length > 0 && viewer.length > 0 ? viewer : false;
					let isBuiltInViewer = 'pdfjs' in eadPublic && eadPublic.pdfjs && eadPublic.pdfjs.length > 0 && viewer === 'built-in';

					if (viewer && (viewer === 'browser' || isBuiltInViewer)) {
						if (PDFObject.supportsPDFs || isBuiltInViewer) {
							let options = {};
							if (! isBuiltInViewer) {
								options = {
									width: iframe.css('width'),
									height: iframe.css('height')
								}
							} else {
								options = {
									forcePDFJS: true,
									PDFJS_URL: eadPublic.pdfjs
								};
							}

							PDFObject.embed(src, documentWrapper, options);
						} else {
							iframe.css('visibility', 'visible');
						}
					}
				}
			}
		}
	}

	fetch( props ) {
		if ( ! this.isStillMounted ) {
			return;
		}
		if ( null !== this.state.response ) {
			this.setState( { response: null } );
		}
		const { block, attributes = null, urlQueryArgs = {} } = props;

		const path = rendererPath( block, attributes, urlQueryArgs );
		// Store the latest fetch request so that when we process it, we can
		// check if it is the current request, to avoid race conditions on slow networks.
		const fetchRequest = ( this.currentFetchRequest = apiFetch( { path } )
			.then( ( response ) => {
				if (
					this.isStillMounted &&
					fetchRequest === this.currentFetchRequest &&
					response
				) {
					this.setState( { response: response.rendered } );
				}
			} )
			.catch( ( error ) => {
				if (
					this.isStillMounted &&
					fetchRequest === this.currentFetchRequest
				) {
					this.setState( {
						response: {
							error: true,
							errorMsg: error.message,
						},
					} );
				}
			} ) );
		return fetchRequest;
	}

	render() {
		const response = this.state.response;
		const {
			className,
			EmptyResponsePlaceholder,
			ErrorResponsePlaceholder,
			LoadingResponsePlaceholder,
		} = this.props;

		if ( response === '' ) {
			return (
				<EmptyResponsePlaceholder
					response={ response }
					{ ...this.props }
				/>
			);
		} else if ( ! response ) {
			return (
				<LoadingResponsePlaceholder
					response={ response }
					{ ...this.props }
				/>
			);
		} else if ( response.error ) {
			return (
				<ErrorResponsePlaceholder
					response={ response }
					{ ...this.props }
				/>
			);
		}

		let wrapperClass = typeof className !== 'undefined' && className ? 'ead-block-content-wrapper ' + className : 'ead-block-content-wrapper';
		return (
			<div ref={ this.eadRef } className={ wrapperClass } dangerouslySetInnerHTML={ { __html: response } } />
		);
	}
}

EadServerSideRender.defaultProps = {
	EmptyResponsePlaceholder: ( { className } ) => (
		<Placeholder label={ __( 'Document', 'embed-any-document' ) } icon={ icon.block } className={ className }>
			{ __( 'No document found!', 'embed-any-document' ) }
		</Placeholder>
	),
	ErrorResponsePlaceholder: ( { response, className } ) => {
		const errorMessage = sprintf(
			// translators: %s: error message describing the problem
			__( 'Error loading the document: %s', 'embed-any-document' ),
			response.errorMsg
		);
		return (
			<Placeholder label={ __( 'Document', 'embed-any-document' ) } icon={ icon.block } className={ className }>{ errorMessage }</Placeholder>
		);
	},
	LoadingResponsePlaceholder: ( { className } ) => {
		return (
			<Placeholder className={ className }>
				<Spinner />
			</Placeholder>
		);
	},
};

export default EadServerSideRender;
