<?php

namespace PressGang;

class AdminLogo {

    /**
     * init
     *
     */
    public static function init () {
        add_action('login_enqueue_scripts', array('PressGang\AdminLogo', 'add_login_logo'));
    }

    /**
     * add_login_logo
     *
     * Replace the Wordpress Logo with the Customizer Logo on the wp-admin login screen
     *
     * @return void
     */
    public static function add_login_logo () {
        if($logo = esc_url(get_theme_mod('logo'))) : ?>
            <style type="text/css">
                .login h1 a {
                    background-image: url(<?php echo $logo; ?>) !important;
                    padding-bottom: 30px;
                    width: auto !important;
                    height: auto !important;
                    -webkit-background-size: contain !important;
                    background-size: contain !important;
                }
            </style>
        <?php endif;
    }
}

AdminLogo::init();