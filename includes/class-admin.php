<?php
/**
 * Created by PhpStorm.
 * User: shramee
 * Date: 27/4/15
 * Time: 5:36 PM
 */

class SFXPX_Admin extends SFXPX_Abstract {

	/**
	 * Called by parent::__construct
	 *
	 */
	public function init(){

		//Adding custom menu item
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		add_action( 'admin_notices', array( $this, 'customizer_notice' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 999 );

		add_action( 'wp_ajax_sfxtp_add_menu_item', array( $this, 'add_menu_item' ), 999 );

	}

	public function admin_init(){

		//Adding custom menu item
		add_meta_box( 'sfxtp-menu-items', __( 'Phone Number' ), array( $this, 'menu_items' ), 'nav-menus', 'side', 'default' );

	}

	public function menu_items(){
		global $_nav_menu_placeholder, $nav_menu_selected_id;

		$_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1;

		?>
		<div class="customlinkdiv" id="customlinkdiv">

			<input type="hidden" value="custom" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-type]" />

			<!-- IDENTIFIER -->
			<input type="hidden" value="contact" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][sfx-telephone]" />

			<input type="hidden" value="" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-title]" id="sfxtp-phone-item-title"/>
			<input type="hidden" value="" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-url]" id="sfxtp-phone-item-url"/>

			<p class="sfxtp-phone-item-wrap">
				<label class="howto" for="sfxtp-phone-item-cta">
					<span><?php _e( 'Call to action text before phone number' ); ?></span>
					<input id="sfxtp-phone-item-cta" type="text" class="sfxtp-field " />
				</label>
			</p>

			<p class="sfxtp-phone-item-wrap">
				<label class="howto" for="sfxtp-phone-item-phone">
					<span><?php _e( 'Phone number' ); ?></span>
					<input id="sfxtp-phone-item-phone" type="text" class="sfxtp-field " />
				</label>
			</p>

			<p class="sfxtp-phone-item-wrap">
				<label class="howto" for="sfxtp-phone-item-skype">
					<span><?php _e( 'Skype ID' ); ?></span>
					<input id="sfxtp-phone-item-skype" type="text" class="sfxtp-field " />
				</label>
			</p>

			<p class="sfxtp-phone-item-wrap">
				<label class="howto" for="sfxtp-phone-item-icon">
					<span><?php _e( 'Icon Class' ); ?></span>
					<select id="sfxtp-phone-item-icon" class="sfxtp-field" >
						<option value="fa fa-phone">fa-phone</option>
						<option value="fa fa-phone-square">fa-phone-square</option>
						<option value="fa fa-skype">fa-skype</option>
					</select>
				</label>
			</p>

			<p class="sfxtp-phone-item-wrap">
				<label class="howto" for="sfxtp-phone-item-color">
					<span><?php _e( 'Icon Color' ); ?></span>
					<input id="sfxtp-phone-item-color" type="text" class="sfxtp-color-field" />
				</label>
			</p>

			<p class="sfxtp-phone-item-wrap">
				<label class="howto" for="sfxtp-phone-item-hover-color">
					<span><?php _e( 'Icon hover Color' ); ?></span>
					<input id="sfxtp-phone-item-hover-color" type="text" class="sfxtp-color-field" />
				</label>
			</p>

			<p class="button-controls">
			<span class="add-to-menu">
				<input type="submit"<?php wp_nav_menu_disabled_check( $nav_menu_selected_id ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Menu'); ?>" name="add-custom-menu-item" id="sfxtp-submit-phone" />
				<span class="spinner"></span>
			</span>
			</p>

		</div><!-- /.customlinkdiv -->
		<?php
	}

	/**
	 * Admin notice
	 * Checks the notice setup in install(). If it exists display it then delete the option so it's not displayed again.
	 * @since   1.0.0
	 * @return  void
	 */
	public function customizer_notice() {
		$notices = get_option( 'sfxtp_activation_notice' );

		if ( $notices = get_option( 'sfxtp_activation_notice' ) ) {

			foreach ( $notices as $notice ) {
				echo '<div class="updated">' . $notice . '</div>';
			}

			delete_option( 'sfxtp_activation_notice' );
		}
	}

	/**
	 * Enqueue CSS and custom styles.
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue() {
		global $pagenow;

		if ( 'nav-menus.php' == $pagenow ) {

			// only in post and page create and edit screen

			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'sfxtp-admin-script', trailingslashit( $this->plugin_url ) . 'assets/js/admin.js', array(
				'wp-color-picker',
				'jquery'
			) );

			wp_enqueue_style( 'jquery-ui-style', '//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'wp-mediaelement' );
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_style( 'sfxtp-admin-style', trailingslashit( $this->plugin_url ) . 'assets/css/admin.css' );
		}
	}

	/**
	 * Enqueue CSS and custom styles.
	 * @since   1.0.0
	 * @return  void
	 */
	public function update_nav_item( $item_id, $args ) {

		$icon = array(
			'classes' => esc_attr( $args[0]['menu-item-classes'] ),
			'color' => esc_attr( $args[0]['menu-item-icon-color'] ),
			'hover-color' => esc_attr( $args[0]['menu-item-icon-hover-color'] ),
		);

		update_post_meta( $item_id[0], '_sfxtp_icon', $icon );

	}

	public function add_menu_item() {
		check_ajax_referer( 'add-menu_item', 'menu-settings-column-nonce' );

		if ( ! current_user_can( 'edit_theme_options' ) )
			wp_die( -1 );

		require_once ABSPATH . 'wp-admin/includes/nav-menu.php';

		// For performance reasons, we omit some object properties from the checklist.
		// The following is a hacky way to restore them when adding non-custom items.

		$menu_items_data = array();
		foreach ( (array) $_POST['menu-item'] as $menu_item_data ) {
			$menu_items_data[] = $menu_item_data;
		}

		$item_ids = wp_save_nav_menu_items( 0, $menu_items_data );

		//Calling SFXPX_Admin::update_nav_item()
		$this->update_nav_item( $item_ids, $menu_items_data );

		if ( is_wp_error( $item_ids ) )
			wp_die( 0 );

		$menu_items = array();

		foreach ( (array) $item_ids as $menu_item_id ) {
			$menu_obj = get_post( $menu_item_id );
			if ( ! empty( $menu_obj->ID ) ) {
				$menu_obj = wp_setup_nav_menu_item( $menu_obj );
				$menu_obj->label = $menu_obj->title; // don't show "(pending)" in ajax-added items
				$menu_items[] = $menu_obj;
			}
		}

		/** This filter is documented in wp-admin/includes/nav-menu.php */
		$walker_class_name = apply_filters( 'wp_edit_nav_menu_walker', 'Walker_Nav_Menu_Edit', $_POST['menu'] );

		if ( ! class_exists( $walker_class_name ) )
			wp_die( 0 );

		if ( ! empty( $menu_items ) ) {
			$args = array(
				'after' => '',
				'before' => '',
				'link_after' => '',
				'link_before' => '',
				'walker' => new $walker_class_name,
			);
			echo walk_nav_menu_tree( $menu_items, 0, (object) $args );
		}

		wp_die();
	}

} // End class