<?php
namespace API\V1\Healthcheck;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Container;
use Slim\Http\Response;
use YX\Api;
use YX\Core\ResponseTemplate;
use YX\Interfaces\RouteInterface;

class HealthcheckController implements RouteInterface
{
    /**
     * @var App
     */
    protected $app;
    
    /**
     * @var Container
     */
    protected $container;
    
    /**
     * HealthcheckController constructor.
     *
     * @param App $app
     */
    public function __construct( App &$app )
    {
        // Set references
        $this->app = $app;
        $this->container = $app->getContainer();
        
        // Set routes
        $app->any( '/healthcheck', [ $this, 'index' ] );
    }
    
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed|Response
     */
    public function index(
        Request $request,
        Response $response,
        array $args
    ) {
        // Set address
        $address = ( isset( $_SERVER['SERVER_ADDR'] ) )
                ? $_SERVER['SERVER_ADDR'] : '::1';
        
        // Response body
        $response_body = new ResponseTemplate(
            200,
            [
                'info' => [
                    'name' => Api::API_NAME . ' @ ' . $address,
                    'author' => Api::API_AUTHOR,
                    'version' => Api::API_VERSION,
                    'copyright' => Api::API_RIGHTS
                ],
                'message' => 'Welcome to the Sandbox API.'
            ],
            API_DEV_MODE
        );
        
        // Set response
        return $response
            ->withHeader( 'Content-Type', 'application/json' )
            ->withJson( $response_body, 200 );
    }
}
