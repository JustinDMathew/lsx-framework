<?php



class Lsx_Properties_Widget extends WP_Widget {

	/**
	 * Create object
	 *
	 * @since 0.0.1
	 */
	function Lsx_Properties_Widget() {
		// Instantiate the parent object
		parent::__construct( false, __('Properties', 'happybeds-properties' ) );
	}

	/**
	 * Render the  widget
	 *
	 * @since 0.0.1
	 *
	 * @param array $args
	 * @param Lsx_Properties_Widget $instance
	 */
	function widget( $args, $instance ) {

		

			extract($args, EXTR_SKIP);

			$out[] = $before_widget;
			$title = empty( $instance[ 'title' ] ) ? ' ' : apply_filters( 'widget_title', $instance[ 'title' ] );
			if ( ! empty( $title ) ) {
				$out[] = $before_title . $title . $after_title;
			};

			$args = array();

			if( !empty( $instance['limit'] ) ){
				$args['limit'] = $instance['limit'];
			}
			if( !empty( $instance['order'] ) ){
				$args['order'] = $instance['order'];
			}
			if(!empty( $instance['featured'] ) ){
				$args['featured'] = true;
			}

			$happybeds_properties = Lsx_Properties::get_instance();
			$content = $happybeds_properties->render_property( $args, null, null );
			if( empty( $content ) ){
				return;
			}
			$out[] = $content;
			$out[] = $after_widget;

			echo implode( '', $out );

	}

	/**
	 * Update widget settings
	 *
	 * @since 0.0.1
	 *
	 * @param Lsx_Properties_Widget $new_instance
	 * @param Lsx_Properties_Widget $old_instance
	 *
	 * @return Lsx_Properties_Widget
	 */
	function update( $new_instance, $old_instance ) {
		return $new_instance;

	}

	/**
	 * Render form
	 *
	 * @since 0.1.0
	 *
	 * @param Lsx_Properties_Widget $instance Class instance
	 */
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
				'title' => '',
				'order' => '',
				'limit' => '',
				'featured' => false,
				'slider' => false
			)
		);

		$title = strip_tags($instance['title']);
		$limit = strip_tags($instance['limit']);

		$isrand = '';
		if( $instance['order'] == 'rand' ){
			$isrand = 'selected="selected"';
		}
		$isrecent = '';
		if( $instance['order'] == 'order_recent' ){
			$isrecent = 'selected="selected"';
		}


		echo "<p><label for=\" " . $this->get_field_id( 'title' ) . "\">" . __( 'Title', 'happybeds-properties' ) . ": <input class=\"widefat\" id=\"" . $this->get_field_id( 'title' ) . "\" name=\"" . $this->get_field_name( 'title' ) . "\" type=\"text\" value=\"" . esc_attr($title). "\" /></label></p>\r\n";

		echo "<p><label for=\" " . $this->get_field_id( 'limit' ) . "\">" . __( 'Limit', 'happybeds-properties' ) . ": <input id=\"" . $this->get_field_id( 'limit' ) . "\" name=\"" . $this->get_field_name( 'limit' ) . "\" type=\"number\" value=\"" . esc_attr($limit). "\" /></label></p>\r\n";

		$featured = '';
		if( !empty( $instance['featured'] ) ){
			$featured = 'checked="checked"';
		};
		echo "<p><label for=\"" . $this->get_field_id( 'featured' ) . "\">" . __( 'Show Featured Only', 'happybeds-properties' ) . ": <input id=\"" . $this->get_field_id( 'featured' ) . "\" ".$featured." type=\"checkbox\" name=\"" . $this->get_field_name( 'featured' ) . "\" ></label>\r\n";

		$slider = '';
		if( !empty( $instance['slider'] ) ){
			$slider = 'checked="checked"';
		};

		echo "<p><label for=\" " . $this->get_field_id( 'order' ) . "\">" . __( 'Order', 'happybeds-properties' ) . ": </label><select style=\"width:100%;\" name=\"" . $this->get_field_name( 'order' ) . "\">\r\n";

			echo "<option value=\"\"></option>\r\n";
			echo "<option value=\"rand\" ".$isrand."\">" . __('Random', 'happybeds-properties') ."</option>\r\n";
			echo "<option value=\"order_recent\" ".$isrecent.">" . __('Recent', 'happybeds-properties') ."</option>\r\n";

		echo "</select></p>\r\n";

		echo "<p><label for=\"" . $this->get_field_id( 'slider' ) . "\">" . __( 'Show as Slider', 'happybeds-properties' ) . ": <input id=\"" . $this->get_field_id( 'slider' ) . "\" ".$slider." type=\"checkbox\" name=\"" . $this->get_field_name( 'slider' ) . "\" ></label>\r\n";


	}

}

//add widget
add_action( 'widgets_init', function() {
	register_widget( 'Lsx_Properties_Widget' );
} );