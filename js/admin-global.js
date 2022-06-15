jQuery(function($) { 
$(document).on('click', '.ead-adobe-notice .notice-dismiss', function(e) { 
    e.preventDefault(); 
    $.ajax( eadAdminGlobal.ajaxurl,
        {
          type: 'POST',
          data: {
            action: 'dismissed_notice_handler',
            nonce: eadAdminGlobal.nonce,
          }
    }).done(function(response) { 
        if (response && response.dismiss) {
            $('.ead-adobe-notice').slideUp('fast'); 
        }
    }).fail(function(xhr) {
        console.log(xhr.responseText);
    });
});
});