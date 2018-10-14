<?php
namespace YX\Core;

/**
 * YX\Core\Salt
 * ----------------------------------------------------------------------
 * Handles the security salt file used by the application to spice up passwords
 * and any other data that required some basic cryptography.
 *
 * There's only one public method, since just getting the security salt is
 * enough (it's automatically generated and saved, with backup copies).
 *
 * In a production environment, you shoyld NEVER change or edit this file,
 * since it compromises ALL passwords or resources that depend on it.
 *
 * @package     YX\Core
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */
class Salt
{
    /**
     * Returns the security salt hash, based on the security salt file data.
     *
     * @return string
     */
    public static function get(): string
    {
        $file_name = self::fileName();
        $path_name = self::pathName();
        
        // Test file
        self::testFile( $path_name, $file_name );
        
        // Return security salt hash
        return md5( file_get_contents( $path_name . $file_name ) );
    }
    
    // Private Methods
    // ------------------------------------------------------------------
    
    /**
     * Checks for environment variables for security salt file name.
     *
     * @return string
     */
    private static function fileName(): string
    {
        $file = getenv( 'SALT_FILE' );
        
        if ( $file !== '' && $file !== false ) return $file;
        
        return '__SALT';
    }
    
    /**
     * Resolves the path for the security salt file.
     *
     * @return string
     */
    private static function pathName(): string
    {
        $path = ( defined( 'API_DATA_DIR' ) )
            ? API_DATA_DIR
            : dirname( dirname( dirname( __DIR__ ) ) ) . '\\data\\';
        
        if ( ! is_dir( $path ) ) mkdir( $path );
        
        return $path;
    }
    
    /**
     * Checks if security salt file exists and, if not, creates it.
     *
     * Also creates two backup copies of the file.
     *
     * @param string $path
     *      Path to save the security salt file with trailing slash
     * @param string $file
     *      Security salt file name
     */
    private static function testFile( string $path, string $file )
    {
        if ( ! file_exists( $path . $file ) ) {
            self::makeFile( $path, $file );
        }
    }
    
    /**
     * Builds the security salt file and two backup copies of it.
     *
     * @param string $path
     *      Path to save the security salt file with trailing slash
     * @param string $file
     *      Security salt file name
     */
    private static function makeFile( string $path, string $file )
    {
        // All characters available for randomization
        $characters = 'abcdefghijklmnopqrstuvwxyz'
            . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
            . '0123456789"|@#$%¨&*()_+\'-=`'
            . '{}^?:><|´[~];/.,\\¹²³£¢¬§ªº°';
        
        // Holds the security salt data
        $data = [];
        
        // Open salt file
        $data[] = "- SECURITY SALT :: DO NOT EDIT -";
        
        // Write contents
        for ( $n = 0; $n < 16; $n++ ) {
            // Holds the line
            $line = '';
            
            for ( $i = 0; $i < 32; $i++ ) {
                $line .= $characters[ rand( 0, strlen( $characters ) - 1 ) ];
            }
    
            $data[] = $line;
        }
        
        // Close salt file
        $data[] = "- SECURITY SALT :: DO NOT EDIT -";
        
        // Saving
        $data = utf8_encode( implode( "\r\n", $data ) );
        
        // Saving main salt
        file_put_contents( $path . $file, $data );
        file_put_contents( $path . $file . '.001', $data );
        file_put_contents( $path . $file . '.002', $data );
    }
}
