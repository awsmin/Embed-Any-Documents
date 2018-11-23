<?php
/**
 * Enqueue JS and CSS files for the Guten Blocks
 *
 * @package embed-any-document
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Awsm_embed_Guten_blocks {
	private static $instance = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_dynamic_block' ) );

		// Hook: Editor assets.
		add_action( 'enqueue_block_editor_assets', array( $this, 'block_editor_assets' ) );
	}

	/**
	 * Creates or returns an instance of this class.
	 */
	public static function get_instance() {
        // If an instance hasn't been created and set to $instance create an instance and set it to $instance.
        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
	}

	/**
	 * Register dynamic block
	 */	
	public function register_dynamic_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
	
		register_block_type( 'embed-any-document/document', array(
			'attributes' => array(
				'className' => array(
					'type' => 'string',
				),
				'shortcode' => array(
					'type' => 'string',
				),
				'url' => array(
					'type' => 'string',
				),
				'width' => array(
					'type' => 'string',
				),
				'height' => array(
					'type' => 'string',
				),
				'cache' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'download' => array(
					'type' => 'string',
				),
				'text' => array(
					'type' => 'string',
				),
				'viewer' => array(
					'type' => 'string',
				)
			),
			'render_callback' => array( $this, 'block_render_callback' )
		) );
	}

	/**
	 * Server side rendering
	 */	
	public function block_render_callback( $atts ) {
		$embed = '';
		$class_name = isset( $atts['className'] ) ? $atts['className'] : '';
		$shortcode = isset( $atts['shortcode'] ) ? $atts['shortcode'] : '';
		$atts['cache'] = isset( $atts['cache'] ) && $atts['cache'] == false ? 'off' : 'on';
		if( ! empty( $shortcode ) ) {
			$parsed_atts = shortcode_parse_atts( $shortcode );
			$atts['url'] = isset( $parsed_atts['url'] ) ? $parsed_atts['url'] : ''; // url remains static
			$ead_atts = array_replace( $parsed_atts, $atts );
			$ead = Awsm_embed::get_instance();
			$embed = $ead->embed_shortcode( $ead_atts );
			if( ! empty( $embed ) && ! empty( $class_name ) ) {
				$embed = sprintf( '<div class="%2$s">%1$s</div>', $embed, esc_attr( $class_name ) );
			}
		}
		return $embed;
	}

	/**
	 * Enqueue Gutenberg block assets for backend editor.
	 *
	 * `wp-blocks`: includes block type registration and related functions.
	 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
	 * `wp-i18n`: To internationalize the block's text.
	 *
	 */
	public function block_editor_assets() {
		// Scripts.
		wp_enqueue_script(
			'ead-block-editor-js',
			plugins_url( 'blocks/document/document-block.js', dirname( __FILE__ ) ),
			array( 'wp-blocks', 'wp-components', 'wp-editor', 'wp-element', 'wp-i18n',  'ead_media_button' ),
			AWSM_EMBED_VERSION,
			true
		);
	}
}

Awsm_embed_Guten_blocks::get_instance();