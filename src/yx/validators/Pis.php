<?php
namespace YX\Validators;

/**
 * YX\Validators\Pis
 * ----------------------------------------------------------------------
 * Provides methods to validate and format the Brazilian PIS/PASEP
 * document number.
 *
 * - PIS stands for Social Integration Program (Programa de Integração Social).
 * - PASEP stands for Program for Formation of the Public Server's Estate
 *      (Programa de Formação do Patrimônio do Servidor Público);
 *
 * @package     YX\Validators
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */
class Pis
{
    // Public Methods
    // ------------------------------------------------------------------
    
    /**
     * Formats the document number.
     *
     * @param string $pis
     * @return string
     */
    public static function format( string $pis ): string
    {
        $pis = self::prepare( $pis );
        
        // If empty
        if ( false === $pis ) return false;
        
        // Format
        $pis = preg_replace(
            '/^([0-9]{3})([0-9]{4})([0-9]{3})([0-9]{1})/',
            '$1.$2.$3-$4',
            $pis
        );
        
        return $pis;
    }
    
    /**
     * Validates the document number.
     *
     * @param string $pis
     * @return bool
     */
    public static function validate( string $pis ): bool
    {
        $pis = self::prepare( $pis );
    
        // If empty
        if ( false === $pis ) return false;
        
        // Set multiplier
        $mult = 3;
        
        // Will hold the sum
        $sum = 0;
        
        // Loop and sum values for modulo
        for ( $n = 0; $n < 10; $n++ ) {
            $sum += $mult * $pis[ $n ];
            
            // Change multiplier value
            $mult -= 1;
            if ( $mult === 1 ) $mult = 9;
        }
        
        // Calculate digit
        $val = 11 - ( $sum % 11 );
        $digit = ( $val === 10 || $val === 11 ) ? 0 : $val;
        
        // Compare digit
        if ( ( int ) $pis[10] !== $digit ) return false;
        
        return true;
    }
    
    // Private Methods
    // ------------------------------------------------------------------
    
    /**
     * Prepares the document value for either formatting and/or validation.
     *
     * @param string $pis
     * @return bool|string
     */
    private static function prepare( string $pis )
    {
        if ( trim( $pis ) === null || trim( $pis ) === '' ) return false;
        
        // Sanitize value
        $pis = preg_replace( '/\D/', '', $pis );
        
        // Check length
        if ( strlen( $pis ) > 11 ) return false;
        if ( strlen( $pis ) < 11 ) $pis = sprintf( "%011s", $pis );
        
        return $pis;
    }
}
