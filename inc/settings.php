<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div class="wrap">
    <h2 class="ead-title"><?php _e('Embed Any Document by AWSM.in',$this->text_domain);?></h2>
    <h2 class="nav-tab-wrapper">
            <a class="ead-tabs nav-tab nav-tab-active" href="#" data-tab="general"><?php _e( 'General Settings', $this->text_domain); ?></a>
            <a class="ead-tabs nav-tab " href="#" data-tab="support"><?php _e( 'Support', $this->text_domain); ?></a>
            <a class="nav-tab " href="http://goo.gl/wJTQlc" target="_blank"><?php _e( 'Get Plus Version', $this->text_domain); ?></a>
        </h2>
    <div class="ead-left-wrap">
        
        <div class="tabs visible" id="general">
            <form method="post" action="options.php">
                <?php settings_fields( 'ead-settings-group' ); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('Default Size', $this->text_domain); ?></th>
                        <td> 
                         <div class="f-left ead-frame-width"><?php _e('Width', $this->text_domain); ?>    
                         <input type="text" class="small" name="ead_width"  value="<?php echo esc_attr( get_option('ead_width','100%') ); ?>" />   
                         </div>
                         <div class="f-left ead-frame-height">
                         <?php _e('Height', $this->text_domain); ?> 
                         <input type="text" class="small" name="ead_height" value="<?php echo esc_attr( get_option('ead_height','500px') ); ?>" />
                        </div>
                        <div class="clear"></div>
                        <span class="note"><?php _e('Enter values in pixels or percentage (Example: 500px or 100%)', $this->text_domain); ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                    <th scope="row"><?php _e('Show Download Link',$this->text_domain);?></th>
                    <td>
                       <?php 
                        $downoptions= array('alluser' => __('For all users',$this->text_domain),'logged' => __('For Logged-in users',$this->text_domain),'none' => __('No Download',$this->text_domain));
                        ead_selectbuilder('ead_download', $downoptions,esc_attr( get_option('ead_download','none'))); 
                        ?> 
                    </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div><!-- #general-->
        <div class="tabs" id="support">
            <div id="embed_message"><p></p></div>
            <div class="col-left">
            <?php  $current_user = wp_get_current_user();   ?>
            <form id="supportform" action="">
                <p>
                    <label><?php _e('Name', $this->text_domain); ?><span class="required">*</span></label>
                    <input type="text" name="site_name"  value="<?php echo  $current_user->display_name; ?>" class="text-input" />
                </p>
                <p>
                    <label><?php _e('Email ID', $this->text_domain); ?><span class="required">*</span></label>
                    <input type="email" name="email_id" value="<?php echo  $current_user->user_email; ?>" class="text-input"/>
                </p>
                <p>
                    <label><?php _e('Problem', $this->text_domain); ?><span class="required">*</span></label>
                    <textarea name="problem"></textarea>
                </p>
                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Submit', $this->text_domain); ?>">
                </p>
            </form>
            </div>
            <div class="col-right">
                <p><strong>Frequently Reported Issues</strong></p>
                <p>
                    <strong>1. File not found error in my localhost site.</strong><br/>
                    The viewers (Google Docs Viewer and Microsoft Office Online) do not support locally hosted files. <span style="border-bottom: 1px solid;">Your document has to be available online for the viewers to access.</span>
                </p>
                <p>
                    <strong>2. Google Docs Viewer shows bandwidth exceeded error.</strong><br/>
                    The issue is caused by Google Docs Viewer, not the plugin. Google Docs Viewer is a standalone documents viewer which doesn't limit bandwidth. When the problem occurs, usually reloading the page will result in the document loading properly. So it looks more like a bug from their side. Many developers reported the same issue in Google Developer Forums. Hope it will be fixed soon.
                </p>
            </div>
            <div class="clear"></div>
        </div><!-- #support-->
    </div><!-- .ead-left-wrap -->
    <div class="ead-right-wrap">
        <a href="http://goo.gl/wJTQlc" target="_blank" title="Embed Any Document Plus"><img src="http://awsm.in/innovations/ead/ead-plus-banner.png" alt="AWSM!"></a>
        <a href="http://awsm.in" target="_blank" title="AWSM Innovations"><img src="http://awsm.in/innovations/ead/logo.png" alt="AWSM!"></a>
    <div class="author-info">
        This plugin is developed <br/>by <a href="http://awsm.in" target="_blank" title="AWSM Innovations">AWSM Innovations.</a>
    </div><!-- .author-info -->
    <ul class="awsm-social">
        <li><a href="https://www.facebook.com/awsminnovations" target="_blank" title="AWSM Innovations"><span class="awsm-icon awsm-icon-facebook">Facebook</span></a></li>
        <li><a href="https://twitter.com/awsmin" target="_blank" title="AWSM Innovations"><span class="awsm-icon awsm-icon-twitter">Twitter</span></a></li>
        <li><a href="https://github.com/awsmin" target="_blank" title="AWSM Innovations"><span class="awsm-icon awsm-icon-github">Github</span></a></li>
        <li><a href="https://www.behance.net/awsmin" target="_blank" title="AWSM Innovations"><span class="awsm-icon awsm-icon-behance">Behance</span></a></li>
    </ul>
    </div><!-- .ead-right-wrap -->
    <div class="clear"></div>
</div><!-- .wrap -->
<script type="text/javascript">
jQuery(document).ready(function ($) {

    jQuery( ".ead-tabs" ).click(function(event) {
        event.preventDefault();
        $('.ead-tabs').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        var tab = '#'+ $(this).data('tab');
        $(".tabs").hide();
        $(tab).show();
    });
    $( "#supportform" ).submit(function( event ) {
        event.preventDefault();
        $.ajax({
            type: "POST",
            url:"<?php echo get_option('home')?>/wp-admin/admin-ajax.php",
            dataType: 'json',
            data: {  action: 'supportform' , contact :   $("#supportform").serialize()},
            success: function(data)
            {
                supportmessage(data.status,data.message);
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                supportmessage(false,'Request failed');
            }
        }); 
    });
    function supportmessage(status,message){
        if(status){
            $('#embed_message').removeClass('awsm-error').addClass('awsm-updated');
            $('#embed_message p').html(message);
        } else{
            $('#embed_message').removeClass('awsm-updated').addClass('awsm-error');
            $('#embed_message p').html(message);
        }
    }
    suppportedlist();
    function suppportedlist(){
        var provider = '#ead_'+$('#ead_provider').val();
        $('.supportedlist').hide();
        $(provider).show();
    }
    $('#ead_provider').live('change', function(e) {
         suppportedlist();
    });
});
</script>