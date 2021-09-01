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
        $assign_date = isset( $_POST['assign_date'] ) ? sanitize_text_field($_POST['assign_date']) : '';
        $assigned_by = get_current_user_id();

        if ( !empty($assign_date) && (strtotime($assign_date) !== false) ){
            //Assign date
            // $str_to_date = strtotime($assign_date);
            // $assign_year = date("Y", $str_to_date);
            // $assign_month = date("m", $str_to_date);
            $date_with_timezone = new DateTime($assign_date, new DateTimeZone(TASKPRESS_TIMEZONE));
            $assign_year = $date_with_timezone->format("Y");
            $assign_month = $date_with_timezone->format("m");

            //Today date
            // $current_month = date("m");
            // $current_year = date("Y");
            if( !empty($task) && !empty($assigned_by) ) {

                //Check same day selected users number of task
                $matched_tasks = $wpdb->get_results("SELECT * FROM $taskpress_task_table_name WHERE assign_date = '$assign_date'");
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
                        $message = json_encode(array('type'=>'success', 'text' => 'Task Successfully Added', 'tasks' => get_task_list_by_date($assign_date), 'assign_date' => $assign_date ) );
                        echo $message;
                        wp_die();
                    }
                }else{
                    $message = json_encode(array('type'=>'error', 'text' => 'Already assigned '.$max_task_limit.' task for this selected date.', 'tasks' => get_task_list_by_date($assign_date), 'assign_date' => $assign_date));
                    echo $message;
                    wp_die();
                }

            }else{
                $message = json_encode(array('type'=>'error', 'text' => 'Please check required (* mark) fields.', 'tasks' => get_task_list_by_date($assign_date), 'assign_date' => $assign_date));
                echo $message;
                wp_die();
            }
        }else{
            $message = json_encode(array('type'=>'error', 'text' => 'Please select correct assign date.', 'invalid_assign_date'=>true));
            echo $message;
            wp_die();
        }
}