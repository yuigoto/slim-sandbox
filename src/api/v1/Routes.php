<?php
namespace API\V1;

/**
 * Api\V1\Routes
 * ----------------------------------------------------------------------
 * Loads all the API routes to be used by the `JwtAuthentication` middleware
 * directly from the data inside `routes.json` inside the `Api\V1` root
 * source folder.
 *
 * @package     Api\V1
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */
class Routes
{
    /**
     * List containing all the API paths where validation is mandatory.
     *
     * @var array
     */
    protected $path;
    
    /**
     * List containing all API paths where public access, without
     * validation is allowed.
     *
     * No JWT validation occurs on these endpoints, so be careful when
     * setting these paths.
     *
     * @var array
     */
    protected $passthrough;
    
    // Constructor
    // ------------------------------------------------------------------
    
    /**
     * Routes constructor.
     */
    public function __construct()
    {
        // Routes JSON file
        $file = API_SOURCE . 'api\\v1\\routes.json';
        
        // Get contents and decode it as array
        $data = json_decode(
            file_get_contents( $file ),
            true
        );
        
        // Set values
        $this->path = $data['path'];
        $this->passthrough = $data['passthrough'];
    }
    
    // Public Methods
    // ------------------------------------------------------------------
    
    /**
     * Returns the array containing the list containing all endpoints that
     * require validation for the API.
     *
     * @return array
     */
    public function getPath(): array
    {
        return $this->path;
    }
    
    /**
     * Returns the array containing the list containing all endpoints that
     * should have pass-through for the API.
     *
     * @return array
     */
    public function getPassthrough(): array
    {
        return $this->passthrough;
    }
}
