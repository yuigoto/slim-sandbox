<?php
namespace YX\Core;

/**
 * YX\Core\Mappable
 * ----------------------------------------------------------------------
 * Implements `\JsonSerializable`, which allows for some lazy object-to-array
 * conversion and, to a certain extent, easier JSON serialization of objects.
 *
 * @package     YX\Core
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */
class Mappable implements \JsonSerializable
{
    // Public Methods
    // ------------------------------------------------------------------
    
    /**
     * Provides some lazy conversion of this object's instance into an
     * associative array, with only the public and protected properties.
     *
     * For cleanliness' sake, this method filters every Doctrine-generated
     * property from the output.
     *
     * @return array
     */
    public function toArray(): array
    {
        $list = get_object_vars( $this );
        
        // Filters Doctrine-generated properties out
        foreach ( $list as $k => $v ) {
            if ( preg_match( '/^\_\_/', $k ) ) unset( $list[ $k ] );
        }
        
        return $list;
    }
    
    /**
     * Specifies which of the object's parameters should be serialized to
     * JSON when using `json_encode` in an instance of this object or any
     * that extends this class.
     *
     * The default behavior is to return whatever `toArray()` returns, but
     * can (and should) be overridden when deemed necessary.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
