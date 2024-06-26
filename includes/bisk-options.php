<?php

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

namespace BISKPlugin\Includes;

defined('ABSPATH') or die;

/**
 * This class handles all the options used in the plugin.
 */
class BISKOptions {

    private static $options = [];

    /**
     * Get the options from the database initializing any missing options and setting the values
     * in the options array for program use.
     */
    private static function initializeOptions() {
        self::$options = get_option(BISKConfig::SETTINGS_KEY);
        self::$options = shortcode_atts(
            [
                BISKConfig::OPENING_DATE => '',
                BISKConfig::CLOSING_DATE => '',
                BISKConfig::FULL_MOON_DATES => '',
                BISKConfig::JUNIOR_SKILLS_START_DATE => '',
                BISKConfig::JUNIOR_SKILLS_END_DATE => '',
                BISKConfig::SUMMER_CAMP_1_START_DATE => '',
                BISKConfig::SUMMER_CAMP_1_END_DATE => '',
                BISKConfig::SUMMER_CAMP_2_START_DATE => '',
                BISKConfig::SUMMER_CAMP_2_END_DATE => '',
                BISKConfig::SUMMER_CAMP_3_START_DATE => '',
                BISKConfig::SUMMER_CAMP_3_END_DATE => '',
                BISKConfig::SUMMER_CAMP_4_START_DATE => '',
                BISKConfig::SUMMER_CAMP_4_END_DATE => '',
                BISKConfig::SUMMER_CAMP_5_START_DATE => '',
                BISKConfig::SUMMER_CAMP_5_END_DATE => '',
                BISKConfig::SUMMER_CAMP_6_START_DATE => '',
                BISKConfig::SUMMER_CAMP_6_END_DATE => '',
                BISKConfig::ROUND_BOWEN_CHALLENGE_NUMBER => '',
                BISKConfig::ROUND_BOWEN_CHALLENGE_DATE => '',
                BISKConfig::ROUND_BOWEN_CHALLENGE_EARLY_BIRD_DATE => '',
                BISKConfig::ROUND_BOWEN_CHALLENGE_LATE_DATE => '',
                BISKConfig::ROUND_BOWEN_CHALLENGE_REGULAR_PRICE => '',
                BISKConfig::ROUND_BOWEN_CHALLENGE_EARLY_BIRD_PRICE => '',
                BISKConfig::ROUND_BOWEN_CHALLENGE_LATE_PRICE => '',
                BISKConfig::BISK_SHOW_NOTIFICATION => false,
                BISKConfig::BISK_NOTIFICATION_HEADER => '',
                BISKConfig::BISK_NOTIFICATION => '',
                BISKConfig::BISK_DEBUG => false
            ],
            self::$options
        );

        // we need esc_js because the id is set through the form
        self::$options[BISKConfig::OPENING_DATE] = esc_js(self::$options[BISKConfig::OPENING_DATE]);
        self::$options[BISKConfig::CLOSING_DATE] = esc_js(self::$options[BISKConfig::CLOSING_DATE]);
        self::$options[BISKConfig::FULL_MOON_DATES] = esc_js(self::$options[BISKConfig::FULL_MOON_DATES]);
        self::$options[BISKConfig::JUNIOR_SKILLS_START_DATE] = esc_js(self::$options[BISKConfig::JUNIOR_SKILLS_START_DATE]);
        self::$options[BISKConfig::JUNIOR_SKILLS_END_DATE] = esc_js(self::$options[BISKConfig::JUNIOR_SKILLS_END_DATE]);
        self::$options[BISKConfig::SUMMER_CAMP_1_START_DATE] = esc_js(self::$options[BISKConfig::SUMMER_CAMP_1_START_DATE]);
        self::$options[BISKConfig::SUMMER_CAMP_1_END_DATE] = esc_js(self::$options[BISKConfig::SUMMER_CAMP_1_END_DATE]);
        self::$options[BISKConfig::SUMMER_CAMP_2_START_DATE] = esc_js(self::$options[BISKConfig::SUMMER_CAMP_2_START_DATE]);
        self::$options[BISKConfig::SUMMER_CAMP_2_END_DATE] = esc_js(self::$options[BISKConfig::SUMMER_CAMP_2_END_DATE]);
        self::$options[BISKConfig::SUMMER_CAMP_3_START_DATE] = esc_js(self::$options[BISKConfig::SUMMER_CAMP_3_START_DATE]);
        self::$options[BISKConfig::SUMMER_CAMP_3_END_DATE] = esc_js(self::$options[BISKConfig::SUMMER_CAMP_3_END_DATE]);
        self::$options[BISKConfig::SUMMER_CAMP_4_START_DATE] = esc_js(self::$options[BISKConfig::SUMMER_CAMP_4_START_DATE]);
        self::$options[BISKConfig::SUMMER_CAMP_4_END_DATE] = esc_js(self::$options[BISKConfig::SUMMER_CAMP_4_END_DATE]);
        self::$options[BISKConfig::SUMMER_CAMP_5_START_DATE] = esc_js(self::$options[BISKConfig::SUMMER_CAMP_5_START_DATE]);
        self::$options[BISKConfig::SUMMER_CAMP_5_END_DATE] = esc_js(self::$options[BISKConfig::SUMMER_CAMP_5_END_DATE]);
        self::$options[BISKConfig::SUMMER_CAMP_6_START_DATE] = esc_js(self::$options[BISKConfig::SUMMER_CAMP_6_START_DATE]);
        self::$options[BISKConfig::SUMMER_CAMP_6_END_DATE] = esc_js(self::$options[BISKConfig::SUMMER_CAMP_6_END_DATE]);
        self::$options[BISKConfig::ROUND_BOWEN_CHALLENGE_NUMBER] = esc_js(self::$options[BISKConfig::ROUND_BOWEN_CHALLENGE_NUMBER]);
        self::$options[BISKConfig::ROUND_BOWEN_CHALLENGE_DATE] = esc_js(self::$options[BISKConfig::ROUND_BOWEN_CHALLENGE_DATE]);
        self::$options[BISKConfig::ROUND_BOWEN_CHALLENGE_EARLY_BIRD_DATE] = esc_js(self::$options[BISKConfig::ROUND_BOWEN_CHALLENGE_EARLY_BIRD_DATE]);
        self::$options[BISKConfig::ROUND_BOWEN_CHALLENGE_LATE_DATE] = esc_js(self::$options[BISKConfig::ROUND_BOWEN_CHALLENGE_LATE_DATE]);
        self::$options[BISKConfig::ROUND_BOWEN_CHALLENGE_REGULAR_PRICE] = esc_js(self::$options[BISKConfig::ROUND_BOWEN_CHALLENGE_REGULAR_PRICE]);
        self::$options[BISKConfig::ROUND_BOWEN_CHALLENGE_EARLY_BIRD_PRICE] = esc_js(self::$options[BISKConfig::ROUND_BOWEN_CHALLENGE_EARLY_BIRD_PRICE]);
        self::$options[BISKConfig::ROUND_BOWEN_CHALLENGE_LATE_PRICE] = esc_js(self::$options[BISKConfig::ROUND_BOWEN_CHALLENGE_LATE_PRICE]);
        self::$options[BISKConfig::BISK_SHOW_NOTIFICATION] = esc_js(self::$options[BISKConfig::BISK_SHOW_NOTIFICATION]);
        self::$options[BISKConfig::BISK_NOTIFICATION_HEADER] = esc_js(self::$options[BISKConfig::BISK_NOTIFICATION_HEADER]);
        self::$options[BISKConfig::BISK_NOTIFICATION] = esc_js(self::$options[BISKConfig::BISK_NOTIFICATION]);
        self::$options[BISKConfig::BISK_DEBUG] = esc_js(self::$options[BISKConfig::BISK_DEBUG]);
    }

    /**
     * Initialize the options for the plugin.
     */
    public static function initialize() {
        self::initializeOptions();
    }

    /**
     * Return all the options used by the plugin.
     * 
     * @return array
     */
    public static function getOptions() {
        return self::$options;
    }

    /**
     * Return a single option used by the plugin.
     * 
     * @param string $option
     * @return string
     */
    public static function getOption($option) {
        return self::$options[$option];
    }

    /**
     * Return the plugin version.
     * 
     * @return string
     */
    public static function getVersion() {
        return BISKConfig::PLUGIN_VERSION;
    }

}
