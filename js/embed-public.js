var awsmEadMain = window.awsmEadMain = window.awsmEadMain || {}; 

jQuery(function($) { 
	$('.ead-iframe-wrapper').each(function() {
		var $wrapper = $(this);
		var $activeIframe = $wrapper.find('.ead-iframe');
		var viewer = $wrapper.parent('.ead-document').data('viewer');
		var isNativeViewer = typeof viewer !== 'undefined' && viewer.length > 0 ? viewer : false;
		if(isNativeViewer !== eadPublicViewer.viewer){
			var lazyLoadSrc = $activeIframe.data('src');
			var lazyLoadAttr = $activeIframe.attr('loading');
			var isLazyLoaded = false;
			if ((typeof lazyLoadSrc !== 'undefined' && lazyLoadSrc.length > 0) || (typeof lazyLoadAttr !== 'undefined' && lazyLoadAttr === 'lazy')) {
				isLazyLoaded = true;
			}
			var $iframe = $activeIframe;
			if (!isLazyLoaded) {
				$iframe = $('<iframe class="ead-iframe"></iframe>');
				$iframe.attr({
					'src': $activeIframe.attr('src'),
					'style': $activeIframe.attr('style'),
					'title': $activeIframe.attr('title')
				});
			}
			if (! isNativeViewer) {
				$iframe.css('visibility', 'visible');
			}
			$iframe.on('load', function() {
				$(this).parents('.ead-document').find('.ead-document-loading').css('display', 'none');
			});

			if (!isLazyLoaded) {
				$wrapper.html($iframe);
			}
		}
	});

	$('.ead-document[data-pdf-src]').each(function() { 
		var $elem = $(this);
		var $iframe = $elem.find('.ead-iframe');
		var src = $elem.data('pdfSrc');
		var viewer = $elem.data('viewer');
		viewer = typeof viewer !== 'undefined' && src.length > 0 && viewer.length > 0 ? viewer : false;
		var isBuiltInViewer = 'pdfjs' in eadPublic && eadPublic.pdfjs.length > 0 && viewer === 'built-in';
		if (viewer && (viewer === 'browser' || isBuiltInViewer)) {
			if (PDFObject.supportsPDFs || isBuiltInViewer) {
				var options = {};
				if (! isBuiltInViewer) {
					options = {
						width: $iframe.css('width'),
						height: $iframe.css('height')
					}
				} else {
					options = {
						forcePDFJS: true,
						PDFJS_URL: eadPublic.pdfjs
					};
				}

				PDFObject.embed(src, $elem, options);
			} else {
				$iframe.css('visibility', 'visible');
			}
		}
	});

    $(document).on('click', '.ead-reload-btn', function(e) {
        e.preventDefault();
        var $wrapper = $(this).parents('.ead-document');
        var iframeSrc = $wrapper.find('.ead-iframe').attr('src');
        $wrapper.find('.ead-iframe').attr('src', iframeSrc);
    });
});

awsmEadMain.getAdobeFileOptions = function($docElem) { 
	var fileURL = $docElem.data('pdfSrc');
	var fileName = fileURL.split('#').shift().split('?').shift().split('/').pop();
	return {
		content:  {
			location: {
				url: $docElem.data('pdfSrc')
			}
		},
		metaData: {
			fileName: fileName
		}
	};
};

awsmEadMain.getAdobeViewerOptions = function($docElem) {
	return {
		showAnnotationTools: false,
		showLeftHandPanel: false,
		enableFormFilling: false,
		showDownloadPDF: false,
		showPrintPDF: false,
		embedMode:'',
		showPageControls: false,
		dockPageControls: false,
	};
};

awsmEadMain.adobeViewer = function($docElem, adobeAPIKey) { 
	adobeAPIKey = typeof adobeAPIKey !== 'undefined' ? adobeAPIKey : eadPublic.adobe_api_key;
	if (adobeAPIKey) {
		var docId = $docElem.attr('id');
		var adobeDCView = new AdobeDC.View({
			clientId: adobeAPIKey,
			divId: docId
		});
		awsmEadMain.adobeFileOptions = awsmEadMain.adobeFileOptions || {};
		awsmEadMain.adobeViewerOptions = awsmEadMain.adobeViewerOptions || {};

		awsmEadMain.adobeFileOptions[ docId ] = awsmEadMain.getAdobeFileOptions($docElem);
		awsmEadMain.adobeViewerOptions[ docId ] = awsmEadMain.getAdobeViewerOptions($docElem);
		$docElem.trigger('awsm_ead_adobe_viewer_init');
		adobeDCView.previewFile(
			awsmEadMain.adobeFileOptions[ docId ],
			awsmEadMain.adobeViewerOptions[ docId ]
		);
	}
};

/**
 * Adobe PDF Embed API
 */
 
document.addEventListener("DOMContentLoaded", function() { 
	var adobeAPIKey = eadPublic.adobe_api_key; 
	if (adobeAPIKey) { 
		let validateAdobeDC = setInterval(() => { 
			if (typeof AdobeDC !== 'undefined') { 
				jQuery(document).trigger('awsm_ead_adobe_sdk_loaded');
				jQuery('.ead-document[data-viewer="adobe"]').each(function () {
					var $docElem = jQuery(this);
					awsmEadMain.adobeViewer($docElem, adobeAPIKey);
					clearInterval(validateAdobeDC);
				});
			}
		}, 250);
	}
});


