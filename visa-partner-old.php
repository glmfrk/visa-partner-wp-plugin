<?php

/*
 * Plugin Name:       Visa Search Form V2
 * Plugin URI:        https://github.com/glmfrk/visa-partner-plugin
 * Description:       This is country with visa filtering search box . You can use any travel management website.
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

 
    // If this file is called directly, abort.
    if (!defined('WPINC')) {
        die;
    }

    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly
    }

    // Enqueue styles and scripts
    add_action('wp_enqueue_scripts', 'evisa_enqueue_scripts');
    function evisa_enqueue_scripts() {
        wp_enqueue_style('visa-bootstrap', plugins_url('/assets/css/bootstrap.min.css', __FILE__));
        wp_enqueue_style('visa-style_inner', plugins_url('/assets/css/visa-style.css', __FILE__));
        wp_enqueue_style('visa-style', plugins_url('/style.css', __FILE__));
        
        wp_enqueue_script('visa-bootstrap-script', plugins_url('/assets/js/bootstrap.min.js', __FILE__), ['jquery'], null, true);
        wp_enqueue_script('visa-gpt-script', plugins_url('/assets/js/gpt_script.js', __FILE__), ['jquery'], null, true);
        wp_enqueue_script('visa-script', plugins_url('/assets/js/visaForm.js', __FILE__), ['jquery'], null, true);
    }
    
    /**
     * Register custom taxonomies.
    */
    add_action('init', 'visa_partner_register_taxonomies');
    function visa_partner_register_taxonomies() {
     // Citizen Country taxonomy
     $labels = array(
         'name' => _x('Citizen Countries', 'taxonomy general name', 'visa-partner'),
         'singular_name' => _x('Citizen Country', 'taxonomy singular name', 'visa-partner'),
         'search_items' => __('Search Citizen Countries', 'visa-partner'),
         'all_items' => __('All Citizen Countries', 'visa-partner'),
         'parent_item' => __('Parent Citizen Country', 'visa-partner'),
         'parent_item_colon' => __('Parent Citizen Country:', 'visa-partner'),
         'edit_item' => __('Edit Citizen Country', 'visa-partner'),
         'update_item' => __('Update Citizen Country', 'visa-partner'),
         'add_new_item' => __('Add New Citizen Country', 'visa-partner'),
         'new_item_name' => __('New Citizen Country Name', 'visa-partner'),
         'menu_name' => __('Citizen Country', 'visa-partner'),
     );
 
     $args = array(
         'hierarchical' => true,
         'labels' => $labels,
         'show_ui' => true,
         'show_admin_column' => true,
         'query_var' => true,
         'rewrite' => array('slug' => 'citizen-country'),
     );
 
     register_taxonomy('citizen_country', array('evisa_country'), $args);
 
     // Travel Country taxonomy
     $labels = array(
         'name' => _x('Travel Countries', 'taxonomy general name', 'visa-partner'),
         'singular_name' => _x('Travel Country', 'taxonomy singular name', 'visa-partner'),
         'search_items' => __('Search Travel Countries', 'visa-partner'),
         'all_items' => __('All Travel Countries', 'visa-partner'),
         'parent_item' => __('Parent Travel Country', 'visa-partner'),
         'parent_item_colon' => __('Parent Travel Country:', 'visa-partner'),
         'edit_item' => __('Edit Travel Country', 'visa-partner'),
         'update_item' => __('Update Travel Country', 'visa-partner'),
         'add_new_item' => __('Add New Travel Country', 'visa-partner'),
         'new_item_name' => __('New Travel Country Name', 'visa-partner'),
         'menu_name' => __('Travel Country', 'visa-partner'),
     );
 
     $args = array(
         'hierarchical' => true,
         'labels' => $labels,
         'show_ui' => true,
         'show_admin_column' => true,
         'query_var' => true,
         'rewrite' => array('slug' => 'travel-country'),
     );
 
     register_taxonomy('travel_country', array('evisa_country'), $args);
 
     // Visa Category taxonomy
     $labels = array(
         'name' => _x('Visa Categories', 'taxonomy general name', 'visa-partner'),
         'singular_name' => _x('Visa Category', 'taxonomy singular name', 'visa-partner'),
         'search_items' => __('Search Visa Categories', 'visa-partner'),
         'all_items' => __('All Visa Categories', 'visa-partner'),
         'parent_item' => __('Parent Visa Category', 'visa-partner'),
         'parent_item_colon' => __('Parent Visa Category:', 'visa-partner'),
         'edit_item' => __('Edit Visa Category', 'visa-partner'),
         'update_item' => __('Update Visa Category', 'visa-partner'),
         'add_new_item' => __('Add New Visa Category', 'visa-partner'),
         'new_item_name' => __('New Visa Category Name', 'visa-partner'),
         'menu_name' => __('Visa Category', 'visa-partner'),
     );
 
     $args = array(
         'hierarchical' => true,
         'labels' => $labels,
         'show_ui' => true,
         'show_admin_column' => true,
         'query_var' => true,
         'rewrite' => array('slug' => 'visa-category'),
     );
 
     register_taxonomy('visa_category', array('evisa_country'), $args);
 }
 
    /**
     * Activation hook.
    */
    register_activation_hook(__FILE__, 'visa_partner_activate');
    function visa_partner_activate() {
        // Register the custom taxonomies on activation
        visa_partner_register_taxonomies();
        // Clear the permalinks after the post type has been registered
        flush_rewrite_rules();
    }
 
    /**
     * Deactivation hook.
    */
    register_deactivation_hook(__FILE__, 'visa_partner_deactivate');
    function visa_partner_deactivate() {
        // Clear the permalinks
        flush_rewrite_rules();
    }
 
/**
 * Create Shortcode to Display the Form
*/
add_shortcode('search_filter_box', 'search_filter_box_shortcode');
function search_filter_box_shortcode() {
    ob_start();
    ?>
 
    <div class="visa_search_box">
        <h2 class="text-center"><?php esc_html_e( 'YOUR VISA PARTNER', 'visapartner' ); ?></h2>
        <h4 class="text-center"><?php esc_html_e( 'NEED A VISA?', 'visapartner' ); ?></h4>

        <form action="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>" method="post"    name="myVisaSelectForm" class="myVisaSelectForm" id="myVisaSelectForm">
           

            <div class="select-box">
                <label><?php esc_html_e( 'I\'m a Citizen Of', 'visapartner' ); ?></label>
                <select id="citizen_country" name="citizen_country">
                    <option value=""><?php esc_html_e( '--Select an option--', 'visapartner' ); ?></option>
                    <?php
                    $countries = get_terms(array('taxonomy' => 'citizen_country', 'hide_empty' => false));
                    foreach ($countries as $country) {
                        echo '<option value="' . $country->term_id . '">' . $country->name . '</option>';
                    }
                    ?>
                </select>
            </div>
            
            <div class="select-box">
                <label><?php esc_html_e( 'Travelling to', 'visapartner' ); ?></label>
                <select id="travel_country" name="travel_country">
                    <option value=""><?php esc_html_e( '--Select an option--', 'visapartner' ); ?></option>
                    <?php
                    $countries = get_terms(array('taxonomy' => 'travel_country', 'hide_empty' => false));
                    foreach ($countries as $country) {
                        echo '<option value="' . $country->term_id . '">' . $country->name . '</option>';
                    }
                    ?>
                </select>
            </div>
            
            <div class="select-box">
                <label><?php esc_html_e( 'Visa Category', 'visapartner' ); ?></label>
                <select id="visa_category" name="visa_category">
                    <option value=""><?php esc_html_e( '--Select an option--', 'visapartner' ); ?></option>
                    <?php
                    $categories = get_terms(array('taxonomy' => 'visa_category', 'hide_empty' => false));
                    foreach ($categories as $category) {
                        echo '<option value="' . $category->term_id . '">' . $category->name . '</option>';
                    }
                    ?>
                </select>
            </div>
        

            <button type="submit" class="thm-btn submit__btn btn-primary"><?php esc_html_e( 'Check Requirement', 'visapartner' ); ?></button>
        </form>
        
        <br>
        <div id="error" style="color: red;"></div>
    </div>
               
    <script type="text/javascript">
        jQuery(document).ready(function($) {
        $('#myVisaSelectForm').on('submit', function(event) {
            event.preventDefault();
            var citizen_country = $('#citizen_country').val();
            var travel_country = $('#travel_country').val();
            var visa_category = $('#visa_category').val();
            var errorDiv = $('#error');

            errorDiv.html('');

            if (!citizen_country || !travel_country || !visa_category) {
            errorDiv.html('Please select all options.');
            return false;
            }

            var data = {
            'action': 'visa_partner_search',
            'citizen_country': citizen_country,
            'travel_country': travel_country,
            'visa_category': visa_category
            };

            $.post('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', data, function(response) {
            if (response.success) {
                window.location.href = response.data.url;
            } else {
                errorDiv.html(response.data.message);
            }
            });

            return false;
        });
        });
    </script>
               
    <?php
    return ob_get_clean();
}

add_shortcode('filter_box_with_flag_title_btn', 'search_filter_box_shortcode_inner');
function search_filter_box_shortcode_inner() {
    ob_start();
    ?>
 
    <div class="visa_search_box">
        <h2 class="text-center"><?php esc_html_e( 'YOUR VISA PARTNER', 'visapartner' ); ?></h2>
        <h4 class="text-center"><?php esc_html_e( 'NEED A VISA?', 'visapartner' ); ?></h4>

        <form action="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>" method="post"    name="myVisaSelectForm" class="myVisaSelectForm" id="myVisaSelectForm">
           

            <div class="select-box">
                <label><?php esc_html_e( 'I\'m a Citizen Of', 'visapartner' ); ?></label>
                <select id="citizen_country" name="citizen_country">
                    <option value=""><?php esc_html_e( '--Select an option--', 'visapartner' ); ?></option>
                    <?php
                    $countries = get_terms(array('taxonomy' => 'citizen_country', 'hide_empty' => false));
                    foreach ($countries as $country) {
                        echo '<option value="' . $country->term_id . '">' . $country->name . '</option>';
                    }
                    ?>
                </select>
            </div>
            
            <div class="select-box">
                <label><?php esc_html_e( 'Travelling to', 'visapartner' ); ?></label>
                <select id="travel_country" name="travel_country">
                    <option value=""><?php esc_html_e( '--Select an option--', 'visapartner' ); ?></option>
                    <?php
                    $countries = get_terms(array('taxonomy' => 'travel_country', 'hide_empty' => false));
                    foreach ($countries as $country) {
                        echo '<option value="' . $country->term_id . '">' . $country->name . '</option>';
                    }
                    ?>
                </select>
            </div>
            
            <div class="select-box">
                <label><?php esc_html_e( 'Visa Category', 'visapartner' ); ?></label>
                <select id="visa_category" name="visa_category">
                    <option value=""><?php esc_html_e( '--Select an option--', 'visapartner' ); ?></option>
                    <?php
                    $categories = get_terms(array('taxonomy' => 'visa_category', 'hide_empty' => false));
                    foreach ($categories as $category) {
                        echo '<option value="' . $category->term_id . '">' . $category->name . '</option>';
                    }
                    ?>
                </select>
            </div>
        

            <button type="submit" class="thm-btn submit__btn btn-primary"><?php esc_html_e( 'Check Requirement', 'visapartner' ); ?></button>
        </form>
        
        <br>
        <div id="error" style="color: red;"></div>
    </div>

    <!-- Search result: flag and title with button markup  -->
    <div class="heading_box">
        <div class="item__left">
            <figure class="post__type_image">
                <img src="./assets/images/australia-flag.jpg" alt="flag" />
            </figure>
            <h1 class="post__type_title"> Australia Study Visa From Bangladesh </h1>
        </div>
        <button type="button" class="thm-btn applay__btn">apply online</button>
    </div>
    <div class="meta__info">
        <h4 class="meta_title"> Available services for Australia</h4>
        <div> <span>&#x2713;</span> Visa Processing in Bangladesh</div>
    </div>
               
    <script type="text/javascript">
        jQuery(document).ready(function($) {
        $('#myVisaSelectForm').on('submit', function(event) {
            event.preventDefault();
            var citizen_country = $('#citizen_country').val();
            var travel_country = $('#travel_country').val();
            var visa_category = $('#visa_category').val();
            var errorDiv = $('#error');

            errorDiv.html('');

            if (!citizen_country || !travel_country || !visa_category) {
            errorDiv.html('Please select all options.');
            return false;
            }

            var data = {
            'action': 'visa_partner_search',
            'citizen_country': citizen_country,
            'travel_country': travel_country,
            'visa_category': visa_category
            };

            $.post('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', data, function(response) {
            if (response.success) {
                window.location.href = response.data.url;
            } else {
                errorDiv.html(response.data.message);
            }
            });

            return false;
        });
        });
    </script>
               
    <?php
    return ob_get_clean();
}


/**
 * Handle AJAX request to search for evisa_country posts and redirect.
 */
add_action('wp_ajax_visa_partner_search', 'visa_partner_search');
add_action('wp_ajax_nopriv_visa_partner_search', 'visa_partner_search');
function visa_partner_search() {
    if (!isset($_POST['citizen_country']) || !isset($_POST['travel_country']) || !isset($_POST['visa_category'])) {
        wp_send_json_error(array('message' => 'Missing parameters.'));
    }

    $citizen_country = intval($_POST['citizen_country']);
    $travel_country = intval($_POST['travel_country']);
    $visa_category = intval($_POST['visa_category']);

    $args = array(
        'post_type' => 'evisa_country',
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'citizen_country',
                'field' => 'term_id',
                'terms' => $citizen_country,
            ),
            array(
                'taxonomy' => 'travel_country',
                'field' => 'term_id',
                'terms' => $travel_country,
            ),
            array(
                'taxonomy' => 'visa_category',
                'field' => 'term_id',
                'terms' => $visa_category,
            ),
        ),
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $query->the_post();
        $url = get_permalink();
        wp_send_json_success(array('url' => $url));
    } else {
        wp_send_json_error(array('message' => 'No results found.'));
    }

    wp_die();
}
 