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
 * This class handles all the shortcodes used in the plugin
 */
final class BISKShortCodes {

    /**
     * Class constructor.
     */
    public function __construct() {

        add_filter( 'get_bisk_shortcode_instance', array( $this, 'get_instance' ) );

    } // __construct

    /**
     * Return an instance of the plugin so that the short code can be overwritten.
     * 
     * @return $this
     */
    public function get_instance() {

        return $this;

    } // get_instance

    /**
     * Display the opening date using the supplied format
     * 
     * @example [bisk_opening_date format='Y-m-d']
     * 
     * @param array $atts Shortcode attributes array
     * 
     * @return string The opening date
     */
    public function opening_date( $atts ) {

        do_action('bisk_before_opening_date');
       
        date_default_timezone_set(get_option('timezone_string'));
     
        $atts = shortcode_atts(
            [
                'format' => 'Y-m-d',
            ],
            $atts,
            'bisk_opening_date'
        );
        
        $html = 'NO OPENING DATE';
        $opening_date = BISKOptions::getOption(BISKConfig::OPENING_DATE);
        if('' !== $opening_date) {
            $html = date($atts['format'], strtotime($opening_date));
        }

        do_action('bisk_after_opening_date');
        $html = apply_filters('bisk_opening_date', $html);

        return $html;

    } // opening_date

    /**
     * Display the season opening in the following format
     * 
     *   If the opening date has already passed
     *     We opened on DAYOFWEEK, MONTH DATE for the YEAR Season
     *   If the opening date has NOT passed
     *     We will open on DAYOFWEEK, MONTH DATE for the YEAR Season
     * 
     * @example [bisk_season_opening]
     * 
     * @return string The season opening format
     */
    public function season_opening( $atts ) {

        do_action('bisk_before_season_opening');
       
        date_default_timezone_set(get_option('timezone_string'));
     
        $html = 'NO OPENING DATE';
        $opening_date = BISKOptions::getOption(BISKConfig::OPENING_DATE);
        if('' !== $opening_date) {
            $opening_time = strtotime($opening_date);
            $today = time();
            if($today > $opening_time) {
                $html = 'We opened on ';
            } else {
                $html = 'We will open on ';
            }
            $html .= date('l, F jS', $opening_time) . ' for the ' . date('Y', $opening_time) . ' Season.';
        }

        do_action('bisk_after_season_opening');
        $html = apply_filters('bisk_season_opening', $html);

        return $html;

    } // season_opening

    /**
     * Display the price or date of the Round Bowen Challenge using the supplied format
     * 
     * @example [bisk_round_bowen_challenge type='regular' format='Y-m-d' price='false']
     *          type can be 'regular', 'early', 'late' or 'actual' default 'actual'
     *          format can be any php date format default 'Y-m-d'
     *          price can be 'true' or 'false' default 'false'
     *          number can be 'true' or 'false' default 'false'
     * 
     * @param array $atts Shortcode attributes array
     * 
     * @return string The date or price for the Round Bowen Challenge
     */
    public function round_bowen_challenge( $atts ) {

        do_action('bisk_before_round_bowen_challenge');
       
        date_default_timezone_set(get_option('timezone_string'));
     
        $atts = shortcode_atts(
            [
                'format' => 'Y-m-d',
                'type'   => 'actual',
                'price'  => 'false',
                'number' => 'false'
            ],
            $atts,
            'round_bowen_challenge'
        );
        
        if('true' === $atts['price']) {
            $html = 'NO ROUND BOWEN CHALLENGE PRICE';
            switch($atts['type']) {
                case 'early':
                    $price = BISKOptions::getOption(BISKConfig::ROUND_BOWEN_CHALLENGE_EARLY_BIRD_PRICE);
                    break;
                case 'late':
                    $price = BISKOptions::getOption(BISKConfig::ROUND_BOWEN_CHALLENGE_LATE_PRICE);
                    break;
                default:
                    $price = BISKOptions::getOption(BISKConfig::ROUND_BOWEN_CHALLENGE_REGULAR_PRICE);
                    break;
            }
            if('' !== $price) {
                $html = "$$price";
            }
        } else if('true' === $atts['number']) {
            $html = 'NO ROUND BOWEN CHALLENGE NUMBER';
            $number = BISKOptions::getOption(BISKConfig::ROUND_BOWEN_CHALLENGE_NUMBER);
            if('' !== $number) {
                $modulus = $number % 10;
                switch($modulus) {
                    case 1:
                        $suffix = 'st';
                        break;
                    case 2:
                        $suffix = 'nd';
                        break;
                    case 3:
                        $suffix = 'rd';
                        break;
                    default:
                        $suffix = 'th';
                }
                $html = $number . $suffix;
            }
        } else {
            $html = 'NO ROUND BOWEN CHALLENGE DATE';
            switch($atts['type']) {
                case 'early':
                    $date = BISKOptions::getOption(BISKConfig::ROUND_BOWEN_CHALLENGE_EARLY_BIRD_DATE);
                    break;
                case 'late':
                    $date = BISKOptions::getOption(BISKConfig::ROUND_BOWEN_CHALLENGE_LATE_DATE);
                    break;
                case 'regular':
                    $early_date = BISKOptions::getOption(BISKConfig::ROUND_BOWEN_CHALLENGE_EARLY_BIRD_DATE);
                    $regular_start = date('Y-m-d', strtotime($early_date . ' +1 day'));
                    $late_date = BISKOptions::getOption(BISKConfig::ROUND_BOWEN_CHALLENGE_LATE_DATE);
                    $regular_end = date('Y-m-d', strtotime($late_date . ' -1 day'));
                    break;
                default:
                    $date = BISKOptions::getOption(BISKConfig::ROUND_BOWEN_CHALLENGE_DATE);
                    break;
            }
            if('regular' === $atts['type']) {
                if('' !== $regular_start && '' !== $regular_end) {
                    $html = date($atts['format'], strtotime($regular_start)) . ' to ' . date($atts['format'], strtotime($regular_end));
                }
            } else {
                if('' !== $date) {
                    $html = date($atts['format'], strtotime($date));
                }
            }
        }

        do_action('bisk_after_round_bowen_challenge');
        $html = apply_filters('bisk_round_bowen_challenge', $html);

        return $html;

    }

    /**
     * Return the cost of a tour
     * 
     * @example [bisk_tour_cost tour='full_moon_tour_cost']
     * 
     * @return string The season opening format
     */
    public function tour_cost( $atts ) {

        do_action('bisk_before_tour_cost');
       
        $html = 'NO COST';
        $atts = shortcode_atts(
            [
                'tour' => ''
            ], $atts, 'bisk_tour_cost'
        );
        
        if('' !== $atts['tour']) {
            $tour_cost = BISKOptions::getOption($atts['tour']);
            $html = ('' === $tour_cost) ? '0' : $tour_cost;
        }
        $html = '$' . $html;

        do_action('bisk_after_tour_cost');
        $html = apply_filters('bisk_tour_cost', $html);

        return $html;

    }
    
    /**
     * Return the full moon dates for the season
     * 
     * @example [bisk_full_moon_dates]
     * 
     * @return string The season opening format
     */
    public function full_moon_dates( ) {

        do_action('bisk_before_full_moon_dates');
       
        $html = 'NO FULL MOON DATES';
        
        $full_moon_dates = BISKOptions::getOption(BISKConfig::FULL_MOON_DATES);
        if('' !== $full_moon_dates) {
            $html = $full_moon_dates;
        }

        do_action('bisk_after_full_moon_dates');
        $html = apply_filters('bisk_full_moon_dates', $html);

        return $html;

    }
    
    /**
     * Return the Junior Skills Camp dates
     * 
     * @example [bisk_junior_skills_camp_dates]
     * 
     * @return string The date for the Junior Skills Camp
     */
    public function junior_skills_camp_dates( ) {

        do_action('bisk_before_junior_skills_camp_dates');
       
        $html = 'NO JUNIOR SKILLS CAMP DATES';
        
        $start = BISKOptions::getOption(BISKConfig::JUNIOR_SKILLS_START_DATE);
        $end = BISKOptions::getOption(BISKConfig::JUNIOR_SKILLS_END_DATE);
        if('' !== $start && '' !== $end) {
            $start_ts = strtotime($start);
            $end_ts = strtotime($end);
            $start_str = date('F jS', $start_ts);
            $end_str = date('F jS, Y', $end_ts);
            $html = $start_str . ' to ' . $end_str;
        }

        do_action('bisk_after_junior_skills_camp_dates');
        $html = apply_filters('bisk_junior_skills_camp_dates', $html);

        return $html;

    }
    
    /**
     * Return the Summer Camp dates
     * 
     * @example [bisk_summer_camp_dates]
     * 
     * @return string The Summer Camp dates
     */
    public function summer_camp_dates( $atts ) {

        do_action('bisk_before_summer_camp_dates');

        $html = 'NO JUNIOR SKILLS CAMP DATES';
        $atts = shortcode_atts(
            [
                'week' => ''
            ], $atts, 'bisk_summer_camp_dates'
        );
        
        if('' !== $atts['week']) {

            switch($atts['week']) {
                case 1:
                    $start = BISKOptions::getOption(BISKConfig::SUMMER_CAMP_1_START_DATE);
                    $end = BISKOptions::getOption(BISKConfig::SUMMER_CAMP_1_END_DATE);
                break;
                case 2:
                    $start = BISKOptions::getOption(BISKConfig::SUMMER_CAMP_2_START_DATE);
                    $end = BISKOptions::getOption(BISKConfig::SUMMER_CAMP_2_END_DATE);
                break;
                case 3:
                    $start = BISKOptions::getOption(BISKConfig::SUMMER_CAMP_3_START_DATE);
                    $end = BISKOptions::getOption(BISKConfig::SUMMER_CAMP_3_END_DATE);
                break;
                case 4:
                    $start = BISKOptions::getOption(BISKConfig::SUMMER_CAMP_4_START_DATE);
                    $end = BISKOptions::getOption(BISKConfig::SUMMER_CAMP_4_END_DATE);
                break;
                case 5:
                    $start = BISKOptions::getOption(BISKConfig::SUMMER_CAMP_5_START_DATE);
                    $end = BISKOptions::getOption(BISKConfig::SUMMER_CAMP_5_END_DATE);
                break;
                case 6:
                    $start = BISKOptions::getOption(BISKConfig::SUMMER_CAMP_6_START_DATE);
                    $end = BISKOptions::getOption(BISKConfig::SUMMER_CAMP_6_END_DATE);
                break;
                default:
                $start = '';
                $end = '';
        }

            if('' !== $start && '' !== $end) {
                $start_ts = strtotime($start);
                $end_ts = strtotime($end);
                $start_str = date('F jS', $start_ts);
                $end_str = date('F jS, Y', $end_ts);
                $html = '<p class="summer-camp">Week ' . $atts['week'] . ': ' . $start_str . ' to ' . $end_str . '</p>';
            }
        }

        do_action('bisk_after_summer_camp_dates');
        $html = apply_filters('bisk_summer_camp_dates', $html);

        return $html;

    }

    /**
     * Return the cost of a lesson
     * 
     * @example [bisk_lesson_cost lesson='basic_sea_kayaking']
     * 
     * @return string The cost of a lesson
     */
    public function lesson_cost( $atts ) {

        do_action('bisk_before_lesson_cost');

        $html = 'NO COST';
        $atts = shortcode_atts(
            [
                'lesson' => ''
            ], $atts, 'bisk_lesson_cost'
        );
        
        if('' !== $atts['lesson']) {
            $tour_cost = BISKOptions::getOption($atts['lesson']);
            $html = ('' === $tour_cost) ? '0' : $tour_cost;
        }
        $html = '$' . $html;

        do_action('bisk_after_lesson_cost');
        $html = apply_filters('bisk_lesson_cost', $html);

        return $html;

    }
    
    public static function initShortcodes() {
        // Create the shortcodes
        add_shortcode( 'bisk_opening_date', array(new BISKShortCodes, 'opening_date'));
        add_shortcode( 'bisk_season_opening', array(new BISKShortCodes, 'season_opening'));
        add_shortcode( 'bisk_round_bowen_challenge', array(new BISKShortCodes, 'round_bowen_challenge'));
        add_shortcode( 'bisk_tour_cost', array(new BISKShortCodes, 'tour_cost'));
        add_shortcode( 'bisk_full_moon_dates', array(new BISKShortCodes, 'full_moon_dates'));
        add_shortcode( 'bisk_junior_skills_camp_dates', array(new BISKShortCodes, 'junior_skills_camp_dates'));
        add_shortcode( 'bisk_summer_camp_dates', array(new BISKShortCodes, 'summer_camp_dates'));
        add_shortcode( 'bisk_lesson_cost', array(new BISKShortCodes, 'lesson_cost'));
    }
    
} // class WEOP_Shortcodes
