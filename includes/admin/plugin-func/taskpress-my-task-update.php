<?php
//Add Task by Ajax functions
add_action('wp_ajax_taskpress_task_status_update', 'taskpress_task_status_update');
function taskpress_task_status_update(){
        // This is a secure process to validate if this request comes from a valid source.
        check_ajax_referer( 'taskpress_secure_nonce_name', 'security' );

        global $wpdb;
        $taskpress_completed_taks_tbl = $wpdb->prefix.'taskpress_completed_tasks';

        $status = 1;
        $task_id = isset( $_POST['id'] ) ? sanitize_text_field($_POST['id']) : null;
        $loggedin_user_id = get_current_user_id();
        $taskpress_today_date = taskpress_timezone()->format("Y-m-d");
        $marked_month = taskpress_timezone()->format("m");
        $marked_year = taskpress_timezone()->format("Y");


        if( !empty($task_id) ) {

            $taskpress_find_task = $wpdb->get_results("SELECT * FROM $taskpress_completed_taks_tbl WHERE task_id = $task_id AND user_id = $loggedin_user_id AND marked_date = '$taskpress_today_date'");

            if(count($taskpress_find_task) > 0){ //if find already marked then throw an error
                //delete code
                $taskCompletedDelete = $wpdb->delete( $taskpress_completed_taks_tbl, array( 'task_id' => $task_id, 'user_id' => $loggedin_user_id, 'marked_date' => $taskpress_today_date) );
                
            
                //Confirmation Message
                if($taskCompletedDelete){
                    $message = json_encode(array('type'=>'success', 'text' => 'Task Successfully Unchcked.' ) );
                    echo $message;
                    wp_die();
                }else{
                    $message = json_encode(array('type'=>'error', 'text' => 'Failed to uncheck task!'));
                    echo $message;
                    wp_die();
                }
            }else{
                //update code
                $taskCompletedQuery = $wpdb->insert( $taskpress_completed_taks_tbl,
                    array(
                        "user_id" => $loggedin_user_id,
                        "task_id" => $task_id,
                        "status" => $status,
                        "marked_date" => $taskpress_today_date,
                        "marked_month" => $marked_month,
                        "marked_year" => $marked_year,
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
                if($taskCompletedQuery){
                    $message = json_encode(array('type'=>'success', 'text' => 'Task mark as done.' ) );
                    echo $message;
                    wp_die();
                }else{
                    $message = json_encode(array('type'=>'error', 'text' => 'Please check again to mark this task as done.' ) );
                    echo $message;
                    wp_die();
                }
            }

        }else{
            $message = json_encode(array('type'=>'error', 'text' => 'Please check again to mark this task as done.'));
            echo $message;
            wp_die();
        }

}