<?php

use \Phalcon\Mvc\Micro\Collection as MicroCollection;

/**
 * Always remember to add collections to `app/collections/collections.php`
 */

// This trick will automagically handle versioning.
$version = basename(dirname(__FILE__));
$controller_path = 'App\Controllers\\' . strtoupper($version) . '\\';

/**
 * Setup collection
 */
$collection = new MicroCollection();
// Set the correct controller here
$collection->setHandler($controller_path . 'UsersController', true);
// Set the endpoint name
$collection->setPrefix('/'. $version . '/users');

/**
 * Define routes
 */
$collection->get('/', 'index');

return $collection;