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
 * 
 * phpcs:ignoreFile PSR1.Files.SideEffects.FoundWithSymbols
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

// Autoload from plugin
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Autoload from ABSPATH
if (file_exists(dirname(ABSPATH) . '/vendor/autoload.php')) {
    require_once dirname(ABSPATH) . '/vendor/autoload.php';
}

$wpService = new NativeWpService();

define('APIEXHIBITIONMANAGER_PATH', $wpService->pluginDirPath(__FILE__));
define('APIEXHIBITIONMANAGER_URL', $wpService->pluginsUrl('', __FILE__));
define('APIEXHIBITIONMANAGER_TEMPLATE_PATH', APIEXHIBITIONMANAGER_PATH . 'templates/');

$wpService->loadPluginTextdomain('api-exhibition-manager', false, $wpService->pluginBasename(dirname(__FILE__)) . '/languages');

require_once __DIR__ . '/Public.php';

// Acf auto import and export
$acfExportManager = new \AcfExportManager\AcfExportManager();
$acfExportManager->setTextdomain('api-exhibition-manager');
$acfExportManager->setExportFolder(APIEXHIBITIONMANAGER_PATH . 'source/php/AcfFields/');
$acfExportManager->autoExport(array(
    // Acf fields to export, eg 'file_name' => 'group_123'
    'exhibition' => 'group_68a837bcde3c7',
));
$acfExportManager->import();

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
