<div class="my-task-wrapper">
    <div class="task-progress-container">
        <div class="calender-wrapper">
            <div id="progressCalendar"></div>
        </div>
        <div class="task-list-wrapper">
            <div id="myTaskList" class="taskpress-list-tables"></div>
            <div class="task-progress" id="taskList">
            </div>
        </div>
    </div>
<?php 
    $taskpress_first_day = new DateTime('first day of this month');
    $taskpress_first_day->setTimezone(new DateTimeZone(TASKPRESS_TIMEZONE));

    //Get task data from db
    global $wpdb;
    $taskpress_task_table_name = $wpdb->prefix.'taskpress_tasks';
    $taskpress_completed_taks_tbl = $wpdb->prefix.'taskpress_completed_tasks';
    $task_user_id = get_current_user_id();
    $current_month = taskpress_timezone()->format('m');
    $current_year = taskpress_timezone()->format('Y');

    //Find matched tasks from db
    $matched_tasks = $wpdb->get_results("SELECT task, id FROM $taskpress_task_table_name");


    $tasks = []; //tasks list
    $tasks_status = []; //tasks status
    $task_json = ''; //json for full calendar
    $currentMonthTotalDays = date('t');
    $currentDayOfMonth = date('j');

    $currentMonth = date('m');
    $currentYear = date('Y');

    for($d=1; $d<=$currentMonthTotalDays; $d++){

        $assignDate = $currentYear.'-'.$currentMonth.'-'.sprintf("%02d", $d);

        //Make current year and month task id array
        $get_marked_tasks = $wpdb->get_results("SELECT task_id FROM $taskpress_completed_taks_tbl WHERE user_id = $task_user_id AND marked_date = '$assignDate'");
        $all_marked_task_ids = [];
        foreach($get_marked_tasks as $taskpress_marked_task){
            $all_marked_task_ids[] = $taskpress_marked_task->task_id;
        }

        //task list generate
        $task_collection = '';
        $task_completed = 0;
        $task_uncompleted = 0;
        for($i=0; $i<count($matched_tasks); $i++){
            $taskStatus = in_array($matched_tasks[$i]->id, $all_marked_task_ids) ? 'completed' : 'uncompleted';
            $task_completed += in_array($matched_tasks[$i]->id, $all_marked_task_ids) ? 1 : 0;
            $task_uncompleted += !in_array($matched_tasks[$i]->id, $all_marked_task_ids) ? 1 : 0;
            $task_collection .=  '<li class="'.$taskStatus.'">'.$matched_tasks[$i]->task.'</li>~';
        }
        $task_collection = rtrim($task_collection,"~");


        $task_json .= "{title: 'Completed : $task_completed' , start: '$assignDate', extendedProps: {tasks: '$task_collection', task_uncompleted: 'Uncompleted : $task_uncompleted', assignDate: '$assignDate', completedNumber: '$task_completed', unCompletedNum: '$task_uncompleted'},},";

        // echo $task_json.'<br>';
    }
?>
</div>
<script>
    jQuery(document).ready(function($){
        //Ajax Get all Task
        // $(window).load(function() {
            
            $.ajax({
                url: '<?php echo admin_url( "admin-ajax.php" ); ?>',
                data: {"action": "taskpress_my_tasks" },
                type: 'post',
                beforeSend: function() {
                    // $(".loaderDiv").show();
                },
                success: function(response) {
                    $('#myTaskList').html(response);
                    // console.log(response)
                },
                fail : function( err ) {
                    // console.log(err)
                },
            });
        // });
    });
</script>
<script>
    //FullJS Calendar Config
    document.addEventListener('DOMContentLoaded', function() {
        
        var calendarEl = document.getElementById('progressCalendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            eventClick: function(info) {
                var eventObj = info.event;

                if (eventObj.extendedProps.tasks) {
                    let todayDate = '<?php echo taskpress_timezone()->format('Y-m-d'); ?>';
                    
                    if(todayDate == eventObj.extendedProps.assignDate){
                        document.querySelector('#taskList').style.display = "none";
                        document.querySelector('#myTaskList').style.display = "block";
                    }else{
                        document.querySelector('#taskList').style.display = "block";
                        document.querySelector('#myTaskList').style.display = "none";
                        let allTasks = eventObj.extendedProps.tasks;
                        let cleanAllTasks = allTasks.replace(/~/g, ''); //remove ~ from taskslist
                        document.querySelector('#taskList').innerHTML =  "<div class='task-progress-wrapper'><h3> Task progress of: " + eventObj.extendedProps.assignDate + "</h3><ul class='task-progress-list'>" + cleanAllTasks + "</ul></div>";
                    }
                }else{
                    alert('No task added.');
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