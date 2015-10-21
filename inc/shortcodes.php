<?php

namespace PressGang;

/**
 * Class Shortcodes
 *
 * @package PressGang
 */
class Shortcodes {

    /**
     * Initialize
     *
     */
    public static function init () {
        // add shortcodes to widget text
        add_filter('widget_text', 'do_shortcode');

        // register shortcodes
        add_shortcode('vimeo', array('PressGang\Shortcodes', 'vimeo'));
    }

    /**
     * Vimeo
     *
     * Embeds a responsive vimeo container
     *
     * @return string
     */
    public static function vimeo($atts) {

        $vimeo = '';

        if (isset($atts['id'])) {
            $atts = shortcode_atts(array(
                'id' => null,
                'title' => 0,
                'badge' => 0,
                'byline' => 0,
                'color' => '000',
                'loop' => 0,
                'portrait' => 0,
                'autoplay' => 0,
                'autopause' => 1,
            ), $atts);

            ob_start();
            ?>
            <!-- vimeo embed -->
            <div class="embed-container">
                <iframe
                    src="http://player.vimeo.com/video/<?php echo esc_html($atts['id']); ?>?badge=<?php echo esc_html($atts['badge']); ?>&byline=<?php echo esc_html($atts['byline']); ?>&color=<?php echo esc_html($atts['color']); ?>&loop=<?php echo esc_html($atts['loop']); ?>&player_id=<?php echo esc_html($atts['player_id']); ?>&portrait=<?php echo esc_html($atts['portrait']); ?>&title=<?php echo esc_html($atts['title']); ?>&autoplay=<?php echo esc_html($atts['autoplay']); ?>&autopause=<?php echo esc_html($atts['autopause']); ?>"
                    frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
            </div>
            <?php
            $vimeo = ob_get_clean();
        }

        return $vimeo;
    }
}

Shortcodes::init();