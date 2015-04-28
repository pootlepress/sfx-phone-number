<?php
/**
 * Plugin Name:			SFX Phone Extension
 * Plugin URI:			http://woothemes.com/products/sfx-phone-extension/
 * Description:			A boilerplate plugin for creating Storefront extensions.
 * Version:				1.0.0
 * Author:				WooThemes
 * Author URI:			http://woothemes.com/
 * Requires at least:	4.0.0
 * Tested up to:		4.0.0
 *
 * Text Domain: sfx-phone-extension
 * Domain Path: /languages/
 *
 * @package SFX_Phone_Extension
 * @category Core
 * @author James Koster
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Sold On Woo - Start
/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

//Abstract Class
require_once plugin_dir_path( __FILE__ ) . '/includes/class-abstract.php';

//Admin Class
require_once plugin_dir_path( __FILE__ ) . '/includes/class-admin.php';

//Public Class
require_once plugin_dir_path( __FILE__ ) . '/includes/class-public.php';

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), 'FILE_ID', 'PRODUCT_ID' );
// Sold On Woo - End

/**
 * Returns the main instance of SFX_Phone_Extension to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object SFX_Phone_Extension
 */
function SFX_Phone_Extension() {
	return SFX_Phone_Extension::instance();
} // End SFX_Phone_Extension()

SFX_Phone_Extension();

/**
 * Main SFX_Phone_Extension Class
 *
 * @class SFX_Phone_Extension
 * @version	1.0.0
 * @since 1.0.0
 * @package	SFX_Phone_Extension
 */
final class SFX_Phone_Extension {
	/**
	 * SFX_Phone_Extension The single instance of SFX_Phone_Extension.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	/**
	 * The admin object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $admin;

	/**
	 * The admin object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $public;

	/*
	 * The plugin directory url.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $plugin_url;

	/*
	 * The plugin directory url.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	protected $plugin_path;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 */
	public function __construct() {
		$this->token 			= 'sfx-phone-extension';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.0.0';

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'init', array( $this, 'setup' ) );

		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_links' ) );
	}

	/**
	 * Setup all the things.
	 * Only executes if Storefront or a child theme using Storefront as a parent is active and the extension specific filter returns true.
	 * Child themes can disable this extension using the sfx_phone_extension_enabled filter
	 * @return void
	 */
	public function setup() {
		$theme = wp_get_theme();

		if (
			'Storefront' == $theme->name || 'storefront' == $theme->template
			&&
			apply_filters( 'sfx_phone_extension_supported', true )
		) {

			$this->admin = new SFXPX_Admin( $this->token, $this->plugin_url, $this->plugin_path );
			$this->public = new SFXPX_Public( $this->token, $this->plugin_url, $this->plugin_path );

			// Hide the 'More' section in the customizer
			add_filter( 'storefront_customizer_more', '__return_false' );
		}
	}

	/**
	 * Main SFX_Phone_Extension Instance
	 *
	 * Ensures only one instance of SFX_Phone_Extension is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see SFX_Phone_Extension()
	 * @return Main SFX_Phone_Extension instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	}

	/**
	 * Load the localisation file.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'sfx-phone-extension', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Plugin page links
	 *
	 * @since  1.0.0
	 */
	public function plugin_links( $links ) {
		$plugin_links = array(
			'<a href="http://support.woothemes.com/">' . __( 'Support', 'sfx-phone-extension' ) . '</a>',
			'<a href="http://docs.woothemes.com/document/sfx-phone-extension/">' . __( 'Docs', 'sfx-phone-extension' ) . '</a>',
		);

		return array_merge( $plugin_links, $links );
	}

	/**
	 * Installation.
	 * Runs on activation. Logs the version number and assigns a notice message to a WordPress option.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install() {
		$this->_log_version_number();

		// get theme customizer url
		$url = admin_url() . 'customize.php?';
		$url .= 'url=' . urlencode( site_url() . '?storefront-customizer=true' ) ;
		$url .= '&return=' . urlencode( admin_url() . 'plugins.php' );
		$url .= '&storefront-customizer=true';

		$notices 		= get_option( 'sfx_px_activation_notice', array() );
		$notices[]		= sprintf( __( '%sThanks for installing the SFX Phone Extension extension. To get started, visit the %sCustomizer%s.%s %sOpen the Customizer%s', 'sfx-phone-extension' ), '<p>', '<a href="' . esc_url( $url ) . '">', '</a>', '</p>', '<p><a href="' . esc_url( $url ) . '" class="button button-primary">', '</a></p>' );

		update_option( 'sfx_px_activation_notice', $notices );
	}

	/**
	 * Log the plugin version number.
	 * @access  private
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number() {
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	}
} // End Class