<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div class="wrap">
    <h2 class="ead-title"><?php _e('Embed Any Document by AWSM.in',$this->text_domain);?></h2>
    <div class="ead-left-wrap">
        <h2 class="nav-tab-wrapper">
            <a class="nav-tab nav-tab-active" href="#" data-tab="general"><?php _e( 'General Settings', $this->text_domain); ?></a>
            <a class="nav-tab " href="#" data-tab="support"><?php _e( 'Support', $this->text_domain); ?></a>
        </h2>
        <div class="tabs visible" id="general">
            <form method="post" action="options.php">
                <?php settings_fields( 'ead-settings-group' ); ?>
                <table class="form-table">
                    <tr valign="top">
                    <th scope="row"><?php _e('Embed Using',$this->text_domain);?></th>
                    <td>
                       <?php 
                        $providers= array('google' => __('Google Docs Viewer',$this->text_domain),'microsoft' => __('Microsoft Office Online',$this->text_domain));
                        ead_selectbuilder('ead_provider', $providers,esc_attr( get_option('ead_provider'))); 
                        ?> 
                    </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Default Size', $this->text_domain); ?></th>
                        <td> 
                         <?php _e('Width', $this->text_domain); ?>    
                         <input type="text" name="ead_width"  value="<?php echo esc_attr( get_option('ead_width','100%') ); ?>" />   
                         &nbsp;&nbsp;&nbsp;&nbsp;
                         <?php _e('Height', $this->text_domain); ?> 
                         <input type="text" name="ead_height" value="<?php echo esc_attr( get_option('ead_height','100%') ); ?>" />
                        <br/>
                        <span class="note"><?php _e('Enter values in pixels or percentage (example:248px or 100%)', $this->text_domain); ?></span>
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
            <div id="supportmessage" class="alert"></div>
            <form id="supportform" action="">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('Name', $this->text_domain); ?><span class="required">*</span></th>
                        <td>
                            <input type="text" name="site_name"  value="<?php echo get_option('blogname'); ?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Email ID', $this->text_domain); ?><span class="required">*</span></th>
                        <td>
                            <?php  $current_user = wp_get_current_user(); ?>
                            <input type="email" name="email_id" value="<?php echo  $current_user->user_email; ?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Shortcode', $this->text_domain); ?></th>
                        <td>
                            <input type="text" name="sc" placeholder="[embedall url=&quot;...&quot;]" value=""/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Embed Url', $this->text_domain); ?></th>
                        <td>
                            <input type="text" name="eurl" placeholder="http://.." value=""/>
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
    <div clss="ead-right-wrap">
        <img src="http://awsm.in/innovations/ead/logo.png" alt="AWSM!">
<div class="author-info">
    This plugin is developed <br/>by <a href="http://awsm.in" target="_blank" title="AWSM Innovations">AWSM Innovations.</a>
</div><!-- .author-info -->
<ul class="awsm-social">
    <li><a href="#" title="AWSM Innovations"><span class="genericon genericon-facebook"></span></a></li>
    <li><a href="#" title="AWSM Innovations"><span class="genericon genericon-twitter"></span></a></li>
    <li><a href="#" title="AWSM Innovations"><span class="genericon genericon-github"></span></a></li>
    <li><a href="#" title="AWSM Innovations"><span class="genericon genericon-dribbble"></span></span></a></li>
</ul>

    </div><!-- .ead-right-wrap -->
    <div class="clear"></div>

</div><!-- .wrap -->
<script type="text/javascript">
jQuery(document).ready(function ($) {
    jQuery( ".nav-tab" ).click(function() {
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
            $('#supportmessage').addClass('success').html(message);
        } else{
            $('#supportmessage').addClass('failed').html(message);
        }
    }
});
</script>