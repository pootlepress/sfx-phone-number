<?php
/**
 * Created by PhpStorm.
 * User: shramee
 * Date: 27/4/15
 * Time: 5:36 PM
 */

/**
 * Abstract for other classes to inherit
 *
 * Class SFXPX_Abstract
 */
abstract class SFXPX_Abstract {

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;
	
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
	public $plugin_path;

	/**
	 * Constructor function.
	 *
	 * @param string $token
	 * @param string $url
	 * @param string $path
	 *
	 * @access  public
	 * @since   1.0.0
	 */
	public function __construct( $token, $url, $path ) {

		$this->token 			= $token;
		$this->plugin_url 		= $url;
		$this->plugin_path 		= $path;

		$this->init( func_get_args() );

	}

	abstract function init();
} // End class