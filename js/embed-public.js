jQuery(function($) {
    function displayEmbeddedDocument($iframe) {
        $iframe.on('load', function() {
            $(this).parents('.ead-document').find('.ead-document-loading').css('display', 'none');
        });
    }
    displayEmbeddedDocument($('.ead-iframe-wrapper .ead-iframe'));

    $(document).on('click', '.ead-reload-btn', function(e) {
        e.preventDefault();
        var $wrapper = $(this).parents('.ead-document');
        var iframeSrc = $wrapper.find('.ead-iframe').attr('src');
        $wrapper.find('.ead-iframe').attr('src', iframeSrc);
        displayEmbeddedDocument($wrapper.find('.ead-iframe'));
    });
});