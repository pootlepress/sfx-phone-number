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

		if( empty( $i['phone'] ) && empty( $i['skype'] ) ){
			print_awesome_r( $i );
			return;
		}

		echo $args['before_widget'];

		$id = $this->id;

		$icon = ( 'none' == $i['fa-icon'] ) ? false : $i['fa-icon'];

		if ( 'desktop' != $i['fa-icon-mobile'] ){
			$icon_mob = ( 'none' == $i['fa-icon-mobile'] ) ? false : $i['fa-icon-mobile'];
		} else {
			$icon_mob = $icon;
		}


		/** @var string $css The CSS for the widget */
		$css = '';
		$css .= "#{$id}{\n";
		$css .= "background: {$i['bg-color']};\n";
		$css .= "}\n";
		$css .= "#{$id} .icon{\n";
		$css .= "color: {$i['icon-color']};\n";
		$css .= "border: {$i['i-border-width']}px solid;\n";
		$css .= "border-radius: {$i['i-border-radius']}px;\n";
		$css .= "}\n";
		$css .= "#{$id} a:hover .icon{\n";
		$css .= "color: {$i['icon-hover-color']};\n";
		$css .= "}\n";

		/** @var string $html The HTML output for the widget */
		$html = "<style>{$css}</style>";

		if ( '' == $i['skype'] ) {
			$html .= '<a class="sfxtp-phone-link" href="callto://' . preg_replace( "/[^0-9]/", '',  $i['phone'] ) . '">';
		} else {
			$html .= '<a class="sfxtp-skype-link" href="skype://' . preg_replace( "/[^.a-z\d]/i", '',  $i['skype'] ) . '">';
		}

		$html .= $icon ? '<i class="icon fa ' . $icon . '"></i>' : '' ;
		$html .= $icon_mob ? '<i class="mobile icon fa ' . $icon_mob . '"></i>' : '' ;

		$html .= '<span class="sfxtp-cta-text">' . $i['cta-text'] . '</span>';

		if ( '' == $i['skype'] ) {
			$html .= '<span class="sfxtp-phone"> ' . $i['phone'] . ' </span>';
		} else {
			$html .= '<span class="sfxtp-skype"> ' . $i['skype'] . ' </span>';
		}

		$html .= '</a>';
		echo $html;

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'sfx-telephone' );
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