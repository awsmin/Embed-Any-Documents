jQuery(function($) {
	var siteOrigin = window.location.protocol + '//' + window.location.host;

	function activateIframe($iframe, src, isNativeViewer) {
		$iframe.attr('src', src);
		if (!isNativeViewer) {
			$iframe.css('visibility', 'visible');
		}
		$iframe.on('load', function() {
			$(this).parents('.ead-document').find('.ead-document-loading').css('display', 'none');
		});
	}

	$('.ead-iframe-wrapper').each(function() {
		var $wrapper = $(this);
		var $activeIframe = $wrapper.find('.ead-iframe');
		var viewer = $wrapper.parent('.ead-document').data('viewer');
		var isNativeViewer = typeof viewer !== 'undefined' && viewer.length > 0 ? viewer : false;
		var viewerSrc = $activeIframe.data('src');
		var fileUrl = $activeIframe.data('fileUrl');
		var lazyLoadAttr = $activeIframe.attr('loading');
		var isNativeLazy = typeof lazyLoadAttr !== 'undefined' && lazyLoadAttr === 'lazy';

		if (viewerSrc) {
			var checkUrl = fileUrl || viewerSrc;
			var isSameOrigin = checkUrl.indexOf(siteOrigin) === 0 || /^\/(?!\/)/.test(checkUrl);

			if (isSameOrigin && typeof fetch !== 'undefined') {
				fetch(checkUrl, { method: 'HEAD' })
					.then(function(response) {
						if (response.ok) {
							activateIframe($activeIframe, viewerSrc, isNativeViewer);
						}
						// response not ok (404, 403, etc.) — file missing, stay hidden
					})
					.catch(function() {
						// Network/fetch error — can't determine, load viewer anyway
						activateIframe($activeIframe, viewerSrc, isNativeViewer);
					});
			} else {
				activateIframe($activeIframe, viewerSrc, isNativeViewer);
			}
		} else if (isNativeLazy) {
			if (!isNativeViewer) {
				$activeIframe.css('visibility', 'visible');
			}
			$activeIframe.on('load', function() {
				$(this).parents('.ead-document').find('.ead-document-loading').css('display', 'none');
			});
		} else {
			var $iframe = $('<iframe class="ead-iframe"></iframe>');
			$iframe.attr({
				'src': $activeIframe.attr('src'),
				'style': $activeIframe.attr('style'),
				'title': $activeIframe.attr('title')
			});
			if (!isNativeViewer) {
				$iframe.css('visibility', 'visible');
			}
			$iframe.on('load', function() {
				$(this).parents('.ead-document').find('.ead-document-loading').css('display', 'none');
			});
			$wrapper.html($iframe);
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
					};
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
