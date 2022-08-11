<?php
/**
 * Wp theme options settings page template
 *
 * This file is used to markup the settings page for options page.
 *
 * @link       //allmarketingsolutions.co.uk
 * @since      1.0.0
 *
 * @package    Wp_Theme_Options
 * @subpackage Wp_Theme_Options/admin/partials
 */
if ( isset( $_GET['settings-updated'] ) ) {
    // add settings saved message with the class of "updated"
    add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
}

// show error/update messages
settings_errors( 'wporg_messages' );
?>
<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form action="options.php" method="post">
        <?php
        // output security fields for the registered setting "wp-theme-settings"
        settings_fields( 'wp-theme-settings' );
        // output setting sections and their fields
        // (sections are registered for "wp-theme-settings", each field is registered to a specific section)
        do_settings_sections( 'wp-theme-settings' );
        // output save settings button
        submit_button( 'Save Settings' );
        ?>
    </form>
</div>
<?php