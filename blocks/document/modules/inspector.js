import EadHelper from './helper';

const { __ } = wp.i18n;
const { Component } = wp.element;
const { InspectorControls } = wp.blockEditor || wp.editor;
const {
    PanelBody,
    TextControl,
    SelectControl,
    ToggleControl,
    Disabled
} = wp.components;

class EadInspector extends Component {
    constructor() {
        super(...arguments);
        this.downloadControlhandle = this.downloadControlhandle.bind(this);
        this.viewerControlHandle = this.viewerControlHandle.bind(this);
        const { attributes: { download, viewer } } = this.props;
        this.state = {
            downloadDisabled: ( download === 'none' ) ? true : false,
            cacheHidden: ( viewer === 'google' ) ? false : true
        };
    }

    componentDidMount(){ 
        if (EadHelper.isPDF(this.props.attributes.url) && EadHelper.getFileSource(this.props.attributes.url) === 'internal') {
            if (typeof eadPublic !== 'undefined' && eadPublic.adobe_api_key){
                this.viewerControlHandle('adobe'); 
            }
        }   
    }

    downloadControlhandle(download) {
        this.setState( { downloadDisabled: ( download === 'none' ) ? true : false } );
        this.props.setAttributes( { download } );
    }

    viewerControlHandle(viewer) { 
        this.setState( { cacheHidden: ( viewer === 'google' ) ? false : true } );
        this.props.setAttributes( { viewer } );
    }

    render() { 
        const { attributes: { url, width, height, text, download, viewer, cache }, setAttributes } = this.props;
        let viewerOptions  = [];
        let viewerSettings = [];
		let downloadTextControl = null;
		let enableViewerControl = viewer && jQuery.inArray( viewer, emebeder.viewers ) !== -1;

		if ( enableViewerControl ) {
            let disableStatus = false; 
            if(emebeder.force_adobe === 'enable' && EadHelper.isPDF(url)){
                disableStatus = true;
            } 
			viewerOptions = [{ value: 'google', label: __( 'Google Docs Viewer', 'embed-any-document' ),disabled:disableStatus}];

			if( EadHelper.isValidMSExtension( url ) ) {
				viewerOptions.push({ value: 'microsoft', label: __( 'Microsoft Office Online', 'embed-any-document' ) });
			}

            //let fileSrc = EadHelper.getFileSource( url );

            if (EadHelper.isPDF(url)) {
                if (typeof eadPublic !== 'undefined' && eadPublic.adobe_api_key == ''){
                    disableStatus = true;
                }
                viewerOptions.push({ value: 'adobe', label: __( 'Adobe Viewer', 'embed-any-document' ),disabled:disableStatus});
                viewerOptions.push({ value: 'browser', label: __( 'Browser Based', 'embed-any-document' ) });
            }

            /**
			 * Customise viewerOptions array.
			 *
			 * @since 3.0.0
			 */
            viewerOptions = wp.hooks.applyFilters('awsm_ead_viewer_options',viewerOptions,EadHelper.isPDF(url));
			downloadTextControl = <TextControl label={ __( 'Download Text', 'embed-any-document' ) } help={ __( 'Default download button text', 'embed-any-document' ) } value={ text } onChange={ text => setAttributes( { text } ) } />;

			if( this.state.downloadDisabled ) {
				downloadTextControl = <Disabled>{ downloadTextControl }</Disabled>;
			}
		}

        return (
            <InspectorControls>
                { 
                 /**
                 * Add content before the Block .
                 *
                 * @since 3.0.0
                 */
                wp.hooks.doAction( 'awsm_ead_before_block_settings') 
                }
                <PanelBody>
                    <TextControl label={ __( 'Width', 'embed-any-document' ) } help={ __( 'Width of document either in px or in %', 'embed-any-document' ) } value={ width } onChange={ width => setAttributes( { width } ) } />
                 </PanelBody>

                <PanelBody>
                    <TextControl label={ __( 'Height', 'embed-any-document' ) } help={ __( 'Height of document either in px or in %', 'embed-any-document' ) } value={ height } onChange={ height => setAttributes( { height } ) } />
                </PanelBody>

               {enableViewerControl && [
                <PanelBody>
                    <SelectControl label={ __( 'Show Download Link', 'embed-any-document' ) } options={[
                        { value: 'all', label: __( 'For all users', 'embed-any-document' ) },
                        { value: 'logged', label: __( 'For Logged-in users', 'embed-any-document' ) },
                        { value: 'none', label: __( 'No Download', 'embed-any-document' ) }
                    ]} value={ download } onChange={ this.downloadControlhandle } />
                </PanelBody>,
                   <PanelBody>{ downloadTextControl }</PanelBody>,
                   <PanelBody>
                    <SelectControl label={ __( 'Viewer', 'embed-any-document' ) } options={ viewerOptions } value={ viewer } onChange={ this.viewerControlHandle } />
                </PanelBody>
                ]}

                { (viewer=="adobe" && typeof emebeder.addon_active.pdf == 'undefined') && [
                    <PanelBody title={ __( <div>Viewer Options <span class="adobe-pro-disabled">Pro Add-on</span></div>, 'embed-any-document' ) } >
                        <SelectControl disabled={true} label={ __( 'Mode', 'embed-any-document' ) } options={[
                        { label: __( 'Full Window', 'embed-any-document' ) }]} />
                        <ToggleControl disabled={true} label={ __( 'Show Download Button', 'embed-any-document' ) } />
                        <ToggleControl disabled={true} label={ __( 'Show Print PDF', 'embed-any-document' ) } />
                        <ToggleControl disabled={true} label={ __( 'Show Annotation Tools', 'embed-any-document' ) } />
                        <ToggleControl disabled={true} label={ __( 'Show Thumbnails ', 'embed-any-document' ) } />
                        <ToggleControl disabled={true} label={ __( 'Show Formfilling', 'embed-any-document' ) } />
                        <ToggleControl disabled={true} label={ __( 'Show ZoomControl', 'embed-any-document' ) } />
                        <SelectControl disabled={true} label={ __( 'View Mode', 'embed-any-document' ) } options={[
                        { label: __( 'Fit Width', 'embed-any-document' ) }]} />
                    </PanelBody>
                ]}

                { ! this.state.cacheHidden && <PanelBody>
                    <ToggleControl label={ __( 'Cache', 'embed-any-document' ) } checked={ cache } onChange={ cache => setAttributes( { cache } ) } />
                </PanelBody> }

                { 
                 /**
                 * Add content after the Block .
                 *
                 * @since 3.0.0
                 */
                wp.hooks.doAction( 'awsm_ead_after_block_settings',viewerSettings,viewer,this.props)
                }
                { viewerSettings }

            </InspectorControls>
        );
    }
}

export default EadInspector;
