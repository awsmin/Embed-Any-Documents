<div id="embed-popup-wrap">
	<div id="embed-popup">
        <div id="embed_message">
        </div>
		<div id="popup-header">
		<h1>Embed Any Docs</h1>
		</div>
		<div class="section">
        <ul class="tabs">
            <li class="current">Upload &amp; Embed</li>
            <li>Add From URL</li>
        </ul>
        <form action="" onSubmit="return false" method="post" enctype="multipart/form-data" id="Docuploader">
        <div class="box visible">
        	<strong>Upload Document</strong><br/>
             <input type="button"  value="Add Document" class="button-primary" id="upload_doc"/> 
        </div>
        <div class="box">
         <strong>Add From URL</strong><br/>
            <input name="awsm_url" type="text" class="opt dwl" id="awsm_url" style="width:200px;"/><br/>
            <input type="button"  value="Add Url" class="button-primary" id="add_url"/>  
        </div>
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
        <tr>
            <td colspan="2">
                <br />
                <strong><?php _e('Shortcode Preview', 'ead'); ?></strong><br/>
                <textarea name="shortcode" style="width:100%" id="shortcode" readonly="readonly"></textarea>
            </td>
        </tr> 
        </table>
        <a class="button" id="adv_options">Show Advanced Options</a>
        <div class="advanced_options">
        <table>
            <tr>
                <td><label>Width</label> <input name="width"  class="embedval" id="ead_width" value="<?php echo get_option('ead_width', '100%' );?>"></td>
                <td><label>Height</label> <input name="height" class="embedval" id="ead_height"  value="<?php echo get_option('ead_height', '100%' );?>"></td>
            </tr>
            <tr>
            <td><label>Show Download Link</label> 
            <?php 
            $downoptions= array('alluser' => 'For all users','logged' => 'For Logged-in users','none' => 'None');
            selectbuilder('ead_download', $downoptions,esc_attr( get_option('ead_download')),'embed_download'); 
            ?> 
            </td>
            </tr>
        </table>
        </div>
        <div class="mceActionPanel">
        <div style="float: left">
            <input type="button" id="insert_doc" name="insert" class="button button-primary button-small" value="<?php _e('Insert', 'ead'); ?>" />
        </div>
        
        <div style="float: right">
            <input type="button" class="cancel_embed" name="cancel"  class="button button-small" value="<?php _e('Cancel', 'ead'); ?>" />
        </div>
        </div>
	</div>	
</div>