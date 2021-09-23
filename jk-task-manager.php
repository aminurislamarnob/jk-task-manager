<?php
/*
Plugin Name: JK Task Manager
Plugin URI:
Description: Wordpress Task Manager Plugin. Shortcode: [frontend_checklist]
Version: 2.0
Author: Jakarea Parvez
Author URI: https://jakarea.github.io
License: GPLv2 or later
Text Domain: wp-task-manager
Domain Path: /languages
*/

/**
 * Restrict this file to call directly
 */
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Currently plugin version.
 */
define('TASKPRESS_PLUGIN_VERSION', '2.0');


/**
 * plugin timezone.
 */
define('TASKPRESS_TIMEZONE', 'Europe/Amsterdam');

function taskpress_timezone(){
    $taskpress_date_time_zone = new DateTime();
    return $taskpress_date_time_zone->setTimezone(new DateTimeZone(TASKPRESS_TIMEZONE));
}

// echo $taskpress_date_time_zone->format('d');

/**
 * Load plugin textdomain.
 */
function taskpress_load_textdomain() {
    load_plugin_textdomain( 'wp-task-manager', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'taskpress_load_textdomain' );


/***
 * Plugin Directory
 **/
define( 'TASKPRESS_DIR',  plugin_dir_path( __FILE__ ) );
define( 'TASKPRESS_ADMIN_ASSETS',  plugin_dir_url( __FILE__ ) . "assets/admin/" );
define( 'TASKPRESS_FRONT_ASSETS',  plugin_dir_url( __FILE__ ) . "assets/public/");


/**
 * The code that runs during plugin activation.
 */
function taskpress_activate_plugin() {
    require_once TASKPRESS_DIR . 'includes/taskpress-activator.php';
}
register_activation_hook( __FILE__, 'taskpress_activate_plugin' );


/****
 * Include Assets
 ***/
require TASKPRESS_DIR . 'assets/taskpress-assets.php';

/****
 * Include Plugin files
 ***/
require TASKPRESS_DIR . 'includes/admin-pages.php'; //Plugin custom post type

/****
 * Create ajax url and nonce
 ***/
function taskpress_ajax_variables(){
    if ( is_user_logged_in() ) {
    ?>
    <script type="text/javascript">
        var taskpress_ajax_url = '<?php echo admin_url( "admin-ajax.php" ); ?>';
        var taskpress_ajax_nonce = '<?php echo wp_create_nonce( "taskpress_secure_nonce_name" ); ?>';
    </script><?php
    }
}
add_action ( 'admin_head', 'taskpress_ajax_variables' );
add_action ( 'wp_head', 'taskpress_ajax_variables' );


/**
 * Get task list by date function
*/
require TASKPRESS_DIR . 'includes/admin/plugin-func/get-tasks-by-date.php';


/**
 * Ajax submission handle scripts
*/
require TASKPRESS_DIR . 'includes/admin/plugin-func/taskpress-add-task-ajax.php';
require TASKPRESS_DIR . 'includes/admin/plugin-func/taskpress-all-task-ajax.php';
require TASKPRESS_DIR . 'includes/admin/plugin-func/taskpress-update-task-ajax.php';
require TASKPRESS_DIR . 'includes/admin/plugin-func/taskpress-delete-task-ajax.php';
require TASKPRESS_DIR . 'includes/admin/plugin-func/taskpress-my-tasks-ajax.php';
require TASKPRESS_DIR . 'includes/admin/plugin-func/taskpress-my-task-update.php';


/*
Register taskpress shortcode
*/
add_shortcode( 'frontend_checklist', 'taskpress_mytask_shortcode' );
function taskpress_mytask_shortcode( $atts ) {
    if ( is_user_logged_in() ) {
        ob_start();
        require TASKPRESS_DIR . 'includes/admin/task-pages/my-tasks-shortcode.php';
        return ob_get_clean();
    }else{
        return 'Gelieve eerst in te loggen.';
    }
}