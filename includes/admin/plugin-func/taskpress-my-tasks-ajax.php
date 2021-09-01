<?php
//My Task
add_action('wp_ajax_taskpress_my_tasks', 'taskpress_my_tasks');
function taskpress_my_tasks(){

    global $wpdb;
    $taskpress_task_table_name = $wpdb->prefix.'taskpress_tasks';
    $taskpress_completed_taks_tbl = $wpdb->prefix.'taskpress_completed_tasks';
    
    $taskpress_today_date = taskpress_timezone()->format("Y-m-d");
    $loggedin_user_id = get_current_user_id();

    $taskpress_all_tasks = $wpdb->get_results("SELECT id, task FROM $taskpress_task_table_name WHERE assign_date = '$taskpress_today_date'");
    
    //Make today task completed loggedin user task id array
    $taskpress_completed_task = $wpdb->get_results("SELECT task_id FROM $taskpress_completed_taks_tbl WHERE user_id = $loggedin_user_id AND marked_date = '$taskpress_today_date'");
    $all_marked_task_ids = [];
    foreach($taskpress_completed_task as $taskpress_task_completed){
        $all_marked_task_ids[] = $taskpress_task_completed->task_id;
    }

    //Task progress calculation
    $totalTodayTasks = count($taskpress_all_tasks);
    $percentagePerTask = (100 / $totalTodayTasks);
    $totalCompletedTask = count($taskpress_completed_task);
    $taskPercentage = floor($percentagePerTask * $totalCompletedTask);

    $my_task_list = '';
    if($taskPercentage>0){
    $my_task_list .= '
		<div class="task-loader" style="display:none;"><div class="task-ellipsis"><div></div><div></div><div></div><div></div></div></div>
        <div class="taskpress-progress-bar">
          <div class="progress-bar" data-percent="'.$taskPercentage.'" style="width: '.$taskPercentage.'%;">
            <span class="progress-label">'.$taskPercentage.'%</span>
          </div>
        </div>';
    }
    $my_task_list .= '<ul class="my-task-list">';
            foreach($taskpress_all_tasks as $taskpress_task){ 
                if( in_array($taskpress_task->id, $all_marked_task_ids) ){
                    $my_task_list .= '<li class="marked-list"><input type="checkbox" class="task-list-check" id="task-'.$taskpress_task->id.'" value="'.$taskpress_task->id.'" checked>';
                }else{
                    $my_task_list .= '<li><input type="checkbox" class="task-list-check" id="task-'.$taskpress_task->id.'" value="'.$taskpress_task->id.'">';
                }
                
                $my_task_list .= '<label for="task-'.$taskpress_task->id.'">'.$taskpress_task->task.'</label></li>';
            }
            if(empty($taskpress_all_tasks)){
                    $my_task_list .= '<li class="no-task-found">Vandaag heb je geen taak!</li>';
            }
        $my_task_list .= '</ul>';

    echo $my_task_list;
    ?>
    <script>
        jQuery(document).ready(function($){
            $('.task-list-check').click(function(event) {
                // if(this.checked){
                    $(this).closest('li').addClass('marked-list');
                    // $(this).prop('disabled', true);
                    var taskId = $(this).val();
                    $.ajax({
                        url: '<?php echo admin_url( "admin-ajax.php" ); ?>',
                        data: {"action": "taskpress_task_status_update", "id": taskId, "security":taskpress_ajax_nonce},
                        type: 'post',
                        beforeSend: function() {
                            $('.task-loader').show();
                        },
                        success: function(response) {
                            
                            loadMyTasks(); //load task from 
							
                        },
						complete: function () {
// 							$('.task-loader').hide();
						},
                    });
                // }
            });

            //load task
            function loadMyTasks(){
                $.ajax({
                    url: '<?php echo admin_url( "admin-ajax.php" ); ?>',
                    data: {"action": "taskpress_my_tasks" },
                    type: 'post',
                    beforeSend: function() {
                        // $(".loaderDiv").show();
                    },
                    success: function(response) {
                        $('#myTaskList').html(response);
                    }
                });
            };
        });
    </script>
    <?php
    wp_die();
}