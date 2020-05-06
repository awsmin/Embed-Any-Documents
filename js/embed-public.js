jQuery(function($) {
    $('.ead-document iframe').on('load', function() {
        $(this).parent('.ead-document').find('.ead-document-loading').css('display', 'none');
    });

    $(document).on('click', '.ead-reload-btn', function(e) {
        e.preventDefault();
        var $wrapper = $(this).parents('.ead-document');
        var iframeSrc = $wrapper.find('iframe').attr('src');
        $wrapper.find('iframe').attr('src', iframeSrc).trigger('load');
    });
});