<?php
/**
 * Index
 * ----------------------------------------------------------------------
 * Loads everything, checks if some folders exist then fires the API.
 *
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */

// Imports
use YX\Api;

// Composer autoload
include '../vendor/autoload.php';

// Initialize Data Directory
if ( ! is_dir( API_DATA_DIR ) ) mkdir( API_DATA_DIR );

// Initialize Upload Directory
if ( ! is_dir( API_UPLOAD_DIR ) ) mkdir( API_UPLOAD_DIR );

// Fire API
( new Api() )->getApp()->run();

// THE END
