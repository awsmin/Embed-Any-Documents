import EadHelper from './helper';

const { __ } = wp.i18n;
const { Component } = wp.element;
const { InspectorControls } = wp.editor;
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
        let viewerOptions = [{ value: 'google', label: __( 'Google Docs Viewer' ) }];
        if( EadHelper.isValidMSExtension(url) ) {
            viewerOptions.push({ value: 'microsoft', label: __( 'Microsoft Office Online' ) });
        }
        let downloadTextControl = <TextControl label={ __( 'Download Text' ) } help={ __( 'Default download button text' ) } value={ text } onChange={ text => setAttributes( { text } ) } />;
        if( this.state.downloadDisabled ) {
            downloadTextControl = <Disabled>{ downloadTextControl }</Disabled>;
        }

        return (
            <InspectorControls>
                <PanelBody>
                    <TextControl label={ __( 'Width' ) } help={ __( 'Width of document either in px or in %' ) } value={ width } onChange={ width => setAttributes( { width } ) } />
                </PanelBody>

                <PanelBody>
                    <TextControl label={ __( 'Height' ) } help={ __( 'Height of document either in px or in %' ) } value={ height } onChange={ height => setAttributes( { height } ) } />
                </PanelBody>

                <PanelBody>
                    <SelectControl label={ __( 'Show Download Link' ) } options={[
                        { value: 'all', label: __( 'For all users' ) },
                        { value: 'logged', label: __( 'For Logged-in users' ) },
                        { value: 'none', label: __( 'No Download' ) }
                    ]} value={ download } onChange={ this.downloadControlhandle } />
                </PanelBody>
                <PanelBody>{ downloadTextControl }</PanelBody>
                <PanelBody>
                    <SelectControl label={ __( 'Viewer ' ) } options={ viewerOptions } value={ viewer } onChange={ this.viewerControlHandle } />
                </PanelBody>

                { ! this.state.cacheHidden && <PanelBody>
                    <ToggleControl label={ __( 'Cache' ) } checked={ cache } onChange={ cache => setAttributes( { cache } ) } />
                </PanelBody> }
            </InspectorControls>
        );
    }
}

export default EadInspector;