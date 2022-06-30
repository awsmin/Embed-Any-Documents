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
        if (EadHelper.isPDF(this.props.attributes.url)) {
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

            let fileSrc = EadHelper.getFileSource( url );

            if (EadHelper.isPDF(url)) {
                if (typeof eadPublic !== 'undefined' && eadPublic.adobe_api_key){
                    viewerOptions.push({ value: 'adobe', label: __( 'Adobe Viewer', 'embed-any-document' ) });
                }
				
                viewerOptions.push({ value: 'browser', label: __( 'Browser Based', 'embed-any-document' ) });
            }

            viewerOptions = wp.hooks.applyFilters('awsm_ead_viewer_options',viewerOptions,EadHelper.isPDF(url));

			downloadTextControl = <TextControl label={ __( 'Download Text', 'embed-any-document' ) } help={ __( 'Default download button text', 'embed-any-document' ) } value={ text } onChange={ text => setAttributes( { text } ) } />;

			if( this.state.downloadDisabled ) {
				downloadTextControl = <Disabled>{ downloadTextControl }</Disabled>;
			}
		}

        return (
            <InspectorControls>
                { wp.hooks.doAction( 'awsm_ead_before_block_settings') }
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

                { ! this.state.cacheHidden && <PanelBody>
                    <ToggleControl label={ __( 'Cache', 'embed-any-document' ) } checked={ cache } onChange={ cache => setAttributes( { cache } ) } />
                </PanelBody> }

                { wp.hooks.doAction( 'awsm_ead_after_block_settings',viewerSettings,viewer,this.props) }
                { viewerSettings }

            </InspectorControls>
        );
    }
}

export default EadInspector;
