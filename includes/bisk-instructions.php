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
?>

<div class="wrap">
    <h1><?php _e('BISK Plugin Options', BISKConfig::TEXT_DOMAIN); ?></h1>
    <ul class="tabs">
        <li class="tab">
            <input type="radio" name="tabs" checked="checked" id="tab1" />
            <label for="tab1"><?php _e('Date', BISKConfig::TEXT_DOMAIN) ?></label>
            <div id="tab-content1" class="content">
                <h2><?php _e('BISK Date Options', BISKConfig::TEXT_DOMAIN); ?></h2>
                <ul class="info">
                    <li><?php _e('Opening Date - Date of the season opening', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Closing Date - Date of the season closing', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Full Moon Dates - List of the full moon dates for the full moon three hour tour', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Junior Skills Camp Start Date - Date of the start of the Junior Skills Camp', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Junior Skills Camp End Date - Date of the end of the Junior Skills Camp', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Summer Camp #1 Start Date - Date of the start of the 1st Summer Camp', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Summer Camp #1 End Date - Date of the end of the 1st Summer Camp', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Summer Camp #2 Start Date - Date of the start of the 2nd Summer Camp', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Summer Camp #2 End Date - Date of the end of the 2nd Summer Camp', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Summer Camp #3 Start Date - Date of the start of the 3rd Summer Camp', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Summer Camp #3 End Date - Date of the end of the 3rd Summer Camp', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Summer Camp #4 Start Date - Date of the start of the 4th Summer Camp', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Summer Camp #4 End Date - Date of the end of the 4th Summer Camp', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Summer Camp #5 Start Date - Date of the start of the 5th Summer Camp', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Summer Camp #5 End Date - Date of the end of the 5th Summer Camp', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Summer Camp #6 Start Date - Date of the start of the 6th Summer Camp', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Summer Camp #6 End Date - Date of the end of the 6th Summer Camp', BISKConfig::TEXT_DOMAIN); ?></li>
                </ul>
            </div>
        </li>
        <li class="tab">
            <input type="radio" name="tabs" id="tab3" />
            <label for="tab3"><?php _e('Round Bowen', BISKConfig::TEXT_DOMAIN) ?></label>
            <div id="tab-content3" class="content">
                <h2><?php _e('BISK Round Bowen Challenge Options', BISKConfig::TEXT_DOMAIN); ?></h2>
                <ul class="info">
                    <li><?php _e('Annual Round Bowen Challenge Number - Number for the Annual Round Bowen Challenge', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Round Bowen Challenge Date - Date of the Round Bowen Challenge', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Round Bowen Challenge Regular Price - Regular cost of the Round Bowen Challeng in dollars', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Round Bowen Challenge Early Bird Date - Early bird date for tickets in the Round Bowen Challenge', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Round Bowen Challenge Early Bird Price - Early bird cost of the Round Bowen Challeng in dollars', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Round Bowen Challenge Late Registration Date - Late date for tickets in the Round Bowen Challenge', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Round Bowen Challenge Late Price - Late cost of the Round Bowen Challeng in dollars', BISKConfig::TEXT_DOMAIN); ?></li>
                </ul>
            </div>
        </li>
        <li class="tab">
            <input type="radio" name="tabs" id="tab4" />
            <label for="tab4"><?php _e('Closed Message', BISKConfig::TEXT_DOMAIN) ?></label>
            <div id="tab-content4" class="content">
                <h2><?php _e('BISK Closed for Season Notification Message', BISKConfig::TEXT_DOMAIN); ?></h2>
                <ul class="info">
                    <li><?php _e('Show Closed Notification - Display the season closed message', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Closed Notification Header - H3 tag message', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><?php _e('Closed Notification to be displayed on the top of the page - Message to be displayed for the closed notification', BISKConfig::TEXT_DOMAIN); ?></li>
                </ul>
            </div>
        </li>
        <li class="tab">
            <input type="radio" name="tabs" id="tab5" />
            <label for="tab5"><?php _e('Debug', BISKConfig::TEXT_DOMAIN) ?></label>
            <div id="tab-content5" class="content">
                <h2><?php _e('BISK Debug Options', BISKConfig::TEXT_DOMAIN); ?></h2>
                <ul class="info">
                    <li><?php _e('Enagle Debug - If checked enable debug logging/messages', BISKConfig::TEXT_DOMAIN); ?></li>
                </ul>
            </div>
        </li>
        <li class="tab">
            <input type="radio" name="tabs" id="tab6" />
            <label for="tab6"><?php _e('Shortcodes', BISKConfig::TEXT_DOMAIN) ?></label>
            <div id="tab-content6" class="content">
                <h2><?php _e('Shortcodes', BISKConfig::TEXT_DOMAIN); ?></h2>
                <ul class="info">
                    <li>
                        <strong>[bisk_opening_date format='FORMAT']</strong> - <?php _e('Displays the Opening date in a specific format', BISKConfig::TEXT_DOMAIN); ?>
                        <ul>
                            <li><strong>FORMAT</strong> - <?php _e('any php date format string - \'Default = \'Y-m-d\'', BISKConfig::TEXT_DOMAIN); ?></li>
                        </ul>
                    </li>
                    <li>
                        <strong>[bisk_season_opening]</strong> - <?php _e('Displays the date of the opening of the season', BISKConfig::TEXT_DOMAIN); ?>
                        <ul>
                            <li><?php _e('If the opening date has already passed will return - "We opened on DAYOFWEEK, MONTH DATE for the YEAR Season"', BISKConfig::TEXT_DOMAIN); ?></li>
                            <li><?php _e('If the opening date has NOT passed will return - "We will open on DAYOFWEEK, MONTH DATE for the YEAR Season"', BISKConfig::TEXT_DOMAIN); ?></li>
                        </ul>
                    </li>
                    <li>
                        <strong>[bisk_round_bowen_challenge type='TYPE' format='FORMAT' price='PRICE' number='NUMBER']</strong> - <?php _e('Displays Round Bowen Challenge Information', BISKConfig::TEXT_DOMAIN); ?>
                        <ul>
                            <li><strong>TYPE</strong> - 'regular', 'early', 'late' or 'actual' - <?php _e('Default', BISKConfig::TEXT_DOMAIN); ?> = 'actual'</li>
                            <li><strong>FORMAT</strong> - any php date format string - <?php _e('Default', BISKConfig::TEXT_DOMAIN); ?> = 'Y-m-d'</li>
                            <li><strong>PRICE</strong> - 'true' or 'false' - <?php _e('Default', BISKConfig::TEXT_DOMAIN); ?> = 'false' <?php _e('... If \'true\' will return the regular, early or late price ONLY', BISKConfig::TEXT_DOMAIN); ?></li>
                            <li><strong>NUMBER</strong> - 'true' or 'false' - <?php _e('Default', BISKConfig::TEXT_DOMAIN); ?> = 'false' <?php _e('... If \'true\' will return the Round Bowen Challenge number ONLY', BISKConfig::TEXT_DOMAIN); ?></li>
                        </ul>
                    </li>
                    <li>
                        <strong>[bisk_cost item='ITEM']</strong> - <?php _e('Displays Costs of tours/lessons/etc', BISKConfig::TEXT_DOMAIN); ?>
                    </li>
                    <li><strong>[bisk_full_moon_dates]</strong> - <?php _e('Displays the dates of the Full Moon Tours', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li><strong>[bisk_junior_skills_camp_dates]</strong> - <?php _e('Displays the dates of the Junior Skills Camp', BISKConfig::TEXT_DOMAIN); ?></li>
                    <li>
                        <strong>[bisk_summer_camp_dates week='WEEK']</strong> - <?php _e('Displays the dates of the Summer Camp', BISKConfig::TEXT_DOMAIN); ?>
                        <ul>
                            <li><strong>WEEK</strong> = '1' to '6'</li>
                        </ul>
                    </li>
                </ul>
                <h2><?php _e('Remove Shortcode', BISKConfig::TEXT_DOMAIN); ?></h2>
                <pre>
                    $shortcode_handler = apply_filter( 'get_bisk_shortcode_instance', NULL );
                    if( is_a( $shortcode_handler, 'BISKShortCodes' ) { <?php _e('// Do something with the instance of the handler', BISKConfig::TEXT_DOMAIN); ?> }
                </pre>
            </div>
        </li>
        <li class="tab">
            <input type="radio" name="tabs" id="tab7" />
            <label for="tab7"><?php _e('Actions', BISKConfig::TEXT_DOMAIN); ?></label>
            <div id="tab-content7" class="content">
                <h2><?php _e('Actions', BISKConfig::TEXT_DOMAIN); ?></h2>
                <ul class="info">
                    <li>bisk_before_opening_date</li>
                    <li>bisk_after_opening_date</li>
                    <li>bisk_before_season_opening</li>
                    <li>bisk_after_season_opening</li>
                    <li>bisk_before_round_bowen_challenge</li>
                    <li>bisk_after_round_bowen_challenge</li>
                    <li>bisk_before_cost</li>
                    <li>bisk_after_cost</li>
                    <li>bisk_before_full_moon_dates</li>
                    <li>bisk_after_full_moon_dates</li>
                    <li>bisk_before_junior_skills_camp_dates</li>
                    <li>bisk_after_junior_skills_camp_dates</li>
                    <li>bisk_before_summer_camp_dates</li>
                    <li>bisk_after_summer_camp_dates</li>
                </ul>
            </div>
        </li>
        <li class="tab">
            <input type="radio" name="tabs" id="tab8" />
            <label for="tab8"><?php _e('Filters', BISKConfig::TEXT_DOMAIN) ?></label>
            <div id="tab-content8" class="content">
                <h2><?php _e('Filters', BISKConfig::TEXT_DOMAIN); ?></h2>
                <ul class="info">
                    <li>get_bisk_shortcode_instance</li>
                    <li>bisk_opening_date</li>
                    <li>bisk_season_opening</li>
                    <li>bisk_round_bowen_challenge</li>
                    <li>bisk_cost</li>
                    <li>bisk_full_moon_dates</li>
                    <li>bisk_junior_skills_camp_dates</li>
                    <li>bisk_summer_camp_dates</li>
                </ul>
            </div>
        </li>
    </ul>
</div><!-- .wrap -->
