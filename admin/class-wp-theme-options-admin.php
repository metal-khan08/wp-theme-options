<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       //allmarketingsolutions.co.uk
 * @since      1.0.0
 *
 * @package    Wp_Theme_Options
 * @subpackage Wp_Theme_Options/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Theme_Options
 * @subpackage Wp_Theme_Options/admin
 * @author     All Marketing Solutions <help@allmarketingsolutions.co.uk>
 */
class Wp_Theme_Options_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Theme_Options_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Theme_Options_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-theme-options-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Theme_Options_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Theme_Options_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-theme-options-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register Wp Theme options page.
	 *
	 * @since    1.0.0
	 */
	function wp_theme_options_settings_page(){
		add_menu_page( 'WP Theme Options', 'Theme Options', 'manage_options', 'wp-theme-settings', array($this,'wp_theme_settings_html'), 'dashicons-admin-generic', 16 );
	}


	/**
	 * Calback for options page.z
	 *
	 * @since    1.0.0
	 */
	function wp_theme_settings_html(){
	?>
	<h1>
		Test page
	</h1>
	<?php
	}

	
function my_alter_logo_fx( $html, $blog_id ) {

    // code here to alter the logo $html depending on the current page, for example make the homepage logo redirect to Google:
    if ( is_front_page() ) {
        $html = sprintf(
	    '<a href="%1$s" class="custom-logo-link" rel="home">%2$s</a>', 'https://google.com', '<img src="http://wp-theme-options.local/wp-content/uploads/2022/08/Trauma-Safe-Badge-2.png" width="300" height="100" alt="website logo">' );
    }
    return $html;

}

}
