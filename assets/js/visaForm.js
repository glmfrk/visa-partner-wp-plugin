// document.addEventListener("DOMContentLoaded", function() {
//     document.getElementById('visaFlagForm').addEventListener('submit', function(event) {
//         event.preventDefault();
//         var citizen_country = document.getElementById('flag_citizen_country').value;
//         var travel_country = document.getElementById('flag_travel_country').value;
//         var visa_category = document.getElementById('flag_visa_category').value;
//         var errorDiv = document.getElementById('flag_error');
//         var resultsDiv = document.getElementById('flag_results');

//         errorDiv.innerHTML = '';
//         resultsDiv.innerHTML = '';

//         if (!citizen_country || !travel_country || !visa_category) {
//             errorDiv.innerHTML = 'Please select all options.';
//             return false;
//         }

//         var data = new URLSearchParams();
//         data.append('action', 'visa_partner_search');
//         data.append('citizen_country', citizen_country);
//         data.append('travel_country', travel_country);
//         data.append('visa_category', visa_category);

//         fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
//             method: 'POST',
//             body: data,
//             headers: {
//                 'Content-Type': 'application/x-www-form-urlencoded'
//             }
//         })
//         .then(response => response.json())
//         .then(response => {
//             if (response.success) {
//                 const posts = response.data.posts;
//                 let html = '';
//                 posts.forEach(function(post) {
//                     if (parseInt(citizen_country) === post.citizen_country.term_id) {
//                         html += `<div class="heading_box">
//                         <div class="item__left">
//                             <figure class="post__type_image">
//                                 <img src="${post.image}" alt="${post.title}" />
//                             </figure>
//                             <h3 class="post__heading"> ${post.title} visa from ${post.citizen_country.name} </h3>
//                         </div>
//                           <a href="${post.url}" class="thm-btn submit__btn btn-primary"><?php esc_html_e('Apply Online', 'visapartner'); ?></a>
//                     </div>`;
//                     }
//                 });
//                 resultsDiv.innerHTML = html;
//             } else {
//                 errorDiv.innerHTML = response.data.message;
//             }
//         })
//         .catch(error => {
//             errorDiv.innerHTML = 'An error occurred. Please try again.';
//             console.error('Error:', error);
//         });

//         return false;
//     });
// });


