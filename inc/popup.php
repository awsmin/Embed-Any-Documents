<?php if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?> 
<div id="embed-popup-wrap">
    <div id="embed-popup">
        <button title="<?php _e('Close',$this->text_domain);?>" type="button" class="mfp-close">×</button>
        <div id="popup-header" class="ead-popup-header">
        <h1><?php _e('Add Document',$this->text_domain);?></h1>
        </div>
        <div class="ead-section">
            <div id="embed-message" class="awsm-error" style="display:none;"><p></p></div>
            <div class="ead-container">
            <form action="" onSubmit="return false" method="post" enctype="multipart/form-data" id="docuploader">
            <ul class="ead-options">
                <li><a href="#" id="upload-doc"><span><img src="<?php echo $this->plugin_url;?>images/icon-upload.png" alt="Upload document" /><?php _e('Upload Document',$this->text_domain);?></span></a></li>
                <li><a href="#" id="add-ead-document"><span><img src="<?php echo $this->plugin_url;?>images/icon-link.png" alt="Add From URL" /><?php _e('Add from URL',$this->text_domain);?></span></a></li>
                <li><?php echo $this->providerlink('Drive') ;?></li>
                <li><?php echo $this->providerlink('Dropbox') ;?></li>
                <li><?php echo $this->providerlink('Box') ;?></li>
            </ul>
            <div class="box addurl-box">
             <label for="awsm-url"><?php _e('Enter document URL',$this->text_domain);?></label>
                <input name="awsm-url" type="text" class="opt dwl input-group-text" placeholder="Eg: http://www.yoursite.com/file.pdf" id="awsm-url"/>
                <input type="button"  value="<?php _e('Add URL',$this->text_domain);?>" class="ead-btn button-primary input-group-btn" id="ead-add-url"/>  
                <div class="clear"></div>
                <a href="#" class="go-back">&larr; <?php _e('back',$this->text_domain);?></a> 
            </div>        
            </form>
            </div><!--ead-container-->
            <div class="upload-success">
                <div class="inner">
                <div class="uploaded-doccument">
                    <p id="ead-filename"></p>
                    <span id="ead-filesize"></span>
                </div>
                <div class="clear"></div>
                </div>
                <div class="advanced-options">
                    <h3><?php _e('Advanced Options',$this->text_domain);?></span></h3>
                <ul class="option-fields">
                    <li>
                        <div class="f-left"><label><?php _e('Width',$this->text_domain);?></label> <input type="text" name="width"  class="embedval input-small" id="ead-width" value="<?php echo get_option('ead-width', '100%' );?>"></div>
                        <div class="f-left"><label><?php _e('Height',$this->text_domain);?></label> <input type="text" name="height" class="embedval input-small" id="ead-height"  value="<?php echo get_option('ead_height', '100%' );?>"></div>
                        <div class="f-left" id="ead-downloadc">
                        <label><?php _e('Show Download Link',$this->text_domain);?></label> 
                        <?php 
                        $downoptions= array('all' => __('For all users',$this->text_domain),'logged' => __('For Logged-in users',$this->text_domain),'none' => __('No Download',$this->text_domain));
                        $this->selectbuilder('ead-download', $downoptions,esc_attr( get_option('ead_download')),'ead-usc'); 
                        ?> 
                        </div>
                        <div class="f-left" id="ead-download-text">
                        <label><?php _e('Download Text',$this->text_domain);?></label> 
                        <input type="text" name="text"  class="embedval" id="ead-text" value="<?php echo get_option('ead_text', 'Download' );?>"> 
                        </div>
                        <div class="f-left last" id="new-provider">
                        <label><?php _e('Viewer',$this->text_domain);?></label> 
                        <?php 
                        $providers = array('google' => __('Google Docs Viewer',$this->text_domain),'microsoft' => __('Microsoft Office Online',$this->text_domain));
                        $this->selectbuilder('ead-provider', $providers,esc_attr( get_option('ead_provider','google')),'ead-usc'); 
                        ?> 
                        </div>
                        <div class="f-left last" id="ead-pseudo" style="display:none">
                        <label><?php _e('Viewer',$this->text_domain);?></label> 
                        <select name="ead-pseudo" disabled>
                            <option value="box"><?php _e('Box',$this->text_domain);?></option>
                            <option value="drive"><?php _e('Drive',$this->text_domain);?></option>
                        </select> 
                        </div>
                        <div class="clear"></div>
                    </li>
              
                     <li>
                        <label><?php _e('Shortcode Preview',$this->text_domain); ?></label>
                        <textarea name="shortcode" style="width:100%" id="shortcode" readonly="readonly"></textarea>
                    </li>            
                </ul>
            </div>
        </div>
       </div> 
        <div class="mceActionPanel ead-action-panel">
        <div style="float: right">
            <input type="button" id="insert-doc" name="insert" data-txt="<?php _e('Insert', $this->text_domain); ?>" data-loading="<?php _e('Loading...', $this->text_domain); ?>" class="ead-btn button button-primary button-medium" value="<?php _e('Insert', $this->text_domain); ?>" disabled/>
        </div>
        
        <div style="float: left">
            <input type="button" name="cancel"  class="ead-btn button cancel-embed button-medium" value="<?php _e('Cancel', $this->text_domain); ?>" />
        </div>
        <div class="clear"></div>
        </div>
     
    </div>
</div> 