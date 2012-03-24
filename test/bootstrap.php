<?php
/**
*
* @package testing
* @copyright (c) 2008 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

// Define some initial constants
define('STK_ROOT', __DIR__ . '/../stk/');
define('PHPBB_FILES', STK_ROOT . 'phpBB/');
define('IN_TEST', true);

// Some to make phpBB files accessable in the first place
$phpbb_root_path = STK_ROOT . 'vendor/phpBB/phpBB/';
define('PHPBB_ROOT_PATH', $phpbb_root_path);
$phpEx = 'php';
define('IN_PHPBB', true);

// Map the db constants to the phpBB vars
if (!defined('dbms'))
{
	define('dbms', 'sqlite');
	define('dbhost', __DIR__ . '/unit_tests.sqlite2'); // filename
	define('dbuser', '');
	define('dbpasswd', '');
	define('dbname', '');
	define('dbport', '');
	define('table_prefix', '');
}
$dbms = dbms;

$phpbb_tests_path = $phpbb_root_path . '../tests/';
$phpEx = 'php';

$table_prefix = (!defined('table_prefix')) ? 'phpbb_' : table_prefix;

require_once $phpbb_tests_path . 'test_framework/phpbb_test_case_helpers.php';
require_once $phpbb_tests_path . 'test_framework/phpbb_test_case.php';
require_once $phpbb_tests_path . 'test_framework/phpbb_database_test_case.php';
require_once $phpbb_tests_path . 'test_framework/phpbb_database_test_connection_manager.php';

require_once __DIR__ . '/test_framework/stk_database_test_case.php';
require_once __DIR__ . '/test_framework/stk_database_test_connection_manager.php';
require_once __DIR__ . '/test_framework/stk_test_case.php';

// Fetch some phpBB files
require_once $phpbb_root_path . 'includes/class_loader.php';
require_once $phpbb_root_path . 'includes/constants.php';
require_once $phpbb_root_path . 'includes/functions.php';
require PHPBB_FILES . 'includes/utf/utf_tools.php';

// Fetch some STK Files
require_once STK_ROOT . 'core/class_loader.php';

// Initialise class loaders
$stk_class_loader = new stk_core_class_loader('stk_', STK_ROOT);
$stk_class_loader->register();
$phpbb_class_loader = new stk_core_class_loader('phpbb_', $phpbb_root_path . 'includes/');
$phpbb_class_loader->register();

// Setup mock cache
require_once $phpbb_tests_path . 'mock/cache.php';
$cache = new phpbb_mock_cache();

// Setup stk cache
$stk_cache_factory = new stk_wrapper_cache_factory('null');
$stk_cache = $stk_cache_factory->get_service();

// Initialise version check controller for global use
$vc = stk_core_version_controller::getInstance('https://raw.github.com/gist/2039820/stk_version_check_test.json', $stk_cache);

// Setup lang mock
require_once $phpbb_tests_path . 'mock/lang.php';
$lang = new phpbb_mock_lang();