<?php

// namespace WPC\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit; // Exit if accessed directly


class Advertisement extends Widget_Base{

    public function get_name(){
        return 'mrk_carousel';
    }

    public function get_title(){
        return 'MRK Carousel';
    }

    public function get_icon(){
        return 'fa fa-camera';
    }

    public function get_categories(){
        return ['general'];
    }

    protected function _register_controls(){

        $this->start_controls_section(
            'section_content',
            [
                'label' => 'Settings',
            ]
        );

        $this->add_control(
            'limit',
            [
                'label' => 'Limit',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '2'
            ]
        );

        $this->end_controls_section();
    }


    protected function render(){
        $settings = $this->get_settings_for_display();

        $posts = new WP_Query([
            'posts_per_page' => $settings['limit'],
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
    }

    protected function _content_template(){

    }
}