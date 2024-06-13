<?php

// Shortcode for search filter box
add_shortcode('search_filter_box', 'search_filter_box_shortcode');
function search_filter_box_shortcode() {
    ob_start();
    ?>
    <div class="visa_search_box">
        <h2 class="text-center"><?php esc_html_e('YOUR VISA PARTNER', 'visapartner'); ?></h2>
        <h5 class="text-center sub_heading"><?php esc_html_e('find your visa requirement', 'visapartner'); ?></h5>
        <form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" name="myVisaSelectForm" class="myVisaSelectForm" id="myVisaSelectForm">
            <div class="select-box">
                <label><?php esc_html_e('I\'m a Citizen Of', 'visapartner'); ?></label>
                <select id="citizen_country" name="citizen_country">
                    <option value=""><?php esc_html_e('--Select an option--', 'visapartner'); ?></option>
                    <?php
                    $countries = get_terms(array('taxonomy' => 'citizen_country', 'hide_empty' => false));
                    foreach ($countries as $country) {
                        echo '<option value="' . esc_attr($country->term_id) . '">' . esc_html($country->name) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="select-box">
                <label><?php esc_html_e('Travelling to', 'visapartner'); ?></label>
                <select id="travel_country" name="travel_country">
                    <option value=""><?php esc_html_e('--Select an option--', 'visapartner'); ?></option>
                    <?php
                    $countries = get_terms(array('taxonomy' => 'travel_country', 'hide_empty' => false));
                    foreach ($countries as $country) {
                        echo '<option value="' . esc_attr($country->term_id) . '">' . esc_html($country->name) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="select-box">
                <label><?php esc_html_e('Visa Category', 'visapartner'); ?></label>
                <select id="visa_category" name="visa_category">
                    <option value=""><?php esc_html_e('--Select an option--', 'visapartner'); ?></option>
                    <?php
                    $categories = get_terms(array('taxonomy' => 'visa_category', 'hide_empty' => false));
                    foreach ($categories as $category) {
                        echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="thm-btn submit__btn btn-primary"><?php esc_html_e('Check Requirement', 'visapartner'); ?></button>
        </form>
        <br>
        <div id="error" style="color: red;"></div>
        <div id="results" class="results"></div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#myVisaSelectForm').on('submit', function(event) {
                event.preventDefault();
                var citizen_country = $('#citizen_country').val();
                var travel_country = $('#travel_country').val();
                var visa_category = $('#visa_category').val();
                var errorDiv = $('#error');
                var resultsDiv = $('#results');

                errorDiv.html('');
                resultsDiv.html('');

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
                        var posts = response.data.posts;
                        console.log(posts);
                        var html = '';
                        posts.forEach(function(post) {
                            window.location.href = post.url;
                        });
                        resultsDiv.html(html);

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
