<?php
/**
 * DB Table Created
 * **** Run on Plugin Activation
 * */


function taskpress_task_table(){
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $taskpress_table_name = $wpdb->prefix . 'taskpress_tasks';
    $taskpress_table_name_sql = "CREATE TABLE $taskpress_table_name (
        id int(9) NOT NULL AUTO_INCREMENT,
        task text NOT NULL,
        assign_date varchar(255) NOT NULL,
        assigned_by int(20) NOT NULL,
        assign_month int(20) NOT NULL,
        assign_year int(20) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        updated_at datetime NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $taskpress_table_name_sql );
}


function taskpress_completed_tasks_table(){
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $taskpress_table_name = $wpdb->prefix . 'taskpress_completed_tasks';
    $taskpress_table_name_sql = "CREATE TABLE $taskpress_table_name (
        id int(9) NOT NULL AUTO_INCREMENT,
        user_id int(20) NOT NULL,
        task_id int(20) NOT NULL,
        status int(20) NOT NULL,
        marked_date varchar(255) NOT NULL,
        marked_month int(20) NOT NULL,
        marked_year int(20) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        updated_at datetime NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $taskpress_table_name_sql );
}