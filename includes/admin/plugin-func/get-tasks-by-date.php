<?php
function get_task_list_by_date($assign_date){
    global $wpdb;
    $taskpress_task_table_name = $wpdb->prefix.'taskpress_tasks';

    //Find matched tasks from db
    $matched_tasks = $wpdb->get_results("SELECT task FROM $taskpress_task_table_name WHERE assign_date = '$assign_date'");
    $tasks = '';
    $task_count = 0;
    foreach($matched_tasks as $task_list){
        $task_count++;
        $tasks .= '<li>'.$task_count.'. '.$task_list->task.'</li>';
    }
    return $tasks;
}