<?php
/*
  Plugin Name: Embed Any Document
  Plugin URI: http://awsm.in/embed-any-documents
  Description: Embed Any Document WordPress plugin lets you upload and embed your documents easily in your WordPress website without any additional browser plugins like Flash or Acrobat reader. The plugin lets you choose between Google Docs Viewer and Microsoft Office Online to display your documents. 
  Version: 2.2.5
  Author: Awsm Innovations
  Author URI: http://awsm.in
  License: GPL V3
  Text Domain: embed-any-document
  Domain Path: /language
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Awsm_embed {
	private static $instance = null;
	private $plugin_path;
	private $plugin_url;
	private $plugin_base;
	private $plugin_file;
	private $plugin_version;
	private $settings_slug;
    private $text_domain 		= 'embed-any-document';
	/**
	 * Creates or returns an instance of this class.
	 */
	public static function get_instance() {
		// If an instance hasn't been created and set to $instance create an instance and set it to $instance.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Initializes the plugin by setting localization, hooks, filters, and administrative functions.
	 */
	private function __construct() {

		$this->plugin_path 		=	plugin_dir_path( __FILE__ );
		$this->plugin_url  		=	plugin_dir_url( __FILE__ );
		$this->plugin_base  	=	dirname( plugin_basename( __FILE__ ) );
		$this->plugin_file  	=	__FILE__  ;
		$this->settings_slug	=	'ead-settings';
		$this->plugin_version	=	'2.2.5';

		add_action( 'media_buttons', array( $this, 'embedbutton' ),1000);

		add_shortcode( 'embeddoc', array( $this, 'embed_shortcode' ));

		//Admin Settings menu
		add_action( 'admin_menu', array( $this, 'admin_menu' ));
		add_action( 'admin_init', array( $this, 'register_eadsettings' ));
		//Add easy settings link
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ),array( $this, 'settingslink' ));
		//ajax Contact Form
 		add_action( 'wp_ajax_supportform',array( $this, 'supportform' ));
 		//default options
 		register_activation_hook($this->plugin_file, array( $this, 'defaults' ));
 		//Load plugin textdomain.
 		add_action( 'plugins_loaded',array( $this, 'load_textdomain' ) );

 		$this->run_plugin();
	}
	/**
	 * Register admin Settings style
	 */
	function setting_styles(){
		wp_register_style( 'embed-settings', plugins_url( 'css/settings.css', $this->plugin_file ), false, $this->plugin_version, 'all' );
		wp_enqueue_style('embed-settings');
	}
	/**
	 * Load plugin textdomain.
	 *
	 * @since 2.2.3
	 */
	function load_textdomain() {
	  load_plugin_textdomain( $this->text_domain, false, $this->plugin_base. '/language/' );
	}
	/**
	 * Embed any Docs Button
	 */
	public function embedbutton( $args = array() ) {
		// Check user previlage  
		if ( !current_user_can( 'edit_posts' )) return;
		// Prepares button target
		$target = is_string( $args ) ? $args : 'content';
		// Prepare args
		$args = wp_parse_args( $args, array(
				'target'    => $target,
				'text'      => __( 'Add Document',  $this->text_domain),
				'class'     => 'awsm-embed button',
				'icon'      => plugins_url( 'images/ead-small.png', __FILE__ ),
				'echo'      => true,
				'shortcode' => false
			) );
		// Prepare EAD icon
		if ( $args['icon'] ) $args['icon'] = '<img src="' . $args['icon'] . '" /> ';
		// Print button in media column
		$button = '<a href="javascript:void(0);" class="' . $args['class'] . '" title="' . $args['text'] . '" data-mfp-src="#embed-popup-wrap" data-target="' . $args['target'] . '" >' . $args['icon'] . $args['text'] . '</a>';
		// Show generator popup
		add_action( 'admin_footer', array($this, 'embedpopup' ) );
		// Request assets
		wp_enqueue_media();
		//Loads Support css and js
		$this->embed_helper();
		// Print/return result
		if ( $args['echo'] ) echo $button;
		return $button;
	}
	/**
	 * Admin Easy access settings link
	 */ 
	function settingslink( $links ) { 
		$settings_link = '<a href="options-general.php?page='.$this->settings_slug.'">' . __('Settings', $this->text_domain) . '</a>'; 
		array_unshift( $links, $settings_link ); 
		return $links; 
	}
	/**
	 * Embed Form popup
	 */ 
	function embedpopup(){
		add_thickbox();
		include( $this->plugin_path.'inc/popup.php' );
	}
	/**
     * Register admin scripts
     */
	function embed_helper(){
		wp_register_style( 'embed-css', plugins_url( 'css/embed.css', $this->plugin_file ), false, $this->plugin_version, 'all' );
		wp_register_script( 'embed', plugins_url( 'js/embed.js', $this->plugin_file ), array( 'jquery' ), $this->plugin_version, true );
		wp_localize_script('embed','emebeder', array(
				'height' 			=> 	get_option('ead_height', '100%'),
        		'width' 			=> 	get_option('ead_width', '100%'), 
        		'download' 			=> 	get_option('ead_download', 'none'), 
        		'text' 				=> 	get_option('ead_text', __('Download',$this->text_domain)), 
        		'provider' 			=> 	get_option('ead_provider', 'google'), 
				'ajaxurl' 			=> 	admin_url( 'admin-ajax.php' ),
				'validtypes' 		=> 	$this->validembedtypes(),
				'msextension' 		=> 	$this->validextensions('ms'), 
        		'drextension'		=> 	$this->validextensions('all'),
				'nocontent'			=> 	__('Nothing to insert', $this->text_domain),
				'invalidurl'		=> 	__('Invalid URL', $this->text_domain),
				'addurl'			=> 	__('Add URL', $this->text_domain),
				'verify'			=> 	__('Verifying...', $this->text_domain),
				'from_url'			=> 	__('From URL', $this->text_domain),
				'select_button'		=> 	__('Select', $this->text_domain),
				
			) );
		wp_enqueue_style('embed-css');
		wp_enqueue_script( 'embed' );
	}
	/**
     * Shortcode Functionality
     */
    function embed_shortcode($atts) {
        $embed 				= 		"";
        $durl 				= 		"";
        $default_width 		= 		$this->sanitize_dims(get_option('ead_width', '100%'));
        $default_height 	= 		$this->sanitize_dims(get_option('ead_height', '100%'));
        $default_provider 	= 		get_option('ead_provider', 'google');
        $default_download 	= 		get_option('ead_download', 'none');
        $default_text 		= 		get_option('ead_text', __('Download',$this->text_domain));
        $show = false;
        extract(shortcode_atts(array('url' 		=> '',
        							 'drive' 	=> '', 
        							 'width' 	=> $default_width,
        							 'height' 	=> $default_height, 
        							 'language' => 'en', 
        							 'text'		=> __($default_text,$this->text_domain),
        							 'viewer' 	=> $default_provider, 
        							 'download' => $default_download), $atts));
	    if(isset($atts['provider']))	
	    	$viewer		=	$atts['provider'];
	    if(!isset($atts['provider']) AND !isset($atts['viewer']))
	    	$viewer		= 	'google';
        if ($url):
            $filedata = wp_remote_head($url);
            $durl = '';
            $privatefile = '';
            if ($this->allowdownload($viewer)) if ($download == 'alluser' or $download == 'all') {
                $show = true;
            } elseif ($download == 'logged' AND is_user_logged_in()) {
                $show = true;
            }
            if ($show) {
                $filesize = 0;
                $url = esc_url($url, array('http', 'https'));
                if (!is_wp_error($filedata) && isset($filedata['headers']['content-length'])) {
				    $filesize = $this->human_filesize($filedata['headers']['content-length']);
				} else {
				    $filesize = 0;
				}
                $fileHtml = '';
                if ($filesize) $fileHtml = ' [' . $filesize . ']';
                $durl = '<p class="embed_download"><a href="' . $url . '" download >' . __($text, $this->text_domain) . $fileHtml . ' </a></p>';
            }
            
            $url = esc_url($url, array('http', 'https'));
            $providerList = array('google', 'microsoft');
            if (!in_array($viewer, $providerList)) $viewer = 'google';
            switch ($viewer) {
                case 'google':
                    $embedsrc = '//docs.google.com/viewer?url=%1$s&embedded=true&hl=%2$s';
                    $iframe = sprintf($embedsrc, urlencode($url), esc_attr($language));
                    break;

                case 'microsoft':
                    $embedsrc = '//view.officeapps.live.com/op/embed.aspx?src=%1$s';
                    $iframe = sprintf($embedsrc, urlencode($url));
                    break;
            }
           	$min_height   =   '';
            if($this->in_percentage($height)){
            	$min_height   =   'min-height:500px;';
            }
            if($this->check_responsive($height) AND $this->check_responsive($width)){
            	$iframe_style = 'style="width:100%; height:100%; border: none; position: absolute;left:0;top:0;"';
            	$doc_style	 	= 'style="position:relative;padding-top:90%;"';
            }else{
            	$iframe_style 		=  sprintf('style="width:%s; height:%s; border: none;'.$min_height.'"',esc_html($width),esc_html($height));
            	$doc_style	 	= 'style="position:relative;"';
            }
            
            $iframe = '<iframe src="' . $iframe . '" ' . $iframe_style . '></iframe>';
            $show = false;
            $embed = '<div class="ead-preview"><div class="ead-document" '.  $doc_style.'>' . $iframe . $privatefile . '</div>'.$durl.'</div>';
        else:
            $embed = __('No Url Found', $this->text_domain);
        endif;
        return $embed;
    }
 
	/**
     * Admin menu setup
     */
	public function admin_menu() {
        $eadsettings 	= 	add_options_page('Embed Any Document', 'Embed Any Document', 'manage_options',$this->settings_slug, array($this, 'settings_page'));
 		add_action( 'admin_print_styles-' . $eadsettings, array( $this,'setting_styles'));
    }
    public function settings_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        include($this->plugin_path.'inc/settings.php');
    }
    /**
     * Register Settings
     */
    function register_eadsettings() {
	    register_setting( 'ead-settings-group', 'ead_width' ,array( $this, 'sanitize_dims' ));
	    register_setting( 'ead-settings-group', 'ead_height',array( $this, 'sanitize_dims' ));
	    register_setting( 'ead-settings-group', 'ead_provider' );
	    register_setting( 'ead-settings-group', 'ead_download' );
	    register_setting( 'ead-settings-group', 'ead_text' );
	    register_setting( 'ead-settings-group', 'ead_mediainsert' );
	}
 
    /**
     * Plugin function
    */
    function run_plugin(){
    	$this->adminfunctions();
    }
    /**
     * Admin Functions init
    */
    function adminfunctions(){
    	if(is_admin()){
    		add_filter('upload_mimes', array($this,'additional_mimes'));
    	}
    }
	/**
     * Adds additional mimetype for meadi uploader
    */
	function additional_mimes($mimes){
		return array_merge($mimes,array (
            'svg' 		=>		'image/svg+xml',
            'ai'		=>		'application/postscript',
		));
	}
	/**
     * To get Overlay link
     */
    function providerlink( $provider) {
        $link = 'http://goo.gl/wJTQlc';
        $id = "";
        $configure = '<span class="overlay"><strong>' . __('Buy Pro Version', $this->text_domain) . '</strong><i></i></span>';
        $target = 'target="_blank"';
        echo '<a href="' . $link . '" id="' . $id . '" ' . $target . '><span><img src="' . $this->plugin_url . 'images/icon-' . strtolower($provider) . '.png" alt="'.sprintf( esc_html__( 'Add From %1$s', $this->text_domain ), $provider). '" />' . sprintf( esc_html__( 'Add From %1$s', $this->text_domain ), $provider) . $configure . '</span></a>';
    }
	/**
     * To initialize default options
    */
	function defaults()
		{
	    $o = array(
	        'ead_width'			=> '100%',
	        'ead_height'    	=> '100%',
	        'ead_download'  	=> 'none',
	        'ead_provider'      => 'google',
	        'ead_mediainsert'   => '1',
	    );
	    foreach ( $o as $k => $v )
	    {
	        if(!get_option($k)) update_option($k, $v);
	    }
	    return;
		}
	//Functions
 
	/**
	 * Dropdown Builder
	 *
	 * @since   1.0
	 * @return  String select html
	 */
	function selectbuilder($name, $options, $selected = "", $class = "") {
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
	function human_filesize($bytes, $decimals = 2) {
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
	function sanitize_dims($dim) {
	    
	    // remove any spacing junk
	    $dim = trim(str_replace(" ", "", $dim));
	    if(!$dim) $dim  = '100%';
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
	 * Check value in percentage
	 *
	 * @since   2.2.3
	 * @return  Int Dimenesion
	 */
	function in_percentage($dim){
		if (strstr($dim, '%')) {
			return true;
		}
		return false;
	}
	/**
	 * Enable Resposive
	 *
	 * @since   2.2.3
	 * @return  Boolean
	 */
	function check_responsive($dim){
		if (strstr($dim, '%')) {
			$dim = preg_replace("/[^0-9]*/", '', $dim);
			if ((int)$dim == 100) {
				return true;
			}
		}
		return false;
	}
 
	/**
	 * Validate Source mime type
	 *
	 * @since   1.0
	 * @return  boolean
	 */
	function validmime_types() {
	    include ('inc/mime_types.php');
	    return $mimetypes;
	}

	/**
	 * Checks Url Validity
	 *
	 * @since   1.0
	 * @return  boolean
	 */
	function valid_type($url) {
	    $doctypes = $this->validmime_types();
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
	function validembedtypes() {
	    $doctypes = $this->validmime_types();
	    return $allowedtype = implode(',', $doctypes);
	}

	/**
	 * Get allowed Extensions
	 *
	 * @since   1.0
	 * @return  string Extenstion
	 */
	function validextensions($list = 'all') {
	    include ('inc/mime_types.php');
	    return $allowedtype = implode(',', $extensions[$list]);
	}

	/**
	 * Get allowed Mime Types for microsoft
	 *
	 * @since   1.0
	 * @return  array Mimetypes
	 */
	function microsoft_mimes() {
	    $micro_mime = array(
	                    'doc'               => 'application/msword',
	                    'pot|pps|ppt'       => 'application/vnd.ms-powerpoint', 
	                    'xla|xls|xlt|xlw'   => 'application/vnd.ms-excel', 
	                    'docx'              => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
	                    'dotx'              => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template', 
	                    'dotm'              => 'application/vnd.ms-word.template.macroEnabled.12', 
	                    'xlsx'              => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
	                    'xlsm'              => 'application/vnd.ms-excel.sheet.macroEnabled.12', 
	                    'pptx'              => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
	                    'ppsx'              => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
	                );
	    return $micro_mime;
	}

	/**
	 * Check Allow Download
	 *
	 * @since   1.0
	 * @return  Boolean
	 */
	function allowdownload($provider) {
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
	function getactive_menu($tab, $needle) {
	    if ($tab == $needle) {
	        echo 'nav-tab-active';
	    }
	}
}

function embed_doc_activation(){
	if ( !defined( 'EAD_PLUS' ) ) {
		Awsm_embed::get_instance();
	}
}
function embed_doc_disable_self(){
	if ( defined( 'EAD_PLUS' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}
}
// Main WPtouch Pro activation hook
add_action( 'plugins_loaded', 'embed_doc_activation' );
add_action( 'admin_init', 'embed_doc_disable_self' );
