<?php
/**
 * Plugin Name: RHD MailChimp Widget
 * Author: Roundhouse Designs
 * Description: A simple pre-configured MailChimp list signup form.
 * Author URI: https://roundhouse-designs.com
 * Version: 1.31
 **/

define( 'RHD_MC_DIR', plugin_dir_url(__FILE__) );

class RHD_Mailchimp_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
	 		'rhd_mailchimp_widget', // Base ID
			'RHD MailChimp Signup', // Name
			array( 'description' => __( 'Pre-configured MailChimp subscribe widget.', 'rhd' ), ) // Args
		);

		add_action( 'wp_enqueue_scripts', array( $this, 'display_styles' ) );
	}

	public function display_styles() {
		wp_enqueue_script( 'rhd-mailchimp-js', RHD_MC_DIR . 'js/rhd-mailchimp.js', array( 'jquery' ) );
		wp_enqueue_style( 'rhd-mailchimp-css', RHD_MC_DIR . 'css/rhd-mailchimp.css' );
	}

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		return $new_instance;
	}

	public function widget( $args, $instance ) {
		$atts['title'] = apply_filters( 'widget_title', $instance['title'] );
		$atts['text'] = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
		$atts['button'] = ( ! empty( $instance['button'] ) ) ? $instance['button'] : "Submit";
		$atts['fname'] = ( ! empty( $instance['fname'] ) ) ? true : false;
		$atts['lname'] = ( ! empty( $instance['lname'] ) ) ? true : false;

		preg_match_all( '!\d+!', $this->id, $matches );

		$widget_id = intval( $matches[0][0] );

		echo rhd_mailchimp_widget( $args, $atts, $widget_id );
	}

	public function form( $instance ) {
		// outputs the options form on admin
		$args['title'] = ( ! empty( $instance['title'] ) ) ? esc_attr( $instance['title'] ) : '';
		$args['text'] = ( ! empty( $instance['text'] ) ) ? esc_textarea( $instance['text'] ): '';
		$args['button'] = ( ! empty( $instance['button'] ) ) ? esc_attr( $instance['button'] ) : '';
		$args['fname'] = ( ! empty( $instance['fname'] ) ) ? esc_attr( $instance['fname'] ) : '';
		$args['lname'] = ( ! empty( $instance['lname'] ) ) ? esc_attr( $instance['lname'] ): '';
		?>

		<h3>Widget Options:</h3>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php  _e( 'Widget Title (optional): ' ); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $args['title']; ?>" >
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Text to display (optional): ' ); ?></label>
			<textarea class="widefat" rows="3" cols="20" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo $args['text']; ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'button'); ?>">Button text <em>(Default: Submit)</em>:</label>
			<input id="<?php echo $this->get_field_id('button'); ?>" name="<?php echo $this->get_field_name( 'button' ); ?>" type="text" value="<?php echo $args['button']; ?>" >
		</p>

		<h3>Optional Fields:</h3>
		<p>
			<input id="<?php echo $this->get_field_id( 'fname' ); ?>" name="<?php echo $this->get_field_name( 'fname' ); ?>" type="checkbox" value="yes" <?php if( $args['fname'] === 'yes' ){ echo 'checked="checked"'; } ?> />
			<label for="<?php echo $this->get_field_id( 'fname' ); ?>"><?php _e( 'First Name' ); ?></label>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'lname' ); ?>" name="<?php echo $this->get_field_name( 'lname' ); ?>" type="checkbox" value="yes" <?php if( $args['lname'] === 'yes' ){ echo 'checked="checked"'; } ?> />
			<label for="<?php echo $this->get_field_id( 'style_vertical' ); ?>"><?php _e( 'Last Name'); ?></label>
		</p>

	<?php
	}
}

function rhd_register_mailchimp_widget() {
    register_widget( 'RHD_Mailchimp_Widget' );
}
add_action( 'widgets_init', 'rhd_register_mailchimp_widget' );


/* ==========================================================================
	Function
   ========================================================================== */

function rhd_mailchimp_widget( $args, $atts, $w_id = null ) {
	extract( $args );
	$w_id = $w_id ? $w_id : rand( 101,200 );

	$output = $before_widget;

	$output .= "<div class=\"rhd-mailchimp-container\">\n";

	if ( $atts['title'] )
		$output .= $before_title . $atts['title'] . $after_title;


	$output .= "<div class=\"rhd-mailchimp clearfix\">\n";

	if ( !empty( $atts['text'] ) )
		$output .= "<p class=\"rhd-mc-text\">{$atts['text']}</p>\n";

	$output .= "
				<form id=\"rhd-mc-subscribe-{$w_id}\" class=\"rhd-mc-subscribe clearfix\" action=\"" . RHD_MC_DIR . "lib/rhd-mc-subscribe.php\" method=\"post\">\n";

				if ( $atts['fname'] )
					$output .= "<input id=\"rhd-mc-fname-{$w_id}\" class=\"rhd-mc-fname\" type=\"text\" name=\"fname\" placeholder=\"First Name\">\n";

				if ( $atts['lname'] )
					$output .= "<input id=\"rhd-mc-lname-{$w_id}\" class=\"rhd-mc-lname\" type=\"text\" name=\"lname\" placeholder=\"Last Name\">\n";

	$output .= "
					<input id=\"rhd-mc-email-{$w_id}\" class=\"rhd-mc-email\" type=\"email\" name=\"email\" placeholder=\"Email\">\n
					<input class=\"rhd-mc-form-id\" type=\"hidden\" value=\"{$w_id}\">\n
					<input id=\"rhd-mc-submit-{$w_id}\" class=\"rhd-mc-submit\" type=\"submit\" value=\"{$atts['button']}\" name=\"submit-{$w_id}\">\n
				</form>\n
				<div id=\"rhd-mc-thanks-{$w_id}\" class=\"rhd-mc-thanks\">\n
					<p>Subscribed!</p>\n
				</div>\n
				<div id=\"rhd-mc-error-{$w_id}\" class=\"rhd-mc-error\">\n
					Please enter a valid email address.
				</div>\n
			</div>\n
		</div>\n";

	$output .= $after_widget;

	return $output;
}


/* ==========================================================================
	Shortcode
   ========================================================================== */

add_shortcode( 'rhd-mailchimp', 'rhd_mailchimp_shortcode' );
function rhd_mailchimp_shortcode( $atts )
{
	shortcode_atts( array(
		'title' 	=> '',
		'text'		=> null,
		'button'	=> null,
		'fname'		=> null,
		'lname'		=> null
	), $atts );

	$args = array(
		'before_title'	=> '<h2 class="widget-title">',
		'after_title'	=> '</h2>',
		'before_widget' => '<div class="widget widget-rhd-mailchimp rhd-mailchimp-shortcode">',
		'after_widget'  => '</div>'
	);

	$output = rhd_mailchimp_widget( $args, $atts, rand( 99, 999 ) );

	return $output;
}
