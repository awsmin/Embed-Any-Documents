jQuery(function($) {
    $('.ead-document iframe').on('load', function() {
        $(this).parent('.ead-document').find('.ead-document-loading').css('display', 'none');
    });
});