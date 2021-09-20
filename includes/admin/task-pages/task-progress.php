<?php
/**
 * Task progress calendar by user
 */

function taskpress_task_progress_calendar(){
    ?>
    <div class="wrap">
        <div class="task-title-wrap">
            <h1 class="wp-heading-inline">Taak Vooruitgang</h1>
        </div>
        <div class="taskpress-nav">
            <ul>
                    <li><a href="admin.php?page=taskpress_tasks">Taakbeheer</a></li>
                    <li><a href="admin.php?page=taskpress_task_list">Takenlijst</a></li>
                    <li><a href="admin.php?page=taskpress_add_task">Taak toevoegen</a></li>
                    <li class="active"><a href="admin.php?page=taskpress_task_progress">Taak Vooruitgang</a></li>
                    <li><a href="admin.php?page=taskpress_my_tasks">Mijn Taken</a></li>
            </ul>
        </div>
        <div class="taskpress-form-sm">
            <form action="" method="get" class="task-progress-form">
                <input type="hidden" name="page" value="taskpress_task_progress">
                <div class="task-form-inline">
                    <div class="task-form-group">
                        <label for="user_id">Selecteer gebruiker <span class="req">*</span> </label>
                        <select name="user_id" id="user_id" required>
                            <option value="" selected disabled>Selecteer gebruiker</option>
                            <?php
                            $taskpress_all_users = get_users(array( 'fields' => array( 'ID', 'display_name' ) ));
                            foreach ( $taskpress_all_users as $user ) { ?>
                                <option value="<?php echo $user->ID; ?>"<?php if(!empty($_REQUEST['user_id']) && $_REQUEST['user_id'] == $user->ID ){ echo ' selected'; }?>><?php esc_html_e( $user->display_name, 'wp-task-manager' ); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="task-form-group">
                        <button type="submit" class="button button-primary taskpress-btn mt-20" value="search" name="action">Zoeken op</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="task-progress-container">
        <div class="task-list-wrapper">
            <div class="task-progress" id="taskList">
                <div class="task-progress-wrapper">
                    <h3> Taak voortgang</h3>
                    <ul class="task-progress-list">
                    <?php
                    if( isset($_REQUEST['user_id']) && !empty($_REQUEST['user_id']) ){
                        global $wpdb;
                        $taskpress_task_table_name = $wpdb->prefix.'taskpress_tasks';
                        $taskpress_completed_taks_tbl = $wpdb->prefix.'taskpress_completed_tasks';
                        $taskpress_user_id = $_REQUEST['user_id'];

                        $taskpress_all_tasks = $wpdb->get_results("SELECT id, task FROM $taskpress_task_table_name");
                        
                        //Make today task completed loggedin user task id array
                        $taskpress_completed_task = $wpdb->get_results("SELECT task_id FROM $taskpress_completed_taks_tbl WHERE user_id = $taskpress_user_id AND status = 1");
                        $all_marked_task_ids = [];
                        foreach($taskpress_completed_task as $taskpress_task_completed){
                            $all_marked_task_ids[] = $taskpress_task_completed->task_id;
                        }

                        foreach($taskpress_all_tasks as $taskpress_task){ 
                            if( in_array($taskpress_task->id, $all_marked_task_ids) ){
                                echo '<li class="completed">'.$taskpress_task->task.'</li>';
                            }else{
                                echo '<li class="uncompleted">'.$taskpress_task->task.'</li>';
                            }
                        }
                        if(empty($taskpress_all_tasks)){
                                $my_task_list .= '<li class="no-task-found">Vandaag heb je geen taak!</li>';
                        }
                    }
                    ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    

    <script>
        jQuery(document).ready(function($){
            //User select 2
            $('#user_id').select2();
        });
    </script>

    <?php if( isset($_REQUEST['user_id']) && !empty($_REQUEST['user_id']) ){ ?>
    <script>
        //FullJS Calendar Config
        document.addEventListener('DOMContentLoaded', function() {
            
            var calendarEl = document.getElementById('progressCalendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                eventClick: function(info) {
                    var eventObj = info.event;

                    if (eventObj.extendedProps.tasks) {
                        // alert('Clicked ' + eventObj.extendedProps.tasks);
                        let allTasks = eventObj.extendedProps.tasks;
                        let cleanAllTasks = allTasks.replace(/~/g, ''); //remove ~ from taskslist
                        document.querySelector('#taskList').innerHTML =  "<div class='task-progress-wrapper'><h3> Taak voortgang van: " + eventObj.extendedProps.assignDate + "</h3><ul class='task-progress-list'>" + cleanAllTasks + "</ul></div>";
                    }else{
                        alert('Geen taak toegevoegd.');
                    }
                },
                initialDate: '<?php echo $taskpress_first_day->format('Y-m-d'); ?>',
                events: [
                    <?php echo $task_json; ?>
                ],
                eventDidMount: function(info) {
                    if(info.event.extendedProps.task_uncompleted != '' && typeof info.event.extendedProps.task_uncompleted  !== "undefined"){  
                        info.el.querySelector('.fc-event-title').innerHTML = ("<span class='completed task-number-"+info.event.extendedProps.completedNumber+"'>" + info.event.title + "</span><span class='uncompleted task-number-"+info.event.extendedProps.unCompletedNum+"'>"+ info.event.extendedProps.task_uncompleted + "</span>");
                    }
                },
            });

            calendar.render();
        });
    </script>
    <?php
    }
}