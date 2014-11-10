<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?> 
<div id="embed-popup-wrap">
    <div id="embed-popup">
        <button title="Close (Esc)" type="button" class="mfp-close">×</button>
        <div id="popup-header">
        <h1><?php _e('Add Document','ead');?></h1>
        </div>
        <div class="section">
            <div id="embed_message" class="awsm-error" style="display:none;"><p></p></div>
            <div class="ead_container">
            <ul class="tabs">
                <li class="current"><?php _e('Upload and Embed','ead');?></li>
                <li><?php _e('Add From URL','ead');?></li>
            </ul>
            <form action="" onSubmit="return false" method="post" enctype="multipart/form-data" id="Docuploader">
            <div class="box visible">
                 <div class="text-center"><input type="button"  value="Upload Document" class="ead-btn button-primary button-large" id="upload_doc"/></div> 
            
            </div>
            <div class="box">
             <label for="awsm_url"><?php _e('Enter document URL','ead');?></label>
                <input name="awsm_url" type="text" class="opt dwl input-group-text" id="awsm_url"/>
                <input type="button"  value="Add URL" class="ead-btn button-primary input-group-btn" id="add_url"/>  
            </div>            
            </form>
            </div><!--ead_container-->
            <div class="upload-success">
                <div class="inner">
                <div class="uploaded-doccument">
                    <p id="ead_filename"></p>
                    <span id="ead_filesize"></span>
                </div>
                <a class="ead-btn button" id="adv_options"><?php _e('Advanced Options','ead');?></a>
                <div class="clear"></div>
            </div>
                     <div class="advanced_options">
            <ul class="option-fields">
              
                <li>
                    <div class="f-left"><label>Width</label> <input type="text" name="width"  class="embedval input-small" id="ead_width" value="<?php echo get_option('ead_width', '100%' );?>"></div>
                    <div class="f-left middle"><label>Height</label> <input type="text" name="height" class="embedval input-small" id="ead_height"  value="<?php echo get_option('ead_height', '100%' );?>"></div>
                    <div class="f-left">
                    <label><?php _e('Show Download Link','ead');?></label> 
                    <?php 
                    $downoptions= array('alluser' => __('For all users',$this->text_domain),'logged' => __('For Logged-in users',$this->text_domain),'none' => __('None',$this->text_domain));
                    ead_selectbuilder('ead_download', $downoptions,esc_attr( get_option('ead_download')),'embed_download'); 
                    ?> 
                    </div>
                    <div class="clear"></div>
                </li>
              
                 <li>
                    <label><?php _e('Shortcode Preview', 'ead'); ?></label>
                    <textarea name="shortcode" style="width:100%" id="shortcode" readonly="readonly"></textarea>
                </li>            
                </ul>
            </div>
            </div>
       
        <div class="mceActionPanel">
        <div style="float: right">
            <input type="button" id="insert_doc" name="insert" class="ead-btn button button-primary button-medium" value="<?php _e('Insert', 'ead'); ?>" disabled/>
        </div>
        
        <div style="float: left">
            <input type="button" name="cancel"  class="ead-btn button cancel_embed button-medium" value="<?php _e('Cancel', 'ead'); ?>" />
        </div>
        <div class="clear"></div>
        </div>
    </div>  
    </div>
</div>