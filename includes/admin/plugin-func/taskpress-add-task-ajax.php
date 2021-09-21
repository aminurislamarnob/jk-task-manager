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
        $assign_date = date("Y-m-d");
        $assigned_by = get_current_user_id();

        if ( !empty($assign_date) ){
            //Assign date
            $assign_year = date("Y");
            $assign_month = date("m");

            if( !empty($task) && !empty($assigned_by) ) {

                //Check max task
                $matched_tasks = $wpdb->get_results("SELECT * FROM $taskpress_task_table_name");
                $max_task_limit = 10;
                
                if(count($matched_tasks) < $max_task_limit){ //maximum 10 task for a user in a day.
                    //insertion code
                    $taskInsertQuery = $wpdb->insert( $taskpress_task_table_name,
                        array(
                            "task" => $task,
                            "assign_date" => $assign_date,
                            "assigned_by" => $assigned_by,
                            "assign_month" => $assign_month,
                            "assign_year" => $assign_year,
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
                        $message = json_encode(array('type'=>'success', 'text' => 'Task Successfully Added', 'tasks' => get_task_list_by_date()));
                        echo $message;
                        wp_die();
                    }
                }else{
                    $message = json_encode(array('type'=>'error', 'text' => 'Already assigned '.$max_task_limit.' tasks.', 'tasks' => get_task_list_by_date()));
                    echo $message;
                    wp_die();
                }

            }else{
                $message = json_encode(array('type'=>'error', 'text' => 'Please check required (* mark) fields.', 'tasks' => get_task_list_by_date()));
                echo $message;
                wp_die();
            }
        }else{
            $message = json_encode(array('type'=>'error', 'text' => 'Please select correct assign date.', 'invalid_assign_date'=>true));
            echo $message;
            wp_die();
        }
}