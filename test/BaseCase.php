<?php
namespace API\Test;

use PHPUnit\Framework\TestCase;
use Slim\App;
use YX\Api;

/**
 * API\Test\BaseCase
 * ----------------------------------------------------------------------
 * Base test case, all other tests inherit from this one.
 *
 * Responsible for booting up the main application instance for the tests.
 *
 * @package     API\Test
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */
class BaseCase extends TestCase
{
    // Protected Properties
    // ------------------------------------------------------------------
    
    /**
     * @var App
     */
    protected $app;
    
    // Public Methods
    // ------------------------------------------------------------------
    
    /**
     * Fires the main application before all tests run.
     *
     * @return void
     */
    public function setUp()
    {
        // Set app instance
        $this->app = ( new Api() )->getApp();
    }
}
