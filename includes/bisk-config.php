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

    const PLUGIN_VERSION                 = '0.1.0';
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
    
    const ROUND_BOWEN_CHALLENGE_NUMBER           = 'round_bowen_challenge_number';
    const ROUND_BOWEN_CHALLENGE_DATE             = 'round_bowen_challenge_date';
    const ROUND_BOWEN_CHALLENGE_EARLY_BIRD_DATE  = 'round_bowen_challenge_early_bird_date';
    const ROUND_BOWEN_CHALLENGE_EARLY_BIRD_PRICE = 'round_bowen_challenge_early_bird_price';
    const ROUND_BOWEN_CHALLENGE_REGULAR_PRICE    = 'round_bowen_challenge_regular_price';
    const ROUND_BOWEN_CHALLENGE_LATE_DATE        = 'round_bowen_challenge_late_date';
    const ROUND_BOWEN_CHALLENGE_LATE_PRICE       = 'round_bowen_challenge_late_price';
            
    const BISK_DEBUG                     = 'bisk_debug';

}
