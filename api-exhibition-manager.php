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

use ApiExhibitionManager\AdminAreasDisabler\AdminAreasDisabler;
use ApiExhibitionManager\GutenbergDisabler\GutenbergDisabler;
use ApiExhibitionManager\RedirectToRestApiOnFrontend\Redirector\Exiter\Exiter;
use ApiExhibitionManager\RedirectToRestApiOnFrontend\Redirector\HeaderDispatcher\HeaderDispatcher;
use ApiExhibitionManager\RedirectToRestApiOnFrontend\Redirector\Redirector;
use ApiExhibitionManager\RedirectToRestApiOnFrontend\RedirectToRestApiOnFrontend;
use ApiExhibitionManager\RedirectToRestApiOnFrontend\RequestUriProvider\RequestUriProvider;
use ApiExhibitionManager\PostTypeRegistrar\PostTypeRegistrar;
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
    'exhibition' => 'group_68a837bcde3c7',
));
$acfExportManager->import();

$wpService = new NativeWpService();

/**
 * Redirects users to the WordPress REST API URL if they are not in the admin area
 * and are not already visiting the REST API or any of its sub-routes.
 */
(new RedirectToRestApiOnFrontend(
    $wpService,
    new RequestUriProvider($wpService),
    new Redirector(new HeaderDispatcher(), new Exiter())
))->addHooks();

/**
 * Disables certain admin areas that are not relevant for this plugin.
 */
(new AdminAreasDisabler($wpService))->addHooks();

/**
 * Disables Gutenberg editor for all post types
 */
(new GutenbergDisabler($wpService))->addHooks();

/**
 * Registers the exhibition post type.
 */
(new PostTypeRegistrar(
    'exhibition',
    $wpService->__('Exhibition', 'api-exhibition-manager'),
    $wpService->__('Exhibitions', 'api-exhibition-manager'),
    $wpService,
    ['menu_icon' => 'dashicons-calendar-alt']
))->addHooks();
