<?php
/**
 * Plugin Name: Custom Widget Creator
 * Plugin URI: /#
 * Description: Create custom widgets on your website without writing code.
 * Version: 1.0.0
 * Author: Kafayat Faniran
 * Author URI: https://www.linkedin.com/in/kafayatfaniran
 * License: GPL2
 */

 if( ! defined( 'ABSPATH' )) {
  exit('What do you want!?');
 }

// Plugin activation and deactivation hooks
register_activation_hook(__FILE__, 'custom_widget_creator_activate');
register_deactivation_hook(__FILE__, 'custom_widget_creator_deactivate');

function custom_widget_creator_activate() {};

function custom_widget_creator_deactivate() {};

class CustomWidgetCreator {

    public function __construct() {
        // The hooks and filters
        add_action('widgets_init', array($this, 'register_custom_widget'));
    }

    // Registering the custom widget
    public function register_custom_widget() {
        register_widget('Custom_Widget');
    }
}

// Instantiate the plugin class here
new CustomWidgetCreator();

// Custom widget class
class Custom_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'custom_widget', // The widget ID
            __('Custom Widget', 'custom-widget-creator'), // Widget Name
            array(
                'description' => __('Create a custom widget without writing code.', 'custom-widget-creator')
            ) // Widget Description
        );
    }

    // This is the front-end display of the widget
    public function widget($args, $instance) {
        // Displaying the widget title
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        // Displaying the widget content
        echo '<div class="widget-content">' . wp_kses_post($instance['content']) . '</div>';

        // Also displaying the widget closing tag
        echo $args['after_widget'];
    }

    // The back-end widget form
    public function form($instance) {
        $title = !empty($instance['title']) ? esc_attr($instance['title']) : '';
        $content = !empty($instance['content']) ? esc_attr($instance['content']) : '';

        // Widget Title
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'custom-widget-creator'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <?php

        // The Widget Content
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('content'); ?>"><?php _e('Content:', 'custom-widget-creator'); ?></label>
            <textarea class="widefat" rows="10" id="<?php echo $this->get_field_id('content'); ?>" name="<?php echo $this->get_field_name('content'); ?>"><?php echo $content; ?></textarea>
        </p>
        <?php
    }

    // Sanitizing widget form values before saving
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['content'] = !empty($new_instance['content']) ? wp_kses_post($new_instance['content']) : '';

        return $instance;
    }
}

// Adding the CSS styles for the widget
function custom_widget_creator_enqueue_styles() {
    wp_enqueue_style('custom-widget-styles', plugins_url('css/custom-widget-styles.css', __FILE__));
}

add_action('wp_enqueue_scripts', 'custom_widget_creator_enqueue_styles');
