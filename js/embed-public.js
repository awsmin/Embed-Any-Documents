jQuery(function($) {
	$('.ead-iframe-wrapper').each(function() {
		var $wrapper = $(this);
		var $activeIframe = $wrapper.find('.ead-iframe');
		var viewer = $wrapper.parent('.ead-document').data('viewer');
		var isNativeViewer = typeof viewer !== 'undefined' && viewer.length > 0 ? viewer : false;
		var $iframe = $('<iframe class="ead-iframe"></iframe>');
		$iframe.attr({
			'src': $activeIframe.attr('src'),
			'style': $activeIframe.attr('style'),
			'title': $activeIframe.attr('title')
		});
		if (! isNativeViewer) {
			$iframe.css('visibility', 'visible');
		}
		$iframe.on('load', function() {
			$(this).parents('.ead-document').find('.ead-document-loading').css('display', 'none');
		});

		$wrapper.html($iframe);
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
