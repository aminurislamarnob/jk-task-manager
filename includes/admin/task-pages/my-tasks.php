<div class="wrap my-task-wrapper">
    <div class="task-title-wrap">
        <h1 class="wp-heading-inline">Vandaag Taken</h1>
    </div>
    <div class="taskpress-nav">
        <ul>
            <li><a href="admin.php?page=taskpress_tasks">Taakbeheer</a></li>
            <?php if(current_user_can('administrator')){ ?>
            <li><a href="admin.php?page=taskpress_task_list">Takenlijst</a></li>
            <li><a href="admin.php?page=taskpress_add_task">Taak toevoegen</a></li>
            <li><a href="admin.php?page=taskpress_task_progress">Taak Vooruitgang</a></li>
            <?php } ?>
            <li class="active"><a href="admin.php?page=taskpress_my_tasks">Mijn Taken</a></li>
        </ul>
    </div>

    <div class="task-progress-container">
        <div class="task-list-wrapper">
            <div id="myTaskList" class="taskpress-list-tables"></div>
            <div class="task-progress" id="taskList">
            </div>
        </div>
    </div>
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
    // document.addEventListener('DOMContentLoaded', function() {
        
    //     var calendarEl = document.getElementById('progressCalendar');

    //     var calendar = new FullCalendar.Calendar(calendarEl, {
    //         eventClick: function(info) {
    //             var eventObj = info.event;

    //             if (eventObj.extendedProps.tasks) {
    //                 let todayDate = '<?php // echo taskpress_timezone()->format("Y-m-d"); ?>';
                    
    //                 if(todayDate == eventObj.extendedProps.assignDate){
    //                     document.querySelector('#taskList').style.display = "none";
    //                     document.querySelector('#myTaskList').style.display = "block";
    //                 }else{
    //                     document.querySelector('#taskList').style.display = "block";
    //                     document.querySelector('#myTaskList').style.display = "none";
    //                     let allTasks = eventObj.extendedProps.tasks;
    //                     let cleanAllTasks = allTasks.replace(/~/g, ''); //remove ~ from taskslist
    //                     document.querySelector('#taskList').innerHTML =  "<div class='task-progress-wrapper'><h3> Task progress of: " + eventObj.extendedProps.assignDate + "</h3><ul class='task-progress-list'>" + cleanAllTasks + "</ul></div>";
    //                 }
    //             }else{
    //                 alert('No task added.');
    //             }
    //         },
    //         initialDate: '<?php //echo $taskpress_first_day->format('Y-m-d'); ?>',
    //         events: [
    //             <?php //echo $task_json; ?>
    //         ],
    //         eventDidMount: function(info) {
    //             if(info.event.extendedProps.task_uncompleted != '' && typeof info.event.extendedProps.task_uncompleted  !== "undefined"){  
    //                 info.el.querySelector('.fc-event-title').innerHTML = ("<span class='completed task-number-"+info.event.extendedProps.completedNumber+"'>" + info.event.title + "</span><span class='uncompleted task-number-"+info.event.extendedProps.unCompletedNum+"'>"+ info.event.extendedProps.task_uncompleted + "</span>");
    //             }
    //         },
    //     });

    //     calendar.render();
    // });
</script>