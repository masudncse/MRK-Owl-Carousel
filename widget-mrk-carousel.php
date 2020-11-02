<?php

// Creating the widget
class mrk_carousel_widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
            'mrk_carousel_widget',
            __('MRK Carousel', 'mrk_carousel_widget_domain'),
            array('description' => __('You can use mrk slider as widget here.', 'mrk_carousel_widget_domain'),)
        );
    }

    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);
        $limit = apply_filters('widget_limit', $instance['limit']);

        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];

        $posts = new WP_Query([
            'posts_per_page' => $limit,
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
                        autoplay: true,
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

        echo $args['after_widget'];
    }


    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('New title', 'mrk_carousel_widget_domain');
        }

        if (isset($instance['limit'])) {
            $limit = $instance['limit'];
        } else {
            $limit = 2;
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>"
                   name="<?php echo $this->get_field_name('limit'); ?>" type="text"
                   value="<?php echo esc_attr($limit); ?>"/>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['limit'] = (!empty($new_instance['limit'])) ? strip_tags($new_instance['limit']) : '';
        return $instance;
    }
}

function wpb_load_widget()
{
    register_widget('mrk_carousel_widget');
}

add_action('widgets_init', 'wpb_load_widget');