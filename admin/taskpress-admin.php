<?php
//Plugin Admin Assets
function hkdc_admin_scripts($hook) {
//    var_dump($hook);

    if( ('task-manager_page_taskpress_task_progress' == $hook) || ('task-manager_page_taskpress_add_task' == $hook) || ('task-manager_page_taskpress_task_list' == $hook) || ('task-manager_page_taskpress_edit_task' == $hook) || ('task-manager_page_taskpress_my_tasks' == $hook) || ('toplevel_page_taskpress_tasks' == $hook)  ){
        wp_enqueue_style('taskpress-form', TASKPRESS_ADMIN_ASSETS . 'css/taskpress-form.css', array(), TASKPRESS_PLUGIN_VERSION);
    }

    //Task Add & Update Styles & Scripts
    if( ('task-manager_page_taskpress_add_task' == $hook) || ('task-manager_page_taskpress_task_list' == $hook) || ('task-manager_page_taskpress_edit_task' == $hook) ){
        wp_enqueue_style('jquery-ui-datepicker', TASKPRESS_ADMIN_ASSETS . 'css/jquery-ui.min.css', array(), TASKPRESS_PLUGIN_VERSION);
        wp_enqueue_style('taskpress-modal', TASKPRESS_ADMIN_ASSETS . 'css/jquery.modal.min.css', array(), TASKPRESS_PLUGIN_VERSION);
        wp_enqueue_script( 'jquery-ui-datepicker', array( 'jquery' ) );
        
        wp_enqueue_script('taskpress-modal', TASKPRESS_ADMIN_ASSETS . 'js/jquery.modal.min.js', array('jquery'), TASKPRESS_PLUGIN_VERSION);
        wp_enqueue_script('taskpress-js', TASKPRESS_ADMIN_ASSETS . 'js/taskpress-js.js', array('jquery'), TASKPRESS_PLUGIN_VERSION);
    }

    //Task Search & calender page styles & scripts
    if(('task-manager_page_taskpress_task_progress' == $hook) || ('task-manager_page_taskpress_my_tasks' == $hook)){
        wp_enqueue_style('taskpress-fullcalender', TASKPRESS_ADMIN_ASSETS . 'css/fullcalender.min.css', array(), TASKPRESS_PLUGIN_VERSION);
        wp_enqueue_script('taskpress-fullcalender', TASKPRESS_ADMIN_ASSETS . 'js/fullcalender.min.js', array(), TASKPRESS_PLUGIN_VERSION);
    }
    if('task-manager_page_taskpress_task_progress' == $hook){
        wp_enqueue_style('taskpress-select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), TASKPRESS_PLUGIN_VERSION);
        wp_enqueue_script('taskpress-select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), TASKPRESS_PLUGIN_VERSION);
    }
}
add_action('admin_enqueue_scripts', 'hkdc_admin_scripts');