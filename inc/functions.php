<?php
/**
 * Dropdown Builder
 *
 * @since   1.0
 * @return  String select html
 */
function ead_selectbuilder( $name, $options,$selected="",$class="") {
    if(is_array($options)):
    echo "<select name=\"$name\" id=\"$name\" class=\"$class\">";
    foreach ($options as $key => $option) {
       echo "<option value=\"$key\"";
        if ( ! empty( $helptext ) ) {
            echo " title=\"$helptext\"";
        }
        if ( $key == $selected ) {
            echo ' selected="selected"';
        }
        echo ">$option</option>\n";
    }
    echo '</select>';
    else:

    endif;
}
/**
 * Human Readable filesize
 *
 * @since   1.0
 * @return  Human readable file size
 * @note    Replaces old gde_sanitizeOpts function
 */
function ead_human_filesize($bytes, $decimals = 2) {
    $size = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f ", $bytes / pow(1024, $factor)) . @$size[$factor];
}

/**
 * Sanitize dimensions (width, height)
 *
 * @since   1.0
 * @return  string Sanitized dimensions, or false if value is invalid
 * @note    Replaces old gde_sanitizeOpts function
 */
function ead_sanitize_dims( $dim ) {
    // remove any spacing junk
    $dim = trim( str_replace( " ", "", $dim ) );
    
    if ( ! strstr( $dim, '%' ) ) {
        $type = "px";
        $dim = preg_replace( "/[^0-9]*/", '', $dim );
    } else {
        $type = "%";
        $dim = preg_replace( "/[^0-9]*/", '', $dim );
        if ( (int) $dim > 100 ) {
            $dim = "100";
        }
    }
    
    if ( $dim ) {
        return $dim.$type;
    } else {
        return false;
    }
}
/**
 * Creates Download link 
 *
 * @since   1.0
 * @return  string Download link 
 */
function ead_getdownloadlink($url){
    $download = get_option('ead_download');
    $show =false;
    if($download=='alluser'){
        $show = true;
    }elseif($download=='logged' AND is_user_logged_in()){
        $show = true;
    }
    if($show){
    $filesize ="";
    $url = esc_url( $url, array( 'http', 'https' ));
    $filedata = wp_remote_head( $url );
    if(isset($filedata['headers']['content-length']))
    $filesize = ead_human_filesize($filedata['headers']['content-length']);
    echo '<p class="embed_download"><a href="'.$url.'" download>'.__('Download','ead'). ' ['.$filesize.']</a></p>';     
    }
}
/**
 * Validate File url
 *
 * @since   1.0
 * @return  string Download link 
 */
function ead_validateurl($url){
    $types  =   ead_validmimeTypes();
    $url    =   esc_url( $url, array( 'http', 'https' ));
    $remote =   wp_remote_head($url);
    $json['status']  =  false;
    $json['message'] =  '';
    if ( is_array( $remote ) ) {
            if ( isset( $remote['headers']['content-length'] ) ) {
                if(in_array($remote['headers']['content-type'], $types)){
                    $json['message'] = __("Done",'ead');
                    $filename = pathinfo($url);
                    if(isset($filename)){
                        $json['file']['filename'] = $filename['basename'];
                    }else{
                        $json['file']['filename'] =  __("Document",'ead');
                    }  
                    $json['file']['filesizeHumanReadable'] = ead_human_filesize($remote['headers']['content-length']);
                    $json['status'] =true;
                }else{
                    $json['message'] = __("Unsupported File Format",'ead');
                    $json['status'] = false;
                }
                
            } else {
                $json['message'] = __('Null Content','ead'); 
                $json['status'] =false;
            }
    }elseif(is_wp_error( $result )){
        $json['message'] = $result->get_error_message();  
        $json['status'] =false;
    }else{
        $json['message'] = __('File Not Found','ead'); 
        $json['status'] =false;
    }
     return $json;
}
/**
 * Get Provider url
 *
 * @since   1.0
 * @return  string iframe embed html
 */
function ead_getprovider($atts){
    $embed = "";
    $default_width      =       gde_sanitize_dims(  get_option('ead_width','100%') );
    $default_height     =       gde_sanitize_dims(  get_option('ead_height','500px') ); 
    extract(shortcode_atts( array(
            'url' => '',
            'width' =>  $default_width,
            'height' => $default_height,
            'language' => 'en'
        ), $atts ) );
    if($url):
    $provider=get_option('ead_provider','google');
    $url =  esc_url( $url, array( 'http', 'https' ));
    switch ($provider) {
        case 'google':
            $embedsrc = '//docs.google.com/viewer?url=%1$s&embedded=true&hl=%2$s';
            $iframe = sprintf( $embedsrc, 
                urlencode( $url ),
                esc_attr( $language )
            );
            break;
        case 'microsoft':
            $embed ='//view.officeapps.live.com/op/embed.aspx?src=%1$s';
            $iframe = sprintf( $embed, 
                urlencode( $url )
            );
            break;
    }
    $style = 'style="width:%1$s; height:%2$s; border: none;"';
    $stylelink = sprintf($style, 
                ead_sanitize_dims($width)  ,
                ead_sanitize_dims($height) 
            );
    $durl   = ead_getdownloadlink($url);
    $iframe = '<iframe src="'.$iframe.'" '.$stylelink.'></iframe>';
    $embed = $iframe.$durl;
    else:
    $embed = __('No Url Found','ead');     
    endif;
    return $embed;
}
/**
 * Get Email node
 *
 * @since   1.0
 * @return  string email html
 */
function ead_getemailnode($emaildata,$postdata){
    $emailhtml = "";
    foreach ($emaildata as $key => $label) {
    if($postdata[$key]){
    $emailhtml .= '<tr bgcolor="#EAF2FA">
        <td colspan="2"><font style="font-family:sans-serif;font-size:12px"><strong>'.$label.'</strong></font></td>
        </tr>
        <tr bgcolor="#FFFFFF">
        <td width="20">&nbsp;</td>
        <td><font style="font-family:sans-serif;font-size:12px">'.$postdata[$key] .'</font></td>
        </tr>';
    }
    }
    return $emailhtml; 
}
/**
 * Validate Source mime type
 *
 * @since   1.0
 * @return  boolean 
 */
function ead_validmimeTypes(){
    include('mime_types.php');
    return $mimetypes;
}
function ead_validType( $url ) {
    $doctypes=ead_validmimeTypes();
    if ( is_array( $doctypes ) ) {
        $allowed_ext = implode( "|", array_keys( $doctypes ) );
        if ( preg_match( "/\.($allowed_ext)$/i", $url ) ) {
            return true;
        }
    } else {
        return false;
    }
}
function ead_validembedtypes(){
    $doctypes=ead_validmimeTypes();
    return $allowedtype = implode(',',$doctypes); 

}
?>