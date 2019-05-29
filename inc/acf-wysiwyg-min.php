<?php

namespace PressGang;

/**
 * Class AcfWysiwygMin
 *
 * See
 *
 * - https://www.advancedcustomfields.com/resources/customize-the-wysiwyg-toolbars/
 * - https://gist.github.com/stianandreassen/6dc87c88c43b2bc43d0ea1a94bd5cd1e
 *
 *
 * @package PressGang
 */
class AcfWysiwygMin
{

    /**
     * AcfWysiwygMin constructor.
     */
    public function __construct()
    {

        add_filter('acf/fields/wysiwyg/toolbars', array($this, 'toolbars'));
        add_action('acf/render_field_settings/type=wysiwyg', array($this, 'wysiwyg_render_field_settings'), 10, 1);
        add_action('acf/render_field/type=wysiwyg', array($this, 'wysiwyg_render_field'), 10, 1);
    }

    /**
     * toolbars
     *
     * @param $toolbars
     * @return mixed
     */
    public function toolbars($toolbars)
    {
        $toolbars['Minimal'] = array();
        $toolbars['Minimal'][1] = array('bold', 'italic', 'underline');
        return $toolbars;
    }

    /**
     * Add height field to ACF WYSIWYG
     */
    public function wysiwyg_render_field_settings($field)
    {
        acf_render_field_setting($field, array(
            'label' => __("Height of Editor"),
            'instructions' => __("Height of Editor in px"),
            'name' => 'wysiwyg_height',
            'type' => 'number',
        ));
    }

    /**
     * Render height on ACF WYSIWYG
     */
    public function wysiwyg_render_field($field)
    {
        $field_class = '.acf-' . str_replace('_', '-', $field['key']);

        if (isset($field['wysiwyg_height']) && $field['wysiwyg_height'] > 0) :
        ?>
        <style type="text/css">
            <?php echo $field_class; ?>
            iframe {
                min-height: <?php echo $field['wysiwyg_height']; ?>px;
            }
        </style>
        <script type="text/javascript">
            jQuery(window).load(function () {
                jQuery('<?php echo $field_class; ?>').each(function () {
                    jQuery('#' + jQuery(this).find('iframe').attr('id')).height( <?php echo $field['wysiwyg_height']; ?> );
                });
            });
        </script>
        <?php endif;
    }

}

new AcfWysiwygMin();