<?php
/*
  Plugin Name: Embed Any Document
  Plugin URI: http://awsm.in/embed-any-documents
  Description: Embed Any Document WordPress plugin lets you upload and embed your documents easily in your WordPress website without any additional browser plugins like Flash or Acrobat reader. The plugin lets you choose between Google Docs Viewer and Microsoft Office Online to display your documents. 
  Version: 2.2.1
  Author: Awsm Innovations
  Author URI: http://awsm.in
  License: GPL V3
  Text Domain: embed-any-document
  Domain Path: /language
 */
require_once( dirname( __FILE__ ) . '/inc/functions.php');
class Awsm_embed {
	private static $instance = null;
	private $plugin_path;
	private $plugin_url;
	private $plugin_base;
	private $plugin_file;
	private $plugin_version;
	private $settings_slug;
    private $text_domain = 'embed-any-document';
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
		$this->plugin_version	=	'2.2.1';

		load_plugin_textdomain($this->text_domain, false,$this->plugin_base . '/language' );

		add_action( 'media_buttons', array( $this, 'embedbutton' ),1000);

		add_shortcode( 'embeddoc', array( $this, 'embed_shortcode'));

		//Admin Settings menu
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action( 'admin_init', array($this, 'register_eadsettings'));
		//Add easy settings link
		add_filter( "plugin_action_links_" . plugin_basename( __FILE__ ),array($this, 'settingslink'));
		//ajax validate file url
		add_action( 'wp_ajax_validateurl',array( $this, 'validateurl' ));
		//ajax Contact Form
 		add_action( 'wp_ajax_supportform',array( $this, 'supportform' ));
 		//default options
 		register_activation_hook($this->plugin_file, array( $this, 'ead_defaults' ));
 		$this->run_plugin();
	}
	/**
	 * Register admin Settings style
	 */
	function setting_styles(){
		wp_register_style( 'embed-settings', plugins_url( 'css/settings.css', $this->plugin_file ), false,$this->plugin_version, 'all' );
		wp_enqueue_style('embed-settings');
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
		//add_action( 'wp_footer',    array( $this, 'embedpopup' ) );
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
		include($this->plugin_path.'inc/popup.php');
	}
	/**
     * Register admin scripts
     */
	function embed_helper(){
		wp_register_style( 'embed-css', plugins_url( 'css/embed.css', $this->plugin_file ), false, $this->plugin_version, 'all' );
		wp_register_script( 'embed', plugins_url( 'js/embed.js', $this->plugin_file ), array( 'jquery' ),$this->plugin_version, true );
		wp_localize_script('embed','emebeder', array(
				'height' 			=> 	get_option('ead_height', '500px'),
        		'width' 			=> 	get_option('ead_width', '100%'), 
        		'download' 			=> 	get_option('ead_download', 'none'), 
        		'provider' 			=> 	get_option('ead_provider', 'google'), 
				'ajaxurl' 			=> 	admin_url( 'admin-ajax.php' ),
				'validtypes' 		=> 	ead_validembedtypes(),
				'msextension' 		=> 	ead_validextensions('ms'), 
        		'drextension'		=> 	ead_validextensions('all'),
				'nocontent'			=> 	__('Nothing to insert', $this->text_domain),
				'invalidurl'		=> 	__('Invalid URL', $this->text_domain),
				'addurl'			=> 	__('Add URL', $this->text_domain),
				'verify'			=> 	__('Verifying...', $this->text_domain),
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
        $default_width 		= 		ead_sanitize_dims(get_option('ead_width', '100%'));
        $default_height 	= 		ead_sanitize_dims(get_option('ead_height', '500px'));
        $default_provider 	= 		get_option('ead_provider', 'google');
        $default_download 	= 		get_option('ead_download', 'none');
        $show = false;
        extract(shortcode_atts(array('url' 		=> '',
        							 'drive' 	=> '', 
        							 'width' 	=> $default_width,
        							 'height' 	=> $default_height, 
        							 'language' => 'en', 
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
            if (ead_allowdownload($viewer)) if ($download == 'alluser' or $download == 'all') {
                $show = true;
            } elseif ($download == 'logged' AND is_user_logged_in()) {
                $show = true;
            }
            if ($show) {
                $filesize = 0;
                $url = esc_url($url, array('http', 'https'));
                if (!is_wp_error($filedata) && isset($filedata['headers']['content-length'])) {
				    $filesize = ead_human_filesize($filedata['headers']['content-length']);
				} else {
				    $filesize = 0;
				}
                $fileHtml = '';
                if ($filesize) $fileHtml = ' [' . $filesize . ']';
                $durl = '<p class="embed_download"><a href="' . $url . '" download >' . __('Download', $this->text_domain) . $fileHtml . ' </a></p>';
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
            $style = 'style="width:%1$s; height:%2$s; border: none;"';
            $stylelink = sprintf($style, ead_sanitize_dims($width), ead_sanitize_dims($height));
            $iframe = '<iframe src="' . $iframe . '" ' . $stylelink . '></iframe>';
            $show = false;
            $embed = '<div class="ead-document">' . $iframe . $privatefile . $durl . '</div>';
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
 		add_action( 'admin_print_styles-' . $eadsettings, array($this,'setting_styles'));
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
	    register_setting( 'ead-settings-group', 'ead_width' ,'ead_sanitize_dims');
	    register_setting( 'ead-settings-group', 'ead_height','ead_sanitize_dims' );
	    register_setting( 'ead-settings-group', 'ead_provider' );
	    register_setting( 'ead-settings-group', 'ead_download' );
	    register_setting( 'ead-settings-group', 'ead_mediainsert' );
	}
	/**
     * Ajax validate file url
    */
	function validateurl(){
		$fileurl =  $_POST['furl'];
		echo json_encode(ead_validateurl($fileurl));
		die(0);
	}
	/**
     * Ajax Contact Form
    */
    function supportform(){
    	if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        include($this->plugin_path.'inc/support-mail.php');
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
    function providerlink($keys, $id, $provider) {
        $link = 'http://goo.gl/wJTQlc';
        $id = "";
        $configure = '<span class="overlay"><strong>' . __('Buy Pro Version', $this->text_domain) . '</strong><i></i></span>';
        $target = 'target="_blank"';
        echo '<a href="' . $link . '" id="' . $id . '" ' . $target . '><span><img src="' . $this->plugin_url . 'images/icon-' . strtolower($provider) . '.png" alt="Add From ' . $provider . '" />' . __('Add from ' . $provider, $this->text_domain) . $configure . '</span></a>';
    }
	/**
     * To initialize default options
    */
	function ead_defaults()
		{
	    $o = array(
	        'ead_width'			=> '100%',
	        'ead_height'    	=> '500px',
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
}

Awsm_embed::get_instance();
