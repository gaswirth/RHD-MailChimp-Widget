<?php
/**
 * Plugin Name: RHD MailChimp Widget
 * Author: Roundhouse Designs
 * Description: A simple pre-configured MailChimp list signup form.
 * Author URI: https://roundhouse-designs.com
 * Version: 1.1
 **/

define( 'RHD_MC_DIR', plugin_dir_url(__FILE__) );

class RHD_Mailchimp_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
	 		'rhd_mailchimp_widget', // Base ID
			'RHD MailChimp Subscribe Widget', // Name
			array( 'description' => __( 'Pre-configured MailChimp subscribe widget.', 'rhd' ), ) // Args
		);
	}

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		return $new_instance;
	}

	public function widget( $args, $instance ) {
		extract( $args );

		wp_enqueue_script( 'rhd-mailchimp-js', RHD_MC_DIR . '/rhd-mailchimp.js', array( 'jquery' ) );
		wp_enqueue_style( 'rhd-mailchimp-css', RHD_MC_DIR . '/rhd-mailchimp.css' );

		$args['title'] = apply_filters( 'widget_title', $instance['title'] );
		$args['text'] = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
		$args['button'] = ( !empty( $instance['button'] ) ) ? $instance['button'] : "Submit";
		$args['fname'] = ( $instance['fname'] == 'yes' ) ? true : false;
		$args['lname'] = ( $instance['lname'] == 'yes' ) ? true : false;

		echo $before_widget;

		if ( $args['title'] )
			echo $before_title . $args['title'] . $after_title;

		$widget_id = substr( $this->id, -1, 1 );
		?>

		<div id="rhd_mc_widget-<?php echo $widget_id; ?>" class="rhd_mailchimp clearfix">
			<p class="rhd_mc_text"><?php echo $args['text']; ?></p>
			<form id="rhd_mc_subscribe-<?php echo $widget_id; ?>" class="rhd_mc_subscribe clearfix" action="<?php echo RHD_THEME_DIR; ?>/lib/mc/mc_subscribe.php" method="post">
				<?php if ( $args['fname'] ) : ?>
					<input id="rhd_mc_fname-<?php echo $widget_id; ?>" class="rhd_mc_fname" type="text" name="fname" placeholder="first">
				<?php endif; ?>

				<?php if ( $args['lname'] ) : ?>
					<input id="rhd_mc_lname-<?php echo $widget_id; ?>" class="rhd_mc_lname" type="text" name="lname" placeholder="last">
				<?php endif; ?>

				<input id="rhd_mc_email-<?php echo $widget_id; ?>" class="rhd_mc_email" type="email" name="email" placeholder="email">
				<input class="rhd_mc_form_id" type="hidden" value="<?php echo $widget_id; ?>">
				<input id="rhd_mc_submit-<?php echo $widget_id; ?>" class="rhd_mc_submit" type="submit" value="<?php echo $args['button']; ?>" name="submit-<?php echo $widget_id; ?>">
			</form>
			<div id="rhd_mc_thanks-<?php echo $widget_id; ?>" class="rhd_mc_thanks">
				<p>Subscribed!</p>
			</div><!-- #rhd_mc_thanks-<?php echo $widget_id; ?> -->
			<div id="rhd_mc_error-<?php echo $widget_id; ?>" class="rhd_mc_error">
				Please enter a valid email address.
			</div><!-- #rhd_mc_error-<?php echo $widget_id; ?> -->
		</div><!-- <?php echo $this->id; ?> -->

	<?php echo $after_widget;

	}

	public function form( $instance ) {
		// outputs the options form on admin
		$args['title'] = esc_attr( $instance['title'] );
		$args['text'] = esc_textarea( $instance['text'] );
		$args['button'] = esc_attr( $instance['button'] );
		$args['fname'] = esc_attr( $instance['fname'] );
		$args['lname'] = esc_attr( $instance['lname'] );
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