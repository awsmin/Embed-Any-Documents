/**
 * BLOCK: document
 *
 * Registering a basic block with Gutenberg.
 */

import EadHelper from './modules/helper';
import EadInspector from './modules/inspector';
import EadServerSideRender from './modules/ead-server-side-render';

import icon from './modules/icon';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
const { Button } = wp.components;
const { Fragment } = wp.element;
const { MediaPlaceholder } = wp.blockEditor || wp.editor;
const { isURL } = wp.url;

const validTypes = ['application/pdf','application/postscript','image/tiff','application/msword','application/vnd.ms-powerpoint','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/vnd.openxmlformats-officedocument.wordprocessingml.template','application/vnd.ms-word.template.macroEnabled.12','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel.sheet.macroEnabled.12','application/vnd.openxmlformats-officedocument.presentationml.presentation','application/vnd.openxmlformats-officedocument.presentationml.slideshow','application/vnd.apple.pages'];
const validExtension = [ '.pdf', '.tif', '.tiff', '.doc', '.pps', '.ppt', '.xla', '.xls', '.xlt', '.xlw', '.docx', '.dotx', '.dotm', '.xlsx', '.xlsm', '.pptx', '.pages', '.ppsx'];
/**
 * Register: a Gutenberg Block.
 *
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'embed-any-document/document', {
	title: __( 'Document', 'embed-any-document' ), // Block title.
	description: __( 'Upload and Embed your documents.', 'embed-any-document' ), // Block description
	icon: icon.block, // Block icon
	category: 'embed', // Block category,
	keywords: [ __( 'add document', 'embed-any-document' ), __( 'embed document', 'embed-any-document' ), __( 'embed any document', 'embed-any-document' ) ], // Access the block easily with keyword aliases
	/**
	 * The edit function describes the structure of the block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 */
	edit: ( props ) => {
		const { attributes, setAttributes, noticeOperations} = props;
		const { shortcode } = attributes;

        let blockProps = null;
		let shortcodeText;
		let embedurl;
		
		const createNotice = (type, msg, id) =>{
			wp.data.dispatch('core/notices').createNotice(
				type, // Can be one of: success, info, warning, error.
				msg, // Text string to display.
				{
					id: id, //assigning an ID prevents the notice from being added repeatedly
					isDismissible: true, // Whether the user can dismiss the notice.
				}
			);
		}

		const onSelectDocument = (media) => { 
			if ( ! media || ! media.url ) {
				return;
			}

 			if(media.url){
				embedurl=media.url;
 			}
 		    eadShortcode(embedurl);
		}

		const onSelectURL = (url) => { 
			let fileType = '';

			if ( ! url) {
				return;
			}

			if (isURL(url)) {
		   	  	embedurl = url;
				let filename = url.split('/').pop(); 
				if (filename.indexOf('.') !== -1) {
					filename = filename.split('.').pop();
					fileType = '.' + filename;
				}
				if (fileType !== '') {
					if (validExtension.includes(fileType)) {
					   eadShortcode(embedurl);
					} else {
						createNotice('error', __( 'File type is not supported!', 'embed-any-document' ), 'eadlinkerror');
						return;
					}
			   } else {
				   createNotice('warning', __( 'Unknown file type. This may cause issues with the document viewer.', 'embed-any-document' ), 'eadunknowntype');
				   eadShortcode(embedurl);
			   }
		    } else {
				createNotice('error', __( 'Please enter a valid URL.', 'embed-any-document' ), 'eadinvalidlink');
				return;
			}
		}

		const onUploadError = (message) => { 
			noticeOperations.removeAllNotices();
			noticeOperations.createErrorNotice( message );
		}

		const eadShortcode = (embedurl) => { 
			blockProps = props;

			if(embedurl) {
		   	  shortcodeText = '[embeddoc url="'+embedurl+'"]';
		    } 

		    let { url, width = emebeder.width, height = emebeder.height, download = emebeder.download, viewer = emebeder.provider, text = emebeder.text, cache = true } = EadHelper.parseShortcode(shortcodeText); 

		    viewer = jQuery.inArray( viewer, emebeder.viewers ) !== -1 ? viewer : 'google';

		    blockProps.setAttributes({
				shortcode: shortcodeText,
				url: url,
				width: width,
				height: height,
				download: download,
				text: text,
				viewer: viewer,
				cache: cache === 'off' ? false : true
			});
		}

		const providerLink = () => {
			let link = 'http://embedanydocument.com/plus-cc';
			window.open(link, '_blank');
		}

		if( typeof shortcode !== 'undefined' ) {
			return [
				<EadInspector { ...{ setAttributes, ...props } } />,
				<EadServerSideRender
					block="embed-any-document/document"
					attributes={ attributes }
				/>
			];
		} else {
			return (
				<MediaPlaceholder className="ead-media-placeholder" onSelect={ onSelectDocument } onSelectURL={ onSelectURL } labels = { { title: __( 'Embed Any Document', 'embed-any-document' ), 'instructions':__( 'Upload a document, pick from your media library, or add from an external URL.', 'embed-any-document' ) } } icon={icon.block}  accept={validExtension.join(', ')} allowedTypes={ validTypes } OnError={ onUploadError }>
					<Fragment>
					<Button className="ead-button-dropbox disabled" onClick={ providerLink } value="click">{ __( 'Add from dropbox', 'embed-any-document' ) }
                        <span className="overlay">
                        	<span>{ __( 'Pro Feature', 'embed-any-document' ) }</span>
                        </span>
					</Button>
					<Button  className="ead-button-drive disabled" onClick={ providerLink } value="click">{ __( 'Add from drive', 'embed-any-document' ) }
 						<span className="overlay">
                        	<span>{ __( 'Pro Feature', 'embed-any-document' ) }</span>
                        </span>
					</Button>
					<Button  className="ead-button-box disabled" onClick={ providerLink } value="click">{ __( 'Add from box', 'embed-any-document' ) }
						<span className="overlay">
                        	<span>{ __( 'Pro Feature', 'embed-any-document' ) }</span>
                        </span>
					</Button>
					<Button  className="ead-button-onedrive disabled" onClick={ providerLink } value="click">{ __( 'Add from OneDrive', 'embed-any-document' ) }
						<span className="overlay">
                        	<span>{ __( 'Pro Feature', 'embed-any-document' ) }</span>
                        </span>
					</Button>
					</Fragment>
				</MediaPlaceholder>
			);
		}
	},   
	/**
	 * The save function defines the way in which the different attributes should be combined into the final markup, which is then serialized by Gutenberg into post_content.
	 */
	save: ( props ) => {
		const { attributes: { shortcode } } = props;
		return shortcode;
	},
} );