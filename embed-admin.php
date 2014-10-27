<?php
/*

*/
class Embed_Admin {
	private static $instance = null;
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
    public function __construct() {
        $this->add_hooks();
        $this->register_settings();
    }
    public function add_hooks() {
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }
    public function register_settings() {
        //exit('register_settings'. microtime());
        register_setting('mySettings', 'mySettings', null);
        add_settings_field('myUrl', 'API URL', array($this, 'myUrl_callback'), 'my');
        add_settings_field('myOauthUrl', 'API Host', array($this, 'myOauthUrl_callback'), 'my');
        add_settings_field('myClientId', 'API Client Id', array($this, 'myClientId_callback'), 'my');
        add_settings_field('mySharedSecret', 'API Shared Secret', array($this, 'mySharedSecret_callback'), 'my');
    }
    public function admin_menu() {
        exit('admin_menu'. mircotime());
        add_options_page('my API Settings', 'my Settings', 'manage_options', 'my-my', array($this, 'settings_page'));
    }
    public function settings_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        print '<h2>my API Settings</h2><form action="options.php" method="post">';
        print settings_fields('mySettings');
        print do_settings_sections('mySettings_selections');
        print submit_button() .'</form>';
    }
    public function myUrl_callback() {
        $options = get_option('mySettings');
        $option = isset($options['myUrl']) ? $options['myUrl'] : '';
        print '<input id="myUrl" name="mySettings[myUrl]" type="text" value="' . $option . '" />';
    }
}
Embed_Admin::get_instance();