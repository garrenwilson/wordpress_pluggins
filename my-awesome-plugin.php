<?php
/*
 * Plugin Name: My Awesome Plugin
 * Version: 1.0
 * Plugin URI:
 * Description: A Description of my Plugin
 * Author: Garren Wilson
 * Author URI: http://www.hughlashbrooke.com/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: my-awesome-plugin
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Garren Wilson
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-my-awesome-plugin.php' );
require_once( 'includes/class-my-awesome-plugin-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-my-awesome-plugin-admin-api.php' );
require_once( 'includes/lib/class-my-awesome-plugin-post-type.php' );
require_once( 'includes/lib/class-my-awesome-plugin-taxonomy.php' );

/**
 * Returns the main instance of My_Awesome_Plugin to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object My_Awesome_Plugin
 */
function My_Awesome_Plugin () {
	$instance = My_Awesome_Plugin::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = My_Awesome_Plugin_Settings::instance( $instance );
	}

	return $instance;
}


//Randomly Add Item to Cart
function add_random_product_to_cart() {
	//if ( ! is_admin() ) {

		global $woocommerce;

		$args = array( 'post_type' => 'product', 'post_status' => 'publish',
		'posts_per_page' => -1 );
		$products = new WP_Query( $args );

		$productTotal = $products->found_posts;
		echo $products;
		$product_id = $ rand ( 0 , $products );
		$found = false;

		//check if product already in cart
		if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
		//	foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
		//		$_product = $values['data'];
			//	if ( $_product->id == $product_id )
					$found = true;
			//}
			// if product not found, add it
		  //else ( ! $found )
			//	$woocommerce->cart->add_to_cart( $product_id );
		} else {
			// if no products in cart, add it
			$woocommerce->cart->add_to_cart( $product_id );
		}
	//}
}
//add_action( 'init', 'add_product_to_cart' );

add_shortcode('randomize_a_flower', 'add_random_product_to_cart');


//WIDGET Area

/**
 * Example Widget Class
 */
class example_widget extends WP_Widget {


    /** constructor -- name this the same as the class above */
    function example_widget() {
        parent::WP_Widget(false, $name = 'Example Text Widget');
    }

    /** @see WP_Widget::widget -- do not rename this */
    function widget($args, $instance) {
        extract( $args );
        $title 		= apply_filters('widget_title', $instance['title']);
        $message 	= $instance['message'];
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
							<ul>
								<li><?php echo $message; ?></li>
							</ul>
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['message'] = strip_tags($new_instance['message']);
        return $instance;
    }

    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {

        $title 		= esc_attr($instance['title']);
        $message	= esc_attr($instance['message']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
          <label for="<?php echo $this->get_field_id('message'); ?>"><?php _e('Simple Message'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('message'); ?>" name="<?php echo $this->get_field_name('message'); ?>" type="text" value="<?php echo $message; ?>" />
        </p>
        <?php
    }


} // end class example_widget
add_action('widgets_init', create_function('', 'return register_widget("example_widget");'));




My_Awesome_Plugin();
