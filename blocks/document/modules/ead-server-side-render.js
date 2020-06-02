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
			jQuery(this.eadRef.current).find('.ead-iframe-wrapper .ead-iframe').on('load', function() {
				jQuery(this).parents('.ead-document').find('.ead-document-loading').css('display', 'none');
			});
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

		return (
			<div ref={ this.eadRef } className={ className } dangerouslySetInnerHTML={ { __html: response } } />
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
