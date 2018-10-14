<?php
namespace YX;

use API\Entity\Users\UserToken;
use API\RouteHandler;
use API\V1\Routes;
use Bnf\Slim3Psr15\CallableResolver;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Dotenv\Dotenv;
use Middlewares\TrailingSlash;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Container;
use Slim\Http\Response;
use Slim\Middleware\JwtAuthentication;
use Tuupola\Middleware\Cors;
use YX\Core\ClientInformation;
use YX\Core\ResponseError;
use YX\Core\ResponseTemplate;
use YX\Core\Salt;
use YX\Core\Utilities;

/**
 * YX\Api
 * ----------------------------------------------------------------------
 * Main application handler, starts a runnable `Slim\App` object.
 *
 * It's possible to do this without the class stuff, but this way we can use
 * and reuse the instance for tests with PHPUnit.
 *
 * @package     YX
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */
class Api
{
    // Constants
    // ------------------------------------------------------------------
    
    /**
     * Application name.
     *
     * @var string
     */
    const API_NAME = 'API Sandbox';
    
    /**
     * Application author.
     *
     * @var string
     */
    const API_AUTHOR = 'Fabio Y. Goto <lab@yuiti.com.br>';
    
    /**
     * API version.
     *
     * @var string
     */
    const API_VERSION = '0.0.1';
    
    /**
     * API rights.
     *
     * @var string
     */
    const API_RIGHTS = '®2018 Fabio Y. Goto';
    
    // Protected Properties
    // ------------------------------------------------------------------
    
    /**
     * `Slim\App` handler.
     *
     * @var App
     */
    protected $app;
    
    /**
     * `Slim\Container` handler.
     *
     * Use it to inject dependencies into the application handler.
     *
     * @var Container
     */
    protected $container;
    
    // Constructor/Destructor
    // ------------------------------------------------------------------
    
    /**
     * Api constructor.
     */
    public function __construct()
    {
        // Load `.env` file from the root folder
        ( new Dotenv( API_ROOT ) )->load();
        
        // Set `Slim\Container` configuration
        $config = [
            'settings' => [
                'displayErrorDetails' => ( getenv( 'FLAG_ERROR_VERBOSE' ) == 1 ),
                'debug' => ( getenv( 'FLAG_DEV_MODE' ) == 1 )
            ]
        ];
        
        // Set container and inject dependencies
        try {
            $this->container = new Container( $config );
            $this->dependencies();
        } catch ( \Exception $e ) {
            // If no container was initialized, kill application
            $this->errorHandleOnStart( $e, 'Dependency container error.' );
            die;
        }
        
        // Start Slim application
        $this->app = new App( $this->container );
        
        // Fire `RouteHandler`
        new RouteHandler( $this->app );
        
        // Middlewares
        // --------------------------------------------------------------
        
        // Add trailing slash middleware
        $this->app->add( new TrailingSlash( false ) );
        
        // Add authentication (implements CORS and JwtAuthentication)
        $this->authentication();
    }
    
    // Public Methods
    // ------------------------------------------------------------------
    
    /**
     * Retrieves the `Slim\App` instance.
     *
     * @return App
     */
    public function getApp(): App
    {
        return $this->app;
    }
    
    /**
     * Retrieves the `Slim\Container` instance.
     *
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }
    
    // Protected Methods
    // ------------------------------------------------------------------
    
    /**
     * Implements `CORS` and `JwtAuthentication` middlewares for the API,
     * allowing the use of JSON Web Tokens for request validation.
     *
     * @return void
     */
    protected function authentication()
    {
        // Set CORS Middleware
        $this->app->add(
            new Cors([
                'origin' => ['*'],
                'methods' => [
                    'GET',
                    'POST',
                    'PUT',
                    'PATCH',
                    'OPTIONS',
                    'DELETE'
                ],
                'headers.allow' => [
                    'Authorization',
                    'Content-Type',
                    'X-Token'
                ],
                'headers.expose' => [],
                'credentials' => false,
                'cache' => 0,
                'error' => null
            ])
        );
        
        // Implement JWT Authentication
        $this->app->add( $this->buildAuthenticationMiddleware() );
    }
    
    /**
     * Injects dependencies into the application container.
     *
     * @return void
     * @throws \Exception
     */
    protected function dependencies()
    {
        // Get container reference
        /** @var Container $container */
        $container = &$this->container;
    
        // EntityManager confic
        $entity_config = Setup::createAnnotationMetadataConfiguration(
            [
                API_SOURCE . '\\api\\entity\\'
            ],
            API_DEV_MODE
        );
    
        // EntityManager database config
        try {
            // Fetch driver from env
            $database_driver = getenv( 'DB_DRIVER' );
        
            // Halt, if no driver is declared
            if ( $database_driver === false ) {
                throw new \Exception( 'Database driver failure.', 500 );
            }
        
            switch ( $database_driver ) {
                case 'pdo_mysql':
                case 'pdo_pgsql':
                    // Postgre or MySQL
                    $connection_args = [
                        'driver' => getenv( 'DB_DRIVER' ),
                        'host' => getenv( 'DB_HOSTNAME' ),
                        'dbname' => getenv( 'DB_DATABASE' ),
                        'user' => getenv( 'DB_USERNAME' ),
                        'password' => getenv( 'DB_PASSWORD' )
                    ];
                    break;
                case 'pdo_sqlite':
                    // SQLite uses `path` instead of `host`
                    $connection_args = [
                        'driver' => getenv( 'DB_DRIVER' ),
                        'path' => API_DATA_DIR . "\\"
                            . getenv( 'DB_HOSTNAME' )
                    ];
                    break;
                default:
                    // Not a valid driver
                    throw new \Exception( 'Invalid database driver.', 500 );
                    break;
            }
        } catch (\Exception $e) {
            $this->errorHandleOnStart( $e, 'Database intialization error.' );
            die;
        }
    
        /* EntityManager
         * ----------------------------------------------------------- */
        try {
            if ( ! empty( $connection_args ) ) {
                $entity_mgr = EntityManager::create(
                    $connection_args,
                    $entity_config
                );
                $container['em'] = $entity_mgr;
            } else {
                throw new \Exception(
                    'Database arguments not declared.',
                    500
                );
            }
        } catch (\Exception $e) {
            $this->errorHandleOnStart(
                $e,
                'Database arguments for `EntityManager` not declared.'
            );
            die;
        }
    
        /* Implement custom callable resolver (for PSR-15 middlewares)
         * ----------------------------------------------------------- */
        $container['callableResolver'] = function( $container )
        {
            return new CallableResolver( $container );
        };
    
        /* Entity sanitizer (cleans some accents)
         * ----------------------------------------------------------- */
        $container['sanitizer'] = function () {
            return function ( $string ) {
                $string = ( ! mb_check_encoding( $string, 'UTF-8' ) )
                    ? utf8_encode( $string )
                    : $string;
            
                // Convert special chars into HTML entities
                $string = htmlentities( $string, ENT_COMPAT, 'UTF-8' );
            
                // Regex flag for entities
                $flag = "uml|acute|grave|circ|tilde|cedil|ring|slash|u";
                
                return preg_replace(
                    "/&([A-Za-z])({$flag});/",
                    "$1",
                    $string
                );
            };
        };
    
        /* Logger
         * ----------------------------------------------------------- */
        $container['logger'] = function () {
            $logger = new Logger( 'yx-logger' );
        
            $logfile = API_ROOT . "\\logs\\yx-logs.log";
        
            $stream = new StreamHandler( $logfile, Logger::DEBUG );
        
            $fingers_crossed = new FingersCrossedHandler(
                $stream,
                Logger::INFO
            );
        
            $logger->pushHandler( $fingers_crossed );
        
            return $logger;
        };
    
        /* Custom exception/error handler
         * ----------------------------------------------------------- */
        $container['errorHandler'] = function ( $container ) {
            return function (
                Request $request,
                Response $response,
                \Exception $exception
            ) use ( $container ) {
                // Set status code
                $error_code = ( $exception->getCode() )
                    ? $exception->getCode()
                    : 500;
                if ( $error_code < 100 ) $error_code = 500;
            
                // Set error body
                $error_body = new ResponseError(
                    $error_code,
                    Utilities::httpStatusName( $error_code ),
                    $exception->getMessage(),
                    json_encode( $exception->getTrace() )
                );
            
                // Set response
                $response_body = new ResponseTemplate(
                    $error_code,
                    $error_body,
                    API_DEV_MODE
                );
            
                // Get user address
                $remote_addr = ( isset( $_SERVER['REMOTE_ADDR'] ) )
                    ? $_SERVER['REMOTE_ADDR']
                    : '::1';
            
                // Log
                /** @var $logger Logger */
                $logger = $container['logger'];
                $logger->info(
                    'User @ ' . $remote_addr . ' reached a ' . $error_code,
                    ( new ClientInformation() )->toArray()
                );
                $logger->info(
                    Utilities::httpStatusName( $error_code )
                    . ': ' . $exception->getMessage()
                );
                $logger->info(
                    'Request body: ' . $request->getBody()
                );
            
                return $response
                    ->withHeader( 'Content-Type', 'application/json' )
                    ->withStatus( $error_code )
                    ->withJson( $response_body, $error_code );
            };
        };
    
        /* Custom 404 error handler
         * ----------------------------------------------------------- */
        $container['notFoundHandler'] = function ( $container ) {
            return function (
                Request $request,
                Response $response
            ) use ( $container ) {
                // Set error body
                $error_body = new ResponseError(
                    404,
                    'Not Found',
                    'The requested resource wasn\'t found or is inaccessible.'
                );
            
                // Set response
                $response_body = new ResponseTemplate(
                    404,
                    $error_body,
                    API_DEV_MODE
                );
            
                // Get user address
                $remote_addr = ( isset( $_SERVER['REMOTE_ADDR'] ) )
                    ? $_SERVER['REMOTE_ADDR']
                    : '::1';
            
                // Log
                /** @var $logger Logger */
                $logger = $container['logger'];
                $logger->info(
                    'User @ ' . $remote_addr . ' reached a 404',
                    ( new ClientInformation() )->toArray()
                );
                $logger->info(
                    'Request body: ' . $request->getBody()
                );
            
                return $response
                    ->withHeader( 'Content-Type', 'application/json' )
                    ->withStatus( 404 )
                    ->withJson( $response_body, 404 );
            };
        };
    
        /* Custom 405 error handler
         * ----------------------------------------------------------- */
        $container['notAllowedHandler'] = function ( $container ) {
            return function (
                Request $request,
                Response $response,
                array $methods
            ) use ( $container ) {
                // Implode allowed
                $methods = implode( ', ', $methods );
            
                // Set error body
                $error_body = new ResponseError(
                    405,
                    'Not Allowed',
                    'Method not allowed. Must be one of: ' . $methods . '.'
                );
            
                // Set response
                $response_body = new ResponseTemplate(
                    405,
                    $error_body,
                    API_DEV_MODE
                );
            
                // Get user address
                $remote_addr = ( isset( $_SERVER['REMOTE_ADDR'] ) )
                    ? $_SERVER['REMOTE_ADDR']
                    : '::1';
            
                // Log
                /** @var $logger Logger */
                $logger = $container['logger'];
                $logger->info(
                    'User @ ' . $remote_addr . ' reached a 405',
                    ( new ClientInformation() )->toArray()
                );
                $logger->info(
                    'Request body: ' . $request->getBody()
                );
            
                return $response
                    ->withHeader( 'Content-Type', 'application/json' )
                    ->withHeader( 'Allow', $methods )
                    ->withHeader( 'Access-Control-Allow-Methods', $methods )
                    ->withStatus( 405 )
                    ->withJson( $response_body, 405 );
            };
        };
    }
    
    // Protected Methods
    // ------------------------------------------------------------------
    
    /**
     * Creates the JWT Authentication instance necessary for the project
     * to work.
     *
     * @return JwtAuthentication
     */
    public function buildAuthenticationMiddleware(): JwtAuthentication
    {
        // Get container reference
        /** @var Container $container */
        $container = &$this->container;
        
        // Load application routes (V1)
        $routes = new Routes();
    
        /**
         * Success callback.
         *
         * @param Request $request
         * @param Response $response
         * @param $args
         * @return bool
         */
        $success_callback = function (
            Request $request,
            Response $response,
            $args
        ) use ( $container ) {
            /** @var EntityManager $em */
            $em = $container->get( 'em' );
            
            // Get token payload
            $token = ( $request->getHeader( 'Authorization' )[0] );
            
            // Find it in the database
            /** @var UserToken $token */
            $token = $em
                ->getRepository( 'UserToken' )
                ->findOneBy(
                    [
                        'payload' => $token
                    ]
                );
            
            // Is token invalid?
            if (
                null === $token
                || false === $token
                || false === $token->getIsValid()
                || time() > $token->getExpireDate()
            ) {
                return false;
            }
            
            // IMPORTANT: Further validation for permissions/capabilities
            // might be needed on the endpoints! So
            
            /**
             * IMPORTANT:
             * Further validation for permissions/capabilities might be
             * needed on the endpoints, so use the member below from the
             * container to fetch the token data.
             */
            $container['jwt'] = $args['decoded'];
            
            return true;
        };
    
        /**
         * Error callback.
         *
         * @param Request $request
         * @param Response $response
         * @param $args
         * @return Response
         */
        $error_callback = function(
            Request $request,
            Response $response,
            $args
        ) use ( $container ) {
            // Generate error object
            $error = new ResponseError(
                401,
                'Invalid Access Token',
                'The token is either invalid or expired.',
                $args
            );
            
            // Response object
            $response_body = new ResponseTemplate(
                401,
                $error,
                true
            );
            
            // Get the user address
            $address = ( isset( $_SERVER['REMOTE_ADDR'] ) )
                ? $_SERVER['REMOTE_ADDR']
                : '::1';
            
            /** @var Logger $logger */
            $logger = $container['logger'];
            $logger->info(
                'User @ ' . $address . ' reached a 401',
                ( new ClientInformation() )->toArray()
            );
            $logger->info(
                Utilities::httpStatusName( 401 )
                    . ': Invalid/Expired Token'
            );
            $logger->info(
                'Request body: ' . $request->getBody()
            );
            
            // Output response
            return $response
                ->withHeader( 'Content-Type', 'application/json' )
                ->withJson( $response_body, 401 );
        };
        
        // Return `JwtAuthentication` instance
        return new JwtAuthentication( [
            // So we can use it with HTTP
            'secure' => API_SECURE_MODE,
            // Security salt
            'secret' => Salt::get(),
            // API path to check for authentication
            'path' => $routes->getPath(),
            // Set API paths for passthrough
            'passthrough' => $routes->getPassthrough(),
            // Regexp for validation
            'regexp' => '/(.*)/',
            // JWT token header
            'header' => 'X-Token',
            // Realm
            'realm' => 'Protected',
            // Passthrough OPTION (not used)
            #'rules' => ['OPTIONS'],
            // Success callback
            'callback' => $success_callback,
            // Error callback
            'error' => $error_callback
        ] );
    }
    
    /**
     * Outputs an error response.
     *
     * It's used before `Slim\App` is initialized, during bootstrapping.
     *
     * @param \Exception $e
     *      Exception handler
     * @param string $title
     *      Error title
     * @return void
     */
    private function errorHandleOnStart( \Exception $e, string $title )
    {
        // Error response
        $error = new ResponseError(
            $e->getCode(),
            $title,
            $e->getMessage(),
            $e->getTrace()
        );
        
        // Set headers and print JSON
        header( 'Content-Type', 'application/json' );
        echo \json_encode(
            new ResponseTemplate(
                $e->getCode(),
                $error,
                API_DEV_MODE
            )
        );
    }
}
