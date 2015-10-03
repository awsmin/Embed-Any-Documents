<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div class="wrap">
    <h2 class="ead-title"><?php _e('Embed Any Document by AWSM.in',$this->text_domain);?></h2>
    <div class="ead-left-wrap">
        
        <div class="tabs visible" id="general">
            <h3>General Settings</h3>
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
                <div class="ead-form-footer">
                <?php submit_button(); ?>
                </div>
            </form>
        </div><!-- #general-->
        <div class="ead-banner">
            <a href="http://goo.gl/wJTQlc" target="_blank"><img src="<?php echo $this->plugin_url;?>images/eadplus-banner.png"></a>
        </div>
    </div><!-- .ead-left-wrap -->
    <div class="ead-right-wrap">
        <div class="ead-right-inner">
        <a href="http://awsm.in" target="_blank" title="AWSM Innovations"><img src="http://awsm.in/innovations/ead/logo2.jpg" alt="AWSM!"></a>
    <ul class="awsm-social">
        <li><a href="https://www.facebook.com/awsminnovations" target="_blank" title="AWSM Innovations"><span class="awsm-icon awsm-icon-facebook">Facebook</span></a></li>
        <li><a href="https://twitter.com/awsmin" target="_blank" title="AWSM Innovations"><span class="awsm-icon awsm-icon-twitter">Twitter</span></a></li>
        <li><a href="https://github.com/awsmin" target="_blank" title="AWSM Innovations"><span class="awsm-icon awsm-icon-github">Github</span></a></li>
        <li><a href="https://www.behance.net/awsmin" target="_blank" title="AWSM Innovations"><span class="awsm-icon awsm-icon-behance">Behance</span></a></li>
    </ul>
    </div>
    <div class="clearfix row-col">
        <div class="col-2">
            <a href="https://wordpress.org/support/view/plugin-reviews/embed-any-document#postform" target="_blank">
                <img src="<?php echo $this->plugin_url;?>images/star.gif"><?php _e('Like the plugin?', $this->text_domain);?><br/><?php _e('Rate Now!', $this->text_domain);?>
            </a>
        </div>
        <div class="col-2">
            <a href="http://awsm.in/support" target="_blank">
                <img src="<?php echo $this->plugin_url;?>images/ticket.gif"><?php _e('Need Help?', $this->text_domain);?><br/><?php _e('Open a Ticket', $this->text_domain);?>
            </a>
        </div>
    </div>
    <div class="ead-right-inner">
    <h3>More Links</h3>
    <ol>
            <li><a href="http://awsm.in/ead-plus-documentation/#embedding" target="_blank" title="How to Embed Documents?"><?php _e('How to Embed Documents?', $this->text_domain); ?></a></li>
            <li><a href="http://awsm.in/ead-plus-documentation/#viewers" target="_blank" title="About Viewers"><?php _e('About Viewers', $this->text_domain); ?></a></li>
            <li><a href="http://awsm.in/ead-plus-documentation/#shortcode" target="_blank" title="Shortcode & Attributes"><?php _e('Shortcode & Attributes', $this->text_domain); ?></a></li>
            <li><a href="http://awsm.in/support" target="_blank" title="FAQs"><?php _e('FAQs', $this->text_domain); ?></a></li>
    </ol>
    </div>
    </div><!-- .ead-right-wrap -->
    <div class="clear"></div>
</div><!-- .wrap -->