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
        $assign_date = isset( $_POST['assign_date'] ) ? sanitize_text_field($_POST['assign_date']) : '';

        if ( !empty($assign_date) && (strtotime($assign_date) !== false) ){
            //Assign date
            $str_to_date = strtotime($assign_date);
            $assign_year = date("Y", $str_to_date);
            $assign_month = date("m", $str_to_date);

            //Today date
            $current_month = date("m");
            $current_year = date("Y");

            if( ($current_month == $assign_month) && ($current_year == $assign_year) ){
                if( !empty($task) ) {
                    //Check same day selected users number of task
                    $matched_tasks = $wpdb->get_results("SELECT * FROM $taskpress_task_table_name WHERE assign_date = '$assign_date' AND id != $task_id");
                    $max_task_limit = 10;

                    if(count($matched_tasks) < $max_task_limit){ //maximum 10 task for a user in a day.
                        //update code
                        $taskUpdateQuery = $wpdb->update( $taskpress_task_table_name,
                            array(
                                "task" => $task,
                                "assign_date" => $assign_date,
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
                        $message = json_encode(array('type'=>'error', 'text' => 'Already assigned '.$max_task_limit.' task for this selected date.', 'tasks' => get_task_list_by_date($assign_date), 'assign_date' => $assign_date));
                        echo $message;
                        wp_die();
                    }
                }else{
                    $message = json_encode(array('type'=>'error', 'text' => 'Please fillup task details.'));
                    echo $message;
                    wp_die();
                }
            }else{
                $message = json_encode(array('type'=>'error', 'text' => 'Please select date from current month only.', 'tasks' => get_task_list_by_date($assign_date), 'assign_date' => $assign_date));
                echo $message;
                wp_die();
            }
        }else{
            $message = json_encode(array('type'=>'error', 'text' => 'Please select correct assign date.', 'invalid_assign_date'=>true));
            echo $message;
            wp_die();
        }

}