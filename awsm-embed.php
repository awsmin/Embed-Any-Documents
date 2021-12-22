<?php
/**
 * Plugin Name: Embed Any Document
 * Plugin URI: https://embedanydocument.com
 * Description: Embed Any Document WordPress plugin lets you upload and embed your documents easily in your WordPress website without any additional browser plugins like Flash or Acrobat reader. The plugin lets you choose between Google Docs Viewer and Microsoft Office Online to display your documents.
 * Version: 3.0.0
 * Author: Awsm Innovations
 * Author URI: https://awsm.in
 * License: GPL V3
 * Text Domain: embed-any-document
 * Domain Path: /language
 *
 * @package embed-any-document
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'AWSM_EMBED_VERSION' ) ) {
	define( 'AWSM_EMBED_VERSION', '3.0.0' );
}

require_once plugin_dir_path( __FILE__ ) . '/lib/fs-init.php';

if(get_option('ead_searchdoc')){
	require_once plugin_dir_path(__FILE__).'/vendor/autoload.php';
}

/**
 * Embed Any Document Main Class.
 */
class Awsm_embed {
	/**
	 * The instance of the class.
	 *
	 * @var Awsm_embed
	 */
	private static $instance = null;

	/**
	 * Plugin path.
	 *
	 * @var string
	 */
	private $plugin_path;

	/**
	 * Plugin url.
	 *
	 * @var string
	 */
	private $plugin_url;

	/**
	 * Plugin directory name.
	 *
	 * @var string
	 */
	private $plugin_base;

	/**
	 * Current file path.
	 *
	 * @var string
	 */
	private $plugin_file;

	/**
	 * Plugin Version.
	 *
	 * @var string
	 */
	private $plugin_version;

	/**
	 * Settings page slug.
	 *
	 * @var string
	 */
	private $settings_slug;

	/**
	 * Creates or returns an instance of this class.
	 */
	public static function get_instance() {
		// If an instance hasn't been created and set to $instance create an instance and set it to $instance.
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initializes the plugin by setting localization, hooks, filters, and administrative functions.
	 */
	private function __construct() {
		$this->plugin_path    = plugin_dir_path( __FILE__ );
		$this->plugin_url     = plugin_dir_url( __FILE__ );
		$this->plugin_base    = dirname( plugin_basename( __FILE__ ) );
		$this->plugin_file    = __FILE__;
		$this->settings_slug  = 'ead-settings';
		$this->plugin_version = AWSM_EMBED_VERSION;

		add_action( 'media_buttons', array( $this, 'embedbutton' ), 1000 );

		add_shortcode( 'embeddoc', array( $this, 'embed_shortcode' ) );

		// Initialize block.
		include_once $this->plugin_path . 'blocks/document.php';

		add_action( 'wp_loaded', array( $this, 'register_scripts' ) );
		// Load plugin textdomain.
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		if(get_option('ead_searchdoc')){
			$this->search_index();
		}

		$this->adminfunctions();
	}

	public function search_index(){
		add_filter( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
		add_filter( 'posts_search', array( $this, 'posts_search' ) );
	}

	public function pre_get_posts($query) {
        if ($query->is_search() && $query->is_main_query()) {
	        $post_types = array('post', 'page', 'attachment');
	        $query->set( 'post_type', $post_types );

	        $post_statuses = array('inherit', 'publish');
	        $query->set( 'post_status', $post_statuses );
	    }
	}

	public function posts_search($search){ 
		global $wpdb;
	    if(empty($search)) {
	        return $search; 
	    }

	    $search_terms = get_query_var('search_terms'); 
	    if (empty( $search_terms )) {
			return $search;
		}

	    $inject = $wpdb->prepare("OR ($wpdb->posts.id IN (
								SELECT pm.post_id 
								FROM $wpdb->postmeta pm
								WHERE 
								    $wpdb->posts.ID = pm.post_id AND 
								    pm.meta_key = '_doc_content' AND ");

		foreach ( $search_terms as $term ) {
			$like = '%' . $wpdb->esc_like( $term ) . '%';
			$inject .= $wpdb->prepare( " (pm.meta_value LIKE %s)))", $like );
		}

	    $search .=$inject;
	    return $search;
	}

	/**
	 * Register admin Settings style
	 */
	public function setting_styles() {
		wp_register_style( 'embed-settings', plugins_url( 'css/settings.min.css', $this->plugin_file ), false, $this->plugin_version, 'all' );
		wp_enqueue_style( 'embed-settings' );
	}

	/**
	 * Load plugin textdomain.
	 *
	 * @since 2.2.3
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'embed-any-document', false, $this->plugin_base . '/language/' );
	}

	/**
	 * Embed any Docs Button.
	 *
	 * @param string $editor_id Unique editor identifier.
	 */
	public function embedbutton( $editor_id ) {
		// Check user previlege.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$btn_text = __( 'Add Document', 'embed-any-document' );
		$btn_icon = sprintf( '<img src="%1$s" alt="%2$s" role="presentation" /> ', esc_url( plugins_url( 'images/ead-small.png', __FILE__ ) ), esc_attr( $btn_text ) );

		printf( '<a href="javascript:void(0);" class="awsm-embed button" title="%2$s" data-mfp-src="#embed-popup-wrap" data-target="%3$s">%1$s</a>', $btn_icon . esc_html( $btn_text ), esc_attr( $btn_text ), esc_attr( $editor_id ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Admin Easy access settings link.
	 *
	 * @param array $links Plugin action links.
	 * @return array Plugin action links with ead settings link included.
	 */
	public function settingslink( $links ) {
		$settings_link = '<a href="options-general.php?page=' . esc_attr( $this->settings_slug ) . '">' . esc_html__( 'Settings', 'embed-any-document' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Embed Form popup
	 */
	public function embedpopup() {
		if ( wp_script_is( 'ead_media_button' ) ) {
			add_thickbox();
			include $this->plugin_path . 'inc/popup.php';
		}
	}

	/**
	 * Register admin scripts
	 */
	public function embed_helper() {
		$script_deps = array( 'jquery' );
		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			if ( method_exists( $screen, 'is_block_editor' ) && $screen->is_block_editor() ) {
				$script_deps[] = 'wp-blocks';
			}
		}
		wp_enqueue_script( 'ead_media_button', plugins_url( 'js/embed.min.js', $this->plugin_file ), $script_deps, $this->plugin_version, true );
		wp_enqueue_style( 'ead_media_button', plugins_url( 'css/embed.min.css', $this->plugin_file ), false, $this->plugin_version, 'all' );

		$localized_script_data = array(
				'viewers'       => array_keys( self::get_viewers() ),
				'height'        => get_option( 'ead_height', '100%' ),
				'width'         => get_option( 'ead_width', '100%' ),
				'download'      => get_option( 'ead_download', 'none' ),
				'text'          => get_option( 'ead_text', __( 'Download', 'embed-any-document' ) ),
				'provider'      => get_option( 'ead_provider', 'google' ),
				'ajaxurl'       => admin_url( 'admin-ajax.php' ),
				'admin_url'     => admin_url(),
				'site_url'      => site_url( '/' ),
				'validtypes'    => $this->validembedtypes(),
				'msextension'   => $this->validextensions( 'ms' ),
				'drextension'   => $this->validextensions( 'all' ),
				'settings_slug'	=> $this->settings_slug,
				'nocontent'     => __( 'Nothing to insert', 'embed-any-document' ),
				'invalidurl'    => __( 'Invalid URL', 'embed-any-document' ),
				'addurl'        => __( 'Add URL', 'embed-any-document' ),
				'verify'        => __( 'Verifying...', 'embed-any-document' ),
				'from_url'      => __( 'From URL', 'embed-any-document' ),
				'select_button' => __( 'Select', 'embed-any-document' ),
				'nopublic'      => __( 'The document you have chosen is a not public.', 'embed-any-document' ) . __( ' Only the owner and explicitly shared collaborators will be able to view it.', 'embed-any-document' ),
				'addon_active'    => $this->is_addon_active(),
			);

		/**
		 * Filters the public script localized data.
		 *
		 * @since 3.0.0
		 *
		 * @param array $localized_script_data Localized data array.
		 */
		$localized_script_data = apply_filters( 'awsm_ead_localized_script_data', $localized_script_data );
		wp_localize_script( 'ead_media_button', 'emebeder', $localized_script_data );
	}

	/**
	 * Check whether the file is viewable by browser or not by checking the file URL.
	 *
	 * @param string $url The file URL.
	 * @return boolean
	 */
	public static function is_browser_viewable_type( $url ) {
		$is_viewable = false;
		$types       = array( 'pdf', 'txt' );
		$splitted    = explode( '.', $url );
		$extension   = end( $splitted );
		if ( ! empty( $extension ) ) {
			if ( in_array( strtolower( trim( $extension ) ), $types, true ) ) {
				$is_viewable = true;
			}
		}
		return $is_viewable;
	}

	/**
	 * Get the iframe preloader content.
	 *
	 * @param array $shortcode_atts The shortcode attributes.
	 */
	public static function get_iframe_preloader( $shortcode_atts ) { 
		if ( ! isset( $shortcode_atts['viewer'] ) || ! isset( $shortcode_atts['url'] ) ) {
			return;
		}

		$document_url = '#';
		if ( $shortcode_atts['viewer'] === 'google' ) {
			if ( self::is_browser_viewable_type( $shortcode_atts['url'] ) ) {
				$document_url = $shortcode_atts['url'];
			} else {
				$src          = 'https://docs.google.com/viewer?url=%1$s&hl=%2$s';
				$document_url = sprintf( $src, rawurlencode( $shortcode_atts['url'] ), esc_attr( $shortcode_atts['language'] ) );
			}
		}

		ob_start();
		?>
			<div class="ead-document-loading" style="width:100%;height:100%;position:absolute;left:0;top:0;z-index:10;">
				<div class="ead-loading-wrap">
					<div class="ead-loading-main">
						<div class="ead-loading">
							<img src="<?php echo esc_url( plugins_url( 'images/loading.svg', __FILE__ ) ); ?>" width="55" height="55" alt="<?php esc_html_e( 'Loader', 'embed-any-document' ); ?>">
							<span><?php esc_html_e( 'Loading...', 'embed-any-document' ); ?></span>
						</div>
					</div>
					<div class="ead-loading-foot">
						<div class="ead-loading-foot-title">
							<img src="<?php echo esc_url( plugins_url( 'images/EAD-logo.svg', __FILE__ ) ); ?>" alt="<?php esc_html_e( 'EAD Logo', 'embed-any-document' ); ?>" width="36" height="23"/>
							<span><?php esc_html_e( 'Taking too long?', 'embed-any-document' ); ?></span>
						</div>
						<p>
							<div class="ead-document-btn ead-reload-btn" role="button">
								<img src="<?php echo esc_url( plugins_url( 'images/reload.svg', __FILE__ ) ); ?>" alt="<?php esc_html_e( 'Reload', 'embed-any-document' ); ?>" width="12" height="12"/> <?php esc_html_e( 'Reload document', 'embed-any-document' ); ?>
							</div>
							<span>|</span>
							<a href="<?php echo esc_url( $document_url ); ?>" class="ead-document-btn" target="_blank">
								<img src="<?php echo esc_url( plugins_url( 'images/open.svg', __FILE__ ) ); ?>" alt="<?php esc_html_e( 'Open', 'embed-any-document' ); ?>" width="12" height="12"/> <?php esc_html_e( 'Open in new tab', 'embed-any-document' ); ?>
							</a>
					</div>
				</div>
			</div>
		<?php
		$preloader = ob_get_clean();

		/**
		 * Customize the document preloader.
		 *
		 * @since 2.6.0
		 *
		 * @param string $preloader The preloader content.
		 * @param array $shortcode_atts The shortcode attributes.
		 */
		return apply_filters( 'awsm_ead_preloader', $preloader, $shortcode_atts );
	}

	/**
	 * Get public script data.
	 *
	 * @return array
	 */
	public function get_public_script_data() {
		/**
		 * Customize the public script data.
		 *
		 * @since 2.6.0
		 *
		 * @param array $script_data The script data.
		 */
		$script_data = apply_filters(
			'awsm_ead_public_script_data',
			array()
		);
		return $script_data;
	}

	/**
	 * Get public script data.
	 *
	 * @return array
	 */
	public function get_public_viewer_check_data() {
		/**
		 * Customize the public script data.
		 *
		 * @since 2.6.0
		 *
		 * @param array $script_data The script data.
		 */
		$script_data = apply_filters(
			'awsm_ead_public_viewer_check',
			array('viewer'=>'')
		);
		return $script_data;
	}

	/**
	 * Register scripts for both back-end and front-end use.
	 */
	public function register_scripts() {
		wp_register_style( 'awsm-ead-public', plugins_url( 'css/embed-public.min.css', $this->plugin_file ), array(), $this->plugin_version, 'all' );

		wp_register_script( 'awsm-ead-pdf-object', plugins_url( 'js/pdfobject.min.js', $this->plugin_file ), array(), $this->plugin_version, true );
		wp_register_script( 'awsm-ead-public', plugins_url( 'js/embed-public.min.js', $this->plugin_file ), array( 'jquery', 'awsm-ead-pdf-object' ), $this->plugin_version, true );

		wp_localize_script( 'awsm-ead-public', 'eadPublic', $this->get_public_script_data() );
		wp_localize_script( 'awsm-ead-public', 'eadPublicViewer', $this->get_public_viewer_check_data() );
	}

	/**
	 * Generate style attribute from attributes array.
	 *
	 * @param array $attrs Attributes array.
	 * @return string
	 */
	public static function build_style_attr( $attrs ) {
		$style = 'style="';
		foreach ( $attrs as $property => $value ) {
			$style .= sprintf( '%1$s: %2$s;', esc_attr( $property ), esc_attr( $value ) );
		}
		$style .= '"';
		return $style;
	}

	/**
	 * Get the supported viewers.
	 *
	 * @return array
	 */
	public static function get_viewers() {
		$viewers = array(
			'google'    => __( 'Google Docs Viewer', 'embed-any-document' ),
			'browser'   => __( 'Browser Based', 'embed-any-document' ),
			'microsoft' => __( 'Microsoft Office Online', 'embed-any-document' ),
		);
		/**
		 * Customize the supported viewers.
		 *
		 * @since 2.6.0
		 *
		 * @param array $viewers Viewers array.
		 */
		return apply_filters( 'awsm_ead_viewers', $viewers );
	}

	/**
	 * Check if Addons are active.
	 *
	 * @return array
	 */
	public function is_addon_active() {
		
		$viewers = array();
		/**
		 * Customize the viewers array data.
		 *
		 * @since 3.0.0
		 *
		 * @param array $viewers The viewers array.
		 */
		return apply_filters( 'awsm_ead_addon_active', $viewers );
	}

	/**
	 * Get all providers.
	 *
	 * @return array
	 */
	public static function get_all_providers() {
		$providers = array( 'google', 'microsoft', 'browser');
		/**
		 * Customize the providers.
		 *
		 * @since 2.6.0
		 *
		 * @param array $providers Providers array.
		 */
		return apply_filters( 'awsm_ead_providers', $providers );
	}

	/**
	 * Get shortcode attributes as an array from EAD shortcode.
	 *
	 * @param string $shortcode EAD Shortcode.
	 * @return array
	 */
	public static function get_shortcode_attrs( $shortcode ) {
		$text       = str_replace( '[embeddoc', '', $shortcode );
		$text       = trim( $text );
		$text       = rtrim( $text, ']' );
		$attributes = shortcode_parse_atts( $text );
		return $attributes;
	}
	/**
	 * Shortcode Functionality.
	 *
	 * @param array $atts The shortcode attributes.
	 * @return string Shortcode output content.
	 */
	public function embed_shortcode( $atts ) { 
		$embed            = '';
		$durl             = '';
		$default_width    = $this->sanitize_dims( get_option( 'ead_width', '100%' ) );
		$default_height   = $this->sanitize_dims( get_option( 'ead_height', '100%' ) );
		$default_provider = get_option( 'ead_provider', 'google' );
		$default_download = get_option( 'ead_download', 'none' );
		$default_text     = get_option( 'ead_text', __( 'Download', 'embed-any-document' ) );
		$show             = false;
		$is_shortcode_url = false;
		$shortcode_atts   = shortcode_atts(
			array(
				'url'      => '',
				'drive'    => '',
				'width'    => $default_width,
				'height'   => $default_height,
				'language' => 'en',
				'text'     => $default_text,
				'viewer'   => $default_provider,
				'download' => $default_download,
				'cache'    => 'on',
			),
			$atts,
			'embeddoc'
		);

		$preloader = get_option( 'ead_preloader');
		
		wp_enqueue_style( 'awsm-ead-public' );
		wp_enqueue_script( 'awsm-ead-public' );

		if( isset($shortcode_atts['url']) && !empty($shortcode_atts['url']) ){
			$is_shortcode_url = true;
		}

		// AMP.
		$is_amp = function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();

		$durl      = '';
		$viewer    = $shortcode_atts['viewer'];
		$providers = self::get_all_providers();

		if ( ! in_array( $viewer, $providers, true ) ) {
			$viewer                   = 'google';
			$shortcode_atts['viewer'] = 'google';
		}

		$is_browser_viewer = false;
		if ( $shortcode_atts['viewer'] === 'browser' ) {
			// fallback for Browser viewer.
			$is_browser_viewer = true;
			$viewer            = 'google';
			// AMP handling.
			if ( $is_amp ) {
				$is_browser_viewer = false;
			}
		}


		if ( $this->allowdownload( $shortcode_atts['viewer'] ) ) {
			if ( $shortcode_atts['download'] === 'alluser' || $shortcode_atts['download'] === 'all' ) {
				$show = true;
			} elseif ( $shortcode_atts['download'] === 'logged' && is_user_logged_in() ) {
				$show = true;
			}
		}

		if($is_shortcode_url){
			$url = esc_url( $shortcode_atts['url'], array( 'http', 'https' ) );
			if ( $show ) {
				$filedata = wp_remote_head( $shortcode_atts['url'] );
				$filesize = 0;
				if ( ! is_wp_error( $filedata ) && isset( $filedata['headers']['content-length'] ) ) {
					$filesize = $this->human_filesize( $filedata['headers']['content-length'] );
				} else {
					$filesize = 0;
				}
				$file_html = '';
				if ( $filesize ) {
					$file_html = ' [' . $filesize . ']';
				}
				$durl = '<p class="embed_download"><a href="' . esc_url( $url ) . '" download >' . $shortcode_atts['text'] . $file_html . ' </a></p>';
			}
		}


		if ( $shortcode_atts['cache'] === 'off' && $viewer === 'google' && $is_shortcode_url) {
			if ( $this->url_get_param( $url ) ) {
				$url .= '?' . time();
			} else {
				$url .= '&' . time();
			}
		}
		

		$iframe_src = '';
		if($is_shortcode_url){
			switch ( $viewer ) {
				case 'google':
					$embedsrc   = '//docs.google.com/viewer?url=%1$s&embedded=true&hl=%2$s';
					$iframe_src = sprintf( $embedsrc, rawurlencode( $url ), esc_attr( $shortcode_atts['language'] ) );
					break;

				case 'microsoft':
					$embedsrc   = '//view.officeapps.live.com/op/embed.aspx?src=%1$s';
					$iframe_src = sprintf( $embedsrc, rawurlencode( $url ) );
					break;
			}
		}

		if(get_option('ead_searchdoc')){
			$this->parse_documents($url);
		}
		
		/**
		 * Add the iframe src.
		 *
		 * @since 3.0.0
		 *
		 * @param string $iframe_src The iframe src variable.
		 * @param array $shortcode_atts The shortcode attributes.
		 */
		$iframe_src = apply_filters( 'awsm_ead_iframe_src', $iframe_src, $shortcode_atts); 

		$iframe_style_attrs = array();
		$doc_style_attrs    = array(
			'position' => 'relative',
		);
		if ( $this->check_responsive( $shortcode_atts['height'] ) && $this->check_responsive( $shortcode_atts['width'] ) && ! $is_browser_viewer ) {
			$iframe_style_attrs = array(
				'width'    => '100%',
				'height'   => '100%',
				'border'   => 'none',
				'position' => 'absolute',
				'left'     => '0',
				'top'      => '0',
			);

			$doc_style_attrs['padding-top'] = '90%';
		} else {
			$iframe_style_attrs = array(
				'width'  => $shortcode_atts['width'],
				'height' => $shortcode_atts['height'],
				'border' => 'none',
			);
			if ( $this->in_percentage( $shortcode_atts['height'] ) ) {
				$iframe_style_attrs['min-height'] = '500px';
			}
		}

		$enable_preloader = ! $is_amp && $viewer === 'google' && $preloader == 1; 

		if ( $enable_preloader ) {  
			$iframe_style_attrs['visibility'] = 'hidden';
		}

		$data_attr = '';
		if ( $is_browser_viewer && $is_shortcode_url ) {
			$data_attr = sprintf( ' data-pdf-src="%1$s" data-viewer="%2$s"', esc_url( $shortcode_atts['url'] ), esc_attr( $shortcode_atts['viewer'] ) );

			$doc_style_attrs = array_merge( $doc_style_attrs, $iframe_style_attrs );
			unset( $doc_style_attrs['visibility'] );
		}

		/**
		 * iframe style attributes.
		 *
		 * @since 3.0.0
		 *
		 * @param string $iframe_style_attrs The iframe stle attributes variable.
		 */
		$iframe_style_attrs = self::build_style_attr( $iframe_style_attrs );
		$iframe_style       = apply_filters( 'awsm_ead_iframe_style_attrs', $iframe_style_attrs );

		$iframe       = sprintf( '<iframe src="%s" title="%s" class="ead-iframe" %s></iframe>', esc_attr( $iframe_src ), esc_html__( 'Embedded Document', 'embed-any-document' ), $iframe_style );

		if ( $enable_preloader ) {
			$iframe = '<div class="ead-iframe-wrapper">' . $iframe . '</div>' . self::get_iframe_preloader( $shortcode_atts );
		}

		$doc_style = self::build_style_attr( $doc_style_attrs ); 
		$embed     = sprintf( '<div class="ead-preview"><div class="ead-document" %3$s>%1$s</div>%2$s</div>', $iframe, $durl, $doc_style . $data_attr );


		/**
		 * Customize the embedded content.
		 *
		 * @since 2.6.0
		 * @since 3.0.0 $iframe_attrs is added.
		 *
		 * @param string $embed The embedded content.
		 * @param array $shortcode_atts The shortcode attributes.
		 * @param array $iframe_attrs The iframe attributes.
		 */
		$iframe_attrs['durl'] = $durl;
		$iframe_attrs['atts'] = $atts;
		$embed = apply_filters( 'awsm_ead_content', $embed, $shortcode_atts, $iframe_attrs );
	
		return $embed;
	}

	public function parse_documents($url){ 
		$file_content = file_get_contents($url); 
		if($file_content === false){
			return false;
		} 
		$mime_type  = wp_check_filetype(wp_basename($url)); 

		$doc_content = "";
		switch ($mime_type['type']) {
			case 'application/pdf':
				$doc_content = $this->pdf_parser($file_content);
				break;

			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
				$doc_content = $this->docx_parser($url);
				break;
			
			default:
				break;
		}

        if($doc_content != ''){
        	$post_id  = get_the_ID();
        	$check    = add_post_meta($post_id, '_doc_content', $doc_content, false); 
        }
	}

	public function pdf_parser($content){
		$pdf  = new \Smalot\PdfParser\Parser();
		$text = "";
	  
        $result = $pdf->parseContent($content);
        $text   = $result->getText();
        $text   = str_replace(["\n\n"], "", $text); 
	   
	    return $text;
	}

	public function docx_parser($url){  
		$text 			  = "";
		$upload_dir       = wp_upload_dir();
		$destination_dir  = $upload_dir['basedir'].'/'.'embed-any-document';
		$file_name  	  = basename($url);
		$dir_path 		  = $destination_dir .'/'. $file_name;
		
		if (!is_dir($destination_dir)) {
    		mkdir($destination_dir, 0777, true);
		}

		if (!copy($url, $dir_path)) {
    		return false;
		}

		$zip = new ZipArchive(); 

		if(file_exists($dir_path)){ 
			if ($zip->open($dir_path)) { 
	 			$dataFile = "word/document.xml";   
			  	if (($index = $zip->locateName($dataFile)) !== false) {
					$text = $zip->getFromIndex($index);
					$xml  = new DOMDocument();
    				$xml->loadXML($text, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
    				$text = strip_tags($xml->saveXML());
			  	}
			} 
			unlink($dir_path);
			$zip->close();
		}
		return $text;
	}

	/**
	 * Admin menu setup
	 */
	public function admin_menu() {
		$title       = __( 'Embed Any Document', 'embed-any-document' );
		$eadsettings = add_options_page( $title, $title, 'manage_options', $this->settings_slug, array( $this, 'settings_page' ) );
		add_action( 'admin_print_styles-' . $eadsettings, array( $this, 'setting_styles' ) );
	}

	/**
	 * Admin settings page.
	 */
	public function settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'default' ) );
		}

		include $this->plugin_path . 'inc/settings.php';
	}

	/**
	 * Register Settings
	 */
	public function register_eadsettings() {
		register_setting( 'ead-settings-group', 'ead_width', array( $this, 'sanitize_dims' ) );
		register_setting( 'ead-settings-group', 'ead_height', array( $this, 'sanitize_dims' ) );
		register_setting( 'ead-settings-group', 'ead_provider' );
		register_setting( 'ead-settings-group', 'ead_download' );
		register_setting( 'ead-settings-group', 'ead_text' );
		register_setting( 'ead-settings-group', 'ead_mediainsert' );
		register_setting( 'ead-settings-group', 'ead_preloader' );
		register_setting( 'ead-settings-group', 'ead_searchdoc' );
	}

	/**
	 * Admin Functions init
	 */
	public function adminfunctions() {
		if ( is_admin() ) {
			add_action( 'wp_enqueue_media', array( $this, 'embed_helper' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_init', array( $this, 'register_eadsettings' ) );
			add_action( 'admin_footer', array( $this, 'embedpopup' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'settingslink' ) );
			add_filter( 'upload_mimes', array( $this, 'additional_mimes' ) );
		}
	}

	/**
	 * Adds additional mime type for media uploader.
	 *
	 * @param array $mimes Mime types.
	 * @return array An array with additional mime types added.
	 */
	public function additional_mimes( $mimes ) {
		return array_merge(
			$mimes,
			array(
				'svg' => 'image/svg+xml',
				'ai'  => 'application/postscript',
			)
		);
	}

	/**
	 * To get Overlay link.
	 *
	 * @param string $provider Service provider.
	 */
	public function providerlink( $provider ) {
		$link      = 'http://embedanydocument.com/plus-cc';
		$id        = '';
		$configure = '<span class="overlay"><strong>' . esc_html__( 'Buy Pro Version', 'embed-any-document' ) . '</strong><i></i></span>';
		$target    = 'target="_blank"';
		/* translators: %1$s: Service provider */
		echo '<a href="' . esc_url( $link ) . '" id="' . esc_attr( $id ) . '" ' . $target . '><span><img src="' . esc_url( $this->plugin_url ) . 'images/icon-' . esc_attr( strtolower( $provider ) ) . '.png" alt="' . esc_attr( sprintf( __( 'Add From %1$s', 'embed-any-document' ), $provider ) ) . '" />' . esc_html( sprintf( __( 'Add From %1$s', 'embed-any-document' ), $provider ) ) . $configure . '</span></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * To be used for addons.
	 *
	 * @param string|array $keys Option name.
	 * @param string       $id 'id' attribute value for anchor tag.
	 * @param string       $provider Service provider.
	 */
	public function providerlink_viewer($keys,$id,$provider) { 
		if ( $this->isprovider_api( $keys ) ) {
			$link      = 'options-general.php?page='.$this->settings_slug. '&tab=cloud';;
			$id        = '';
			$configure = '';
			$target    = 'target="_blank"';
		}else{
			$configure = '';
			$link      = '#';
			$target    = '';
		}
		/* translators: %1$s: Service provider */
		$imageurl = esc_url( plugins_url( 'images/icon-'. strtolower( $provider ) . '.svg', __FILE__ ) );

		/* translators: %1$s: Service provider */
		$linktext = sprintf( __( 'Add from %1$s', 'embed-any-document' ), $provider );

		$link_content = sprintf(
			wp_kses(
				'<li><a href="%1$s" id="%2$s" %3$s><span><img src="%4$s" alt="%2$s" height="50" />%5$s %6$s</span></a></li>',
				array(
					'a'    => array(
						'href'   => array(),
						'id'     => array(),
						'target' => array(),
					),
					'span' => array(),
					'img'  => array(
						'src'    => array(),
						'alt'    => array(),
						'width'  => array(),
						'height' => array(),
					),
					'li' => array(),
				)
			),
			esc_url( $link ),
			esc_attr( $id ),
			$target,
			esc_url( $imageurl ),
			esc_html( $linktext ),
			$configure
		);

		return $link_content;
	}

	/**
	 * Check Provider API.
	 *
	 * @param string|array $keys Option name.
	 * @return bool
	 */
	public function isprovider_api( $keys ) {
		$itemflag = false;
		if ( is_array( $keys ) ) {
			foreach ( $keys as $key ) {
				if ( ! get_option( $key ) ) {
					$itemflag = true;
					break;
				}
			}
		} else {
			if ( ! get_option( $keys ) ) {
				$itemflag = true;
			}
		}
		return $itemflag;
	}

	/**
	 * To initialize default options
	 */
	public function defaults() {
		$options = array(
			'ead_width'       => '100%',
			'ead_height'      => '100%',
			'ead_download'    => 'none',
			'ead_provider'    => 'google',
			'ead_mediainsert' => '1',
		);
		foreach ( $options as $key => $value ) {
			if ( ! get_option( $key ) ) {
				update_option( $key, $value );
			}
		}
	}

	/**
	 * Dropdown Builder.
	 *
	 * @param string $name 'name' attribute value.
	 * @param array  $options Array of choices.
	 * @param mixed  $selected Optional. One of the values to compare. Default empty.
	 * @param string $class Optional. 'class' attribute value. Default empty.
	 */
	public function selectbuilder( $name, $options, $selected = '', $class = '', $attr = '' ) {
		if ( is_array( $options ) ) :
			$select_html = '<select name="' . esc_attr( $name ) . '" id="' . esc_attr( $name ) . '" class="' . esc_attr( $class ) . '" ' . $attr . '>';
			
			foreach ( $options as $key => $option ) {
				$selected_html = '';
				if ( $key == $selected ) {
					$selected_html = ' selected="selected"';
				}
				$select_html .= '<option value="' . esc_attr( $key ) . '" ' . $selected_html . '>' . esc_html( $option ) . '</option>';
			}
			echo $select_html .= '</select>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		endif;
	}

	/**
	 * Human Readable filesize.
	 *
	 * @param int|string $bytes Number of bytes.
	 * @param int        $decimals Optional. Precision of number of decimal places. Default 2.
	 * @return string Human readable file size.
	 */
	public function human_filesize( $bytes, $decimals = 2 ) {
		$size   = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' );
		$factor = floor( ( strlen( $bytes ) - 1 ) / 3 );
		$size   = isset( $size[ $factor ] ) ? $size[ $factor ] : '';
		return sprintf( "%.{$decimals}f ", $bytes / pow( 1024, $factor ) ) . $size;
	}

	/**
	 * Sanitize dimensions (width, height).
	 *
	 * @param string $dim Value to be sanitized.
	 * @return string|bool Sanitized dimensions or false if value is invalid.
	 */
	public function sanitize_dims( $dim ) {
		// remove any spacing junk.
		$dim = trim( str_replace( ' ', '', $dim ) );
		if ( ! $dim ) {
			$dim = '100%';
		}
		if ( ! strstr( $dim, '%' ) ) {
			$type = 'px';
			$dim  = preg_replace( '/[^0-9]*/', '', $dim );
		} else {
			$type = '%';
			$dim  = preg_replace( '/[^0-9]*/', '', $dim );
			if ( (int) $dim > 100 ) {
				$dim = '100';
			}
		}

		if ( $dim ) {
			return $dim . $type;
		} else {
			return false;
		}
	}

	/**
	 * Check value in percentage.
	 *
	 * @since 2.2.3
	 *
	 * @param string $dim Value to be checked.
	 * @return bool
	 */
	public function in_percentage( $dim ) {
		if ( strstr( $dim, '%' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Enable Responsive.
	 *
	 * @since 2.2.3
	 *
	 * @param string $dim Value to be checked.
	 * @return bool
	 */
	public function check_responsive( $dim ) {
		if ( strstr( $dim, '%' ) ) {
			$dim = preg_replace( '/[^0-9]*/', '', $dim );
			if ( (int) $dim === 100 ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Validate Source mime type
	 *
	 * @since  1.0
	 * @return array
	 */
	public function validmime_types() {
		$mimetypes = array(
			// Text formats.
			'txt|asc|c|cc|h'  => 'text/plain',
			'rtx'             => 'text/richtext',
			'css'             => 'text/css',
			// Misc application formats.
			'js'              => 'application/javascript',
			'pdf'             => 'application/pdf',
			'ai'              => 'application/postscript',
			'tif'             => 'image/tiff',
			'tiff'            => 'image/tiff',
			// MS Office formats.
			'doc'             => 'application/msword',
			'pot|pps|ppt'     => 'application/vnd.ms-powerpoint',
			'xla|xls|xlt|xlw' => 'application/vnd.ms-excel',
			'docx'            => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'dotx'            => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
			'dotm'            => 'application/vnd.ms-word.template.macroEnabled.12',
			'xlsx'            => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'xlsm'            => 'application/vnd.ms-excel.sheet.macroEnabled.12',
			'pptx'            => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
			'ppsx'            => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
			// iWork formats.
			'pages'           => 'application/vnd.apple.pages',
			// Additional Mime Types.
			'svg'             => 'image/svg+xml',
		);

		return $mimetypes;
	}

	/**
	 * Checks Url Validity.
	 *
	 * @since 1.0
	 *
	 * @param string $url File URL.
	 * @return boolean
	 */
	public function valid_type( $url ) {
		$doctypes = $this->validmime_types();
		if ( is_array( $doctypes ) ) {
			$allowed_ext = implode( '|', array_keys( $doctypes ) );
			if ( preg_match( "/\.($allowed_ext)$/i", $url ) ) {
				return true;
			}
		} else {
			return false;
		}
	}

	/**
	 * Get allowed Mime Types.
	 *
	 * @since 1.0
	 *
	 * @return string Mimetypes
	 */
	public function validembedtypes() {
		$doctypes = $this->validmime_types();
		return implode( ',', $doctypes );
	}

	/**
	 * Get allowed Extensions.
	 *
	 * @param string $list Optional. 'all' extensions or 'ms' only extensions. Default 'all'.
	 * @return string Comma-separated extenstions list.
	 */
	public function validextensions( $list = 'all' ) {
		$extensions['all'] = array( '.css', '.js', '.pdf', '.ai', '.tif', '.tiff', '.doc', '.txt', '.asc', '.c', '.cc', '.h', '.pot', '.pps', '.ppt', '.xla', '.xls', '.xlt', '.xlw', '.docx', '.dotx', '.dotm', '.xlsx', '.xlsm', '.pptx', '.pages', '.svg', '.ppsx' );
		$extensions['ms']  = array( '.doc', '.pot', '.pps', '.ppt', '.xla', '.xls', '.xlt', '.xlw', '.docx', '.dotx', '.dotm', '.xlsx', '.xlsm', '.pptx', '.ppsx' );

		return implode( ',', $extensions[ $list ] );
	}

	/**
	 * Get allowed Mime Types for Microsoft.
	 *
	 * @return array Mimetypes.
	 */
	public function microsoft_mimes() {
		$micro_mime = array(
			'doc'             => 'application/msword',
			'pot|pps|ppt'     => 'application/vnd.ms-powerpoint',
			'xla|xls|xlt|xlw' => 'application/vnd.ms-excel',
			'docx'            => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'dotx'            => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
			'dotm'            => 'application/vnd.ms-word.template.macroEnabled.12',
			'xlsx'            => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'xlsm'            => 'application/vnd.ms-excel.sheet.macroEnabled.12',
			'pptx'            => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
			'ppsx'            => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
		);

		return $micro_mime;
	}

	/**
	 * Check Allow Download.
	 *
	 * @param string $provider Service provider.
	 * @return bool
	 */
	public function allowdownload( $provider ) {
		$blacklist = array( 'drive', 'box' );
		if ( in_array( $provider, $blacklist, true ) ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Get Active Menu Class.
	 *
	 * @param string $tab One of the values to compare.
	 * @param string $needle The other value to compare.
	 */
	public function getactive_menu( $tab, $needle ) {
		if ( $tab === $needle ) {
			echo 'nav-tab-active';
		}
	}

	/**
	 * Checks for url query parameter.
	 *
	 * @since 2.6.0
	 * @param string $url Document url.
	 * @return bool
	 */
	public function url_get_param( $url ) {
		$urldata = parse_url( $url );
		if ( isset( $urldata['query'] ) ) {
			return false;
		} else {
			return true;
		}
	}
}

if ( defined( 'EAD_PLUS' ) ) {
	if ( ! function_exists( 'embed_doc_disable_self' ) ) {
		/**
		 * Deactivate free version if Plus version is available.
		 */
		function embed_doc_disable_self() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
		add_action( 'admin_init', 'embed_doc_disable_self' );
	}
} else {
	// Initialize the class.
	$awsm_embed = Awsm_embed::get_instance();

	// Register defaults.
	register_activation_hook( __FILE__, array( $awsm_embed, 'defaults' ) );
}
