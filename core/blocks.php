<?php

namespace PressGang;

/**
 * Class Blocks
 *
 * https://wordpress.org/gutenberg/handbook/designers-developers/developers/tutorials/block-tutorial/writing-your-first-block-type/
 *
 * @package PressGang
 */
class Blocks
{
    /**
     * __construct
     *
     */
    public function __construct()
    {
        add_action('init', array($this, 'setup'));
    }

    /**
     * setup
     */
    public function setup()
    {
        $blocks = Config::get('blocks');

        foreach ($blocks as $key => &$args) {

            $block = array();

            if (isset($args['editor_script'])) {

                wp_register_script(
                    $args['editor_script']['handle'],
                    $args['editor_script']['src'],
                    $args['editor_script']['deps']
                );

                $block['editor_script'] = $args['editor_script']['handle'];
            }

            if (isset($args['editor_style'])) {
                wp_register_style(
                    $args['editor_style']['handle'],
                    $args['editor_style']['src'],
                    $args['editor_style']['deps'],
                    $args['editor_style']['version']
                );

                $block['editor_style'] = $args['editor_script']['handle'];
            }

            if (isset($args['style'])) {
                wp_register_style(
                    $args['style']['handle'],
                    $args['style']['src'],
                    $args['style']['deps'],
                    $args['style']['version']
                );

                $block['style'] = $args['style']['handle'];
            }

            register_block_type($key, $block);
        }
    }

}

new Blocks();