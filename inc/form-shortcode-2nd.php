<?php

// Shortcode for filter box with flag title and button
add_shortcode('filter_box_with_flag_title_btn', 'filter_box_with_flag_title_btn_shortcode');
function filter_box_with_flag_title_btn_shortcode() {
    ob_start();
    ?>
    <div class="visa_search_box visa_flag_box">
        <h2 class="text-center"><?php esc_html_e('YOUR VISA PARTNER', 'visapartner'); ?></h2>
        <h5 class="text-center sub_heading"><?php esc_html_e('find your visa requirement', 'visapartner'); ?></h5>
        <form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" name="visaFlagForm" class="myVisaSelectForm" id="visaFlagForm">
            <div class="select-box">
                <label><?php esc_html_e('Your Nationality', 'visapartner'); ?></label>
                <select id="flag_citizen_country" name="citizen_country">
                    <option value=""><?php esc_html_e('--Select a nationality--', 'visapartner'); ?></option>
                    <?php
                    $countries = get_terms(array('taxonomy' => 'citizen_country', 'hide_empty' => false));
                    foreach ($countries as $country) {
                        echo '<option value="' . esc_attr($country->term_id) . '">' . esc_html($country->name) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="select-box">
                <label><?php esc_html_e('Travel Country', 'visapartner'); ?></label>
                <select id="flag_travel_country" name="travel_country">
                    <option value=""><?php esc_html_e('--Select a country--', 'visapartner'); ?></option>
                    <?php
                    $countries = get_terms(array('taxonomy' => 'travel_country', 'hide_empty' => false));
                    foreach ($countries as $country) {
                        echo '<option value="' . esc_attr($country->term_id) . '">' . esc_html($country->name) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="select-box">
                <label><?php esc_html_e('Visa Type', 'visapartner'); ?></label>
                <select id="flag_visa_category" name="visa_category">
                    <option value=""><?php esc_html_e('--Select a visa type--', 'visapartner'); ?></option>
                    <?php
                    $categories = get_terms(array('taxonomy' => 'visa_category', 'hide_empty' => false));
                    foreach ($categories as $category) {
                        echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="thm-btn submit__btn btn-primary"><?php esc_html_e('Find Visa', 'visapartner'); ?></button>
        </form>
        <br>
        <div id="flag_error" style="color: red;"></div>
    </div>
    <div id="flag_results" class="results"></div>

    <!-- Popup box markup  -->
    <div class="popup_box_wrapper" id="popupBox_wrapper">
        <div class="popup_info_box" id="popupBox">
            <h3 class="heading"><?php esc_html_e('nothing to say', 'visapartner'); ?></h3>
            <h5 class="sub_heading"><?php esc_html_e('Apply for visa requirement', 'visapartner'); ?></h5>

            <form action="#" method="post" class="popup_info_form" id="popup_info_form">
                <div class="item_box">
                    <label for="fullName"><?php esc_html_e('Full Name', 'visapartner'); ?></label>
                    <input type="text" name="fullName" id="fullName" placeholder="Enter Your Name" required>
                </div>
                <div class="item_box">
                    <label for="userEmail"><?php esc_html_e('Write Email', 'visapartner'); ?></label>
                    <input type="email" name="userEmail" id="userEmail" placeholder="hello@apisolutionsltd.com" required>
                </div>
                <div class="item_box">
                    <label for="phoneNumber"><?php esc_html_e('Write Phone Number', 'visapartner'); ?></label>
                    <input type="number" name="phoneNumber" id="phoneNumber" placeholder="+88-02 55035911" required>
                </div>
                <div class="item_box">
                    <label for="messageBox"><?php esc_html_e('Write Message', 'visapartner'); ?></label>
                    <textarea name="messageBox" id="messageBox" placeholder="Write your message..."></textarea>
                </div>
                <input type="submit" value="Send Message" class="thm-btn submit__btn btn-primary">
            </form>
            <button type="button" class="closePopupBox" id="closePopupForm">&#x2715;</button>
        </div>
    </div>

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('visaFlagForm').addEventListener('submit', function(event) {
                event.preventDefault();
                var citizen_country = document.getElementById('flag_citizen_country').value;
                var travel_country = document.getElementById('flag_travel_country').value;
                var visa_category = document.getElementById('flag_visa_category').value;
                var errorDiv = document.getElementById('flag_error');
                var resultsDiv = document.getElementById('flag_results');

                errorDiv.innerHTML = '';
                resultsDiv.innerHTML = '';

                if (!citizen_country || !travel_country || !visa_category) {
                    errorDiv.innerHTML = 'Please select all options.';
                    return false;
                }

                var data = new URLSearchParams();
                data.append('action', 'visa_partner_search');
                data.append('citizen_country', citizen_country);
                data.append('travel_country', travel_country);
                data.append('visa_category', visa_category);

                fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
                    method: 'POST',
                    body: data,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        const posts = response.data.posts;
                        let html = '';
                        posts.forEach(function(post) {
                            post.citizens.forEach(function(citizen){
                                if (citizen_country == citizen.term_id) {
                                    html += `<div class="heading_box">
                                        <div class="item__left">
                                            <h3 class="post__heading"> You Applied '${post.title}' visa from ${citizen.name} </h3>
                                        </div>
                                        <button type="button" class="thm-btn apply__btn btn-primary" onclick="return applyBtnHandler();"><?php esc_html_e('Apply Online', 'visapartner'); ?></button>
                                    </div>`;
                                } 
                            });
                        });
                        resultsDiv.innerHTML = html;
                    } else {
                        errorDiv.innerHTML = response.data.message;
                    }
                })
                .catch(error => {
                    errorDiv.innerHTML = 'An error occurred. Please try again.';
                    console.error('Error:', error);
                });

                return false;
            });

            document.getElementById('popup_info_form').addEventListener('submit', function(event) {
                event.preventDefault();
                var fullName = document.getElementById('fullName').value;
                var userEmail = document.getElementById('userEmail').value;
                var phoneNumber = document.getElementById('phoneNumber').value;
                var messageBox = document.getElementById('messageBox').value;
                var data = new URLSearchParams();
                data.append('action', 'send_visa_message');
                data.append('fullName', fullName);
                data.append('userEmail', userEmail);
                data.append('phoneNumber', phoneNumber);
                data.append('messageBox', messageBox);

                fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
                    method: 'POST',
                    body: data,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        alert('Message sent successfully.');
                        document.getElementById('popup_info_form').reset();
                        document.getElementById('popupBox_wrapper').classList.remove('visibilityClass');
                    } else {
                        alert('Failed to send message. Please try again.');
                    }
                })
                .catch(error => {
                    alert('An error occurred. Please try again.');
                    console.error('Error:', error);
                });

                return false;
            });
        });

        function applyBtnHandler () {
            const wrapper = document.getElementById("popupBox_wrapper");
            const closePopupForm = document.getElementById("closePopupForm");

            // Show the popup box
            wrapper.classList.add('visibilityClass');

            // Add click event listener to the close button
            closePopupForm.addEventListener("click", function(event) {
                event.stopPropagation();
                wrapper.classList.remove('visibilityClass');
            });

            // Add click event listener to the popup box itself
            wrapper.addEventListener("click", function(event) {
                event.stopPropagation();
                this.classList.remove('visibilityClass');
            });

            // Prevent clicks inside the popup from closing it
            document.getElementById("popupBox").addEventListener("click", function(event) {
                event.stopPropagation();
            });
        }
    </script>

    <?php
    return ob_get_clean();
}

// AJAX handler for visa search
add_action('wp_ajax_visa_partner_search', 'gpt_visa_partner_search');
add_action('wp_ajax_nopriv_visa_partner_search', 'gpt_visa_partner_search');
function gpt_visa_partner_search() {
    $citizen_country = isset($_POST['citizen_country']) ? intval($_POST['citizen_country']) : 0;
    $travel_country = isset($_POST['travel_country']) ? intval($_POST['travel_country']) : 0;
    $visa_category = isset($_POST['visa_category']) ? intval($_POST['visa_category']) : 0;

    // Dummy data for response
    $response = [
        'success' => true,
        'data' => [
            'posts' => [
                [
                    'title' => 'Tourist Visa',
                    'citizens' => [
                        [
                            'term_id' => $citizen_country,
                            'name' => 'USA'
                        ]
                    ]
                ]
            ]
        ]
    ];

    wp_send_json($response);
}

// AJAX handler to send the message
add_action('wp_ajax_send_visa_message', 'send_visa_message');
add_action('wp_ajax_nopriv_send_visa_message', 'send_visa_message');
function send_visa_message() {
    $fullName = sanitize_text_field($_POST['fullName']);
    $userEmail = sanitize_email($_POST['userEmail']);
    $phoneNumber = sanitize_text_field($_POST['phoneNumber']);
    $messageBox = sanitize_textarea_field($_POST['messageBox']);

    $to = 'your-email@example.com'; // Change to your email address
    $subject = 'New Visa Inquiry';
    $message = "Name: $fullName\nEmail: $userEmail\nPhone: $phoneNumber\nMessage: $messageBox";
    $headers = "From: $userEmail";

    $sent = wp_mail($to, $subject, $message, $headers);

    if ($sent) {
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}
