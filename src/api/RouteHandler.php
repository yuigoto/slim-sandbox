<?php
namespace API;

use API\V1\Healthcheck\HealthcheckController;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Http\Response;
use YX\Api;
use YX\Core\ResponseTemplate;
use YX\Interfaces\RouteInterface;

/**
 * Api\RouteHandler
 * ----------------------------------------------------------------------
 * Main application routes handler.
 *
 * Manages all application routes and its controllers.
 *
 * Also handles route versioning.
 *
 * @package     Api
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */
class RouteHandler implements RouteInterface
{
    /**
     * RouteHandler constructor.
     *
     * @param App $app
     */
    public function __construct( App &$app )
    {
        // Make a reference to this instance
        $route_handler = $this;
        
        // Root endpoint
        $app->any( '/', [ $this, 'index' ] );
        
        // Set application route groups
        $app->group( '/api', function () use ( $app, $route_handler ) {
            // API root
            $app->map(
                [ 'GET', 'POST', 'OPTIONS', 'DELETE', 'PUT' ],
                '',
                [ $route_handler, 'root' ]
            );
    
            // V1 Endpoints
            $app->group( '/v1', function () use ( $app, $route_handler ) {
                $app->map(
                    [ 'GET', 'POST', 'OPTIONS', 'DELETE', 'PUT' ],
                    '',
                    [ $route_handler, '__test' ]
                );
                
                // Add route controllers
                new HealthcheckController( $app );
            } );
    
            // V2 Endpoints (test only)
            $app->group( '/v2', function () use ( $app, $route_handler ) {
                $app->map(
                    [ 'GET', 'POST', 'OPTIONS', 'DELETE', 'PUT' ],
                    '',
                    [ $route_handler, '__test' ]
                );
            } );
        } );
    }
    
    // Route methods
    // ------------------------------------------------------------------
    
    /**
     * Base endpoint for this route.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function index(
        Request $request,
        Response $response,
        array $args
    ) {
        // Response
        $body = new ResponseTemplate(
            200,
            [
                'message' => 'Hello, dumbass! Why are you using this API, if it\'s still not ready to use?'
            ],
            API_DEV_MODE
        );
        
        return $response
            ->withHeader( 'Content-Type', 'application/json' )
            ->withJson( $body, 200 );
    }
    
    /**
     * Route endpoint for `/api`.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function root(
        Request $request,
        Response $response,
        array $args
    ) {
        // Set address
        $addr = ( isset( $_SERVER['SERVER_ADDR'] ) )
            ? $_SERVER['SERVER_ADDR']
            : '::1';
    
        // Build response object
        $res = new ResponseTemplate(
            200,
            [
                'name' => Api::API_NAME . ' @ ' . $addr,
                'version' => Api::API_VERSION,
                'request' => $request,
                'args' => $args
            ],
            API_DEV_MODE
        );
    
        return $response
            ->withHeader( 'Content-Type', 'application/json' )
            ->withJson( $res, 200 );
    }
    
    /**
     * Test for a not-allowed endpoint.
     *
     * @return void
     * @throws \Exception
     */
    public function __test() {
        throw new \Exception(
            'This endpoint is for tests only! Go away! GTFO!',
            400
        );
    }
}
