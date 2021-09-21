<?php
//All Task by Ajax functions
add_action('wp_ajax_taskpress_all_task', 'taskpress_all_task');
function taskpress_all_task(){
    // This is a secure process to validate if this request comes from a valid source.
    check_ajax_referer( 'taskpress_secure_nonce_name', 'security' );

    global $wpdb;
    $taskpress_task_table_name = $wpdb->prefix.'taskpress_tasks';
    $taskpress_all_tasks = $wpdb->get_results("SELECT * FROM $taskpress_task_table_name");
    $task_count = 0;

    $table_data = '';
    $table_data .= '
    <table class="wp-list-table widefat striped table-view-list">
        <thead>
            <tr>
                <th scope="col" class="manage-column column-title column-ticker-serial">#</th>
                <th scope="col" class="manage-column column-title column-primary">Task</th>
                <th scope="col" class="manage-column column-ticker-action">Action</th>
            </tr>
        </thead>
        <tbody id="the-list">';
            foreach($taskpress_all_tasks as $taskpress_task){ 
                $task_count++;
                $table_data .= '
                <tr id="post-62" class="">
                    <td width="50">'.$task_count.'</td>
                    <td class="title column-title column-primary page-title" data-colname="Title">
                        <strong class="task-text-'.$taskpress_task->id.'">'.$taskpress_task->task.'</strong>
                    </td>
                    <th width="180" scope="col" class="manage-column column-task-action">
                        <div class="task-actions">
                            <button class="button button-primary task-edit-btn" data-id="'.$taskpress_task->id.'" data-date="'.$taskpress_task->assign_date.'">Edit</button>
                            <form class="deleteTaskpressTask">
                                <input type="hidden" name="action" value="taskpress_delete_task">
                                <input type="hidden" name="task_id" value="'.$taskpress_task->id.'">
                                <button class="button task-delete-btn" type="submit">Delete</button>
                            </form>
                        </div>
                    </th>
                </tr>';
            }
            if(empty($taskpress_all_tasks)){
                    $table_data .= '<tr>
                    <td colspan="4">No task found!</td>
                </tr>';
            }
        $table_data .= '</tbody>
    </table>';

    echo $table_data;

    ?>
    <script>
        jQuery(document).ready(function($){
            $('.task-edit-btn').click(function(event) {
                $('.update-task-modal').modal({
                    fadeDuration: 250,
                    clickClose: false, 
                });
                var taskId = $(this).attr('data-id');
                var taskText = $('.task-text-'+taskId).text();
                var taskDate = $(this).attr('data-date');
                $('.update-task-modal textarea[name="task"]').val(taskText);
                $('.update-task-modal input[name="task_id"]').val(taskId);
                $('.update-task-modal input[name="assign_date"]').val(taskDate);

                //Remove modal message
                $('#formEditResponseMsg .taskpress-notice-text').removeClass('notice-success notice-error notice');

                return false;
            });
        });
    </script>
    <script>
        jQuery(document).ready(function($){
            //Ajax delete Task
            $( '.deleteTaskpressTask' ).on( 'submit', function() {
                var form_data = jQuery( this ).serializeArray();
                
                // Here we add our nonce.
                form_data.push( { "name" : "security", "value" : taskpress_ajax_nonce } );

                $.ajax({
                    url : '<?php echo admin_url( "admin-ajax.php" ); ?>',
                    type : 'post',
                    data : form_data,
                    success : function( response ) {
                        var jsonResponse = $.parseJSON(response);
                        
                        //Output response
                        if(jsonResponse.type == 'error'){
                            $('#alertMsgTable .taskpress-notice-text').removeClass('notice-success notice'); //remove notice class
                            $('#alertMsgTable .notice-text').html(jsonResponse.text); //add response message
                            $('#alertMsgTable .taskpress-notice-text').addClass('notice-error notice'); //add notice class
                        }else{
                            $('#alertMsgTable .taskpress-notice-text').removeClass('notice-error notice'); //remove notice class
                            $('#alertMsgTable .notice-text').html(jsonResponse.text); //add response message
                            $('#alertMsgTable .taskpress-notice-text').addClass('notice-success notice'); //add notice class
                            loadAllTask(); //load all task
                        }
                    },
                    fail : function( err ) {
                        $('#alertMsgTable .taskpress-notice-text').removeClass('notice-success notice'); //remove notice class
                        $('#alertMsgTable .notice-text').html('Something went wrong! Please try again later.'); //add response message
                        $('#alertMsgTable .taskpress-notice-text').addClass('notice-error notice'); //add notice class
                    },
                });
                
                // This return prevents the submit event to refresh the page.
                return false;
            });
        });
    </script>
    <?php

    wp_die();
}