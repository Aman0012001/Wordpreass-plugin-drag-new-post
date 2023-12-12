<?php
/*
plugin Name: Post Data
Author: Aman Jeet Singh
version: 1.0
Description: This plugin allows you to create a custom post type and drag and drop posts in the WordPress admin area.
*/

// for peridefiend function for show navigation bar in a page in show_admin_bar(fales) then page top naigation bar canot shown in a page 

// Load the SVG image for the plugin's icon
// function my_custom_admin_enqueue_scripts()
// {
//     // Make sure the SVG image file exists
//     $svg_path = plugin_dir_path(__FILE__) . 'icon.svg';
//     if (file_exists($svg_path)) {
//         // Read the contents of the SVG image file
//         $svg_content = file_get_contents($svg_path);

//         // Use a unique ID for the SVG icon style
//         $style_id = 'my-custom-plugin-icon-style';

//         // Inject the SVG image into the WordPress admin
//         echo '<style id="' . $style_id . '">' . $svg_content . '</style>';
//     }
// }
// add_action('admin_enqueue_scripts', 'my_custom_admin_enqueue_scripts');


if (!defined('ABSPATH')) {
    header("Location: /");
    die("");
}
// Enqueue jQuery UI in your theme
function my_theme_enqueue_scripts()
{
    wp_enqueue_style('jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-draggable');
    wp_enqueue_script('jquery-ui-droppable');
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_scripts');


// function for embend my files link bootstrap javascript.js and stye.css

function my_admin_scripts()
{
    // link bootstrap 5.0 link
    wp_enqueue_style('bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css');
    // link bootstrap 5.0 jquery add
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.2.1.slim.min.js',);
    wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js',);
    wp_enqueue_script('popper-js', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js',);
    wp_enqueue_script('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js',);
    wp_enqueue_style('my_admin_css', get_template_directory_uri() . '/../../plugins/post-data/assets/style.css',);
    wp_enqueue_script('custom-script', plugin_dir_url(__FILE__) . 'assets/javascript.js', array('jquery'), null, true);
    wp_localize_script('custom-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
// add_action is a wordpress hook that allows to embend my style and javascripts functions
add_action('admin_enqueue_scripts', 'my_admin_scripts', 'my_admin_style', 'enqueue_custom_script', 'manage_posts_js');


// function for my poat shown in admin dashboard bar
function wpb_manage_posts()
{
    add_menu_page('post data ', 'post data', 'administrator', 'manage-posts', 'manage_posts_page');
}
add_action('admin_menu', 'wpb_manage_posts', 'manage-posts', 'manage_posts_page');
// close function for my poat shown in admin dashboard bar












function my_custom_drag_event()
{

    add_filter('wp_query_orderby_requests', 'change_post_orderby');
}


// Change the post orderby to menu_order

function change_post_orderby($vars)
{

    if (is_admin()) {

        $vars['orderby'] = 'menu_order title';

        $vars['order'] = 'asc';
    }

    return $vars;
}

function save_post_callback($post_id){
    global $post; 
    if ($post->post_type != 'MY_CUSTOM_POST_TYPE_NAME'){
    }};



    function change_post_category_based_on_position()
    {
        // Check if the request is valid and has the necessary data
        if (!isset($_POST['post_id']) || !isset($_POST['position'])) {
            wp_send_json_error('Invalid request data.');
        }
    
        $post_id = intval($_POST['post_id']);
        $position = intval($_POST['position']);
    
        if ($position > 10) {
            $category = 'hell123';
        } else if ($position >= 10 && $position < 300) {
            $category = 'java1234';
        } else {
            $category = 'Uncategorized';
        }
    
        // Update the post category using the post ID and category
        wp_set_object_terms($post_id, $category, 'category');
    
        // Send the response back to the jQuery AJAX request
        wp_send_json_success('Post category updated successfully.');
    }
    add_action('wp_ajax_change_post_category_based_on_position', 'change_post_category_based_on_position');


function display_categories() {
    // Get the categories for the current post
    $categories = get_the_category();

    // Check if there are any categories for the current post
    if ($categories) {
        // Loop through each category
        foreach ($categories as $category) {
            // Print the category name
            echo '<a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a><br>';
        }
    } else {
        // No categories were found for the current post
        echo 'No categories were found for this post.';
    }
}


function manage_posts_page()
{
    // Create a nonce field for security
    wp_nonce_field('manage_posts_save_position', 'manage_posts_nonce');

    $post_query = new WP_Query(array(
        'post_type' => 'post', // or 'page'

        'posts_per_page' => -1, // all page shown in my page
    ));


    // Display the post with my custom  style 
    if ($post_query->have_posts()) {
        while ($post_query->have_posts()) {
            // echo '<div class="row row-cols-1 row-cols-sm-4 row-cols-md-6">';
            $post_query->the_post();
            echo '<div class="row ">';
            echo '<div class="col-md-4">';
            echo '<div class="card">';
            echo '<h2 class="postpost text-center" data-post_id="' . get_the_ID() . '">' . get_the_post_thumbnail(null, 'my-custom-size') . '<br>' . '</h2>';
            echo  get_permalink();
            echo"<br>";
            echo display_categories() ;
    
            echo "</div>";
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo 'No posts found';
    };
};
// close function for create post style and post query  and other feacture that allows to create a function design 

// Hook for drag event
add_action('admin_init', 'my_custom_drag_event');



// code for  Set custom image size
function my_theme_setup()
{
    add_theme_support('post-thumbnails');
    add_image_size('my-custom-size', 400, 200, true); // width, height, crop
}
add_action('after_setup_theme', 'my_theme_setup');





// Enqueue necessary scripts
add_action('wp_enqueue_scripts', 'my_custom_enqueue_scripts');
// Enqueue necessary scripts
add_action('wp_enqueue_scripts', 'my_custom_enqueue_scripts');






function my_custom_enqueue_scripts()
{
    wp_enqueue_script('my-drag-script', plugin_dir_url(__FILE__) . 'js/drag-script.js', array('jquery'), '1.0.0', true);
}


function update_post_order()
{
    if (!isset($_POST['post']) || !isset($_POST['action']) || 'update_post_order' !== $_POST['action']) {
        return;
    }

    // Check nonce
    check_ajax_referer('manage_posts_save_position', 'manage_posts_nonce');

    // Check if current user can edit posts
    if (!current_user_can('edit_posts')) {
        wp_send_json_error('You do not have the permission to edit posts.');
        // exit;
    }

    $order = $_POST['post'];

    foreach ($order as $i => $post_id) {
        $post_id = intval($post_id);

        if ($post_id > 0) {
            $my_post = array(
                'ID' => $post_id,
                'menu_order' => $i
            );

            wp_update_post($my_post);
        }
    }

    wp_send_json_success('Order updated successfully.');
}
add_action('wp_ajax_update_post_order', 'update_post_order');









// function for show java post on the page 
function manage_post_page()
{
    // Create a nonce field for security
    wp_nonce_field('manage_posts_save_position', 'manage_posts_nonce');
    $post_query = new WP_Query(array(

        'post_type' => 'post', // or 'page'

        'post_status' => 'hell123',
        'posts_per_page' => -1, // all page shown in my page

    ));
    // Display the post with my custom  style 
    if ($post_query->have_posts()) {
        while ($post_query->have_posts()) {
            // echo '<div class="row row-cols-1 row-cols-sm-4 row-cols-md-6">';
            $post_query->the_post();
            echo '<div class="row mt-5 mb-5">';
            echo '<div class="col-md-4" . get_the_ID()>';
            echo '<div class="card">';
            echo '<h2 class="postpost text-center" data-post_id="' . '">' . get_the_post_thumbnail(null, 'my-custom-size') . '<br>' . get_the_title() . '</h2>';
            echo "</div>";
            echo '</div>';
            echo '</div>';
            wp_enqueue_style('bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css');
            // link bootstrap 5.0 jquery add
            wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.2.1.slim.min.js',);
            // wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js',);
            wp_enqueue_script('popper-js', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js',);
            wp_enqueue_script('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js',);
            wp_enqueue_style('my_admin_css', get_template_directory_uri() . '/../../plugins/post-data/assets/style.css',);
            wp_enqueue_script('custom-script', plugin_dir_url(__FILE__) . 'assets/javascript.js', array('jquery'), null, true);
            wp_localize_script('custom-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
        }
    } else {
        echo 'No posts found in hell123 catagery ';
    };
};


add_shortcode('display_custom_page', 'manage_post_page', 'my_custom_enqueue_scripts');



// Add the following code to your plugin file
