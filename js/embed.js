jQuery(document).ready(function($) {
    var $embedurl = $('#awsm-url'),
        $shortcode = $('#shortcode'),
        $message = $('#embed-message p'),
        $ActionPanel = $('.mceActionPanel'),
        $container = $('.ead-container'),
        fileurl = "",
        newprovider = "",
        frame,
        msextension = emebeder.msextension,
        drextension = emebeder.drextension;
    //Opens Embed popup
    $('body').on('click', '.awsm-embed', function(e) {
        ead_reset();
        e.preventDefault();
        $('body').addClass('ead-popup-on');
        tb_show("Embed Any Document", "#TB_inline?inlineId=embed-popup-wrap&amp;width=1030&amp;modal=true", null);
        ead_tb_position();
        return;
    });
    
    //Update shortcode on change
    $(".ead-usc").change(function() {
        newprovider = "";
        ead_updateshortcode($(this).attr('id'));
        ead_customize_popup();
    });
    $('.embedval').keyup(function() {
        ead_updateshortcode($(this).attr('id'));
    });
    //Wordpress Uploader
    $('#upload-doc').click(ead_open_media_window);

    //Add url
    $('#ead-add-url').click(ead_embded_url);

    //insert Shortcode
    $('#insert-doc').click(ead_shortcode);
    // Add from URL support
    $('#add-ead-document').on('click', function(e) {
        e.preventDefault();
        $('.addurl-box').fadeIn();
        $('.ead-options').hide();
    });
    //Add fromrom URL cancel handler
    $('.go-back').on('click', function(e) {
        e.preventDefault();
        $('.addurl-box').hide();
        $('.ead-options').fadeIn();
    });
     // Close embed dialog
    $('#embed-popup').on('click', '.cancel-embed,.ead-close', function(e) {
        // Prevent default action
        e.preventDefault();
        ead_remove_pop();
    });
    //Insert Media window
    function ead_open_media_window() {
        var uClass = 'upload';
        if (frame) {
            frame.open();
            return;
        }
        frame = wp.media({
            title: 'Embed Any Document',
            multiple: false,
            library: {
                type: emebeder.validtypes,
            },
            button: {
                text: emebeder.select_button
            }
        });
        frame.on('select', function() {
            var file = frame.state().get('selection').first().toJSON();
            ead_updateprovider(file, uClass);
        });
        frame.open();
    }
    

    //update provider
    function ead_updateprovider(file, uClass) {
        fileurl = file.url;
        ead_valid_viewer(file, uClass);
        ead_updateshortcode();
        ead_uploaddetails(file, uClass);
    }
    //sanitize width and height
    function ead_sanitize(dim) {
        if (dim.indexOf("%") == -1) {
            dim = dim.replace(/[^0-9]/g, '');
            dim += "px";
        } else {
            dim = dim.replace(/[^0-9]/g, '');
            dim += "%";
        }
        return dim;
    }
    //Thickbox Handler
    function ead_tb_position() {
            var tbWindow = $('#TB_window');
            var width = $(window).width();
            var H = $(window).height();
            var W = ( 1080 < width ) ? 1080 : width;

            if ( tbWindow.size() ) {
                tbWindow.width( W - 50 ).height( H - 45 );
                //$('#TB_iframeContent').width( W - 50 ).height( H - 75 );
                $('#TB_ajaxContent').css({'width':'100%','height':'100%','padding':'0'});
                tbWindow.css({'margin-left': '-' + parseInt((( W - 50 ) / 2),10) + 'px'});
                if ( typeof document.body.style.maxWidth != 'undefined' )
                    tbWindow.css({'top':'20px','margin-top':'0'});
                $('#TB_title').css({'background-color':'#fff','color':'#cfcfcf'});
            };
    };
    $(window).resize( function() { ead_tb_position() } );
    //to getshortcode
    function getshortcode(url, item) {
        var height = ead_sanitize($('#ead-height').val()),
            width = ead_sanitize($('#ead-width').val()),
            download = $('#ead-download').val(),
            provider = $('#ead-provider').val(),
            text = $('#ead-text').val(),
            heightstr = "",
            widthstr = "",
            downloadstr = "",
            providerstr = "",
            textstr="",
            drivestr = "";
        if (ead_itemcheck('height', item)) {
            heightstr = ' height="' + height + '"';
        }
        if (ead_itemcheck('width', item)) {
            widthstr = ' width="' + width + '"';
        }
        if (ead_itemcheck('download', item)) {
            downloadstr = ' download="' + download + '"';

        }
        if (ead_itemcheck('provider', item)) {
            providerstr = ' viewer="' + provider + '"';
        }
        if (ead_itemcheck('text', item) && download!='none' ) {
            textstr = ' text="' + text + '"';
        }
        return '[embeddoc url="' + url + '"' + widthstr + heightstr + downloadstr + providerstr + drivestr + textstr +']';
    }
    // Checks with default setting value
    function ead_itemcheck(item, dataitem) {
        var check = $('#ead-' + item).val();
        if(!check) return false;
        var datacheck = 'ead-' + item;
        if (datacheck == dataitem) {
            return true;
        } else if (check != emebeder[item]) {
            return true;
        }
        return false;
    }
    //Print uploaded file details
    function ead_uploaddetails(file, uClass) {
        $('#insert-doc').removeAttr('disabled');
        $('#ead-filename').html(file.filename);
        if (file.filesizeHumanReadable) {
            $('#ead-filesize').html(file.filesizeHumanReadable);
        } else {
            $('#ead-filesize').html('&nbsp;');
        }
        $('.upload-success').fadeIn();
        $container.hide();
        ead_upload_class(uClass);
    }
   

    function ead_embded_url() {
        var checkurl = $embedurl.val();
        if (checkurl !== '') {
            ead_validateurl(checkurl);
        } else {
            $embedurl.addClass('urlerror');
            updateshortcode();
        }
    }
    function ead_is_valid_url(url) {
        return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
    }
    //Validate file url
    function ead_validateurl(url) {
        var uClass = 'link';
        $('#embed-message').hide();
        if(ead_is_valid_url(url)){
            fileurl = url;
            $('#insert-doc').removeAttr('disabled');
            $('#ead-filename').html(emebeder.from_url);
            $('#ead-filesize').html('&nbsp;');
            $('.upload-success').fadeIn();
            $container.hide();
            ead_upload_class(uClass);
            ead_updateshortcode();
        }else{
            ead_showmsg(emebeder.invalidurl);
        }
    }
    //Show Message
    function ead_showmsg(msg) {
        $('#embed-message').fadeIn();
        $message.text(msg);
    }
    

    function ead_shortcode() {
        if (fileurl) {
            wp.media.editor.insert($shortcode.text());
            ead_remove_pop();
        } else {
            showmsg(emebeder.nocontent);
        }
    }
    
    //Update ShortCode
    function ead_updateshortcode(item) {
        item = typeof item !== 'undefined' ? item : false;
        if (fileurl) {
            $shortcode.text(getshortcode(fileurl, item));
        } else {
            $shortcode.text('');
        }
    }
   
    //UploadClass
    function ead_upload_class(uPclass) {
        $(".uploaded-doccument").removeClass("ead-link ead-upload ead-dropbox ead-drive ead-box");
        $('.uploaded-doccument').addClass('ead-' + uPclass);
    }
    //close popup
    function ead_remove_pop(){
        // Close popup
        tb_remove();
        setTimeout(function() {
           $('body').removeClass('ead-popup-on');
        }, 800);
    }
    //Convert Filesize to human Readable filesize
    function ead_human_file_size(bytes) {
        var thresh = 1024;
        if (bytes < thresh) return bytes + ' B';
        var units = ['KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        var u = -1;
        do {
            bytes /= thresh;
            ++u;
        } while (bytes >= thresh);
        return bytes.toFixed(1) + ' ' + units[u];
    }
    // Viewer Check
    function ead_valid_viewer(file, provider) {
        var cprovider = ["link", "upload"];
        var validext = msextension.split(',');
        var ext = '.' + file.filename.split('.').pop();
        $("#new-provider  option[value='microsoft']").attr('disabled', false);
        if ($.inArray(provider, cprovider) != -1) {
            if ($.inArray(ext, validext) == -1) {
                newprovider = "google";
                $("#new-provider option[value='google']").attr("selected", "selected");
                $("#new-provider  option[value='microsoft']").attr('disabled', true);
            } else {
                newprovider = "microsoft";
                $("#new-provider option[value='microsoft']").attr("selected", "selected");
            }
        }
    }
    //Download text show
    function ead_customize_popup(){
        if($('#ead-download').val()=="none"){
            $('#ead-download-text').hide();    
        }else{
            $('#ead-download-text').show();  
        }
        
    }
    //Reset form data
    function ead_reset() {
        $container.show();
        $embedurl.val('');
        $('.ead-options').fadeIn();
        $('.addurl-box').hide();
        $('.upload-success').hide();
        $('#embed-message').hide();
        $('#insert-doc').attr('disabled', 'disabled');
        $('#new-provider').show();
        $('#ead-pseudo').hide();
        newprovider = "";
        $("#new-provider  option[value='microsoft']").attr('disabled', false);
        $('#ead-downloadc').show();
        ead_customize_popup();
    }   
});