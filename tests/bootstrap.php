<?php
declare(strict_types=1);

/**
 * Test suite bootstrap for cakephp-data-validation-testing.
 *
 * This function is used to find the location of CakePHP whether CakePHP
 * has been installed as a dependency of the plugin, or the plugin is itself
 * installed as a dependency of an application.
 */
$findRoot = function ($root) {
    do {
        $lastRoot = $root;
        $root = dirname($root);
        if (is_dir($root . '/vendor/cakephp/cakephp')) {
            return $root;
        }
    } while ($root !== $lastRoot);

    throw new Exception('Cannot find the root of the application, unable to run tests');
};
$root = $findRoot(__FILE__);
unset($findRoot);

chdir($root);

require_once $root . '/vendor/autoload.php';

/**
 * Define fallback values for required constants and configuration.
 * To customize constants and configuration remove this require
 * and define the data required by your plugin here.
 */
require_once $root . '/vendor/cakephp/cakephp/tests/bootstrap.php';

/**
 * Configure an in-memory SQLite connection so that Table instances
 * used in our tests have a default connection available. The tests
 * do not actually read/write data — tables override their schema
 * in-memory to avoid describing from the database.
 */
use Cake\Datasource\ConnectionManager;

if (!in_array('test', ConnectionManager::configured(), true)) {
    ConnectionManager::setConfig('test', [
        'className' => 'Cake\Database\Connection',
        'driver' => 'Cake\Database\Driver\Sqlite',
        'database' => ':memory:',
        'quoteIdentifiers' => true,
        'cacheMetadata' => false,
    ]);
}

if (!in_array('default', ConnectionManager::configured(), true)) {
    ConnectionManager::alias('test', 'default');
}

if (file_exists($root . '/config/bootstrap.php')) {
    require $root . '/config/bootstrap.php';

    return;
}
