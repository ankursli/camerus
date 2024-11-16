<?php

/*
|--------------------------------------------------------------------------
| Notes - README
|--------------------------------------------------------------------------
|
| You can add as many WordPress constants as you want here. Just make sure
| to add them at the end of the file or at least after the "WordPress
| authentication keys and salts" section.
|
*/

/*
|--------------------------------------------------------------------------
| WordPress authentication keys and salts
|--------------------------------------------------------------------------
|
| @link https://api.wordpress.org/secret-key/1.1/salt/
|
*/
define('AUTH_KEY', config('app.salts.auth_key'));
define('SECURE_AUTH_KEY', config('app.salts.secure_auth_key'));
define('LOGGED_IN_KEY', config('app.salts.logged_in_key'));
define('NONCE_KEY', config('app.salts.nonce_key'));
define('AUTH_SALT', config('app.salts.auth_salt'));
define('SECURE_AUTH_SALT', config('app.salts.secure_auth_salt'));
define('LOGGED_IN_SALT', config('app.salts.logged_in_salt'));
define('NONCE_SALT', config('app.salts.nonce_salt'));

/*
|--------------------------------------------------------------------------
| WordPress database
|--------------------------------------------------------------------------
*/
define('DB_NAME', config('database.connections.mysql.database'));
define('DB_USER', config('database.connections.mysql.username'));
define('DB_PASSWORD', config('database.connections.mysql.password'));
define('DB_HOST', config('database.connections.mysql.host'));
define('DB_CHARSET', config('database.connections.mysql.charset'));
define('DB_COLLATE', config('database.connections.mysql.collation'));

/*
|--------------------------------------------------------------------------
| WordPress URLs
|--------------------------------------------------------------------------
*/
define('WP_HOME', config('app.url'));
define('WP_SITEURL', config('app.wp.url'));
define('WP_CONTENT_URL', WP_HOME.'/'.CONTENT_DIR);

/*
|--------------------------------------------------------------------------
| WordPress debug
|--------------------------------------------------------------------------
*/
define('SAVEQUERIES', config('app.debug'));
define('WP_DEBUG', config('app.debug'));
define('WP_DEBUG_DISPLAY', config('app.debug'));
define('SCRIPT_DEBUG', config('app.debug'));

/*
|--------------------------------------------------------------------------
| WordPress auto-update
|--------------------------------------------------------------------------
*/
define('WP_AUTO_UPDATE_CORE', false);

/*
|--------------------------------------------------------------------------
| WordPress file editor
|--------------------------------------------------------------------------
*/
define('DISALLOW_FILE_EDIT', true);

/*
|--------------------------------------------------------------------------
| WordPress default theme
|--------------------------------------------------------------------------
*/
define('WP_DEFAULT_THEME', 'default');

/*
|--------------------------------------------------------------------------
| Application Text Domain
|--------------------------------------------------------------------------
*/
define('APP_TD', env('APP_TD', 'themosis'));

/*
|--------------------------------------------------------------------------
| JetPack
|--------------------------------------------------------------------------
*/
define('JETPACK_DEV_DEBUG', config('app.debug'));

/*
|--------------------------------------------------------------------------
| WP MEMORY LIMIT
|--------------------------------------------------------------------------
*/
define('WP_MEMORY_LIMIT', 1024);

/*
|--------------------------------------------------------------------------
| WP CRON
|--------------------------------------------------------------------------
*/
define('DISABLE_WP_CRON', true);
define('ALTERNATE_WP_CRON', false);

/**
 *
 */
define('SITE_MAIN_SYS_NAME', 'Camerus');
define('URL_SERVICE_RENT_PLUS', 'http://195.135.0.91:8081/RentSonoWebServices/RentPlusServices.asmx/WriteOrder');
define('DEFAULT_SLUG_EVENT_TYPE', 'paris_2023');

/**
 * Salon Constant
 */
define('SLUG_EVENT_SALON_QUERY', 'event_salon');
define('SLUG_EVENT_CITY_QUERY', 'event_city');
define('SLUG_EVENT_TYPE_DEFAULT', 'event_type_default');
define('SLUG_EVENT_SALON_QUERY_DEFAULT', 'generic_salon');
define('SLUG_EVENT_CITY_QUERY_DEFAULT', 'generic_city');
define('SLUG_EVENT_SESSION_SALON', 'cmrs_selected_salon_event');
define('SLUG_CITY_SESSION_SALON', 'cmrs_selected_salon_city');
define('SLUG_STATUS_PRO_SESSION_SALON', 'cmrs_status_pro_salon');
define('SLUG_STATUS_PRO_SESSION_SALON_DATA', 'cmrs_status_pro_salon_data');
define('SLUG_SEARCH_QUERY_SESSION_PRODUCT', 'cmrs_search_query_product');
define('SLUG_REED_DATA_INFO', 'cmrs_reed_data_info');
define('SLUG_REED_PRODUCT_DOTATION', 'cmrs_reed_product_dotation');
define('SLUG_PRODUCT_TAX_ATTRIBUT_CITY', 'pa_city');
define('SLUG_PRODUCT_TAX_ATTRIBUT_COLOR', 'pa_color');
define('SLUG_TAX_MEDIA_CATEGORY', 'media_category');

define('WPML_TEMPLATES_PATHS_CUSTOM', '/var/www/vhosts/camerus.org/httpdocs');
/**
 * File permission mode
 */
define('FS_CHMOD_FILE', 0755);
define('FS_CHMOD_DIR', 0755);


define('REED_EXPORT_CODE', 'RIST-yI6DS27ur3mWBS');


define( 'WPMS_ON', true );
define( 'WPMS_MAIL_FROM', env('WPMS_MAIL_FROM'));
define( 'WPMS_MAIL_FROM_FORCE', true );
define( 'WPMS_MAIL_FROM_NAME', env('WPMS_MAIL_FROM_NAME'));
define( 'WPMS_MAIL_FROM_NAME_FORCE', true );
define( 'WPMS_SET_RETURN_PATH', true );
define( 'WPMS_DO_NOT_SEND', false );
define( 'WPMS_SMTP_HOST', env('WPMS_SMTP_HOST')); // The SMTP mail host.
define( 'WPMS_SMTP_PORT', 465 ); // The SMTP server port number.
define( 'WPMS_SSL', 'ssl' ); // Possible values '', 'ssl', 'tls' - note TLS is not STARTTLS.
define( 'WPMS_SMTP_AUTH', true ); // True turns it on, false turns it off.
define( 'WPMS_SMTP_USER', env('WPMS_SMTP_USER')); // SMTP authentication username, only used if WPMS_SMTP_AUTH is true.
define( 'WPMS_SMTP_PASS', env('WPMS_SMTP_PASS') ); // SMTP authentication password, only used if WPMS_SMTP_AUTH is true.
define( 'WPMS_SMTP_AUTOTLS', true ); // True turns it on, false turns it off.
define( 'WPMS_MAILER', 'smtp' );