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

/**
 * Used for blocks registration and rendering.
 */
class Awsm_embed_Guten_blocks {
	/**
	 * The instance of the class.
	 *
	 * @var Awsm_embed_Guten_blocks|null
	 */
	private static $instance = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_dynamic_block' ) );

		// Hook: Assets for both editor and front-end.
		add_action( 'enqueue_block_assets', array( $this, 'block_assets' ) );
		// Hook: Editor assets.
		add_action( 'enqueue_block_editor_assets', array( $this, 'block_editor_assets' ) );
	}

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
	 * Register dynamic block
	 */
	public function register_dynamic_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		$dynamic_block_data = array(
			'attributes'      => array(
				'uniqueId'  => array(
					'type' => 'string',
				),
				'className' => array(
					'type' => 'string',
				),
				'shortcode' => array(
					'type' => 'string',
				),
				'url'       => array(
					'type' => 'string',
				),
				'width'     => array(
					'type' => 'string',
				),
				'height'    => array(
					'type' => 'string',
				),
				'cache'     => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'download'  => array(
					'type' => 'string',
				),
				'text'      => array(
					'type' => 'string',
				),
				'viewer'    => array(
					'type' => 'string',
				),
			),
			'render_callback' => array( $this, 'block_render_callback' ),
		);

		/**
		 * Filters the block attributes data.
		 *
		 * @since 3.0.0
		 *
		 * @param array $localized_script_data Localized data array.
		 */
		$dynamic_block_data = apply_filters( 'awsm_ead_dynamic_block_data', $dynamic_block_data );
		register_block_type( 'embed-any-document/document', $dynamic_block_data );
	}

	/**
	 * Server side rendering
	 *
	 * @param array $atts Attributes array.
	 * @return string
	 */
	public function block_render_callback( $atts ) {
		$embed         = '';
		$class_name    = isset( $atts['className'] ) ? $atts['className'] : '';
		$shortcode     = isset( $atts['shortcode'] ) ? $atts['shortcode'] : '';
		$atts['uid']   = isset( $atts['uniqueId'] ) ? $atts['uniqueId'] : 1;
		$atts['cache'] = isset( $atts['cache'] ) && $atts['cache'] == false ? 'off' : 'on';

		/**
		 * Customize the atts.
		 *
		 * @since 3.0.0
		 *
		 * @param array $atts Attribute data array.
		 */
		$atts = apply_filters( 'awsm_ead_block_atts', $atts );

		if ( ! empty( $shortcode ) ) {
			$parsed_atts = Awsm_embed::get_shortcode_attrs( $shortcode );
			$atts['url'] = isset( $parsed_atts['url'] ) ? $parsed_atts['url'] : ''; // url remains static.
			$ead_atts    = array_replace( $parsed_atts, $atts );
			$ead         = Awsm_embed::get_instance();
			$embed       = $ead->embed_shortcode( $ead_atts );
			if ( ! empty( $embed ) && ! empty( $class_name ) ) {
				$embed = sprintf( '<div class="%2$s">%1$s</div>', $embed, esc_attr( $class_name ) );
			}
		}

		/**
		 * Customize the embed data.
		 *
		 * @since 3.0.0
		 *
		 * @param string $embed The embedded content.
		 * @param array $atts Attribute data array.
		 */
		$embed = apply_filters( 'awsm_ead_block_render_callback', $embed, $atts );

		return $embed;
	}

	/**
	 * Enqueue Gutenberg block assets for both editor and front-end.
	 */
	public function block_assets() {
		wp_enqueue_style( 'awsm-ead-public' );
		wp_enqueue_script( 'awsm-ead-public' );
	}

	/**
	 * Enqueue Gutenberg block assets for backend editor.
	 *
	 * `wp-blocks`: includes block type registration and related functions.
	 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
	 * `wp-i18n`: To internationalize the block's text.
	 */
	public function block_editor_assets() {
		// Styles.
		wp_enqueue_style(
			'ead-block-editor-css',
			plugins_url( 'blocks/document/editor.css', dirname( __FILE__ ) ),
			array( 'ead_media_button' ),
			AWSM_EMBED_VERSION,
			'all'
		);
		// Scripts.
		wp_enqueue_script(
			'ead-block-editor-js',
			plugins_url( 'blocks/document/document-block.js', dirname( __FILE__ ) ),
			array( 'wp-blocks', 'wp-components', 'wp-editor', 'wp-element', 'wp-i18n', 'wp-url', 'wp-api-fetch', 'lodash', 'ead_media_button', 'awsm-ead-pdf-object' ),
			AWSM_EMBED_VERSION,
			true
		);
	}
}

Awsm_embed_Guten_blocks::get_instance();
