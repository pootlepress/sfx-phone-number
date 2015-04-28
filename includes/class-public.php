<?php
/**
 * Created by PhpStorm.
 * User: shramee
 * Date: 27/4/15
 * Time: 5:36 PM
 */

class SFXPX_Public extends SFXPX_Abstract {

	private $phone_menu_items = array();

	public function init(){

		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ), 999 );

		add_action( 'wp_footer', array( $this, 'phone_menu_item_styles' ), 999 );

		add_filter( 'body_class', array( $this, 'body_class' ) );

		add_filter( 'walker_nav_menu_start_el', array( $this, 'menu_item_output' ), 10, 4 );

	}

	/**
	 * Enqueue CSS and custom styles.
	 * @since   1.0.0
	 * @return  void
	 */
	public function styles() {
		wp_enqueue_script( 'sfxtp-public-script', trailingslashit( $this->plugin_url ) . 'assets/js/public.js', array('jquery') );

		wp_enqueue_style( 'sfxtp-public-style', trailingslashit( $this->plugin_url ) . 'assets/css/public.css' );

		wp_enqueue_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css' );

		$style = '';

		wp_add_inline_style( 'sfxtp-public-styles', $style );
	}

	/**
	 * SFX Telephone Body Class
	 * Adds a class based on the extension name and any relevant settings.
	 */
	public function body_class( $classes ) {
		$classes[] = 'sfx-telephone-active';

		return $classes;
	}

	/**
	 * @param string $item_output
	 * @param object $item
	 * @param int $depth
	 * @param array $args
	 *
	 * @return string Output for menu item
	 */
	public function menu_item_output( $item_output, $item, $depth, $args ){
		//Return $item_output if no class sfxtp-phone
		if ( ! in_array( 'sfxtp-phone', $item->classes ) ) {
			return $item_output;
		}

		$icon_classes = '';

		foreach ( $item->classes as $class ) {

			if ( false !== strstr( $class, 'fa' ) ){
				$icon_classes .= $class . ' ';
			}

		}

		$output_data = new SimpleXMLElement( $item_output );

		$value = $item->url;

		$href = str_replace( array( 'http://', '?closeproto&' ), array( '', '://' ), $value );

		$this->phone_menu_items[$item->ID] = get_post_meta( $item->ID, '_sfxtp_icon', true );

		$output_data['href'] = $href;

		$output_data->addChild( 'i', 'nbsp' );

		$output_data->i->addAttribute( 'class', $icon_classes );

		$item_output = str_replace( array( '<?xml version="1.0"?>', 'nbsp' ), '' , $output_data->asXML() );

		return $item_output;
	}

	public function phone_menu_item_styles(){
		?>
		<style id="sfxtp-phone-menu-styles">
		<?php
		foreach ( $this->phone_menu_items as $id => $properties ){
			?>
		#menu-item-<?php echo $id ?> a i.fa{
			color: <?php echo $properties['color'] ?>;
		}
		#menu-item-<?php echo $id ?> a:hover i.fa{
			color: <?php echo $properties['hover-color'] ?>;
		}
			<?php
		};
	?>
		</style>
	<?php
	}
} // End class