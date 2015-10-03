<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?> 
<div id="embed-popup-wrap">
    <div id="embed-popup">
        <button title="Close" type="button" class="mfp-close">×</button>
        <div id="popup-header" class="ead-popup-header">
        <h1><?php _e('Add Document',$this->text_domain);?></h1>
        </div>
        <div class="ead-section">
            <div id="embed_message" class="awsm-error" style="display:none;"><p></p></div>
            <div class="ead_container">
            <form action="" onSubmit="return false" method="post" enctype="multipart/form-data" id="Docuploader">
            <ul class="ead-options">
                <li><a href="#" id="upload_doc"><span><img src="<?php echo $this->plugin_url;?>images/icon-upload.png" alt="Upload document" /><?php _e('Upload Document',$this->text_domain);?></span></a></li>
                <li><a href="#" id="addDocUrl"><span><img src="<?php echo $this->plugin_url;?>images/icon-link.png" alt="Add From URL" /><?php _e('Add from URL',$this->text_domain);?></span></a></li>
                <li><?php echo $this->providerlink('ead_dropbox','DropBoxUpload','Dropbox') ;?></li>
                <li><?php echo $this->providerlink(array('ead_drivekey','ead_driveClient'),'GooglePicker','Drive') ;?></li>
                <li><?php echo $this->providerlink('ead_box','boxPicker','Box') ;?></li>
            </ul>
            <div class="box addurl_box">
             <label for="awsm_url"><?php _e('Enter document URL',$this->text_domain);?></label>
                <input name="awsm_url" type="text" class="opt dwl input-group-text" placeholder="Eg: http://www.yoursite.com/file.pdf" id="awsm_url"/>
                <input type="button"  value="Add URL" class="ead-btn button-primary input-group-btn" id="add_url"/>  
                <div class="clear"></div>
                <a href="#" class="go-back">&larr; back</a> 
            </div>        
            </form>
            </div><!--ead_container-->
            <div class="upload-success">
                <div class="inner">
                <div class="uploaded-doccument">
                    <p id="ead_filename"></p>
                    <span id="ead_filesize"></span>
                </div>
                <div class="clear"></div>
                </div>
                <div class="advanced_options">
                    <h3><?php _e('Advanced Options',$this->text_domain);?></span></h3>
                <ul class="option-fields">
                    <li>
                        <div class="f-left"><label>Width</label> <input type="text" name="width"  class="embedval input-small" id="ead_width" value="<?php echo get_option('ead_width', '100%' );?>"></div>
                        <div class="f-left"><label>Height</label> <input type="text" name="height" class="embedval input-small" id="ead_height"  value="<?php echo get_option('ead_height', '500px' );?>"></div>
                        <div class="f-left" id="ead_downloadc">
                        <label><?php _e('Show Download Link',$this->text_domain);?></label> 
                        <?php 
                        $downoptions= array('all' => __('For all users',$this->text_domain),'logged' => __('For Logged-in users',$this->text_domain),'none' => __('No Download',$this->text_domain));
                        ead_selectbuilder('ead_download', $downoptions,esc_attr( get_option('ead_download')),'ead_usc'); 
                        ?> 
                        </div>
                        <div class="f-left last" id="new_provider">
                        <label><?php _e('Viewer',$this->text_domain);?></label> 
                        <?php 
                        $providers = array('google' => __('Google Docs Viewer',$this->text_domain),'microsoft' => __('Microsoft Office Online',$this->text_domain));
                        ead_selectbuilder('ead_provider', $providers,esc_attr( get_option('ead_provider','google')),'ead_usc'); 
                        ?> 
                        </div>
                        <div class="f-left last" id="ead_pseudo" style="display:none">
                        <label><?php _e('Viewer',$this->text_domain);?></label> 
                        <select name="ead_pseudo" disabled>
                            <option value="box">Box</option>
                            <option value="drive">Drive</option>
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
            <input type="button" id="insert_doc" name="insert" data-txt="<?php _e('Insert', $this->text_domain); ?>" data-loading="<?php _e('Loading...', $this->text_domain); ?>" class="ead-btn button button-primary button-medium" value="<?php _e('Insert', $this->text_domain); ?>" disabled/>
        </div>
        
        <div style="float: left">
            <input type="button" name="cancel"  class="ead-btn button cancel_embed button-medium" value="<?php _e('Cancel', $this->text_domain); ?>" />
        </div>
        <div class="clear"></div>
        </div>
     
    </div>
</div> 