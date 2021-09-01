<?php
//Add Task by Ajax functions
add_action('wp_ajax_taskpress_delete_task', 'taskpress_delete_task');
function taskpress_delete_task(){
        // This is a secure process to validate if this request comes from a valid source.
        check_ajax_referer( 'taskpress_secure_nonce_name', 'security' );
 
        //empty message array.
        $message = [];

        global $wpdb;
        $taskpress_task_table_name = $wpdb->prefix.'taskpress_tasks';

        $task_id = isset( $_POST['task_id'] ) ? sanitize_text_field($_POST['task_id']) : '';


        if( !empty($task_id) ) {
            //delete code
            $taskDeleteQuery = $wpdb->delete( $taskpress_task_table_name, array( 'id' => $task_id ) );
            
            //Confirmation Message
            if($taskDeleteQuery){
                $message = json_encode(array('type'=>'success', 'text' => 'Task Successfully Deleted.' ) );
                echo $message;
                wp_die();
            }else{
                $message = json_encode(array('type'=>'error', 'text' => 'Failed to delete task!'));
                echo $message;
                wp_die();
            }

        }else{
            $message = json_encode(array('type'=>'error', 'text' => 'Failed to delete task!'));
            echo $message;
            wp_die();
        }

}