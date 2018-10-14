<?php
namespace YX\Core;

/**
 * YX\Core\ClientInformation
 * ----------------------------------------------------------------------
 * Defines a serializable object containing the user client information.
 *
 * @package     YX\Core
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */
class ClientInformation extends Mappable
{
    // Protected Properties
    // ------------------------------------------------------------------
    
    /**
     * HTTP user agent.
     *
     * @var string|null
     */
    protected $http_user_agent = null;
    
    /**
     * HTTP connection.
     *
     * @var string|null
     */
    protected $http_connection = null;
    
    /**
     * HTTP host.
     *
     * @var string|null
     */
    protected $http_host = null;
    
    /**
     * HTTP referer.
     *
     * @var string|null
     */
    protected $http_referer = null;
    
    /**
     * Remote user IP address.
     *
     * @var string|null
     */
    protected $remote_addr = null;
    
    /**
     * Remote user host name.
     *
     * @var string|null
     */
    protected $remote_host = null;
    
    /**
     * HTTP request method.
     *
     * @var string|null
     */
    protected $request_method = null;
    
    /**
     * Server request URI.
     *
     * @var string|null
     */
    protected $request_uri = null;
    
    /**
     * Date for this information.
     *
     * @var string|null|bool
     */
    protected $date = null;
    
    // Constructor/Destructor
    // ------------------------------------------------------------------
    
    /**
     * ClientInformation constructor.
     *
     * @param bool $with_date
     *      Optional, if the date should be included in the response
     */
    public function __construct( bool $with_date = false )
    {
        $attr_list = [
            'HTTP_USER_AGENT'   => 'http_user_agent',
            'HTTP_CONNECTION'   => 'http_connection',
            'HTTP_HOST'         => 'http_host',
            'HTTP_REFERER'      => 'http_referer',
            'REMOTE_ADDR'       => 'remote_addr',
            'REMOTE_HOST'       => 'remote_host',
            'REQUEST_METHOD'    => 'request_method',
            'REQUEST_URI'       => 'request_uri',
        ];
        
        // Loop through the attributes list, set the properties
        foreach ( $attr_list as $server => $attr ) {
            if ( isset( $_SERVER[ $server ] ) ) {
                $this->$attr = $_SERVER[ $server ];
            }
        }
        
        // Should we place the request timestamp?
        if ( $with_date === true ) $this->date = date( 'c' );
    }
}
