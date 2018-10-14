<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Slim\Container;
use YX\Api;

/**
 * CLI Config
 * ----------------------------------------------------------------------
 * Used by Doctrine's Entity Builder to define/update the model schemas.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */

// Require composer autoloader
require_once 'vendor/autoload.php';

/** @var Container $container */
$container = ( new Api() )->getContainer();

/** @var EntityManager $em */
$em = $container->get( 'em' );

// Return helper set
return ConsoleRunner::createHelperSet( $em );
