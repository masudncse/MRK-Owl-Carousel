<?php

/**
 * Plugin Name: MRK Owl Carousel
 */

/**
 *
 */
function register_mrk_carousel_custom_post_type()
{
    register_post_type('mrk_carousel',
        array(
            'labels' => array(
                'name' => __('MRK Carousel', 'textdomain'),
                'singular_name' => __('MRK Carousel', 'textdomain'),
            ),
            'supports' => ['title', 'editor', 'thumbnail'],
            'public' => true,
            'has_archive' => true,
        )
    );
}

add_action('init', 'register_mrk_carousel_custom_post_type');

/**
 *
 */
function mrk_carousel_shortcode($args = [])
{
    $posts = new WP_Query([
        'posts_per_page' => 2,
        'post_type' => 'mrk_carousel'
    ]);
    if ($posts->have_posts()): ?>
        <div class="owl-carousel owl-theme">
            <?php while ($posts->have_posts()):
                $posts->the_post();
                ?>
                <div class="item">
                    <img src="<?php the_post_thumbnail_url('full'); ?>" alt="<?php echo get_the_title() ?>">
                    <div class="inner-items">
                        <h4><?php the_title(); ?></h4>
                        <p><?php the_excerpt(); ?></p>
                        <a href="<?php echo get_post_meta(get_the_ID(), '_mrk_carousel_button_link', true); ?>"
                           class="button">
                            <?php echo get_post_meta(get_the_ID(), '_mrk_carousel_button_text', true); ?>
                        </a>
                    </div>
                </div>
            <?php
            endwhile; ?>
        </div>
        <script type="text/javascript">
            jQuery(function ($) {
                $('.owl-carousel').owlCarousel({
                    loop: true,
                    margin: 10,
                    nav: true,
                    autoplay: <?php echo $args['autoplay']; ?>,
                    responsive: {
                        0: {
                            items: 1
                        },
                        600: {
                            items: 1
                        },
                        1000: {
                            items: 1
                        }
                    }
                })
            })
        </script>
    <?php
    endif;
}

add_shortcode('mrk-carousel', 'mrk_carousel_shortcode');


/**
 *
 */
function mrk_register_scripts()
{
    $theme_version = wp_get_theme()->get('Version');

    wp_enqueue_style('twentytwenty-owl.carousel.css', plugin_dir_url(__FILE__) . 'public/dist/assets/owl.carousel.css', array(), $theme_version);
    wp_enqueue_style('twentytwenty-mrk-carousel.css', plugin_dir_url(__FILE__) . 'mrk-carousel.css', array(), $theme_version);
    wp_enqueue_script('twentytwenty-owl.carousel.js', plugin_dir_url(__FILE__) . 'public/dist/owl.carousel.js', array('jquery'), $theme_version, false);
}

add_action('wp_enqueue_scripts', 'mrk_register_scripts');

function mrk_add_button_box_meta()
{
    $screens = ['mrk_carousel'];
    foreach ($screens as $screen) {
        add_meta_box(
            'mrk_button_box',                 // Unique ID
            'Button Box',      // Box title
            'mrk_button_box_html',  // Content callback, must be of type callable
            $screen                            // Post type
        );
    }
}

add_action('add_meta_boxes', 'mrk_add_button_box_meta');

function mrk_button_box_html($post)
{
    $_mrk_carousel_button_text = get_post_meta($post->ID, '_mrk_carousel_button_text', true);
    $_mrk_carousel_button_link = get_post_meta($post->ID, '_mrk_carousel_button_link', true);
    ?>
    <label for="_mrk_carousel_button_text" style="margin-bottom: 5px; font-weight: bold; display: block">Button
        Text</label>
    <input type="text" name="_mrk_carousel_button_text" value="<?php echo $_mrk_carousel_button_text; ?>"
           id="_mrk_carousel_button_text"
           class="postbox" style="width: 60%;">

    <br>

    <label for="_mrk_carousel_button_link" style="margin-bottom: 5px; font-weight: bold; display: block">Button
        Link</label>
    <input type="text" name="_mrk_carousel_button_link" value="<?php echo $_mrk_carousel_button_link; ?>"
           id="_mrk_carousel_button_link"
           class="postbox" style="width: 60%;">
    <?php
}

/**
 * @param $post_id
 */
function mrk_button_box_save_postdata($post_id)
{
    if (array_key_exists('_mrk_carousel_button_text', $_POST)) {
        update_post_meta(
            $post_id,
            '_mrk_carousel_button_text',
            $_POST['_mrk_carousel_button_text']
        );
    }
    if (array_key_exists('_mrk_carousel_button_link', $_POST)) {
        update_post_meta(
            $post_id,
            '_mrk_carousel_button_link',
            $_POST['_mrk_carousel_button_link']
        );
    }
}

add_action('save_post', 'mrk_button_box_save_postdata');

/**
 *
 */
function wpdocs_register_my_custom_menu_page()
{
    add_menu_page(
        __('MRK Setting', 'textdomain'),
        'MRK Setting',
        'administrator',
        'mrk_carousel_setting.php',
        'my_custom_menu_page',
        '',
        6
    );
    add_submenu_page('mrk_carousel_setting.php', 'Statistic', 'Statistic',
        'administrator', 'my-top-level-slug', 'my_custom_sub_menu_page');
    add_submenu_page('mrk_carousel_setting.php', 'Feedback List', 'Feedback List',
        'administrator', 'external-mrk-menu', 'my_custom_sub_menu_page_external');
}

add_action('admin_menu', 'wpdocs_register_my_custom_menu_page');
function my_custom_menu_page()
{
    if (array_key_exists('submit_feedback_form', $_POST)) {
        global $wpdb;
        $table = $wpdb->prefix . 'mrk_feedback';
        $data = array('time' => current_time('mysql'), 'name' => $_POST['name'], 'email' => $_POST['email'], 'message' => $_POST['message']);
        $format = array('%s', '%s', '%s');
        $wpdb->insert($table, $data, $format);
        $my_id = $wpdb->insert_id;
    }
    ?>
    <h3>Feedback Form</h3>
    <?php if (isset($my_id)): ?>
    <h3 style="color: green;">Saved successfully</h3>
<?php endif; ?>
    <form action="" method="post">
        <input type="text" name="name" placeholder="Name" required>
        <br>
        <br>
        <input type="text" name="email" placeholder="Email" required>
        <br>
        <br>
        <textarea name="message" id="" cols="30" rows="10" required></textarea>
        <br>
        <button type="submit" name="submit_feedback_form">Submit</button>
    </form>
    <?php

}

function my_custom_sub_menu_page()
{
    $posts = get_posts([
        'post_type' => 'mrk_carousel'
    ]);
    echo "<h2>Total Posts : " . count($posts) . '</h2>';
}

function my_custom_sub_menu_page_external()
{
    include('external-settings.php');
}

include('mrk-custom-elementor.php');
include('vc-mrk-carousel.php');
include('widget-mrk-carousel.php');

global $jal_db_version;
$jal_db_version = '1.0';

function mrk_db_create()
{
    global $wpdb;
    global $jal_db_version;

    $table_name = $wpdb->prefix . 'mrk_feedback';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		name tinytext NOT NULL,
		email tinytext NOT NULL,
		message text NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    add_option('jal_db_version', $jal_db_version);
}

register_activation_hook(__FILE__, 'mrk_db_create');