<?php
namespace YX\Core;

/**
 * YX\Core\ResponseError
 * ----------------------------------------------------------------------
 * Response error template, use this to standardize error response data.
 *
 * @package     YX\Core
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */
class ResponseError extends Mappable
{
    // Protected Properties
    // ------------------------------------------------------------------
    
    /**
     * Error code, usually the HTTP request code.
     *
     * @var int
     */
    protected $code;
    
    /**
     * The error title.
     *
     * @var string
     */
    protected $title;
    
    /**
     * Short description of the error.
     *
     * @var string
     */
    protected $description;
    
    /**
     * Response error data, stack trace or helpful stuff for debugging.
     *
     * @var mixed|null
     */
    protected $data = null;
    
    // Constructor/Destructor
    // ------------------------------------------------------------------
    
    /**
     * ResponseError constructor.
     *
     * @param int $code
     *      Error code, usually the same HTTP request error as the response
     * @param string $title
     *      Error title
     * @param string $description
     *      Short description of the error, so people can rapidly check it
     * @param null $data
     *      Optional, detailed information about the error, stack trace or
     *      other information
     */
    public function __construct(
        int $code,
        string $title,
        string $description,
        $data = null
    ) {
        $this->code = $code;
        $this->data = $data;
        $this->title = $title;
        $this->description = $description;
    }
}
