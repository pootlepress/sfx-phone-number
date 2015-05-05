<?php
/**
 * Contains class for rendering the admin fields
 *
 * @package SFX_Page_Customizer
 * @category Admin
 */

if( ! class_exists( 'SFXTP_Widget_Render_Control' ) ) {
	/**
	 * Renders the fields in Post edit page and Taxonomy edit page
	 *
	 * @class sfxtp_Render_Controls
	 * @version    1.0.0
	 * @since 1.0.0
	 * @package    SFX_Page_Customizer
	 * @author PootlePress
	 */
	class SFXTP_Widget_Render_Control {

		/**
		 * Render a field of a given type.
		 * @access public
		 * @since 1.0.0
		 *
		 * @param array $args The field parameters.
		 * @param array $current_val - Current data to put current values in the fields
		 * @param string $wid_prefix - prefix for classes of fields
		 *
		 * @return string
		 */
		public function render_field( $args, $current_val, $wid_prefix = '' ) {

			//Setting blank css-class key if not set
			if ( ! isset( $args['css-class'] ) ) {
				$args['css-class'] = '';
			}

			$args['css-class'] .= ' sfxtp-field ' . $args['html_id'];

			// Make sure we have some kind of default, if the key isn't set.
			if ( ! isset( $args['default'] ) ) {
				$args['default'] = '';
			}

			$this->_output_rendered_field( $args, $current_val, $wid_prefix );

		}

		/**
		 * Outputs the field after rendering with HTML for the context
		 *
		 * @param array $args Argument for field
		 * @param string $current_val Current value of the field
		 */
		protected function _output_rendered_field( $args, $current_val, $wid_prefix ) {

			echo '<p class="' . $wid_prefix . '-' . $args['type'] . '-control ' . $wid_prefix . '-control">';
			echo ( $args['type'] == 'checkbox' ) ? '' : '<label class="label" for="' . esc_attr( $args['html_id'] ) . '">' . esc_html( $args['label'] ) . '</label>';

			//Getting the method for field
			$method = '_render_field_' . $args['type'];
			if ( ! method_exists( $this, $method ) ) {
				$method = '_render_field_text';
			}

			//Output the field
			$this->$method( $args['key'], $current_val, $args );

			// Output the description
			if ( isset( $args['description'] ) ) {
				echo '<br><span class="description">' . wp_kses_post( $args['description'] ) . '</span>' . "\n";
			}

			echo '</p>';
		}

		/**
		 * Render HTML markup for the "text" field type.
		 * @access  protected
		 * @since   1.0
		 *
		 * @param   string $key The unique ID of this field.
		 * @param   string $current_val The current value of the field
		 *
		 * @return  string       HTML markup for the field.
		 */
		protected function _render_field_text( $key, $current_val, $args ) {
			$html = '<input class="widefat" id="' . esc_attr( $args['html_id'] ) . '" name="' . esc_attr( $key ) . '" size="40" type="text" value="' . esc_attr( $current_val ) . '" />' . "\n";

			echo $html;
		}

		/**
		 * Render HTML markup for the "radio" field type.
		 * @access  protected
		 * @since   1.0
		 *
		 * @param   string $key The unique ID of this field.
		 * @param   string $current_val The current value of the field
		 * @param   array $args Arguments used to construct this field.
		 *
		 * @return  string       HTML markup for the field.
		 */
		protected function _render_field_radio( $key, $current_val, $args ) {
			$html = '';
			if ( isset( $args['options'] ) && ( 0 < count( (array) $args['options'] ) ) ) {
				foreach ( $args['options'] as $k => $v ) {
					$html .= '<label for="' . esc_attr( $key ) . '"><input id="' . esc_attr( $args['html_id'] ) . '" type="radio" name="' . esc_attr( $key );
					$html .= '" value="' . esc_attr( $k ) . '"' . checked( esc_attr( $current_val ), $k, false ) . ' /> ' . $v . '</label><br>' . "\n";
				}
			}

			echo $html;
		}

		/**
		 * Render HTML markup for the "textarea" field type.
		 * @access  protected
		 * @since   1.0
		 *
		 * @param   string $key The unique ID of this field.
		 * @param   string $current_val The current value of the field
		 *
		 * @return  string       HTML markup for the field.
		 */
		protected function _render_field_textarea( $key, $current_val, $args ) {
			$html = '<textarea id="' . esc_attr( $args['html_id'] ) . '" name="' . esc_attr( $key ) . '" cols="42" rows="5">' . $current_val . '</textarea>' . "\n";

			echo $html;
		}

		/**
		 * Render HTML markup for the "checkbox" field type.
		 * @access  protected
		 * @since   1.0
		 *
		 * @param   string $key The unique ID of this field.
		 * @param   array $current_val Arguments used to construct this field.
		 *
		 * @return  string       HTML markup for the field.
		 */
		protected function _render_field_checkbox( $key, $current_val, $args ) {
			$html = '<label class="label" for="' . esc_attr( $args['html_id'] ) . '">';
			$html .= '<input id="' . esc_attr( $args['html_id'] ) . '" name="' . esc_attr( $key ) . '" type="checkbox" value="true" ' . checked( $current_val, 'true', false ) . ' />';
			$html .= esc_html( $args['label'] ) . '</label>';
			echo $html;
		}

		/**
		 * Render HTML markup for the "select" field type.
		 * @access  protected
		 * @since   1.0
		 *
		 * @param   string $key The unique ID of this field.
		 * @param   string $current_val The current value of the field
		 * @param   array $args Arguments used to construct this field.
		 *
		 * @return  string       HTML markup for the field.
		 */
		protected function _render_field_select( $key, $current_val, $args ) {
			if ( isset( $args['options'] ) && ( 0 < count( (array) $args['options'] ) ) ) {
				echo '<select id="' . esc_attr( $args['html_id'] ) . '" name="' . esc_attr( $key ) . '">' . "\n";
				foreach ( $args['options'] as $k => $v ) {
					echo '<option value="' . esc_attr( $k ) . '" ';
					selected( esc_attr( $current_val ), $k );
					echo '>' . esc_html( $v ) . '</option>' . "\n";
				}
				echo '</select>' . "\n";
			}
		}

		/**
		 * Render HTML markup for the "color" field type.
		 * @access  protected
		 * @since   1.0
		 *
		 * @param   string $key The unique ID of this field.
		 * @param   string $current_val The current value of the field
		 *
		 * @return  string       HTML markup for the field.
		 */
		protected function _render_field_color( $key, $current_val, $args ) {
			$html = '<input class="sfxtp-color-field" id="' . esc_attr( $args['html_id'] ) . '" name="' . esc_attr( $key ) . '" type="text" value="' . esc_attr( $current_val ) . '" />';

			echo $html;
		}

		/**
		 * Render HTML markup for the "image" field type.
		 * @access  protected
		 * @since   1.0
		 *
		 * @param   string $key The unique ID of this field.
		 * @param   string $current_val The current value of the field
		 *
		 * @return  string       HTML markup for the field.
		 */
		protected function _render_field_image( $key, $current_val, $args ) {
			$html = '<input class="image-upload-path" type="text" id="' . esc_attr( $args['html_id'] ) . '" style="width: 200px; max-width: 100%;" name="';
			$html .= esc_attr( $key ) . '" value="' . esc_attr( $current_val ) . '" /><button class="button upload-button">Upload</button>';

			echo $html;
		}
	}
}