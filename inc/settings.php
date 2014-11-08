<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div class="wrap">
    <h2 class="ead-title"><?php _e('Embed Any Document by AWSM.in',$this->text_domain);?></h2>
    <h2 class="nav-tab-wrapper">
            <a class="nav-tab nav-tab-active" href="#" data-tab="general"><?php _e( 'General Settings', $this->text_domain); ?></a>
            <a class="nav-tab " href="#" data-tab="support"><?php _e( 'Support', $this->text_domain); ?></a>
        </h2>
    <div class="ead-left-wrap">
        
        <div class="tabs visible" id="general">
            <form method="post" action="options.php">
                <?php settings_fields( 'ead-settings-group' ); ?>
                <table class="form-table">
                    <tr valign="top">
                    <th scope="row"><?php _e('Embed Using',$this->text_domain);?></th>
                    <td>
                       <?php 
                        $providers= array('google' => __('Google Docs Viewer',$this->text_domain),'microsoft' => __('Microsoft Office Online',$this->text_domain));
                        ead_selectbuilder('ead_provider', $providers,esc_attr( get_option('ead_provider','google'))); 
                        ?> 
                        <div class="ead_supported">
                        <span><?php _e('Supported file formats',$this->text_domain);?></span>
                        <div class="supportedlist hidden" id="ead_google">
                            <ul>
                                <li>Microsoft Word (docx, docm, dotm, dotx)</li>
                                <li>Microsoft Excel (xlsx, xlsb, xls, xlsm)</li>
                                <li>Microsoft PowerPoint (pptx, ppsx, ppt, pps, pptm, potm, ppam, potx, ppsm)</li>
                                <li>Adobe Portable Document Format (pdf)</li>
                                <li>Text files (txt)</li>    
                            </ul>
                        </div>
                        <div class="supportedlist hidden" id="ead_microsoft">
                            <ul>
                                <li>Microsoft Word (docx, docm, dotm, dotx)</li>
                                <li>Microsoft Excel (xlsx, xlsb, xls, xlsm)</li>
                                <li>Microsoft PowerPoint (pptx, ppsx, ppt, pps, pptm, potm, ppam, potx, ppsm)</li>
                            </ul>
                        </div>
                        </div>
                    </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Default Size', $this->text_domain); ?></th>
                        <td> 
                         <div class="f-left ead-frame-width"><?php _e('Width', $this->text_domain); ?>    
                         <input type="text" class="small" name="ead_width"  value="<?php echo esc_attr( get_option('ead_width','100%') ); ?>" />   
                         </div>
                         <div class="f-left ead-frame-height">
                         <?php _e('Height', $this->text_domain); ?> 
                         <input type="text" class="small" name="ead_height" value="<?php echo esc_attr( get_option('ead_height','300px') ); ?>" />
                        </div>
                        <div class="clear"></div>
                        <span class="note"><?php _e('Enter values in pixels or percentage (Example: 300px or 100%)', $this->text_domain); ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                    <th scope="row"><?php _e('Show Download Link',$this->text_domain);?></th>
                    <td>
                       <?php 
                        $downoptions= array('alluser' => __('For all users',$this->text_domain),'logged' => __('For Logged-in users',$this->text_domain),'none' => __('None',$this->text_domain));
                        ead_selectbuilder('ead_download', $downoptions,esc_attr( get_option('ead_download'))); 
                        ?> 
                    </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div><!-- #general-->
        <div class="tabs" id="support">
            <div id="embed_message"><p></p></div>
            <form id="supportform" action="">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('Name', $this->text_domain); ?><span class="required">*</span></th>
                        <td>
                             <?php  $current_user = wp_get_current_user();   ?>
                            <input type="text" name="site_name"  value="<?php echo  $current_user->display_name; ?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Email ID', $this->text_domain); ?><span class="required">*</span></th>
                        <td>
                           
                            <input type="email" name="email_id" value="<?php echo  $current_user->user_email; ?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Problem', $this->text_domain); ?><span class="required">*</span></th> 
                        <td>
                        <textarea name="problem"></textarea>
                    </td>
                    </tr>
                    <tr valign="top">
                        <td>
                        <p class="submit">
                            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Submit', $this->text_domain); ?>">
                        </p>
                        </td>
                    </tr>
                </table>
            </form>
        </div><!-- #support-->
    </div><!-- .ead-left-wrap -->
    <div class="ead-right-wrap">
        <a href="http://awsm.in" target="_blank" title="AWSM Innovations"><img src="http://awsm.in/innovations/ead/logo.png" alt="AWSM!"></a>
    <div class="author-info">
        This plugin is developed <br/>by <a href="http://awsm.in" target="_blank" title="AWSM Innovations">AWSM Innovations.</a>
    </div><!-- .author-info -->
    <ul class="awsm-social">
        <li><a href="https://www.facebook.com/awsminnovations" target="_blank" title="AWSM Innovations"><span class="awsm-icon awsm-icon-facebook">Facebook</span></a></li>
        <li><a href="https://twitter.com/awsmin" target="_blank" title="AWSM Innovations"><span class="awsm-icon awsm-icon-twitter">Twitter</span></a></li>
        <li><a href="https://github.com/awsmin" target="_blank" title="AWSM Innovations"><span class="awsm-icon awsm-icon-github">Github</span></a></li>
        <li><a href="https://www.behance.net/awsmin" target="_blank" title="AWSM Innovations"><span class="awsm-icon awsm-icon-behance">Behance</span></a></li>
        <li><a href="#" target="_blank" title="AWSM Innovations"><span class="awsm-icon awsm-icon-dribbble">Dribble</span></span></a></li>
    </ul>
    <div class="paypal">
        <p>Liked the plugin? You can support our Open Source projects with a donation</p>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="YX8V3PJR65YRN">
    <input type="text" name="amount" value="10" class="small">
    <input type="hidden" name="currency_code" value="USD">
    <input type="image" class="donate-btn" src="<?php echo $this->plugin_url . 'images/donate.gif';?>" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
    <div class="clear"></div>
    <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
    </form>
    </div>
    </div><!-- .ead-right-wrap -->
    <div class="clear"></div>
</div><!-- .wrap -->
<script type="text/javascript">
jQuery(document).ready(function ($) {

    jQuery( ".nav-tab" ).click(function(event) {
        event.preventDefault();
        $('.nav-tab').removeClass('nav-tab-active');
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