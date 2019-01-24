<?php
/**
 * Created by PhpStorm.
 * User: benedict
 * Date: 26/10/2017
 * Time: 14:24
 */

namespace Pressgang;

class WidgetTemplates {

    // TODO
    // use Twig embed to simplify widget wrapping and decouple it from settings file.
    // these arguments (before_title, etc.) are passed into the register_sidebar function

    /**
     * __construct
     *
     * WidgetTemplates constructor.
     *
     */
    public function __construct()
    {
        add_action('', array($this, 'wrap_widget_template'));
    }

    /**
     * wrap_widget_template
     *
     */
    public function wrap_widget_template() {

    }
}


new WidgetTemplates();