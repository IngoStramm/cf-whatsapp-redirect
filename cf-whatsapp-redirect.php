<?php

/**
 * Plugin Name: Converte Fácil: Whatsapp Redirect
 * Plugin URI: https://agencialaf.com
 * Description: Descrição do Converte Fácil: Whatsapp Redirect.
 * Version: 1.2.2
 * Author: Ingo Stramm
 * Text Domain: cf-whatsapp-redirect
 * License: GPLv2
 */

defined('ABSPATH') or die('No script kiddies please!');

define('CWR_DIR', plugin_dir_path(__FILE__));
define('CWR_URL', plugin_dir_url(__FILE__));

function cwr_debug($debug)
{
    echo '<pre>';
    var_dump($debug);
    echo '</pre>';
}

require_once 'tgm/tgm.php';
// require_once 'classes/classes.php';
// require_once 'scripts.php';
require_once 'cwr-options.php';
require_once 'cwr-functions.php';

require 'plugin-update-checker-4.10/plugin-update-checker.php';
$updateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://raw.githubusercontent.com/IngoStramm/cf-whatsapp-redirect/master/info.json',
    __FILE__,
    'cf-whatsapp-redirect'
);
