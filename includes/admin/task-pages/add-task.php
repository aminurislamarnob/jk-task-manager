<?php
/**
 * Add New Task
 */
?>
    <div class="wrap">
        <div class="task-title-wrap">
            <h1 class="wp-heading-inline">Nieuwe taak toevoegen</h1>
        </div>
        <div class="taskpress-nav">
            <ul>
                <li><a href="admin.php?page=taskpress_tasks">Taakbeheer</a></li>
                <li><a href="admin.php?page=taskpress_task_list">Takenlijst</a></li>
                <li class="active"><a href="admin.php?page=taskpress_add_task">Taak toevoegen</a></li>
                <li><a href="admin.php?page=taskpress_task_progress">Taak Vooruitgang</a></li>
                <li><a href="admin.php?page=taskpress_my_tasks">Mijn Taken</a></li>
            </ul>
        </div>
        <div class="taskadd-row">
            <div class="task-column-left">
                <div class="taskpress-form">
                    <div id="formResponseMsg" class="taskpress-notice">
                        <div class="taskpress-notice-text"><p class="notice-text"></p><span class="dashicons dashicons-no-alt remove-notice"></span></div>
                    </div>
                    <form id="addTaskpressTask" action="" method="post">
                        <div class="task-form-group">
                            <label for="task">Taak Details <span class="req">*</span> </label>
                            <textarea class="large-text code" name="task" id="task" cols="30" rows="5" placeholder="Vul hier de details van uw taak in"></textarea>
                        </div>
                        <div class="task-form-group">
                            <label for="assign_date">Selecteer Datum toewijzen <span class="req">*</span> </label>
                            <input name="assign_date" type="text" id="assign_date" class="assign_date regular-text" autocomplete="off" placeholder="Kies datum toewijzen">
                        </div>
                        <div class="task-form-group">
                            <input type="hidden" name="action" value="taskpress_add_task">
                            <button type="submit" id="submit" class="button button-primary taskpress-btn"><span>Taak toevoegen</span></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="task-column-right">
                <div class="task-added-list">
                    <h3>Takenlijst van: <span id="taskListAddedDate"></span></h3>
                    <ul id="taskList"></ul>
                </div>
            </div>
        </div>
    </div>
    <script>
    jQuery(document).ready(function($){
        //Date Picker Config
        var currentTime = new Date();
        var startDateFrom = new Date(currentTime.getFullYear(),currentTime.getMonth(),1);
        var startDateTo = new Date(currentTime.getFullYear(),currentTime.getMonth() +1,0);
        var dateToday = new Date(); 

        $(".assign_date").datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: startDateFrom,
            maxDate: startDateTo,
            changeMonth: false,
            changeYear: false,
            stepMonths: false,
            minDate: dateToday
        });

        //Remove Notice
        // $('.remove-notice').click(function(){
        //     $('.taskpress-notice-text').removeClass('notice');
        // });
    });
    </script>
    <script>
        jQuery(document).ready(function($){
            //Ajax Add Task
            $( '#addTaskpressTask' ).on( 'submit', function() {
                var form_data = jQuery( this ).serializeArray();
                
                // Here we add our nonce.
                form_data.push( { "name" : "security", "value" : taskpress_ajax_nonce } );

                $.ajax({
                    url : '<?php echo admin_url( "admin-ajax.php" ); ?>',
                    type : 'post',
                    data : form_data,
                    beforeSend: function () {
                        $('#addTaskpressTask button[type=submit]').addClass('btn-loading');
                    },
                    success : function( response ) {
                        var jsonResponse = $.parseJSON(response);
                        
                        //Output response
                        if(jsonResponse.type == 'error'){
                            $('.taskpress-notice-text').removeClass('notice-success notice'); //remove notice class
                            $('#formResponseMsg .notice-text').html(jsonResponse.text); //add response message
                            $('.taskpress-notice-text').addClass('notice-error notice'); //add notice class

                            //generate already added task list
                            if(!jsonResponse.invalid_assign_date){
                                $('#taskList').html(jsonResponse.tasks);
                                $('#taskListAddedDate').html(jsonResponse.assign_date);
                                $('.task-added-list').show();
                            }
                        }else{
                            $('.taskpress-notice-text').removeClass('notice-error notice'); //remove notice class
                            $('#formResponseMsg .notice-text').html(jsonResponse.text); //add response message
                            $('.taskpress-notice-text').addClass('notice-success notice'); //add notice class
                            $('[name="task"]').val(''); //empty task input field
                            
                            //generate already added task list
                            $('#taskList').html(jsonResponse.tasks);
                            $('#taskListAddedDate').html(jsonResponse.assign_date);
                            $('.task-added-list').show();
                        }
                    },
                    fail : function( err ) {
                        $('.taskpress-notice-text').removeClass('notice-success notice'); //remove notice class
                        $('#formResponseMsg .notice-text').html('Something went wrong! Please try again later.'); //add response message
                        $('.taskpress-notice-text').addClass('notice-error notice'); //add notice class
                    },
                    complete: function () {
                        $('#addTaskpressTask button[type=submit]').removeClass('btn-loading');
                    },
                });
                
                // This return prevents the submit event to refresh the page.
                return false;
            });
        });
    </script>