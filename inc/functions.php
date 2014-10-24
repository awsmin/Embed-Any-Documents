<?php
/**
 * Dropdown Builder
 *
 * @since   1.0
 * @return  String select html
 */
function selectbuilder( $name, $options,$selected="",$class="") {
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
function human_filesize($bytes, $decimals = 2) {
    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
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
function sanitize_dims( $dim ) {
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
function getdownloadlink($url){
    $download = get_option('ead_download');
    $show =false;
    if($download=='alluser'){
        $show = true;
    }elseif($download=='logged' AND is_user_logged_in()){
        $show = true;
    }
    if($show){
    $filesize ="";
    $durl = esc_url( $url, array( 'http', 'https' ));
    $filedata = wp_remote_head( $url );
    if(isset($filedata['headers']['content-length']))
    $filesize = human_filesize($filedata['headers']['content-length']);
    echo '<p class="embed_download"><a href="'.$url.'" download>Download ['.$filesize.']</a></p>';     
    }
}
/**
 * Validate File url
 *
 * @since   1.0
 * @return  string Download link 
 */
function validateurl($url){
    $types = array(
    // ext      =>  mime_type
    "ai"        =>  "application/postscript",
    "doc"       =>  "application/msword",
    "docx"      =>  "application/vnd.openxmlformats-officedocument.wordprocessingml",
    "dxf"       =>  "application/dxf",
    "eps"       =>  "application/postscript",
    "otf"       =>  "font/opentype",
    "pages"     =>  "application/x-iwork-pages-sffpages",
    "pdf"       =>  "application/pdf",
    "pps"       =>  "application/vnd.ms-powerpoint",
    "ppt"       =>  "application/vnd.ms-powerpoint",
    "pptx"      =>  "application/vnd.openxmlformats-officedocument.presentationml",
    "ps"        =>  "application/postscript",
    "psd"       =>  "image/photoshop",
    "rar"       =>  "application/rar",
    "svg"       =>  "image/svg+xml",
    "tif"       =>  "image/tiff",
    "tiff"      =>  "image/tiff",
    "ttf"       =>  "application/x-font-ttf",
    "xls"       =>  "application/vnd.ms-excel",
    "xlsx"      =>  "application/vnd.openxmlformats-officedocument.spreadsheetml",
    "xps"       =>  "application/vnd.ms-xpsdocument",
    "zip"       =>  "application/zip"
    );
    $remote = wp_remote_head( $url );
    $json['status'] =false;
    $json['message'] = '';
    if ( is_array( $remote ) ) {
            if ( isset( $remote['headers']['content-length'] ) ) {
                $json['response']['filesize'] = $remote['headers']['content-length'];
                //if(in_array($remote['headers']['content-type'], $types)){
                    $json['message'] = "Done";
                    $json['status'] =true;
               // }else{
                    //$json['message'] = "Unsupported File Format";
                    //$json['status'] = false;
                //}
                
            } else {
                $json['message'] = 'Null Content'; 
                $json['status'] =false;
            }
    }elseif(is_wp_error( $result )){
        $json['message'] = $result->get_error_message(); 
        $json['status'] =false;
    }else{
        $json['message'] = 'File Not Found'; 
        $json['status'] =false;
    }
     return $json;
}
/**
 * Get Provider url
 *
 * @since   1.0
 * @return  string of service provider
 */
function getprovider($atts){

    extract(shortcode_atts( array(
            'url' => '',
            'width' => '100%',
            'height' => '100%',
            'language' => 'en'
        ), $atts ) );
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
                sanitize_dims($width)  ,
                sanitize_dims($height) 
            );
    return '<iframe src="'.$iframe.'" '.$stylelink.'></iframe>';
}
?>