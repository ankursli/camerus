<?php

// BEGIN iThemes Security - Do not modify or remove this line
// iThemes Security Config Details: 2
define( 'FORCE_SSL_ADMIN', true ); // Redirect All HTTP Page Requests to HTTPS - Security > Settings > Enforce SSL
define( 'DISALLOW_FILE_EDIT', true ); // Disable File Editor - Security > Settings > WordPress Tweaks > File Editor
// END iThemes Security - Do not modify or remove this line

define( 'ITSEC_ENCRYPTION_KEY', 'aTxxUXw6fjZuN2FjISgxWWQsJnU9aHBpaVJLKGp7bUhNKF9GNUdXSl5WQXxtPilCYXdLY1g4bk99IS49VnoySQ==' );

//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL cookie settings

define( 'WP_CACHE', true ); // Added by WP Rocket



/** WP 2FA plugin data encryption key. For more information please visit melapress.com */
define( 'WP2FA_ENCRYPT_KEY', 'Dm58deImRHmFcodcxLOR2A==' );


/*----------------------------------------------------*/
// Directory separator
/*----------------------------------------------------*/
defined('DS') ? DS : define('DS', DIRECTORY_SEPARATOR);

/*----------------------------------------------------*/
// Application paths
/*----------------------------------------------------*/
define('THEMOSIS_PUBLIC_DIR', 'htdocs');
define('THEMOSIS_ROOT', realpath(__DIR__.'/../'));
define('CONTENT_DIR', 'content');
define('WP_CONTENT_DIR', realpath(THEMOSIS_ROOT.DS.THEMOSIS_PUBLIC_DIR.DS.CONTENT_DIR));
 define('ICL_LANGUAGE_CODE', 'EN');
// define('SITE_MAIN_SYS_NAME', 'media');
// define('SLUG_EVENT_CITY_QUERY', 'media');
// define('SLUG_EVENT_TYPE_DEFAULT', 'media');
// define('DEFAULT_SLUG_EVENT_TYPE', 'media');
// define('SLUG_EVENT_CITY_QUERY3', 'media');
// define('SLUG_EVENT_CITY_QUERY4', 'media');
// define('SLUG_EVENT_CITY_QUERY5', 'media');



/*----------------------------------------------------*/
// Composer autoload
/*----------------------------------------------------*/
if (file_exists($autoload = THEMOSIS_ROOT.'/vendor/autoload.php')) {
    require $autoload;
}


$app = require __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Start the application
|--------------------------------------------------------------------------
|
| We're going to initialize the kernel instance and capture the current
| request. We won't directly manage a response from the current file.
| We let WordPress bootstrap its stuff and we'll manage the response
| once WordPress is fully loaded.
|
*/
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->init(
    Illuminate\Http\Request::capture()
);

/*----------------------------------------------------*/
// Database prefix (WordPress)
/*----------------------------------------------------*/
$table_prefix = getenv('DATABASE_PREFIX') ? getenv('DATABASE_PREFIX') : 'cmrs_';

define( 'DUPLICATOR_AUTH_KEY', 'd97aa58385a807bc2bf6270210e33127' );
/* That's all, stop editing! Happy blogging. */
require_once ABSPATH.'/wp-settings.php';


























