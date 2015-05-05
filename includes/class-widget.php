<?php
/**
 * Created by PhpStorm.
 * User: shramee
 * Date: 29/4/15
 * Time: 1:24 PM
 */

class SFXTP_Widget extends WP_Widget {

	private $render;

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {

		parent::__construct(

			'sfx-telephone', // Base ID

			__( 'Telephone', 'sfx-telephone' ), // Name

			array( 'description' => __( 'Adds telephone number to widget area', 'sfx-telephone' ), ) // Args

		);

		$this->render = new SFXTP_Widget_Render_Control();

	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $i Instance : Saved values from database.
	 */
	public function widget( $args, $i ) {

		//Return if neither phone nor skype is set
		if( empty( $i['phone'] ) && empty( $i['skype'] ) ){
			return;
		}

		//The mobile class
		$classes = $i['only-mobile'] ? 'mobile' : '';

		//Adding the classes to before widget args
		echo str_replace( 'widget_sfx-telephone', 'widget_sfx-telephone ' . $classes, $args['before_widget'] );

		/** @var string $id Caching the widget id */
		$id = $this->id;

		//Widget styles
		$html = $this->widget_styles( $i, $id );

		//Widget html
		$html .= $this->widget_html( $i, $id, $classes );

		//Print $html
		echo $html;

		//After widget args
		echo $args['after_widget'];
	}

	/**
	 * Returns the styles for the widget
	 *
	 * @param array $i instance
	 * @param string $id the html id of the widget
	 *
	 * @return string Styles for the widget
	 */
	private function widget_styles( $i, $id ){
		/** @var string $css The CSS for the widget */
		$css = '';

		//Widget background
		$css .= "#{$id}{\n";
		$css .= "\t background: {$i['bg-color']};\n";
		$css .= "}\n";

		//Icon color and border
		$css .= "#{$id} .icon{\n";
		$css .= "\t color: {$i['icon-color']};\n";
		$css .= "\t border: {$i['i-border-width']}px solid;\n";
		$css .= "\t border-radius: {$i['i-border-radius']}px;\n";
		$css .= "}\n";

		//Icon hover color
		$css .= "#{$id} a:hover .icon{\n";
		$css .= "\t color: {$i['icon-hover-color']};\n";
		$css .= "}\n";

		return "<style>{$css}</style>";
	}

	private function widget_html( $i, $id, $classes ){
		if ( empty( $i['skype'] ) ) {
			$classes .= 'sfxtp-phone-link ';
			$url = 'callto://' . preg_replace( "/[^0-9]/", '',  $i['phone'] );
			$span_text = $i['phone'];
			$span_classes = 'sfxtp-phone';
		} else {
			$classes .= 'sfxtp-skype-link ';
			$url = 'skype://' . preg_replace( "/[^.a-z\d]/i", '',  $i['skype'] );
			$span_text = $i['skype'];
			$span_classes = 'sfxtp-skype';
		}

		//Adding display format ( button / text ) class
		$classes .= $i['display-format'];

		//Adding anchor
		$html = "<a class='{$classes}' href='{$url}'>";

		$html .= $this->widget_icon( $i, $id );

		//Adding Call to action text
		$html .= '<span class="sfxtp-cta-text">' . $i['cta-text'] . '</span>';

		//Adding the contact
		$html .= "<span class='{$span_classes}'> $span_text </span>";

		return $html;
	}

	/**
	 * Returns html for icon
	 *
	 * @param array $i instance
	 *
	 * @return string Styles for the widget
	 */
	private function widget_icon( $i ){

		//The icon
		$icon = $this->get_icon_class( $i['fa-icon'] );

		//Mobile icon
		if ( 'desktop' != $i['fa-icon-mobile'] ) {
			$icon_mob = $this->get_icon_class( $i['fa-icon'] );
		} else {
			$icon_mob = $icon;
		}

		//Adding icon
		$html = $this->get_icon_html( $icon, 'icon fa' );
		$html .= $this->get_icon_html( $icon_mob, 'mobile icon fa' );

		//Closing anchor
		$html .= '</a>';

		return $html;
	}

	/**
	 * Returns the FA icon class
	 * @param string $value
	 * @return bool|string
	 */
	private function get_icon_class( $value ) {
		if ( 'none' == $value ) {
			$icon = false;
		} else {
			$icon = $value;
		}

		return $icon;
	}

	/**
	 * @param string $icon_class
	 * @param string $classes
	 * @return string|bool HTML for icon output
	 */
	private function get_icon_html( $icon_class, $classes='' ) {
		if ( $icon_class ) {
			return "<i class=' $classes $icon_class '></i>";
		}

		return '';
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		$fields = $this->fields();

		foreach ( $fields as $f ){

			$current =  ! empty( $instance[ $f['id'] ] ) ? $instance[ $f['id'] ] : '';
			$f['key'] = $this->get_field_name( $f['id'] );
			$f['html_id'] = $this->get_field_id( $f['id'] );
			$this->render->render_field( $f, $current, 'sfxtp' );

		}

		?>
<script>
( function( $ ) {
	$('.sfxtp-color-field').wpColorPicker( {
		change: function(e, ui){
			$(e.target)
				.val(ui.color.toString())
				.change();
		},
		clear: true,
		width: 200
	} );
} )( jQuery );
</script>
	<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $new_instance;
		$instance['only-mobile'] = ! empty( $new_instance['only-mobile'] );

		return $instance;
	}

	public function fields(){

		global $SFX_Telephone_widget_fields;

		$fields = array();


		return array_merge( $fields, $SFX_Telephone_widget_fields );

	}

} // end class
	//@TODO Register Widget