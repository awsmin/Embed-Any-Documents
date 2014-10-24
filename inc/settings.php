<div class="wrap">
<h2>Embed Any Document Settings</h2>
<h2 class="nav-tab-wrapper">
 <a class="nav-tab nav-tab-active"   href="#">Basic Setting</a>
 <a class="nav-tab"   href="#">Support</a> 
</h2>
<form method="post" action="options.php">
    <?php settings_fields( 'ead-settings-group' ); ?>
    <?php do_settings_sections( 'ead-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Embed Using</th>
        <td>
           <?php 
            $providers= array('google' => 'Google Docs Viewer','microsoft' => 'Microsoft Office Online');
            selectbuilder('ead_provider', $providers,esc_attr( get_option('ead_provider'))); 
            ?> 
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">Embed Style</th>
        <td>
            <?php 
            $themes= array('flat' => 'Flat','dark' => 'Dark','light' => 'Light');
            selectbuilder('ead_theme', $themes,esc_attr( get_option('ead_theme') )); 
            ?>
        </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Default Size', 'ead'); ?></th>
            <td> 
             <?php _e('Width', 'ead'); ?>    
             <input type="text" name="ead_width"  value="<?php echo esc_attr( get_option('ead_width','100%') ); ?>" />   
             &nbsp;&nbsp;&nbsp;&nbsp;
             <?php _e('Height', 'ead'); ?> 
             <input type="text" name="ead_height" value="<?php echo esc_attr( get_option('ead_height','100%') ); ?>" />
            <br/>
            <span class="note"><?php _e('Enter values in pixels or percentage (example:248px or 100%)', 'ead'); ?></span>
            </td>
        </tr>
        <tr valign="top">
        <th scope="row">Show Download Link</th>
        <td>
           <?php 
            $downoptions= array('alluser' => 'For all users','logged' => 'For Logged-in users','none' => 'None');
            selectbuilder('ead_download', $downoptions,esc_attr( get_option('ead_download'))); 
            ?> 
        </td>
        </tr>
    </table>
    <?php submit_button(); ?>
</form>
</div>