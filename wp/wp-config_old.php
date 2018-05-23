<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'db213837_1clk_wordpress_SWWm3IjS8KQ8WZLW');

/** MySQL database username */
define('DB_USER', 'wordpress_eUST18');

/** MySQL database password */
define('DB_PASSWORD', 'YL4HAmqQ');

/** MySQL hostname */
define('DB_HOST', 'internal-db.s213837.gridserver.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'I474QBtj7U68qSZrH48Zx1db2CyyBTFSLoBpycQo7vKukNBG0xqAnEMdb5lVqeAG');
define('SECURE_AUTH_KEY',  'NG6wtJQjUd3eE5wztT7H2Zu3FXFD3h32bdAfsMjPp7ELfO3Mg9hlDzxenxLok2Lv');
define('LOGGED_IN_KEY',    'ngdep4YOjvxUWH8FFqfIX6NEZnSjVu9S5M7zyTO4IejDY9WfNSUZdyXSuCsQ0pxE');
define('NONCE_KEY',        'BBdGI22qh7Lk1hHvWnFM6LApGP8vKKv0KEEhC8roesTDYaUx9iMg935Ok3b8p0VS');
define('AUTH_SALT',        'VUPNJC3LL9LDPvhBzCq72U3MEDmz0lGZxobyYY9fy2b4wBNoyn34rvHGSSBGZRoa');
define('SECURE_AUTH_SALT', '7cfUPmbnrWgNbOu67bC6mdBGsVzSfUqzsgm87873AZVisBOUxAMSjXpMRpGx7ZsK');
define('LOGGED_IN_SALT',   'Q7VRGKEyhgbzX8jpzk02zJ7TAkBzB7KsLAW9wOQc6c6QximMRgV1ERnoYw50RQq7');
define('NONCE_SALT',       'WBEWpC7dPH0Tg29uhJCKE4CKL5LpYIsoTJ8N8erk1qucC6y93SmVw0OQMFQZCcop');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'boyw_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
