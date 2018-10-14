<?php
namespace YX\Validators;

/**
 * YX\Validators\Cpf
 * ----------------------------------------------------------------------
 * Provides methods to validate and format the Brazilian Natural Person
 * Registry number (CPF - Cadastro de Pessoa Física).
 *
 * @package     YX\Validators
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */
class Cpf
{
    // Public Methods
    // ------------------------------------------------------------------
    
    /**
     * Formats the document number.
     *
     * @param string $cpf
     * @return string
     */
    public static function format( string $cpf ): string
    {
        $cpf = self::prepare( $cpf );
        
        // If empty
        if ( false === $cpf ) return false;
        
        // Format
        $cpf = preg_replace(
            '/^([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2})/',
            '$1.$2.$3-$4',
            $cpf
        );
        
        return $cpf;
    }
    
    /**
     * Validates the document number.
     *
     * @param string $cpf
     * @return bool
     */
    public static function validate( string $cpf ): bool
    {
        $cpf = self::prepare( $cpf );
    
        // If empty
        if ( false === $cpf ) return false;
        
        // Checks for repetition
        for ( $i = 0; $i < 10; $i++ ) {
            if ( preg_match( "/^{$i}{11}$/", $cpf ) !== 0 ) return false;
        }
        
        // Validate first digit
        $sum = 0;
        for ( $n = 0; $n < 9; $n++ ) $sum += $cpf[ $n ] * ( 10 - $n );
        $val = 11 - ( $sum % 11 );
        if ( $val === 10 || $val === 11 ) $val = 0;
        if ( ( int ) $cpf[9] !== $val ) return false;
        
        // Validate second digit
        $sum = 0;
        for ( $n = 0; $n < 10; $n++ ) $sum += $cpf[ $n ] * ( 11 - $n );
        $val = 11 - ( $sum % 11 );
        if ( $val === 10 || $val === 11 ) $val = 0;
        if ( ( int ) $cpf[10] !== $val ) return false;
        
        return true;
    }
    
    // Private Methods
    // ------------------------------------------------------------------
    
    /**
     * Prepares the document value for either formatting and/or validation.
     *
     * @param string $cpf
     * @return bool|string
     */
    private static function prepare( string $cpf )
    {
        if ( trim( $cpf ) === null || trim( $cpf ) === '' ) return false;
    
        // Sanitize value
        $cpf = preg_replace( '/\D/', '', $cpf );
    
        // Check length
        if ( strlen( $cpf ) > 11 ) return false;
        if ( strlen( $cpf ) < 11 ) $cpf = sprintf( "%011s", $cpf );
        
        return $cpf;
    }
}
