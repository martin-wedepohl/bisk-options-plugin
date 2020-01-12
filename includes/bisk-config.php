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
 * This class is used to provide all the constants used by the plugin
 */
class BISKConfig {

    const PLUGIN_VERSION                 = '0.1.1';
    const TEXT_DOMAIN                    = 'bisk-options-plugin';

    const ADMIN_CAPABILITY               = 'manage_options';
    const ADMIN_MENU_SLUG                = 'bisk_options';
    const ADMIN_OPTION_GROUP             = 'bisk_option_group';
    const ADMIN_DATE_ID                  = 'bisk_date_section';
    const ADMIN_COST_ID                  = 'bisk_cost_section';
    const ADMIN_ROUND_BOWEN_CHALLENGE_ID = 'bisk_round_bowen_challenge_section';
    const ADMIN_DEBUG_ID                 = 'bisk_debug_section';

    const SETTINGS_KEY                   = 'bisk_config';
    const OPENING_DATE                   = 'opening_date';
    const CLOSING_DATE                   = 'closing_date';
    const FULL_MOON_DATES                = 'full_moon_dates';
    const JUNIOR_SKILLS_START_DATE       = 'junior_skills_start_date';
    const JUNIOR_SKILLS_END_DATE         = 'junior_skills_end_date';
    const SUMMER_CAMP_1_START_DATE       = 'summer_camp_1_start_date';
    const SUMMER_CAMP_1_END_DATE         = 'summer_camp_1_end_date';
    const SUMMER_CAMP_2_START_DATE       = 'summer_camp_2_start_date';
    const SUMMER_CAMP_2_END_DATE         = 'summer_camp_2_end_date';
    const SUMMER_CAMP_3_START_DATE       = 'summer_camp_3_start_date';
    const SUMMER_CAMP_3_END_DATE         = 'summer_camp_3_end_date';
    const SUMMER_CAMP_4_START_DATE       = 'summer_camp_4_start_date';
    const SUMMER_CAMP_4_END_DATE         = 'summer_camp_4_end_date';
    const SUMMER_CAMP_5_START_DATE       = 'summer_camp_5_start_date';
    const SUMMER_CAMP_5_END_DATE         = 'summer_camp_5_end_date';
    const SUMMER_CAMP_6_START_DATE       = 'summer_camp_6_start_date';
    const SUMMER_CAMP_6_END_DATE         = 'summer_camp_6_end_date';

    const SHORE_LINE_TOUR_COST           = 'shore_line_tour_cost';
    const FULL_MOON_TOUR_COST            = 'full_moon_tour_cost';
    const SUNSET_TOUR_COST               = 'sunset_tour_cost';
    const PASLEY_ISLAND_TOUR_COST        = 'pasley_island_tour_cost';
    const HALF_ISLAND_TOUR_COST          = 'half_island_tour_cost';
    const HOWE_SOUND_TOUR_TWO_DAY_COST   = 'howe_sound_tour_two_day_cost';
    const HOWE_SOUND_TOUR_THREE_DAY_COST = 'howe_sound_tour_three_day_cost';
    const HOWE_SOUND_TOUR_FOUR_DAY_COST  = 'howe_sound_tour_four_day_cost';
    const LITTLE_PADDLERS_PARTY          = 'little_paddlers_party';
    const LITTLE_PADDLERS_ADDITIONAL     = 'little_paddlers_additional';
    const TEEN_PADDLERS_PARTY            = 'teen_paddlers_party';
    const TEEN_PADDLERS_ADDITIONAL       = 'teen_paddlers_additional';
    const ADULT_PADDLERS_PARTY           = 'adult_paddlers_party';
    const ADULT_PADDLERS_ADDITIONAL      = 'adult_paddlers_additional';
    const BASIC_SEA_KAYAKING_COST        = 'basic_sea_kayaking_cost';
    const INTRO_TO_STROKES_COST          = 'intro_to_strokes_cost';
    const CAPSIZE_RECOVERY_COST          = 'capsize_recovery_cost';
    const STAND_UP_PADDLE_BOARD_COST     = 'stand_up_paddle_board_cost';
    const JUNIOR_SKILLS_CAMP_COST        = 'junior_skills_camp_cost';
    const KIDS_SUMMER_CAMPS_COST         = 'kids_summer_camps_cost';
    const SEA_KAYAK_BASIC_SKILLS_COST    = 'sea_kayak_basic_skills_cost';
    const SEA_KAYAK_LEVEL_1_COST         = 'sea_kayak_level_1_cost';
    
    const ROUND_BOWEN_CHALLENGE_NUMBER           = 'round_bowen_challenge_number';
    const ROUND_BOWEN_CHALLENGE_DATE             = 'round_bowen_challenge_date';
    const ROUND_BOWEN_CHALLENGE_EARLY_BIRD_DATE  = 'round_bowen_challenge_early_bird_date';
    const ROUND_BOWEN_CHALLENGE_EARLY_BIRD_PRICE = 'round_bowen_challenge_early_bird_price';
    const ROUND_BOWEN_CHALLENGE_REGULAR_PRICE    = 'round_bowen_challenge_regular_price';
    const ROUND_BOWEN_CHALLENGE_LATE_DATE        = 'round_bowen_challenge_late_date';
    const ROUND_BOWEN_CHALLENGE_LATE_PRICE       = 'round_bowen_challenge_late_price';
            
    const BISK_DEBUG                     = 'bisk_debug';

}
