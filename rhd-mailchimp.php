<?php
/**
 * Plugin Name: RHD MailChimp Widget
 * Author: Roundhouse Designs
 * Description: A simple pre-configured MailChimp list signup form.
 * Author URI: https://roundhouse-designs.com
 * Version: 1.0
 **/
 
class rhd_mailchimp extends WP_Widget {
	public function __construct() {
		parent::__construct(
	 		'rhd_mailchimp', // Base ID
			'RHD MailChimp Subscribe Widget', // Name
			array( 'description' => __( 'Pre-configured MailChimp subscribe widget.', 'roundhouse-designs' ), ) // Args
		);
	}

	public function update($new_instance, $old_instance) {
		// processes widget options to be saved
		return $new_instance;
	}
    
	public function widget( $args, $instance ) {
		extract( $args );
		
		$title = apply_filters('widget_title', $instance['title']);
		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
		
		echo $before_widget;
		
		if ( $title )
			echo $before_title . $title . $after_title;
		?>
		
		<aside id="mailchimp-widget">
			<div id="mailchimp-widget-content">
				
				<!-- Ajax Loader Image (Replace if necessary) -->
				<img class="ajax-loader" src="<?php echo plugin_dir_url(__FILE__); ?>/img/ajax-loader.gif" alt="One moment, please">
				<p class="subscribe"><?php echo $text; ?></p>
				
				<form id="mc_subscribe" action="<?php echo plugin_dir_url(__FILE__); ?>/lib/rhd-mc-subscribe.php" method="post">
					<input id="fname" type="text" name="fname" placeholder="first">
					<input id="lname" type="text" name="lname" placeholder="last">
					<input id="email" type="email" name="email" placeholder="email">
					<input id="mc-submit" type="submit" name="mc-submit" value="join">
				</form>
			</div><!-- #mailchimp-widget-content -->
			<div id="mc_thanks">
				<p class="thanks-text">Thanks for subscribing!<br>
				<br>
				A confirmation email should be arriving soon.</p>
			</div><!-- #mc_thanks -->
		</aside><!-- #mailchimp-widget -->
	
	<?php echo $after_widget;
	
	}
	
	public function form($instance) {
		// outputs the options form on admin
		$args['title'] = esc_attr($instance['title']);
		$text = esc_textarea($instance['text']);
		?>
		
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Widget Title (optional): </label><input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $args['title']; ?>" ></p>

		<p><label for="<?php echo $this->get_field_id('text'); ?>">Text to display (optional):</label><textarea class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea></p>

		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label></p>
	
	<?php
	}
}

function rhd_register_mailchimp_widget() {
    register_widget('rhd_mailchimp');
}
add_action('widgets_init', 'rhd_register_mailchimp_widget');