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
 * Handle the setting of the options used in the plugin
 */
class BISKSettingsPage {

    private $optionsPage = '';

    /**
     * Class constructor.
     *
     * @param string $plugin_name
     */
    public function __construct($plugin_name) {
        add_action('admin_menu', [$this, 'addMenu']);
        add_action('admin_init', [$this, 'registerSettingsPage']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminStylesScripts']);
        add_filter('plugin_action_links_' . $plugin_name, [$this, 'addSettingsLink']);
        add_filter('custom_menu_order', [$this, 'reorder_admin_menu']);
    }

    /**
     * Add the menu to the WordPress menu.
     *
     * Can be displayed under a sub menu or as a primary menu.
     */
    public function addMenu() {

        $this->optionsPage = add_menu_page(
                esc_html__('BISK Plugin Options', BISKConfig::TEXT_DOMAIN),
                esc_html__('BISK Options', BISKConfig::TEXT_DOMAIN),
                BISKConfig::ADMIN_CAPABILITY,
                BISKConfig::ADMIN_MENU_SLUG,
                [$this, 'createMenuPage'],
                'dashicons-admin-generic',
                500
        );
        
        add_submenu_page(
            BISKConfig::ADMIN_MENU_SLUG,
            esc_html__('BISK Plugin Options', BISKConfig::TEXT_DOMAIN),
            esc_html__('Options', BISKConfig::TEXT_DOMAIN),
            BISKConfig::ADMIN_CAPABILITY,
            BISKConfig::ADMIN_MENU_SLUG,
            [$this, 'createMenuPage']
        );
        
        add_submenu_page(
            BISKConfig::ADMIN_MENU_SLUG,
            esc_html__('BISK Plugin Information', BISKConfig::TEXT_DOMAIN),
            esc_html__('Information', BISKConfig::TEXT_DOMAIN),
            BISKConfig::ADMIN_CAPABILITY,
            BISKConfig::ADMIN_MENU_SLUG . '-info',
            [$this, 'displayInfo']
        );
        
    }
    
    /**
     * Re-order the custom menu
     * 
     * @global array $submenu
     * @param boolean $menu_order
     * @return boolean
     */
    public function reorder_admin_menu($menu_order) {

        global $submenu;

        $menu = $submenu[BISKConfig::ADMIN_MENU_SLUG];

        $newmenu = array();
        $key1 = array_search('Options', array_column($menu, 0));
        $newmenu[] = $menu[$key1];
        $key2 = array_search('Information', array_column($menu, 0));
        $newmenu[] = $menu[$key2];
        unset($menu[$key1]);
        unset($menu[$key2]);
        foreach($menu as $key => $menu) {
            $newmenu[] = $menu;
        }

        $submenu[BISKConfig::ADMIN_MENU_SLUG] = $newmenu;

        return $menu_order;

    } // reorder_admin_menu
    
    /**
     * Create the menu page that will show all the options associated with the plugin.
     */
    public function createMenuPage() {
        if (!current_user_can(BISKConfig::ADMIN_CAPABILITY)) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page', BISKConfig::TEXT_DOMAIN));
        }

        printf('<div class="wrap"><h2>%s</h2><form action="options.php" method="post">', esc_html__('BISK Plugin Options', BISKConfig::TEXT_DOMAIN));
        
        settings_fields(BISKConfig::ADMIN_OPTION_GROUP);
        do_settings_sections(BISKConfig::ADMIN_MENU_SLUG);
        submit_button();
        settings_errors();
        
        printf('</form></div> <!-- /.wrap -->');
        printf('<div class="wrap"><p>%s %s</p></div> <!-- /.wrap -->', esc_html__('Plugin Version:', BISKConfig::TEXT_DOMAIN), BISKOptions::getVersion());
    }

        /**
         * Display the Wedepohl Engineeering Information Page
         */
        public function displayInfo() {
?>

<div class="wrap">
<?php settings_errors(); ?>
    <h1><?php _e('BISK Options', $this->text_domain); ?></h1>
    <h2><?php _e('Analytics Options', $this->text_domain); ?></h2>
    <ul>
        <li><?php _e('Google Analytics Code: - Code for Google Analytics', $this->text_domain); ?></li>
    </ul>
    <h2><?php _e('Shortcodes', $this->text_domain); ?></h2>
    <ul>
        <li>[bisk_opening_date format='Y-m-d'] - Displays the Opening date in a specific format</li>
        <li>[bisk_season_opening] - Displays the date of the opening of the season</li>
    </ul>
    <h2><?php _e('Remove Shortcode', $this->text_domain); ?></h2>
    <pre>
        $shortcode_handler = apply_filter( 'get_bisk_shortcode_instance', NULL );
        if( is_a( $shortcode_handler, 'BISKShortCodes' ) {
           // Do something with the instance of the handler
        }
    </pre>
</div>

<?php
        } // displayInfo
    
    
    /**
     * Register the settings page with settings sections and fields.
     */
    public function registerSettingsPage() {
        register_setting(
                BISKConfig::ADMIN_OPTION_GROUP,
                BISKConfig::SETTINGS_KEY,
                [$this, 'validateData']
        );
        add_settings_section(
                BISKConfig::ADMIN_DATE_ID,
                null,
                [$this, 'sectionDateTitle'],
                BISKConfig::ADMIN_MENU_SLUG
        );
        add_settings_field(
                BISKConfig::OPENING_DATE,
                esc_html__('Opening Date:', BISKConfig::TEXT_DOMAIN),
                [$this, 'textField'],
                BISKConfig::ADMIN_MENU_SLUG,
                BISKConfig::ADMIN_DATE_ID,
                array(
                    'classes' => 'bisk_date',
                    'value' => BISKOptions::getOption(BISKConfig::OPENING_DATE),
                    'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::OPENING_DATE . ']',
                    'id' => BISKConfig::OPENING_DATE,
                    'description' => esc_html__('Opening date for the season', BISKConfig::TEXT_DOMAIN)
                )
        );
        add_settings_field(
                BISKConfig::CLOSING_DATE,
                esc_html__('Closing Date:', BISKConfig::TEXT_DOMAIN),
                [$this, 'textField'],
                BISKConfig::ADMIN_MENU_SLUG,
                BISKConfig::ADMIN_DATE_ID,
                array(
                    'classes' => 'bisk_date',
                    'value' => BISKOptions::getOption(BISKConfig::CLOSING_DATE),
                    'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::CLOSING_DATE . ']',
                    'id' => BISKConfig::CLOSING_DATE,
                    'description' => esc_html__('Closing date for the season', BISKConfig::TEXT_DOMAIN)
                )
        );
        add_settings_field(
                BISKConfig::FULL_MOON_DATES,
                esc_html__('Full Moon Dates:', BISKConfig::TEXT_DOMAIN),
                [$this, 'textField'],
                BISKConfig::ADMIN_MENU_SLUG,
                BISKConfig::ADMIN_DATE_ID,
                array(
                    'classes' => 'widefat',
                    'value' => BISKOptions::getOption(BISKConfig::FULL_MOON_DATES),
                    'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::FULL_MOON_DATES . ']',
                    'id' => BISKConfig::FULL_MOON_DATES,
                    'description' => esc_html__('Full Moon dates for the season', BISKConfig::TEXT_DOMAIN)
                )
        );
        add_settings_field(
            BISKConfig::JUNIOR_SKILLS_START_DATE,
            esc_html__('Junior Skills Camp Start Date:', BISKConfig::TEXT_DOMAIN),
            [$this, 'textField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_DATE_ID,
            array(
                'classes' => 'bisk_date',
                'value' => BISKOptions::getOption(BISKConfig::JUNIOR_SKILLS_START_DATE),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::JUNIOR_SKILLS_START_DATE . ']',
                'id' => BISKConfig::JUNIOR_SKILLS_START_DATE
            )
        );
        add_settings_field(
            BISKConfig::JUNIOR_SKILLS_END_DATE,
            esc_html__('Junior Skills Camp End Date:', BISKConfig::TEXT_DOMAIN),
            [$this, 'textField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_DATE_ID,
            array(
                'classes' => 'bisk_date',
                'value' => BISKOptions::getOption(BISKConfig::JUNIOR_SKILLS_END_DATE),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::JUNIOR_SKILLS_END_DATE . ']',
                'id' => BISKConfig::JUNIOR_SKILLS_END_DATE
            )
        );
        add_settings_field(
            BISKConfig::SUMMER_CAMP_1_START_DATE,
            esc_html__('Summer Camp #1 Start Date:', BISKConfig::TEXT_DOMAIN),
            [$this, 'textField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_DATE_ID,
            array(
                'classes' => 'bisk_date',
                'value' => BISKOptions::getOption(BISKConfig::SUMMER_CAMP_1_START_DATE),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::SUMMER_CAMP_1_START_DATE . ']',
                'id' => BISKConfig::SUMMER_CAMP_1_START_DATE
            )
        );
        add_settings_field(
            BISKConfig::SUMMER_CAMP_1_END_DATE,
            esc_html__('Summer Camp #1 End Date:', BISKConfig::TEXT_DOMAIN),
            [$this, 'textField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_DATE_ID,
            array(
                'classes' => 'bisk_date',
                'value' => BISKOptions::getOption(BISKConfig::SUMMER_CAMP_1_END_DATE),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::SUMMER_CAMP_1_END_DATE . ']',
                'id' => BISKConfig::SUMMER_CAMP_1_END_DATE
            )
        );
        add_settings_field(
            BISKConfig::SUMMER_CAMP_2_START_DATE,
            esc_html__('Summer Camp #2 Start Date:', BISKConfig::TEXT_DOMAIN),
            [$this, 'textField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_DATE_ID,
            array(
                'classes' => 'bisk_date',
                'value' => BISKOptions::getOption(BISKConfig::SUMMER_CAMP_2_START_DATE),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::SUMMER_CAMP_2_START_DATE . ']',
                'id' => BISKConfig::SUMMER_CAMP_2_START_DATE
            )
        );
        add_settings_field(
            BISKConfig::SUMMER_CAMP_2_END_DATE,
            esc_html__('Summer Camp #2 End Date:', BISKConfig::TEXT_DOMAIN),
            [$this, 'textField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_DATE_ID,
            array(
                'classes' => 'bisk_date',
                'value' => BISKOptions::getOption(BISKConfig::SUMMER_CAMP_2_END_DATE),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::SUMMER_CAMP_2_END_DATE . ']',
                'id' => BISKConfig::SUMMER_CAMP_2_END_DATE
            )
        );
        add_settings_field(
            BISKConfig::SUMMER_CAMP_3_START_DATE,
            esc_html__('Summer Camp #3 Start Date:', BISKConfig::TEXT_DOMAIN),
            [$this, 'textField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_DATE_ID,
            array(
                'classes' => 'bisk_date',
                'value' => BISKOptions::getOption(BISKConfig::SUMMER_CAMP_3_START_DATE),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::SUMMER_CAMP_3_START_DATE . ']',
                'id' => BISKConfig::SUMMER_CAMP_3_START_DATE
            )
        );
        add_settings_field(
            BISKConfig::SUMMER_CAMP_3_END_DATE,
            esc_html__('Summer Camp #3 End Date:', BISKConfig::TEXT_DOMAIN),
            [$this, 'textField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_DATE_ID,
            array(
                'classes' => 'bisk_date',
                'value' => BISKOptions::getOption(BISKConfig::SUMMER_CAMP_3_END_DATE),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::SUMMER_CAMP_3_END_DATE . ']',
                'id' => BISKConfig::SUMMER_CAMP_3_END_DATE
            )
        );
        add_settings_field(
            BISKConfig::SUMMER_CAMP_4_START_DATE,
            esc_html__('Summer Camp #4 Start Date:', BISKConfig::TEXT_DOMAIN),
            [$this, 'textField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_DATE_ID,
            array(
                'classes' => 'bisk_date',
                'value' => BISKOptions::getOption(BISKConfig::SUMMER_CAMP_4_START_DATE),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::SUMMER_CAMP_4_START_DATE . ']',
                'id' => BISKConfig::SUMMER_CAMP_4_START_DATE
            )
        );
        add_settings_field(
            BISKConfig::SUMMER_CAMP_4_END_DATE,
            esc_html__('Summer Camp #4 End Date:', BISKConfig::TEXT_DOMAIN),
            [$this, 'textField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_DATE_ID,
            array(
                'classes' => 'bisk_date',
                'value' => BISKOptions::getOption(BISKConfig::SUMMER_CAMP_4_END_DATE),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::SUMMER_CAMP_4_END_DATE . ']',
                'id' => BISKConfig::SUMMER_CAMP_4_END_DATE
            )
        );
        add_settings_field(
            BISKConfig::SUMMER_CAMP_5_START_DATE,
            esc_html__('Summer Camp #5 Start Date:', BISKConfig::TEXT_DOMAIN),
            [$this, 'textField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_DATE_ID,
            array(
                'classes' => 'bisk_date',
                'value' => BISKOptions::getOption(BISKConfig::SUMMER_CAMP_5_START_DATE),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::SUMMER_CAMP_5_START_DATE . ']',
                'id' => BISKConfig::SUMMER_CAMP_5_START_DATE
            )
        );
        add_settings_field(
            BISKConfig::SUMMER_CAMP_5_END_DATE,
            esc_html__('Summer Camp #5 End Date:', BISKConfig::TEXT_DOMAIN),
            [$this, 'textField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_DATE_ID,
            array(
                'classes' => 'bisk_date',
                'value' => BISKOptions::getOption(BISKConfig::SUMMER_CAMP_5_END_DATE),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::SUMMER_CAMP_5_END_DATE . ']',
                'id' => BISKConfig::SUMMER_CAMP_5_END_DATE
            )
        );
        add_settings_field(
            BISKConfig::SUMMER_CAMP_6_START_DATE,
            esc_html__('Summer Camp #6 Start Date:', BISKConfig::TEXT_DOMAIN),
            [$this, 'textField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_DATE_ID,
            array(
                'classes' => 'bisk_date',
                'value' => BISKOptions::getOption(BISKConfig::SUMMER_CAMP_6_START_DATE),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::SUMMER_CAMP_6_START_DATE . ']',
                'id' => BISKConfig::SUMMER_CAMP_6_START_DATE
            )
        );
        add_settings_field(
            BISKConfig::SUMMER_CAMP_6_END_DATE,
            esc_html__('Summer Camp #6 End Date:', BISKConfig::TEXT_DOMAIN),
            [$this, 'textField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_DATE_ID,
            array(
                'classes' => 'bisk_date',
                'value' => BISKOptions::getOption(BISKConfig::SUMMER_CAMP_6_END_DATE),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::SUMMER_CAMP_6_END_DATE . ']',
                'id' => BISKConfig::SUMMER_CAMP_6_END_DATE
            )
        );
        add_settings_section(
                BISKConfig::ADMIN_COST_ID,
                null,
                [$this, 'sectionPriceTitle'],
                BISKConfig::ADMIN_MENU_SLUG
        );
        
        add_settings_field(
            BISKConfig::SHORE_LINE_TOUR_COST,
            esc_html__('Shore Line Tour Cost ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::SHORE_LINE_TOUR_COST),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::SHORE_LINE_TOUR_COST . ']',
                'id' => BISKConfig::SHORE_LINE_TOUR_COST,
                'min' => '0',
                'max' => '5000'
            )
        );
        
        add_settings_field(
            BISKConfig::FULL_MOON_TOUR_COST,
            esc_html__('Full Moon Tour Cost ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::FULL_MOON_TOUR_COST),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::FULL_MOON_TOUR_COST . ']',
                'id' => BISKConfig::FULL_MOON_TOUR_COST,
                'min' => '0',
                'max' => '5000'
            )
        );
        
        add_settings_field(
            BISKConfig::SUNSET_TOUR_COST,
            esc_html__('Sunset Tour Cost ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::SUNSET_TOUR_COST),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::SUNSET_TOUR_COST . ']',
                'id' => BISKConfig::SUNSET_TOUR_COST,
                'min' => '0',
                'max' => '5000'
            )
        );
        
        add_settings_field(
            BISKConfig::PASLEY_ISLAND_TOUR_COST,
            esc_html__('Pasley Island Tour Cost ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::PASLEY_ISLAND_TOUR_COST),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::PASLEY_ISLAND_TOUR_COST . ']',
                'id' => BISKConfig::PASLEY_ISLAND_TOUR_COST,
                'min' => '0',
                'max' => '5000'
            )
        );
        
        add_settings_field(
            BISKConfig::HALF_ISLAND_TOUR_COST,
            esc_html__('Half Island Tour Cost ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::HALF_ISLAND_TOUR_COST),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::HALF_ISLAND_TOUR_COST . ']',
                'id' => BISKConfig::HALF_ISLAND_TOUR_COST,
                'min' => '0',
                'max' => '5000'
            )
        );
        
        add_settings_field(
            BISKConfig::HOWE_SOUND_TOUR_TWO_DAY_COST,
            esc_html__('Howe Sound 2 Day Tour Cost ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::HOWE_SOUND_TOUR_TWO_DAY_COST),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::HOWE_SOUND_TOUR_TWO_DAY_COST . ']',
                'id' => BISKConfig::HOWE_SOUND_TOUR_TWO_DAY_COST,
                'min' => '0',
                'max' => '5000'
            )
        );
        
        add_settings_field(
            BISKConfig::HOWE_SOUND_TOUR_THREE_DAY_COST,
            esc_html__('Howe Sound 3 Day Tour Cost ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::HOWE_SOUND_TOUR_THREE_DAY_COST),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::HOWE_SOUND_TOUR_THREE_DAY_COST . ']',
                'id' => BISKConfig::HOWE_SOUND_TOUR_THREE_DAY_COST,
                'min' => '0',
                'max' => '5000'
            )
        );
        
        add_settings_field(
            BISKConfig::HOWE_SOUND_TOUR_FOUR_DAY_COST,
            esc_html__('Howe Sound 4 Day Tour Cost ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::HOWE_SOUND_TOUR_FOUR_DAY_COST),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::HOWE_SOUND_TOUR_FOUR_DAY_COST . ']',
                'id' => BISKConfig::HOWE_SOUND_TOUR_FOUR_DAY_COST,
                'min' => '0',
                'max' => '5000'
            )
        );
        
        add_settings_field(
            BISKConfig::LITTLE_PADDLERS_PARTY,
            esc_html__('Little Paddlers Party ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::LITTLE_PADDLERS_PARTY),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::LITTLE_PADDLERS_PARTY . ']',
                'id' => BISKConfig::LITTLE_PADDLERS_PARTY,
                'min' => '0',
                'max' => '5000'
            )
        );
        
        add_settings_field(
            BISKConfig::LITTLE_PADDLERS_ADDITIONAL,
            esc_html__('Little Paddlers Additional ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::LITTLE_PADDLERS_ADDITIONAL),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::LITTLE_PADDLERS_ADDITIONAL . ']',
                'id' => BISKConfig::LITTLE_PADDLERS_ADDITIONAL,
                'min' => '0',
                'max' => '5000',
                'description' => esc_html('Cost for the Little Paddlers Party Additional Guests', BISKConfig::TEXT_DOMAIN)
            )
        );
        
        add_settings_field(
            BISKConfig::TEEN_PADDLERS_PARTY,
            esc_html__('Teen Paddlers Party ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::TEEN_PADDLERS_PARTY),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::TEEN_PADDLERS_PARTY . ']',
                'id' => BISKConfig::TEEN_PADDLERS_PARTY,
                'min' => '0',
                'max' => '5000'
            )
        );
        
        add_settings_field(
            BISKConfig::TEEN_PADDLERS_ADDITIONAL,
            esc_html__('Teen Paddlers Additional ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::TEEN_PADDLERS_ADDITIONAL),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::TEEN_PADDLERS_ADDITIONAL . ']',
                'id' => BISKConfig::TEEN_PADDLERS_ADDITIONAL,
                'min' => '0',
                'max' => '5000',
                'description' => esc_html('Cost for the Teen Paddlers Party Additional Guests', BISKConfig::TEXT_DOMAIN)
            )
        );
        
        add_settings_field(
            BISKConfig::ADULT_PADDLERS_PARTY,
            esc_html__('Adult Paddlers Party ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::ADULT_PADDLERS_PARTY),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::ADULT_PADDLERS_PARTY . ']',
                'id' => BISKConfig::ADULT_PADDLERS_PARTY,
                'min' => '0',
                'max' => '5000'
            )
        );
        
        add_settings_field(
            BISKConfig::ADULT_PADDLERS_ADDITIONAL,
            esc_html__('Adult Paddlers Additional ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::ADULT_PADDLERS_ADDITIONAL),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::ADULT_PADDLERS_ADDITIONAL . ']',
                'id' => BISKConfig::ADULT_PADDLERS_ADDITIONAL,
                'min' => '0',
                'max' => '5000',
                'description' => esc_html('Cost for the Adult Paddlers Party Additional Guests', BISKConfig::TEXT_DOMAIN)
            )
        );
        
        add_settings_field(
            BISKConfig::BASIC_SEA_KAYAKING_COST,
            esc_html__('Basic Sea Kayaking Cost ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::BASIC_SEA_KAYAKING_COST),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::BASIC_SEA_KAYAKING_COST . ']',
                'id' => BISKConfig::BASIC_SEA_KAYAKING_COST,
                'min' => '0',
                'max' => '5000'
            )
        );
        
        add_settings_field(
            BISKConfig::INTRO_TO_STROKES_COST,
            esc_html__('Intro to Strokes Cost ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::INTRO_TO_STROKES_COST),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::INTRO_TO_STROKES_COST . ']',
                'id' => BISKConfig::INTRO_TO_STROKES_COST,
                'min' => '0',
                'max' => '5000'
            )
        );
        
        add_settings_field(
            BISKConfig::CAPSIZE_RECOVERY_COST,
            esc_html__('Capsize Recovery Cost ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::CAPSIZE_RECOVERY_COST),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::CAPSIZE_RECOVERY_COST . ']',
                'id' => BISKConfig::CAPSIZE_RECOVERY_COST,
                'min' => '0',
                'max' => '5000'
            )
        );
        
        add_settings_field(
            BISKConfig::STAND_UP_PADDLE_BOARD_COST,
            esc_html__('Stand Up Paddle Board Cost ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::STAND_UP_PADDLE_BOARD_COST),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::STAND_UP_PADDLE_BOARD_COST . ']',
                'id' => BISKConfig::STAND_UP_PADDLE_BOARD_COST,
                'min' => '0',
                'max' => '5000'
            )
        );
        
        add_settings_field(
            BISKConfig::JUNIOR_SKILLS_CAMP_COST,
            esc_html__('Junior Skills Camp Cost ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::JUNIOR_SKILLS_CAMP_COST),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::JUNIOR_SKILLS_CAMP_COST . ']',
                'id' => BISKConfig::JUNIOR_SKILLS_CAMP_COST,
                'min' => '0',
                'max' => '5000'
            )
        );
        
        add_settings_field(
            BISKConfig::KIDS_SUMMER_CAMPS_COST,
            esc_html__('Kids Summer Camp Cost ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::KIDS_SUMMER_CAMPS_COST),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::KIDS_SUMMER_CAMPS_COST . ']',
                'id' => BISKConfig::KIDS_SUMMER_CAMPS_COST,
                'min' => '0',
                'max' => '5000'
            )
        );
        
        add_settings_field(
            BISKConfig::SEA_KAYAK_BASIC_SKILLS_COST,
            esc_html__('Sea Kayak Basic Skills Cost ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::SEA_KAYAK_BASIC_SKILLS_COST),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::SEA_KAYAK_BASIC_SKILLS_COST . ']',
                'id' => BISKConfig::SEA_KAYAK_BASIC_SKILLS_COST,
                'min' => '0',
                'max' => '5000'
            )
        );
        
        add_settings_field(
            BISKConfig::SEA_KAYAK_LEVEL_1_COST,
            esc_html__('Sea Kayak Level 1 Cost ($):', BISKConfig::TEXT_DOMAIN),
            [$this, 'numberField'],
            BISKConfig::ADMIN_MENU_SLUG,
            BISKConfig::ADMIN_COST_ID,
            array(
                'classes' => '',
                'value' => BISKOptions::getOption(BISKConfig::SEA_KAYAK_LEVEL_1_COST),
                'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::SEA_KAYAK_LEVEL_1_COST . ']',
                'id' => BISKConfig::SEA_KAYAK_LEVEL_1_COST,
                'min' => '0',
                'max' => '5000'
            )
        );
        
        add_settings_section(
                BISKConfig::ADMIN_ROUND_BOWEN_CHALLENGE_ID,
                null,
                [$this, 'sectionRoundBowenTitle'],
                BISKConfig::ADMIN_MENU_SLUG
        );
        
        add_settings_field(
                BISKConfig::ROUND_BOWEN_CHALLENGE_NUMBER,
                esc_html__('Annual Round Bowen Challenge Number:', BISKConfig::TEXT_DOMAIN),
                [$this, 'numberField'],
                BISKConfig::ADMIN_MENU_SLUG,
                BISKConfig::ADMIN_ROUND_BOWEN_CHALLENGE_ID,
                array(
                    'classes' => '',
                    'value' => BISKOptions::getOption(BISKConfig::ROUND_BOWEN_CHALLENGE_NUMBER),
                    'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::ROUND_BOWEN_CHALLENGE_NUMBER . ']',
                    'id' => BISKConfig::ROUND_BOWEN_CHALLENGE_NUMBER,
                    'description' => esc_html__('Number for the Annual Round Bowen Challenge', BISKConfig::TEXT_DOMAIN)
                )
        );

        add_settings_field(
                BISKConfig::ROUND_BOWEN_CHALLENGE_DATE,
                esc_html__('Round Bowen Challenge Date:', BISKConfig::TEXT_DOMAIN),
                [$this, 'textField'],
                BISKConfig::ADMIN_MENU_SLUG,
                BISKConfig::ADMIN_ROUND_BOWEN_CHALLENGE_ID,
                array(
                    'classes' => 'bisk_date',
                    'value' => BISKOptions::getOption(BISKConfig::ROUND_BOWEN_CHALLENGE_DATE),
                    'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::ROUND_BOWEN_CHALLENGE_DATE . ']',
                    'id' => BISKConfig::ROUND_BOWEN_CHALLENGE_DATE
                )
        );

        add_settings_field(
                BISKConfig::ROUND_BOWEN_CHALLENGE_REGULAR_PRICE,
                esc_html__('Round Bowen Challenge Regular Price:', BISKConfig::TEXT_DOMAIN),
                [$this, 'numberField'],
                BISKConfig::ADMIN_MENU_SLUG,
                BISKConfig::ADMIN_ROUND_BOWEN_CHALLENGE_ID,
                array(
                    'classes' => '',
                    'value' => BISKOptions::getOption(BISKConfig::ROUND_BOWEN_CHALLENGE_REGULAR_PRICE),
                    'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::ROUND_BOWEN_CHALLENGE_REGULAR_PRICE . ']',
                    'id' => BISKConfig::ROUND_BOWEN_CHALLENGE_REGULAR_PRICE
                )
        );

        add_settings_field(
                BISKConfig::ROUND_BOWEN_CHALLENGE_EARLY_BIRD_DATE,
                esc_html__('Round Bowen Challenge Early Bird Date:', BISKConfig::TEXT_DOMAIN),
                [$this, 'textField'],
                BISKConfig::ADMIN_MENU_SLUG,
                BISKConfig::ADMIN_ROUND_BOWEN_CHALLENGE_ID,
                array(
                    'classes' => 'bisk_date',
                    'value' => BISKOptions::getOption(BISKConfig::ROUND_BOWEN_CHALLENGE_EARLY_BIRD_DATE),
                    'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::ROUND_BOWEN_CHALLENGE_EARLY_BIRD_DATE . ']',
                    'id' => BISKConfig::ROUND_BOWEN_CHALLENGE_EARLY_BIRD_DATE
                )
        );

        add_settings_field(
                BISKConfig::ROUND_BOWEN_CHALLENGE_EARLY_BIRD_PRICE,
                esc_html__('Round Bowen Challenge Early Bird Price:', BISKConfig::TEXT_DOMAIN),
                [$this, 'numberField'],
                BISKConfig::ADMIN_MENU_SLUG,
                BISKConfig::ADMIN_ROUND_BOWEN_CHALLENGE_ID,
                array(
                    'classes' => '',
                    'value' => BISKOptions::getOption(BISKConfig::ROUND_BOWEN_CHALLENGE_EARLY_BIRD_PRICE),
                    'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::ROUND_BOWEN_CHALLENGE_EARLY_BIRD_PRICE . ']',
                    'id' => BISKConfig::ROUND_BOWEN_CHALLENGE_EARLY_BIRD_PRICE
                )
        );

        add_settings_field(
                BISKConfig::ROUND_BOWEN_CHALLENGE_LATE_DATE,
                esc_html__('Round Bowen Challenge Late Registration Date:', BISKConfig::TEXT_DOMAIN),
                [$this, 'textField'],
                BISKConfig::ADMIN_MENU_SLUG,
                BISKConfig::ADMIN_ROUND_BOWEN_CHALLENGE_ID,
                array(
                    'classes' => 'bisk_date',
                    'value' => BISKOptions::getOption(BISKConfig::ROUND_BOWEN_CHALLENGE_LATE_DATE),
                    'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::ROUND_BOWEN_CHALLENGE_LATE_DATE . ']',
                    'id' => BISKConfig::ROUND_BOWEN_CHALLENGE_LATE_DATE
                )
        );

        add_settings_field(
                BISKConfig::ROUND_BOWEN_CHALLENGE_LATE_PRICE,
                esc_html__('Round Bowen Challenge Late Price:', BISKConfig::TEXT_DOMAIN),
                [$this, 'numberField'],
                BISKConfig::ADMIN_MENU_SLUG,
                BISKConfig::ADMIN_ROUND_BOWEN_CHALLENGE_ID,
                array(
                    'classes' => '',
                    'value' => BISKOptions::getOption(BISKConfig::ROUND_BOWEN_CHALLENGE_LATE_PRICE),
                    'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::ROUND_BOWEN_CHALLENGE_LATE_PRICE . ']',
                    'id' => BISKConfig::ROUND_BOWEN_CHALLENGE_LATE_PRICE
                )
        );

        add_settings_section(
                BISKConfig::ADMIN_DEBUG_ID,
                null,
                [$this, 'sectionDebugTitle'],
                BISKConfig::ADMIN_MENU_SLUG
        );

        $checked = ('' === BISKOptions::getOption(BISKConfig::BISK_DEBUG)) ? '' : 'checked';
        add_settings_field(
                BISKConfig::BISK_DEBUG,
                esc_html__('Enable Debug:', BISKConfig::TEXT_DOMAIN),
                [$this, 'checkboxField'],
                BISKConfig::ADMIN_MENU_SLUG,
                BISKConfig::ADMIN_DEBUG_ID,
                array(
                    'classes' => '',
                    'name' => BISKConfig::SETTINGS_KEY . '[' . BISKConfig::BISK_DEBUG . ']',
                    'id' => BISKConfig::BISK_DEBUG,
                    'description' => esc_html__('Enable debugging of the plugin and theme', BISKConfig::TEXT_DOMAIN),
                    'checked' => $checked
                )
        );
        
    }

    /**
     * Called when the save changes button has been pressed to save the plugin options and used
     * to validate all the input fields.
     *
     * @param array $input
     * @return array
     */
    public function validateData($input) {
        $output = $input;
        
        // TODO: Add validation code for all the options
        
        return apply_filters( 'validateData', $output, $input );
    }

    /**
     * Display the date sub title for the Options Page.
     */
    public function sectionDateTitle() {
        printf('<h3>' . esc_html__('BISK Date Options', BISKConfig::TEXT_DOMAIN ) . '</h3>');
    }

    /**
     * Display the date sub title for the Options Page.
     */
    public function sectionPriceTitle() {
        printf('<h3>' . esc_html__('BISK Price Options', BISKConfig::TEXT_DOMAIN ) . '</h3>');
    }

    /**
     * Display the date sub title for the Options Page.
     */
    public function sectionRoundBowenTitle() {
        printf('<h3>' . esc_html__('BISK Round Bowen Challenge Options', BISKConfig::TEXT_DOMAIN ) . '</h3>');
    }

    /**
     * Display the date sub title for the Options Page.
     */
    public function sectionDebugTitle() {
        printf('<h3>' . esc_html__('BISK Debug Options', BISKConfig::TEXT_DOMAIN ) . '</h3>');
    }

    /**
     * Display a text field in the form.
     * 
     * @param array $args The arguments passed to the function.
     */
    public function textField($args) {

        $args = shortcode_atts(
                array(
                    'classes'     => '',
                    'name'        => '',
                    'id'          => '',
                    'value'       => '',
                    'description' => ''
                ),
                $args
        );        
        
        printf('<input type="text" class="%s" name="%s" id="%s" value="%s" /><span class="description"> %s</span>',
                $args['classes'], $args['name'], $args['id'], $args['value'], $args['description']
        );
    }

    /**
     * Display a number field in the form.
     * 
     * @param array $args The arguments passed to the function.
     */
    public function numberField($args) {

        $args = shortcode_atts(
                array(
                    'classes'     => '',
                    'name'        => '',
                    'id'          => '',
                    'value'       => '',
                    'description' => '',
                    'min'         => '0',
                    'max'         => '200'
                ),
                $args
        );        
        
        printf('<input type="number" min="%s" max="%s" class="%s" name="%s" id="%s" value="%s" /><span class="description"> %s</span>',
                $args['min'], $args['max'], $args['classes'], $args['name'], $args['id'], $args['value'], $args['description']
        );
    }

    /**
     * Display a checkbox field in the form.
     * 
     * @param array $args The arguments passed to the function.
     */
    public function checkboxField($args) {

        $args = shortcode_atts(
                array(
                    'classes'     => '',
                    'name'        => '',
                    'id'          => '',
                    'description' => '',
                    'checked'     => ''
                ),
                $args
        );        
        
        printf('<input type="checkbox" class="%s" name="%s" id="%s" value="1" %s /><span class="description"> %s</span>',
                $args['classes'], $args['name'], $args['id'], $args['checked'], $args['description']
        );
    }

    /**
     * Enqueue plugin styles and scripts used in the plugin.
     */
    public function enqueueAdminStylesScripts() {
        wp_enqueue_style('bisk-admin', plugins_url('../dist/css/admin.min.css', __FILE__));
        wp_enqueue_style('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/redmond/jquery-ui.min.css');

        wp_enqueue_script(
                'bisk-admin',
                plugins_url('../dist/js/admin.min.js', __FILE__),
                array('jquery', 'jquery-ui-datepicker'),
                false,
                true
        );
        wp_enqueue_script(
                'jquery-ui-datepicker',
                '',
                array('jquery'),
                false,
                true
        );
    }

    /**
     * Display a settings link on the plugins page.
     * 
     * @param array $links
     * @return array
     */
    public function addSettingsLink($links) {
        $settings = [
            'settings' => sprintf(
                    '<a href="%s">%s</a>',
                    admin_url('admin.php?page=' . BISKConfig::ADMIN_MENU_SLUG),
                    'Settings'
            )
        ];
        return array_merge($settings, $links);
    }

}
