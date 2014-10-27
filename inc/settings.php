<div class="wrap">
<h2>Embed Any Document Settings</h2>
<h2 class="nav-tab-wrapper">
    <a class="nav-tab nav-tab-active" href="#" data-tab="general"><?php _e( 'General', $this->text_domain); ?></a>
    <a class="nav-tab " href="#" data-tab="support"><?php _e( 'Support', $this->text_domain); ?></a>
</h2>
<div class="tabs visible" id="general">
<form method="post" action="options.php">
    <?php settings_fields( 'ead-settings-group' ); ?>
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
<div class="tabs" id="support">
    <div id="supportmessage"></div>
    <form id="supportform" action="">
    <table class="form-table">
            <tr valign="top">
            <th scope="row"><?php _e('Name', 'ead'); ?></th>
            <td>
              <input type="text" name="site_name" value="<?php echo get_option('blogname'); ?>"/>
            </td>
            </tr>
            <tr valign="top">
            <th scope="row"><?php _e('Email ID', 'ead'); ?></th>
            <td>
                <?php  $current_user = wp_get_current_user(); ?>
               <input type="email" name="email_id" value="<?php echo  $current_user->user_email; ?>"/>
            </td>
            </tr>
            <tr valign="top">
               <th scope="row"><?php _e('Problem', 'ead'); ?></th> 
               <td>
                <textarea name="problem"></textarea>
            </td>
            </tr>
            <tr valign="top">
            <td>
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Submit">
            </p>
            </td>
            </tr>
    </table>
    </form>
</div>
</div>
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