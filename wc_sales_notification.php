<?php
/**
 * Plugin Name: WC Sales Notification
 * Description: WooCommerce Sales Notification for WordPress.
 * Plugin URI:  https://hasthemes.com/plugins/
 * Version:     1.2.5
 * Author:      HasThemes
 * Author URI:  https://hasthemes.com/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wc-sales-notification
 * Domain Path: /languages
 * WC tested up to: 8.9.3
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'WC_SALENOTIFICATION_VERSION', '1.2.5' );
define( 'WC_SALENOTIFICATION_PL_ROOT', __FILE__ );
define( 'WC_SALENOTIFICATION_PL_URL', plugin_dir_url(  WC_SALENOTIFICATION_PL_ROOT ) );
define( 'WC_SALENOTIFICATION_PL_PATH', plugin_dir_path( WC_SALENOTIFICATION_PL_ROOT ) );
define( 'WC_SALENOTIFICATION_PLUGIN_BASE', plugin_basename( WC_SALENOTIFICATION_PL_ROOT ) );
define( 'WC_SALENOTIFICATION_ASSETS', trailingslashit( WC_SALENOTIFICATION_PL_URL . 'assets' ) );

require ( WC_SALENOTIFICATION_PL_PATH . 'includes/base.php' );
\WC_Sale_Notification\Base::instance();
