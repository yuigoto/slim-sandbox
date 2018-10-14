<?php
namespace YX\Validators;

/**
 * YX\Validators\Cnpj
 * ----------------------------------------------------------------------
 * Provides methods to validate and format the Brazilian Legal Entity
 * Registry number (CNPJ - Cadastro Nacional de Pessoa Jurídica).
 *
 * @package     YX\Validators
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */
class Cnpj
{
    // Public Methods
    // ------------------------------------------------------------------
    
    /**
     * Formats the document number.
     *
     * @param string $cnpj
     * @return string
     */
    public static function format( string $cnpj ): string
    {
        $cnpj = self::prepare( $cnpj );
    
        // If empty
        if ( false === $cnpj ) return false;
    
        // Format
        $cnpj = preg_replace(
            '/^([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{2})$/',
            '$1.$2.$3-$4/$5',
            $cnpj
        );
        
        return $cnpj;
    }
    
    /**
     * Validates the document number.
     *
     * @param string $cpf
     * @return bool
     */
    public static function validate( string $cnpj ): bool
    {
        $cnpj = self::prepare( $cnpj );
        
        // If empty
        if ( false === $cnpj ) return false;
    
        // Checks for repetition
        for ( $i = 0; $i < 10; $i++ ) {
            if ( preg_match( "/^{$i}{14}$/", $cnpj ) !== 0 ) return false;
        }
    
        // Validate first digit
        $sum = 0;
        $val = 5;
        for ( $n = 0; $n < 12; $n++ ) {
            $sum += ( $cnpj[ $n ] ) * $val;
            $val = ( $val - 1 === 1 ) ? 9 : $val - 1;
        }
        $val = ( $sum % 11 < 2 ) ? 0 : 11 - ( $sum % 11 );
        if ( ( int ) $cnpj[12] !== $val ) return false;
        
        // Validate second digit
        $sum = 0;
        $val = 6;
        for ( $n = 0; $n < 13; $n++ ) {
            $sum += ( $cnpj[ $n ] ) * $val;
            $val = ( $val - 1 === 1 ) ? 9 : $val - 1;
        }
        $val = ( $sum % 11 < 2 ) ? 0 : 11 - ( $sum % 11 );
        if ( ( int ) $cnpj[13] !== $val ) return false;
        
        return true;
    }
    
    // Private Methods
    // ------------------------------------------------------------------
    
    /**
     * Prepares the document value for either formatting and/or validation.
     *
     * @param string $cnpj
     * @return bool|string
     */
    private static function prepare( string $cnpj )
    {
        if ( trim( $cnpj ) === null || trim( $cnpj ) === '' ) return false;
        
        // Sanitize value
        $cnpj = preg_replace( '/\D/', '', $cnpj );
        
        // Check length
        if ( strlen( $cnpj ) > 14 ) return false;
        if ( strlen( $cnpj ) < 14 ) $cnpj = sprintf( "%014s", $cnpj );
        
        return $cnpj;
    }
}
