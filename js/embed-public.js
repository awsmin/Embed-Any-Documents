jQuery(function($) {
	$('.ead-iframe-wrapper').each(function() {
		var $wrapper = $(this);
		var $activeIframe = $wrapper.find('.ead-iframe');
		var $document = $wrapper.parent('.ead-document');
		var viewer = $document.data('viewer');
		var isNativeViewer = typeof viewer !== 'undefined' && viewer.length > 0 ? viewer : false;
		var dataSrc = $activeIframe.data('src');
		var fileUrl = $activeIframe.attr('data-file-url');
		var lazyLoadAttr = $activeIframe.attr('loading');
		var isLazyLoaded = false;
		if ((typeof dataSrc !== 'undefined' && dataSrc.length > 0) || (typeof lazyLoadAttr !== 'undefined' && lazyLoadAttr === 'lazy')) {
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

		var activateIframe = function($el, src) {
			$el.attr('src', src);
			if (!isNativeViewer) {
				$el.css('visibility', 'visible');
			}
			$el.on('load', function() {
				$(this).parents('.ead-document').find('.ead-document-loading').css('display', 'none');
			});
		};

		var showNoPreview = function() {
			$document.find('.ead-document-loading').css('display', 'none');
			var msg = (typeof eadPublic !== 'undefined' && eadPublic.noPreviewMsg) ? eadPublic.noPreviewMsg : 'No preview available.';
			$wrapper.html('<p class="ead-no-preview" style="padding:1em;text-align:center;">' + msg + '</p>');
		};

		var googleCanAccess = function(url) {
			try {
				var hostname = new URL(url).hostname;
				return hostname !== 'localhost' &&
					!/\.local$/.test(hostname) &&
					!/^127\./.test(hostname);
			} catch(e) { return false; }
		};

		if (typeof dataSrc !== 'undefined' && dataSrc.length > 0 && typeof fileUrl !== 'undefined' && fileUrl.length > 0) {
			if (googleCanAccess(fileUrl)) {
				if (!isLazyLoaded) { $wrapper.html($iframe); }
				activateIframe($iframe, dataSrc);
			} else {
				showNoPreview();
			}
		} else {
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

		if (typeof src !== 'string' || !/^https?:\/\//i.test(src)) {
			return;
		}

		viewer = (typeof viewer !== 'undefined' && src.length > 0 && viewer.length > 0) ? viewer : false;
		var isBuiltInViewer = 'pdfjs' in eadPublic && eadPublic.pdfjs.length > 0 && viewer === 'built-in';

		if (viewer && (viewer === 'browser' || isBuiltInViewer)) {
			if (PDFObject.supportsPDFs || isBuiltInViewer) {
				var options = {};
				if (!isBuiltInViewer) {
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

				try {
					PDFObject.embed(src, $elem[0], options);
				} catch (e) {
					console.error('PDFObject failed to embed:', e);
				}
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
