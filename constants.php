<?php
namespace YX\Config;

/**
 * YX\Constants
 * ----------------------------------------------------------------------
 * Application constants.
 *
 * @package     YX\Config
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */

// PATHS
// ----------------------------------------------------------------------

/**
 * API root folder.
 *
 * @var string
 */
define( 'API_ROOT', __DIR__ . '\\' );

/**
 * API source folder.
 *
 * @var string
 */
define( 'API_SOURCE', API_ROOT . 'src\\' );

/**
 * Stores static and flat-file data like XML/JSON files.
 *
 * When using a SQLite database driver with Doctrine, this folder's used
 * to store the database too.
 *
 * DO NOT USE THIS FOLDER FOR UPLOADS, KEEP THIS FOLDER SAFE FROM PUBLIC!
 *
 * @var string
 */
define( 'API_DATA_DIR', API_ROOT . 'data\\' );

/**
 * Public folder, usually where the `index.php` and `.htaccess` files are
 * placed.
 *
 * In some servers, this folder's also called `www` or `public_html`.
 *
 * @var string
 */
define( 'API_PUBLIC_DIR', API_ROOT . 'public\\' );

/**
 * File upload directory, publicly available files sent by the admin or user
 * accounts should be stored in this folder.
 *
 * @var string
 */
define( 'API_UPLOAD_DIR', API_PUBLIC_DIR . 'upload\\' );

// FLAGS
// ----------------------------------------------------------------------

/**
 * Turn debug logs on/off.
 *
 * @var bool
 */
define( 'API_DEV_LOGS', true );

/**
 * Turns development mode on/off.
 *
 * Use it to set some specific outputs for development mode.
 *
 * @var bool
 */
define( 'API_DEV_MODE', true );

/**
 * Turns HTTPS mode on/off, specially for authentication.
 *
 * @var bool
 */
define( 'API_SECURE_MODE', false );
