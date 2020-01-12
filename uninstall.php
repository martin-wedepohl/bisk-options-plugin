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

namespace BISKPlugin;

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

use BISKPlugin\Includes\BISKConfig;

// if unstall.php is not called by Wordpress die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

delete_option(BISKConfig::SETTINGS_KEY);
