<?php
namespace YX\Interfaces;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Http\Response;

/**
 * YX\Interfaces\RouteInterface
 * ----------------------------------------------------------------------
 * Base route controller interface, implements the minimum required for
 * the route to work.
 *
 * @package     YX\Interfaces
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */
interface RouteInterface
{
    /**
     * RouteEndpointInterface constructor.
     *
     * @param App $app
     */
    public function __construct( App &$app );
    
    /**
     * Base method, serves as the base path for an API route/group.
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
    );
}
