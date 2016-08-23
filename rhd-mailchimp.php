<?php
/**
 * Plugin Name: RHD MailChimp Widget
 * Author: Roundhouse Designs
 * Description: A MailChimp signup widget from Roundhouse Designs.
 * Author URI: https://roundhouse-designs.com
 * Version: 2.0.3
 **/

/* ==========================================================================
	Initialization
   ========================================================================== */

define( 'RHD_MC_DIR', plugin_dir_url( __FILE__ ) );
define( 'RHD_MC_PATH', plugin_dir_path( __FILE__ ) );

require_once( RHD_MC_PATH . '/vendor/autoload.php' );
use \DrewM\MailChimp\MailChimp;

$options = get_option( 'rhd_mc_settings' );


/* ==========================================================================
	Base Functionality
   ========================================================================== */


/**
 * rhd_mailchimp function.
 *
 * @access public
 * @param mixed $args
 * @param mixed $atts
 * @param mixed $hash (default: null)
 * @return void
 */
function rhd_mailchimp( $args, $atts, $hash = null ) {
	extract( $args );
	extract( $atts );

	$button = $button ? esc_attr( $button ) : 'Submit';

	$hash = $hash ? $hash : rand( 101,200 );

	$output = $before_widget;

	$output .= "<div class=\"rhd-mailchimp-container\">\n";

	if ( $title )
		$output .= $before_title . $title . $after_title;


	$output .= "<div class=\"rhd-mailchimp clearfix\">\n";

	if ( !empty( $text ) )
		$output .= "<p class=\"rhd-mc-text\">{$text}</p>\n";

	$output .= "
				<form id=\"rhd-mc-subscribe-{$hash}\" class=\"rhd-mc-subscribe clearfix\" action=\"#\" method=\"post\">\n";

				if ( $fname ) {
					$output .= "<input id=\"rhd-mc-fname-{$hash}\" class=\"rhd-mc-fname\" type=\"text\" name=\"fname\" placeholder=\"First Name\">\n";
				} else {
					$output .= "<input id=\"rhd-mc-fname-{$hash}\" type=\"hidden\" name=\"fname\" value=\"0\" >\n";
				}

				if ( $lname ) {
					$output .= "<input id=\"rhd-mc-lname-{$hash}\" class=\"rhd-mc-lname\" type=\"text\" name=\"lname\" placeholder=\"Last Name\">\n";
				} else {
					$output .= "<input id=\"rhd-mc-lname-{$hash}\" type=\"hidden\" name=\"lname\" value=\"0\" >\n";
				}
	$output .= "
					<input id=\"rhd-mc-email-{$hash}\" class=\"rhd-mc-email\" type=\"email\" name=\"email\" placeholder=\"Email\">\n
					<input class=\"rhd-mc-form-id\" type=\"hidden\" value=\"{$hash}\">\n
					<input id=\"rhd-mc-list-id-{$hash}\" type=\"hidden\" name=\"list_id\" value=\"{$list_id}\" />
					<input id=\"rhd-mc-submit-{$hash}\" class=\"rhd-mc-submit\" type=\"submit\" value=\"{$button}\" name=\"submit-{$hash}\">\n
				</form>\n
				<div id=\"rhd-mc-thanks-{$hash}\" class=\"rhd-mc-thanks\">\n
					<p>Subscribed!</p>\n
				</div>\n
				<div id=\"rhd-mc-error-{$hash}\" class=\"rhd-mc-error\">\n
					Please enter a valid email address.
				</div>\n
			</div>\n
		</div>\n";

	$output .= $after_widget;

	return $output;
}


function rhd_mc_submit() {
	global $options;

	$data = $_POST['data'];
	$apikey = esc_attr( $options['rhd_mc_api_key'] );
	$list_id = $data['list_id'];

	$email = $data['email'];
	$fname = ( ! empty( $data['fname'] ) ) ? $data['fname'] : null;
	$lname = ( ! empty( $data['lname'] ) ) ? $data['lname'] : null;

	$mc = new MailChimp( $apikey );

	// By default this sends a confirmation email - you will not see new members
	// until the link contained in it is clicked!

	$result = $mc->post( "lists/{$list_id}/members", [
		'email_address'	=> $email,
		'merge_fields'	=> ['FNAME'=>$fname, 'LNAME'=>$lname],
		'status'		=> 'pending'
	]);

	if ( $mc->errorCode ){
		header( 'MailChimp error: ' . $mc->getLastError() );
	}
}
add_action( 'wp_ajax_rhd_mc_submit', 'rhd_mc_submit' );
add_action( 'wp_ajax_nopriv_rhd_mc_submit', 'rhd_mc_submit' );


/* ==========================================================================
	Admin Settings
   ========================================================================== */

function rhd_mc_settings_init() {
	register_setting(
		'rhd_site_settings',
		'rhd_mc_settings',
		'rhd_mc_sanitize'
	);

	add_settings_section(
		'rhd_mc_settings_section',
		'MailChimp Settings',
		'',
		'rhd-settings-admin'
	);

	add_settings_field(
		'rhd_mc_api_key',
		'API Key',
		'rhd_mc_api_key_cb',
		'rhd-settings-admin',
		'rhd_mc_settings_section'
	);
}
add_action( 'admin_init', 'rhd_mc_settings_init' );


function rhd_mc_api_key_cb() {
	global $options;
	$apikey = $options['rhd_mc_api_key'];

	?>
	<p>
		<input type="text" id="rhd_mc_api_key" name="rhd_mc_settings[rhd_mc_api_key]" class="widefat" value="<?php echo esc_attr( $apikey ); ?>" />
	</p>
	<?php
}


function rhd_mc_sanitize( $input ) {
	$valid = array();
	$valid['rhd_mc_api_key'] = preg_match( '/^[0-9a-z]{32}(-us)(0?[1-9]|1[0-3])?$/', $input['rhd_mc_api_key'] ) ? $input['rhd_mc_api_key'] : false;

	if ( $valid['rhd_mc_api_key'] != $input['rhd_mc_api_key'] ) {
		add_settings_error(
			'rhd_mc_api_key',
			'rhd_mc_api_key_error',
			'Invalid MailChimp API Key format.',
			'error'
		);
	}

	return $valid;
}


/* ==========================================================================
	Widget
   ========================================================================== */

class RHD_MailChimp extends WP_Widget {
	public function __construct() {
		parent::__construct(
	 		'rhd_mailchimp', // Base ID
			'RHD MailChimp', // Name
			array( 'description' => __( 'A MailChimp signup widget from Roundhouse Designs.', 'rhd' ), ) // Args
		);

		add_action( 'wp_enqueue_scripts', array( $this, 'display_styles' ) );
	}

	public function display_styles() {
		wp_enqueue_script( 'rhd-mailchimp-js', RHD_MC_DIR . 'js/rhd-mailchimp.js', array( 'jquery' ) );
		wp_enqueue_style( 'rhd-mailchimp-css', RHD_MC_DIR . 'css/rhd-mailchimp.css' );

		wp_localize_script( 'rhd-mailchimp-js', 'rhd_mc_ajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
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

		echo rhd_mailchimp( $args, $atts, $widget_id );
	}

	public function form( $instance ) {
		// outputs the options form on admin
		$args['title'] = ( ! empty( $instance['title'] ) ) ? esc_attr( $instance['title'] ) : '';
		$args['list_id'] = ( ! empty( $instance['list_id'] ) ) ? esc_attr( $instance['list_id'] ) : '';
		$args['text'] = ( ! empty( $instance['text'] ) ) ? esc_textarea( $instance['text'] ): '';
		$args['button'] = ( ! empty( $instance['button'] ) ) ? esc_attr( $instance['button'] ) : '';
		$args['fname'] = ( ! empty( $instance['fname'] ) ) ? esc_attr( $instance['fname'] ) : '';
		$args['lname'] = ( ! empty( $instance['lname'] ) ) ? esc_attr( $instance['lname'] ): '';
		?>

		<h3>Widget Options:</h3>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php  _e( 'Widget Title (optional): ' ); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $args['title']; ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'list_id' ); ?>"><?php _e( 'MailChimp List ID:' ); ?></label>
			<input id="<?php echo $this->get_field_id('list_id'); ?>" name="<?php echo $this->get_field_name( 'list_id' ); ?>" type="text" value="<?php echo $args['list_id']; ?>">
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
    register_widget( 'RHD_MailChimp' );
}
add_action( 'widgets_init', 'rhd_register_mailchimp_widget' );


/* ==========================================================================
	Shortcode
   ========================================================================== */

add_shortcode( 'rhd-mailchimp', 'rhd_mailchimp_shortcode' );
function rhd_mailchimp_shortcode( $atts )
{
	shortcode_atts( array(
		'title' 	=> '',
		'list_id'	=> null,
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

	$output = rhd_mailchimp( $args, $atts, rand( 99, 999 ) );

	return $output;
}
