<?php
//Add Task by Ajax functions
add_action('wp_ajax_taskpress_add_task', 'taskpress_add_task');
function taskpress_add_task(){
        // This is a secure process to validate if this request comes from a valid source.
        check_ajax_referer( 'taskpress_secure_nonce_name', 'security' );
 
        //empty message array.
        $message = [];

        global $wpdb;
        $taskpress_task_table_name = $wpdb->prefix.'taskpress_tasks';

        $task = isset( $_POST['task'] ) ? sanitize_text_field($_POST['task']) : '';

        $assigned_by = get_current_user_id();

        if( !empty($task) && !empty($assigned_by) ) {
            //insertion code
            $taskInsertQuery = $wpdb->insert( $taskpress_task_table_name,
                array(
                    "task" => $task,
                    "assign_date" => '',
                    "assigned_by" => $assigned_by,
                    "assign_month" => '',
                    "assign_year" => '',
                ),
                array(
                    '%s', //data formate %s for string
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                )

            );
            //Confirmation Message
            if($taskInsertQuery){
                $message = json_encode(array('type'=>'success', 'text' => 'Task Successfully Added', 'tasks' => get_task_list() ) );
                echo $message;
                wp_die();
            }

        }else{
            $message = json_encode(array('type'=>'error', 'text' => 'Please check required (* mark) fields.', 'tasks' => get_task_list()));
            echo $message;
            wp_die();
        }
}