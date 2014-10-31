<?php
/*
  Plugin Name: Embed Any Docs
  Plugin URI: https://awsm.in/embed-any-documents
  Description: An object oriented boilerplate for developing a WordPress plugin.
  Version: 1.0.0
  Author: Adhun Anand 
  Author URI: http://www.codelikeaboss.com
  License: GPL V3
 */
require_once( dirname( __FILE__ ) . '/inc/functions.php');
class Awsm_embed {
	private static $instance = null;
	private $plugin_path;
	private $plugin_url;
	private $plugin_file;
	private $settings_slug;
    private $text_domain = 'ead';
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
		$this->plugin_file  	=	__FILE__  ;
		$this->settings_slug	=	'ead-settings';

		load_plugin_textdomain( $this->text_domain, false, 'lang' );

		add_action( 'media_buttons', array( $this, 'embedbutton' ),1000);

		add_shortcode( 'embedall', array( $this, 'embed_shortcode'));

		//Admin Settings menu
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action( 'admin_init', array($this, 'register_eadsettings'));
		//Add easy settings link
		add_filter( "plugin_action_links_" . plugin_basename( __FILE__ ),array($this, 'settingslink'));
		//ajax validate file url
		add_action( 'wp_ajax_validateurl',array( $this, 'validateurl' ));
		//ajax Contact Form
 		add_action( 'wp_ajax_supportform',array( $this, 'supportform' ));
	}
	/**
	 * Register admin Settings style
	 */
	function setting_styles(){
		wp_register_style( 'embed-settings', plugins_url( 'css/settings.css', $this->plugin_file ), false, '1.0', 'all' );
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
		if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
		include($this->plugin_path.'inc/popup.php');
	}
	/**
     * Register admin scripts
     */
	function embed_helper(){
		wp_register_style( 'magnific-popup', plugins_url( 'css/magnific-popup.css', $this->plugin_file ), false, '0.9.9', 'all' );
		wp_register_style( 'embed-css', plugins_url( 'css/embed.css', $this->plugin_file ), false, '1.0', 'all' );
		wp_register_script( 'magnific-popup', plugins_url( 'js/magnific-popup.js', $this->plugin_file ), array( 'jquery' ), '0.9.9', true );
		wp_register_script( 'embed', plugins_url( 'js/embed.js', $this->plugin_file ), array( 'jquery' ), '0.9.9', true );
		wp_localize_script('embed','emebeder', array(
				'default_height'=> get_option('ead_height', '100%' ),
				'default_width' =>  get_option('ead_width', '100%' ),
				'download' =>  get_option('ead_download', 'none' ),
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			) );
		wp_enqueue_style('magnific-popup');
		wp_enqueue_script( 'magnific-popup' );
		wp_enqueue_style('embed-css');
		wp_enqueue_script( 'embed' );
	}
	/**
     * Shortcode Functionality
     */
	function embed_shortcode( $atts){
		$embedcode ="";
		$embedcode = ead_getprovider($atts);
		return $embedcode;
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
	    register_setting( 'ead-settings-group', 'ead_download' );
	    register_setting( 'ead-settings-group', 'ead_provider' );
	}
	/**
     * Ajax validate file url
    */
	function validateurl(){
		if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
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
}

Awsm_embed::get_instance();
