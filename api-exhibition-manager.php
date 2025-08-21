<?php

/**
 * Plugin Name:       Api Exhibition Manager
 * Plugin URI:        https://github.com/helsingborg-stad/api-exhibition-manager
 * Description:       Creates WordPress Rest API endpoint for exhibitions
 * Author:            Thor Brink
 * Author URI:        https://github.com/helsingborg-stad
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       api-exhibition-manager
 * Domain Path:       /languages
 */

use AcfService\Implementations\NativeAcfService;
use WpService\Implementations\NativeWpService;

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('APIEXHIBITIONMANAGER_PATH', plugin_dir_path(__FILE__));
define('APIEXHIBITIONMANAGER_URL', plugins_url('', __FILE__));
define('APIEXHIBITIONMANAGER_TEMPLATE_PATH', APIEXHIBITIONMANAGER_PATH . 'templates/');

load_plugin_textdomain('api-exhibition-manager', false, plugin_basename(dirname(__FILE__)) . '/languages');

// Autoload from plugin
if (file_exists(APIEXHIBITIONMANAGER_PATH . 'vendor/autoload.php')) {
    require_once APIEXHIBITIONMANAGER_PATH . 'vendor/autoload.php';
}

// Autoload from ABSPATH
if (file_exists(dirname(ABSPATH) . '/vendor/autoload.php')) {
    require_once dirname(ABSPATH) . '/vendor/autoload.php';
}

require_once APIEXHIBITIONMANAGER_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once APIEXHIBITIONMANAGER_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new \ApiExhibitionManager\Vendor\Psr4ClassLoader();
$loader->addPrefix('ApiExhibitionManager', APIEXHIBITIONMANAGER_PATH);
$loader->addPrefix('ApiExhibitionManager', APIEXHIBITIONMANAGER_PATH . 'source/php/');
$loader->register();

// Acf auto import and export
$acfExportManager = new \AcfExportManager\AcfExportManager();
$acfExportManager->setTextdomain('api-exhibition-manager');
$acfExportManager->setExportFolder(APIEXHIBITIONMANAGER_PATH . 'source/php/AcfFields/');
$acfExportManager->autoExport(array(
    // Acf fields to export, eg 'file_name' => 'group_123'
));
$acfExportManager->import();

// Start application
new \ApiExhibitionManager\App(new NativeWpService(), new NativeAcfService());
