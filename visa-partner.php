<?php
/*
 * Plugin Name:       Visa Search Form
 * Plugin URI:        https://github.com/glmfrk/visa-partner-plugin
 * Description:       This is a country with a visa filtering search box for travel management websites.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Gulam
 * Author URI:        https://facebook.com/gulamfrk
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       search_visa
 * Domain Path:       /languages
 */

// Prevent direct access
if (!defined('WPINC')) {
    die;
}


if (!defined('ABSPATH')) {
    exit;
}


// Enqueue styles and scripts
add_action('wp_enqueue_scripts', 'evisa_enqueue_scripts');
function evisa_enqueue_scripts() {
    wp_enqueue_style('visa-bootstrap', plugins_url('/assets/css/bootstrap.min.css', __FILE__));
    wp_enqueue_style('visa-style_inner', plugins_url('/assets/css/visa-style.css', __FILE__));
    wp_enqueue_style('visa-style', plugins_url('/style.css', __FILE__));
    
    wp_enqueue_script('visa-bootstrap-script', plugins_url('/assets/js/bootstrap.min.js', __FILE__), ['jquery'], null, true);
    wp_enqueue_script('visa-script', plugins_url('/assets/js/visaForm.js', __FILE__), ['jquery'], null, true);
}


if (file_exists( __DIR__ . '/inc/custom-taxonomy.php' )) {
    require_once __DIR__ . '/inc/custom-taxonomy.php';
}


// Activation hook
register_activation_hook(__FILE__, 'visa_partner_activate');
function visa_partner_activate() {
    // Register the custom taxonomies on activation
    visa_partner_register_taxonomies();
    // Clear the permalinks after the post type has been registered
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'visa_partner_deactivate');
function visa_partner_deactivate() {
    // Clear the permalinks
    flush_rewrite_rules();
}


if (file_exists( __DIR__ . '/inc/ajax-handler.php' )) {
    require_once __DIR__ . '/inc/ajax-handler.php';
}

if (file_exists( __DIR__ . '/inc/form-shortcode-1st.php' )) {
    require_once __DIR__ . '/inc/form-shortcode-1st.php';
}
if (file_exists( __DIR__ . '/inc/form-shortcode-2nd.php' )) {
    require_once __DIR__ . '/inc/form-shortcode-2nd.php';
}

// if (file_exists( __DIR__ . '/inc/visaForm-2nd-shortcode.php' )) {
//     require_once __DIR__ . '/inc/visaForm-2nd-shortcode.php';
// }



?>
