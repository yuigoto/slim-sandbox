<?php
namespace YX\Core;

/**
 * YX\Core\ResponseTemplate
 * ----------------------------------------------------------------------
 * Serializable response template object. Use it to standardize JSON responses.
 *
 * @package     YX\Core
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */
class ResponseTemplate extends Mappable
{
    // Private Properties
    // ------------------------------------------------------------------
    
    /**
     * Response HTTP code.
     *
     * @var int
     */
    protected $code;
    
    /**
     * Response payload.
     *
     * @var array|mixed
     */
    protected $result;
    
    /**
     * Client information object. Mostly used during debug.
     *
     * @var array
     */
    protected $client;
    
    // Constructor/Destructor
    // ------------------------------------------------------------------
    
    /**
     * ResponseTemplate constructor.
     *
     * @param int $code
     *      HTTP response code
     * @param mixed $result
     *      Response payload data
     * @param bool $client
     *      Optional, set it to `true` to return the user client information
     */
    public function __construct(
        int $code,
        $result,
        bool $client = false
    ) {
        $this->code = $code;
        $this->result = $result;
        $this->client = ( $client === true )
            ? ( new ClientInformation() )->toArray() : [];
    }
}
