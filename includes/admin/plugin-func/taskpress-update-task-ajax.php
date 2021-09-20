<?php
//Add Task by Ajax functions
add_action('wp_ajax_taskpress_update_task', 'taskpress_update_task');
function taskpress_update_task(){
        // This is a secure process to validate if this request comes from a valid source.
        check_ajax_referer( 'taskpress_secure_nonce_name', 'security' );
 
        //empty message array.
        $message = [];

        global $wpdb;
        $taskpress_task_table_name = $wpdb->prefix.'taskpress_tasks';

        $task = isset( $_POST['task'] ) ? sanitize_text_field($_POST['task']) : '';
        $task_id = isset( $_POST['task_id'] ) ? sanitize_text_field($_POST['task_id']) : '';


        if( !empty($task) ) {
            //update code
            $taskUpdateQuery = $wpdb->update( $taskpress_task_table_name,
                array(
                    "task" => $task,
                ),
                array(
                    'id' => $task_id
                )

            );
            
            //Confirmation Message
            if($taskUpdateQuery){
                $message = json_encode(array('type'=>'success', 'text' => 'Task Successfully Updated' ) );
                echo $message;
                wp_die();
            }else{
                $message = json_encode(array('type'=>'success', 'text' => 'Task Successfully Updated' ) );
                echo $message;
                wp_die();
            }
        }else{
            $message = json_encode(array('type'=>'error', 'text' => 'Please fillup task details.'));
            echo $message;
            wp_die();
        }

}