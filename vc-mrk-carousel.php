<?php
class VcMrkCarousel
{
    public function __construct()
    {
        add_action( 'init', array( $this, 'vcMap' ) );
        add_shortcode( 'vc_mrk_carousel', array( $this, 'render' ) );
    }

    public function vcMap()
    {
        vc_map(array(
            "name" => "MRK Carousel",
            "base" => 'vc_mrk_carousel',
            "category" => 'Webomnizz Elements',
            "allowed_container_element" => 'vc_row',
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => "Limit",
                    "param_name" => "limit",
                    "admin_label" => true
                ),
            )
        ));
    }

    public function render($atts, $content = null)
    {
        ob_start();
        $args = array(
            "limit"       => "2",
        );

        $params	= shortcode_atts($args, $atts);

        $posts = new WP_Query([
            'posts_per_page' => $params['limit'],
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
        return ob_get_clean();
    }
}

new VcMrkCarousel();