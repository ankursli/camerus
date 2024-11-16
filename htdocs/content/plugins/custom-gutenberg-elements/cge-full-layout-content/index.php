<?php

/**
 * Plugin Name: Gutenberg Elements Layout Content Page
 * Plugin URI: https://github.com/WordPress/custom-gutenberg-elements
 * Description: This is a plugin demonstrating how to register new blocks for the Gutenberg editor.
 * Version: 1.0.2
 * Author: the Gutenberg Team
 *
 * @package custom-gutenberg-elements
 */

defined('ABSPATH') || exit;

/**
 * Load all translations for our plugin from the MO file.
 */
add_action('init', 'custom_gutenberg_elements_cge_full_layout_content_load_textdomain');

function custom_gutenberg_elements_cge_full_layout_content_load_textdomain()
{
    load_plugin_textdomain('custom-gutenberg-elements', false, basename(__DIR__) . '/languages');
}

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * Passes translations to JavaScript.
 */
function custom_gutenberg_elements_cge_full_layout_content_register_block()
{

    if (!function_exists('register_block_type')) {
        // Gutenberg is not active.
        return;
    }

    wp_register_script(
        'custom-gutenberg-elements-cge-full-layout-content',
        plugins_url('block.build.js', __FILE__),
        array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'underscore'),
        filemtime(plugin_dir_path(__FILE__) . 'block.build.js')
    );

    wp_register_style(
        'custom-gutenberg-elements-cge-full-layout-content',
        plugins_url('style.css', __FILE__),
        array(),
        filemtime(plugin_dir_path(__FILE__) . 'style.css')
    );

    register_block_type('custom-gutenberg-elements/cge-full-layout-content', array(
        'editor_style'    => 'custom-gutenberg-elements-cge-full-layout-content',
        'editor_script'   => 'custom-gutenberg-elements-cge-full-layout-content',
    ));

    if (function_exists('wp_set_script_translations')) {
        /**
         * May be extended to wp_set_script_translations( 'my-handle', 'my-domain',
         * plugin_dir_path( MY_PLUGIN ) . 'languages' ) ). For details see
         * https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
         */
        wp_set_script_translations('custom-gutenberg-elements-cge-full-layout-content', 'custom-gutenberg-elements');
    }
}

add_action('init', 'custom_gutenberg_elements_cge_full_layout_content_register_block');
