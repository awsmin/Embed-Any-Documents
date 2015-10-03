<?php

/**
 * Dropdown Builder
 *
 * @since   1.0
 * @return  String select html
 */
function ead_selectbuilder($name, $options, $selected = "", $class = "") {
    if (is_array($options)):
        echo "<select name=\"$name\" id=\"$name\" class=\"$class\">";
        foreach ($options as $key => $option) {
            echo "<option value=\"$key\"";
            if (!empty($helptext)) {
                echo " title=\"$helptext\"";
            }
            if ($key == $selected) {
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
    $size = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
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
function ead_sanitize_dims($dim) {
    
    // remove any spacing junk
    $dim = trim(str_replace(" ", "", $dim));
    
    if (!strstr($dim, '%')) {
        $type = "px";
        $dim = preg_replace("/[^0-9]*/", '', $dim);
    } else {
        $type = "%";
        $dim = preg_replace("/[^0-9]*/", '', $dim);
        if ((int)$dim > 100) {
            $dim = "100";
        }
    }
    
    if ($dim) {
        return $dim . $type;
    } else {
        return false;
    }
}

/**
 * Validate File url
 *
 * @since   1.0
 * @return  string Download link
 */
function ead_validateurl($url) {
    $types = ead_validmimeTypes();
    $url = esc_url($url, array('http', 'https'));
    $remote = wp_remote_head($url);
    $json['status'] = false;
    $json['message'] = '';
    if (wp_remote_retrieve_response_code($remote) == 200) {
        
        //Gzip Support
        $filename = pathinfo($url);
        $doctypes = ead_validmimeTypes();
        if (ead_validType($url, $doctypes)) {
            $json['status'] = true;
            $json['message'] = __("Done", 'embed-any-document');
            $json['file']['url'] = $url;
            if (isset($filename)) {
                $json['file']['filename'] = $filename['basename'];
            } else {
                $json['file']['filename'] = __("Document", 'embed-any-document');
            }
            if (!is_wp_error($filedata) && isset($filedata['headers']['content-length'])) {
                $json['file']['filesizeHumanReadable'] = ead_human_filesize($remote['headers']['content-length']);
            } else {
                $json['file']['filesizeHumanReadable'] = 0;
            }
        } else {
            $json['message'] = __("File format is not supported.", 'embed-any-document');
            $json['status'] = false;
        }
    } elseif (is_wp_error($remote)) {
        $json['message'] = $remote->get_error_message();
        $json['status'] = false;
    } else {
        $json['message'] = __('Sorry, the file URL is not valid.', 'embed-any-document');
        $json['status'] = false;
    }
    return $json;
}

/**
 * Validate google url
 *
 * @since   1.0
 * @return  string Download link
 */
function ead_ValidateDriveUrl($url) {
    $remote = wp_remote_head($url);
    $json['status'] = false;
    $json['message'] = '';
    if (wp_remote_retrieve_response_code($remote) == 200) {
        $json['message'] = __("Done", 'embed-any-document');
        $json['status'] = true;
    } else {
        $json['message'] = __('The document you have chosen is a not public.', 'embed-any-document') . __(' Only the owner and explicitly shared collaborators will be able to view it.', 'embed-any-document');
        $json['status'] = false;
    }
    return $json;
}
/**
 * Get Email node
 *
 * @since   1.0
 * @return  string email html
 */
function ead_getemailnode($emaildata, $postdata) {
    $emailhtml = "";
    foreach ($emaildata as $key => $label) {
        if ($postdata[$key]) {
            $emailhtml.= '<tr bgcolor="#EAF2FA">
        <td colspan="2"><font style="font-family:sans-serif;font-size:12px"><strong>' . $label . '</strong></font></td>
        </tr>
        <tr bgcolor="#FFFFFF">
        <td width="20">&nbsp;</td>
        <td><font style="font-family:sans-serif;font-size:12px">' . $postdata[$key] . '</font></td>
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
function ead_validmimeTypes() {
    include ('mime_types.php');
    return $mimetypes;
}

/**
 * Checks Url Validity
 *
 * @since   1.0
 * @return  boolean
 */
function ead_validType($url) {
    $doctypes = ead_validmimeTypes();
    if (is_array($doctypes)) {
        $allowed_ext = implode("|", array_keys($doctypes));
        if (preg_match("/\.($allowed_ext)$/i", $url)) {
            return true;
        }
    } else {
        return false;
    }
}

/**
 * Get allowed Mime Types
 *
 * @since   1.0
 * @return  string Mimetypes
 */
function ead_validembedtypes() {
    $doctypes = ead_validmimeTypes();
    return $allowedtype = implode(',', $doctypes);
}

/**
 * Get allowed Extensions
 *
 * @since   1.0
 * @return  string Extenstion
 */
function ead_validextensions($list = 'all') {
    include ('mime_types.php');
    return $allowedtype = implode(',', $extensions[$list]);
}

/**
 * Get allowed Mime Types for microsoft
 *
 * @since   1.0
 * @return  array Mimetypes
 */
function ead_microsoft_mimes() {
    $micro_mime = array(
                    'doc'               => 'application/msword',
                    'pot|pps|ppt'       => 'application/vnd.ms-powerpoint', 
                    'xla|xls|xlt|xlw'   => 'application/vnd.ms-excel', 
                    'docx'              => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                    'dotx'              => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template', 
                    'dotm'              => 'application/vnd.ms-word.template.macroEnabled.12', 
                    'xlsx'              => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
                    'xlsm'              => 'application/vnd.ms-excel.sheet.macroEnabled.12', 
                    'pptx'              => 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
                );
    return $micro_mime;
}

/**
 * Check Allow Download
 *
 * @since   1.0
 * @return  Boolean
 */
function ead_allowdownload($provider) {
    $blacklist = array('drive', 'box');
    if (in_array($provider, $blacklist)) {
        return false;
    } else {
        return true;
    }
}

/**
 * Get Active Menu Class
 *
 * @since   1.0
 * @return  string Class name
 */
function ead_getactiveMenu($tab, $needle) {
    if ($tab == $needle) {
        echo 'nav-tab-active';
    }
}