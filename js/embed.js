jQuery(function($) { 
    var eadEmbed = window.eadEmbed = window.eadEmbed || {};

    eadEmbed.updateprovider = function(file,handle) {
        ead_updateprovider(file,handle);
    };

    eadEmbed.human_filesize = function(bytes){
        ead_human_file_size(bytes);
    }

    eadEmbed.setpseudo = function(viewer) {
        setpseudo(viewer);
    };

    eadEmbed.ead_showmsg = function(msg){
        ead_showmsg(msg);
    };
    
    var template = wp.template('embed-popup-template');
    $('#embed-popup-wrap-template').html(template);
    var popupContent = $('#embed-popup-wrap-template').html();

    var fileurl = "",
        filedata={},
        newprovider = "",
        frame,
        msextension = emebeder.msextension;

	var isAdobeViewerEnabled = typeof eadPublic !== 'undefined' && eadPublic.adobe_api_key;
    // Opens Embed popup
    $(document).on('click', '.awsm-embed', function(e) {
        if ($('#embed-popup').length === 0) {
            $('body').append(popupContent);
        }
        e.preventDefault();
        ead_reset();
        $('body').addClass('ead-popup-on');
        tb_show("Embed Any Document", "#TB_inline?inlineId=embed-popup-wrap&amp;width=1030&amp;modal=true", null);
        ead_tb_position();
        $("#upload-doc").focus();
        return;
    });

    //Update shortcode on change
    $(document).on('change', '.ead-usc', function() {
        newprovider = "";
        ead_updateshortcode($(this).attr('id'));
        ead_customize_popup();
    });

    $('.embedval').keyup(function() {
        ead_updateshortcode($(this).attr('id'));
    });

    //Wordpress Uploader
    $(document).on('click', '#upload-doc', ead_open_media_window);

    //Add url
    $(document).on('click', '#ead-add-url', ead_embded_url);

    //insert Shortcode
    $(document).on('click', '#insert-doc', ead_shortcode);

    // Add from URL support
    $(document).on('click', '#add-ead-document', function(e) {
        e.preventDefault();
        $('.addurl-box').fadeIn();
        $('.ead-options').hide();
    });

    //Add fromrom URL cancel handler
    $(document).on('click', '.go-back', function(e) {
        e.preventDefault();
        $('.addurl-box').hide();
        $('.ead-options').fadeIn();
    });

    // Close embed dialog
    $(document).on('click', '#embed-popup .cancel-embed,.ead-close', function(e) {
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
        filedata = file;
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
    function getshortcode(filedata, item) {
        var height = ead_sanitize($('#ead-height').val()),
            width = ead_sanitize($('#ead-width').val()),
            download = $('#ead-download').val(),
            provider = $('#ead-provider').val(),
            text = $('#ead-text').val(),
            cache = $('#ead-cache').is(':checked');

        eadEmbed.shortcodeAttrs = {};

        if(filedata.url){
            eadEmbed.shortcodeAttrs.url = filedata.url;
        }

        if (ead_itemcheck('height', item)) {
            eadEmbed.shortcodeAttrs.height = height;
        }
        if (ead_itemcheck('width', item)) {
            eadEmbed.shortcodeAttrs.width = width;
        }
        if (ead_itemcheck('download', item)) {
            eadEmbed.shortcodeAttrs.download = download;
        }
        if (ead_itemcheck('provider', item)) {
            eadEmbed.shortcodeAttrs.viewer = provider;
        }

        if (ead_itemcheck('text', item) && download!='none' ) {
            eadEmbed.shortcodeAttrs.text = text;
        }

        eadEmbed.file = filedata;

        if (provider == 'google') {
            $('#eadcachemain').show();
            if (cache) {
                eadEmbed.shortcodeAttrs.cache = "off";
            }
        } else {
            $('#eadcachemain').hide();
            $('.ead-browser-viewer-note').hide();
        }

        $('#embed-popup').trigger('awsm_ead_generate_embed_shortcode', [eadEmbed.shortcodeAttrs,eadEmbed.file]);
        var attrs = "";

        $.each(eadEmbed.shortcodeAttrs,function(index,item){
            attrs += index+'="'+item+'" ';
        });

        var embed_shortcode = '[embeddoc ' + attrs.trim() + ']';
        return embed_shortcode;
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
        $('#insert-doc').prop('disabled', false);
        $('#ead-filename').html(file.filename);
        if (file.filesizeHumanReadable) {
            $('#ead-filesize').html(file.filesizeHumanReadable);
        } else {
            $('#ead-filesize').html('&nbsp;');
        }
        $('.upload-success').fadeIn();
        $('.ead-container').hide();
        ead_upload_class(uClass);
    }


    function ead_embded_url() {
        var checkurl = $('#awsm-url').val();
        if (checkurl !== '') {
            ead_validateurl(checkurl);
        } else {
            $('#awsm-url').addClass('urlerror');
            ead_updateshortcode();
        }
    }
    function ead_is_valid_url(url) {
        return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
    }
    //Validate file url
    function ead_validateurl(url) {
        var uClass = 'link';
        $('#embed-message').hide();
        if(ead_is_valid_url(url)) {
            fileurl = url;
            var filename = url.split('/').pop();
            if (!filename) filename = emebeder.from_url;
            filedata = {
                url: fileurl,
                filename: filename
            };
            $('#insert-doc').prop('disabled', false);
            $('#ead-filename').html(emebeder.from_url);
            $('#ead-filesize').html('&nbsp;');
            $('.upload-success').fadeIn();
            $('.ead-container').hide();
            ead_upload_class(uClass);
            ead_valid_viewer(filedata, uClass);
            ead_updateshortcode();
        } else {
            ead_showmsg(emebeder.invalidurl);
        }
    }
    //Show Message
    function ead_showmsg(msg) {
        $('#embed-message').fadeIn();
        $('#embed-message p').text(msg);
    }


    function ead_shortcode() {
        if (filedata) {
            // @rel: document guten-block
            var ins_shortcode = true;
            if(typeof wp.blocks !== 'undefined') {
                var document_block = wp.blocks.getBlockType('embed-any-document/document');
                if(typeof document_block !== 'undefined') {
                    ins_shortcode = false;
                }
            }
            if(ins_shortcode) {
                wp.media.editor.insert($('#embed-popup #shortcode').text());
            }
            ead_remove_pop();
        } else {
            ead_showmsg(emebeder.nocontent);
        }
    }

    //Update ShortCode
    function ead_updateshortcode(item) {
        item = typeof item !== 'undefined' ? item : false;

        if (filedata) {
            $('#embed-popup #shortcode').text(getshortcode(filedata, item));
        } else {
            $('#embed-popup #shortcode').text('');
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
        var checkitem = file.filename;

        var is_link = false;
        if (provider == 'link') {
            is_link = true; 
            checkitem = file.url;
        }

        var ext = '.' + checkitem.split('.').pop();

        var flexible_viewers = ['adobe', 'built-in', 'browser', 'microsoft'];
        $.each(flexible_viewers, function(i, value) {
            $("#new-provider option[value='" + value + "']").attr({
                'disabled': false,
                'hidden': false
            });
        });
        $('.ead-browser-viewer-note').hide();

        if ($.inArray(provider, cprovider) !== -1) {
            if ($.inArray(ext, validext) === -1) {
                newprovider = 'google';
				if (ext === '.pdf' && isAdobeViewerEnabled) {
					newprovider = 'adobe';
				} else {
					$("#new-provider option[value='adobe']").attr({
						'disabled': true,
					});
				}
                $("#new-provider option[value='" + newprovider + "']").attr("selected", "selected");
                $("#new-provider option[value='microsoft']").attr({
                    'disabled': true,
                    'hidden': true
                });

                $("#ead-provider").val($("#new-provider option[value='" + newprovider + "']").val());
            } else {
                newprovider = "microsoft";
                $("#new-provider option[value='microsoft']").attr("selected", "selected");
                $("#ead-provider").val($("#new-provider option[value='microsoft']").val());
            }

            // Hide the Browser viewer and built-in viewer if the extension is not pdf and also if the provider is not in the supported providers list.
            if (ext !== '.pdf'){
                $("#new-provider option[value='browser']").attr({
                    'disabled': true,
                    'hidden': true
                });
				$("#new-provider option[value='adobe']").attr({
                    'disabled': true,
                    'hidden': true
                });
            }

            if (ext !== '.pdf' || (provider === 'link' && checkitem.indexOf(emebeder.site_url) === -1)) {
                $("#new-provider option[value='built-in']").attr({
                    'disabled': true,
                    'hidden': true
                });
            }

            if(is_link){
                $('#new-provider').trigger('awsm_ead_check_item',[checkitem]);
            }
        }
    }

    function setpseudo(viewer){
        $('#new-provider').hide();
        $('#ead-pseudo').show();
        $('#ead-downloadc').hide();
        $('select[name="ead-pseudo"]').val(viewer);
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
        $('.ead-container').show();
        $('#awsm-url').val('');
        $('.ead-options').fadeIn();
        $('.addurl-box').hide();
        $('.upload-success').hide();
        $('#embed-message').hide();
        $('#insert-doc').prop('disabled', true);
        $('#new-provider').show();
        $('#ead-pseudo').hide();
        $('select[name="ead-pseudo"]').val('');
        newprovider = "";
        $("#new-provider  option[value='microsoft']").attr('disabled', false);
        $('#ead-downloadc').show();
        ead_customize_popup();
    }
});
