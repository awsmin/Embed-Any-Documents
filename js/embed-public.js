jQuery(function($) {
	$('.ead-iframe-wrapper').each(function() {
		var $wrapper = $(this);
		var $activeIframe = $wrapper.find('.ead-iframe');

		var $iframe = $('<iframe class="ead-iframe"></iframe>');
		$iframe.attr({
			'src': $activeIframe.attr('src'),
			'style': $activeIframe.attr('style'),
			'title': $activeIframe.attr('title')
		});
		$iframe.css('visibility', 'visible');
		$iframe.on('load', function() {
			$(this).parents('.ead-document').find('.ead-document-loading').css('display', 'none');
		});

		$wrapper.html($iframe);
	});

    $(document).on('click', '.ead-reload-btn', function(e) {
        e.preventDefault();
        var $wrapper = $(this).parents('.ead-document');
        var iframeSrc = $wrapper.find('.ead-iframe').attr('src');
        $wrapper.find('.ead-iframe').attr('src', iframeSrc);
    });
});