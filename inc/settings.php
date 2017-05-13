<?php if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly ?>
<div class="wrap">
    <h1 class="ead-title">Embed Any Document by AWSM.in</h1>
    <div class="row clearfix">
        <div class="ead-left-wrap">
            <div class="ead-tabs"> 
                <div class="tabs visible" id="general">
                    <h3><?php esc_html_e( 'General Settings', 'embed-any-document' ); ?></h3>
                    <form method="post" action="options.php">
                        <?php settings_fields( 'ead-settings-group' ); ?>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Default Size', 'embed-any-document' ); ?></th>
                                <td>
                                    <div class="f-left ead-frame-width"><?php esc_html_e( 'Width', 'embed-any-document' ); ?>
                                        <input type="text" class="small" name="ead_width" value="<?php echo esc_attr( get_option( 'ead_width', '100%' ) ); ?>"/>
                                    </div>
                                    <div class="f-left ead-frame-height">
                                        <?php esc_html_e( 'Height', 'embed-any-document' ); ?>
                                        <input type="text" class="small" name="ead_height" value="<?php echo esc_attr( get_option( 'ead_height', '100%' ) ); ?>"/>
                                    </div>
                                    <div class="clear"></div>
                                    <span class="note"><?php esc_html_e( 'Enter values in pixels or percentage (Example: 500px or 100%)', 'embed-any-document' ); ?></span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Show Download Link', 'embed-any-document' ); ?></th>
                                <td>
                                    <?php
                                    $downoptions = array( 'alluser' => __( 'For all users', 'embed-any-document' ), 'logged' => __( 'For Logged-in users', 'embed-any-document' ), 'none' => __( 'No Download', 'embed-any-document' ) );
                                    $this->selectbuilder( 'ead_download', $downoptions, esc_attr( get_option( 'ead_download', 'none' ) ) );
                                    ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e( 'Download Text', 'embed-any-document' ); ?></th>
                                <td>
                                    <input type="text" class="" name="ead_text" value="<?php echo esc_attr( get_option( 'ead_text', 'Download' ) ); ?>"/>
                                </td>
                            </tr>
                        </table>
                        <div class="ead-form-footer">
                            <?php submit_button(); ?>
                        </div>
                    </form>
                </div><!-- #general-->
            </div><!-- .ead-tabs -->
            <div class="ead-banner">
                <a href="http://goo.gl/wJTQlc" target="_blank"><img src="<?php echo esc_url( $this->plugin_url ); ?>images/eadplus-banner.png"></a>
            </div>
        </div><!-- .ead-left-wrap -->
        <div class="ead-right-wrap">
                <div class="ead-right-widget we-are-awsm">
                    <div class="awsm-branding">
                        <img src="<?php echo $this->plugin_url;?>/images/awsm-logo.png" width="67" height="67" alt="AWSM Innovations">
                        <div class="left-clear">
                            <h2><?php esc_html_e('Designed and developed by','embed-any-document-plus');?></h2>
                            <h3><a href="http://awsm.in/" target="_blank" title="awsm innovations">awsm innovations</a></h3>
                            <ul class="awsm-social">
                                <li><a href="https://www.facebook.com/awsminnovations" target="_blank" title="AWSM Innovations"><span class="awsm-icon awsm-icon-facebook"><?php esc_html_e('Facebook','embed-any-document-plus');?></span></a></li>
                                <li><a href="https://twitter.com/awsmin" target="_blank" title="AWSM Innovations"><span class="awsm-icon awsm-icon-twitter"><?php esc_html_e('Twitter','embed-any-document-plus');?></span></a></li>
                                <li><a href="https://github.com/awsmin" target="_blank" title="AWSM Innovations"><span class="awsm-icon awsm-icon-github"><?php esc_html_e('Github','embed-any-document-plus');?></span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- .ead-right-inner -->
                <div class="clearfix row-2 ead-right-widget">
                    <div class="col-2">
                        <a href="https://goo.gl/V2XQOM" target="_blank">
                   <img src="<?php echo $this->plugin_url;?>images/star.gif"><?php esc_html_e('Like the plugin?', 'embed-any-document-plus');?><br/><?php esc_html_e('Rate Now!', 'embed-any-document-plus');?>
                   </a>
                    </div>
                    <!-- .col-2 -->
                    <div class="col-2">
                        <a href="http://awsm.in/support" target="_blank">
                   <img src="<?php echo $this->plugin_url;?>images/ticket.gif"><?php esc_html_e('Need Help?', 'embed-any-document-plus');?><br/><?php esc_html_e('Open a Ticket', 'embed-any-document-plus');?>
                   </a>
                    </div>
                    <!-- .col-2 -->
                </div>
                <!-- .row -->
                <div class="ead-right-inner">
                    <h3><?php esc_html_e('Quick Help', 'embed-any-document-plus');?></h3>
                    <ol>
                        <li>
                            <a href="http://awsm.in/ead-plus-documentation/#embedding" target="_blank" title="<?php esc_html_e('How to Embed Documents?', 'embed-any-document-plus'); ?>">
                                <?php esc_html_e('How to Embed Documents?', 'embed-any-document-plus'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="http://awsm.in/ead-plus-documentation/#viewers" target="_blank" title="<?php esc_html_e('About Viewers', 'embed-any-document-plus'); ?>">
                                <?php esc_html_e('About Viewers', 'embed-any-document-plus'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="http://awsm.in/ead-plus-documentation/#shortcode" target="_blank" title="<?php esc_html_e('Shortcode & Attributes', 'embed-any-document-plus'); ?>">
                                <?php esc_html_e('Shortcode & Attributes', 'embed-any-document-plus'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="http://awsm.in/support" target="_blank" title="<?php esc_html_e('FAQs', 'embed-any-document-plus'); ?>">
                                <?php esc_html_e('FAQs', 'embed-any-document-plus'); ?>
                            </a>
                        </li>
                    </ol>
                </div>
                <!-- .ead-right-inner -->
        </div>
        <!-- .ead-right-wrap -->
    </div>
</div><!-- .wrap -->