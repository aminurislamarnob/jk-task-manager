<?php
/**
 * All Admin Pages
 */

/**
Plugin Main Page
 */
add_action('admin_menu', 'taskpress_task_manager');
function taskpress_task_manager() {
    add_menu_page(__('WordPress Taakbeheer', 'wp-task-manager'), __('Task Manager', 'wp-task-manager'), 'read', 'taskpress_tasks', 'taskpress_tasks_manager_callback', 'dashicons-networking');
}
//Plugin main page callback
function taskpress_tasks_manager_callback(){
    require TASKPRESS_DIR . 'includes/admin/task-pages/dashboard.php';
}


/**
Plugin All Task Page
 */
add_action('admin_menu', 'taskpress_all_task_page');

function taskpress_all_task_page() {
    add_submenu_page(
        'taskpress_tasks',
        __( 'Takenlijst', 'wp-task-manager' ),
        __( 'Takenlijst', 'wp-task-manager' ),
        'manage_options',
        'taskpress_task_list',
        'taskpress_task_list_callback'
    );
}

//All Task callback
function taskpress_task_list_callback() {
    require TASKPRESS_DIR . 'includes/admin/task-pages/all-task.php';
}


/**
Plugin Add Task Page
 */
add_action('admin_menu', 'taskpress_add_task_page');

function taskpress_add_task_page() {
    add_submenu_page(
        'taskpress_tasks',
        __( 'Nieuwe taak toevoegen', 'wp-task-manager' ),
        __( 'Taak toevoegen', 'wp-task-manager' ),
        'manage_options',
        'taskpress_add_task',
        'taskpress_add_task_callback'
    );
}

//Add Task callback
function taskpress_add_task_callback() {
    /*Include add task form*/
    require TASKPRESS_DIR . 'includes/admin/task-pages/add-task.php';
}


/**
Task progress calendar page
*/
add_action('admin_menu', 'taskpress_progress_calendar');

function taskpress_progress_calendar() {
    add_submenu_page(
        'taskpress_tasks',
        __( 'Taakvoortgang per gebruiker', 'wp-task-manager' ),
        __( 'Taak Vooruitgang', 'wp-task-manager' ),
        'manage_options',
        'taskpress_task_progress',
        'taskpress_task_progress_callback'
    );
}

//Task progress callback
function taskpress_task_progress_callback() {
    /*Task progress calendar by user*/
    require TASKPRESS_DIR . 'includes/admin/task-pages/task-progress.php';
    taskpress_task_progress_calendar();
}


/**
My Task List
*/
add_action('admin_menu', 'taskpress_my_task_list');

function taskpress_my_task_list() {
    add_submenu_page(
        'taskpress_tasks',
        __( 'Mijn Taken Vandaag', 'wp-task-manager' ),
        __( 'Mijn Taken', 'wp-task-manager' ),
        'read',
        'taskpress_my_tasks',
        'taskpress_my_tasks_callback'
    );
}

//My task callback
function taskpress_my_tasks_callback() {
    require TASKPRESS_DIR . 'includes/admin/task-pages/my-tasks.php';
}