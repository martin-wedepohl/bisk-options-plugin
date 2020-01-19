<?php

/**
 * Plugin Name: BISK Options Plugin
 * Plugin URI: https://github.com/martin-wedepohl/bisk-options-plugin
 * Description: The BISK Options Plugin provides all the required options, custom post
 * types and shortcodes required by the BISK website.
 * Author: Martin Wedepohl <martin@wedepohlengineering.com>
 * Author URI: https://wedepohlengineering.com
 * Version: 0.1.5
 * Text Domain: bisk-options-plugin
 */
/*
 * Copyright (C) 2019-present, Wedepohl Engineering
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the License.
 * this program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Genera Public License for more details.
 */

/**
 * @package BISKPlugin
 */

namespace BISKPlugin;

defined('ABSPATH') or die;

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

use BISKPlugin\Includes\BISKConfig;
use BISKPlugin\Includes\BISKOptions;
use BISKPlugin\Includes\Cpt\BISKTours;
use BISKPlugin\Includes\BISKShortCodes;
use BISKPlugin\Includes\BISKSettingsPage;

/**
 * Class for the Plugin Options.
 */
class BISKOptionsPlugin {

    private function addNotification() {

        $enableNotification = BISKOptions::getOption(BISKConfig::BISK_SHOW_NOTIFICATION);

        if(1 == $enableNotification) {
            ob_start();
            add_action('shutdown', function() {
                if ( is_admin() ) {
                    return;
                }
                $final = '';
                $levels = ob_get_level();
                for ($i = 0; $i < $levels; $i++){
                    $final .= ob_get_clean();
                }
                echo apply_filters('final_output', $final);
            }, 0);
            
            add_filter('final_output', function($output) {  
                if ( is_admin() ) {
                    return;
                }
                $header = BISKOptions::getOption(BISKConfig::BISK_NOTIFICATION_HEADER);
                $notification = BISKOptions::getOption(BISKConfig::BISK_NOTIFICATION);
                $notification = str_replace('\n', '<br />', $notification);
                $after_body = apply_filters('after_body','', $header, $notification);
                $output = preg_replace("/(\<body.*\>)/", "$1".$after_body, $output);
                return $output;
            });
            
            add_filter('after_body',function($after_body, $header, $notification) {
                $after_body.='<div class="bisk-notification"><h3>' . $header . '</h3><p>' . $notification . '</p></div>';
                return $after_body;
            }, 10, 3);
        }

    }

    /**
     * Plugin constructor
     */
    public function __construct() {

        // Remove not used options from the header
        add_action('setup_theme', [$this, 'removeHeadOptions']);
        
        // Enqueue styles and scripts
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminScripts']);

        // Initialize options
        BISKOptions::initialize();

        // load textdomain
        load_plugin_textdomain(BISKConfig::TEXT_DOMAIN, false, basename(dirname(__FILE__)) . '/languages/');

        // initialize admin page config
        $this->registerSettingsPage();
        
        // Initialize the Short Codes
        BISKShortCodes::initShortcodes();
        
        // Display used template in footer
        add_action( 'wp_footer', [$this, 'bisk_which_template_is_loaded'] );

        $this->addNotification();
        
    }
    
    /**
     * Enqueue the front end styles and scripts
     */
    public function enqueueScripts() {
        // Required styles
        wp_enqueue_style('bisk-css', plugins_url( 'dist/css/style.min.css', __FILE__));

        // Required scripts
        wp_enqueue_script( 'bisk-scripts', plugins_url( 'dist/js/scripts.min.js', __FILE__), [], false, true );        
    }

    /**
     * Enqueue the administration styles and scripts
     */
    public function enqueueAdminScripts() {
        // Required styles
        wp_enqueue_style('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/redmond/jquery-ui.min.css');

        // Required scripts
        wp_enqueue_script('jquery-ui-datepicker', '', ['jquery'], false, true);
        wp_enqueue_script('bisk-scripts', plugins_url( 'dist/js/admin.min.js', __FILE__), ['jquery', 'jquery-ui-datepicker'], false, true);
    }

    /**
     * Remove not used options from the head section of the website
     */
    public function removeHeadOptions() {
        // Remove "Really Simple Discovery" tag; not needed if XML-RPC is disabled.
        remove_action('wp_head', 'rsd_link');

        // Remove "Windows Live Writer Manifest" tag.
        remove_action('wp_head', 'wlwmanifest_link');

        // Remove emoji JavaScript tags.
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');

        // Remove emoji CSS tags.
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');

        // Remove emoji DNS prefetch tag.
        add_filter('emoji_svg_url' , '__return_false');

        // Remove shortlink tag.
        remove_action('wp_head', 'wp_shortlink_wp_head');

        // Remove generator tag.
        remove_action('wp_head', 'wp_generator');

        // Remove general RSS feed tags.
        remove_action('wp_head', 'feed_links', 2);

        // Remove specific-post RSS comment feed tags.
        remove_action('wp_head', 'feed_links_extra', 3);

        // Remove prev/next relational tags.
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

        // Remove WordPress API call tag.
        remove_action('wp_head', 'rest_output_link_wp_head');

        // Remove oembed discovery tags.
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
    }

    /**
     * Helper function for registering the settings page
     */
    public function registerSettingsPage() {

        if (is_admin()) {
            $plugin_name = plugin_basename(__FILE__);
            new BISKSettingsPage($plugin_name);
        }
    }

    function bisk_which_template_is_loaded() {
        if ( is_super_admin() && ('' !== BISKOptions::getOption(BISKConfig::BISK_DEBUG)) ) {
            global $template;
            print_r( $template );
        }
    }
}

$BISK_Options_Plugin = new BISKOptionsPlugin();
