<?php
/**
 * Task list by user & date
 */
?>
<div class="wrap">
    <div class="task-title-wrap">
        <h1 class="wp-heading-inline">Takenlijst</h1>
    </div>
    <div class="taskpress-nav">
        <ul>
                <li><a href="admin.php?page=taskpress_tasks">Taakbeheer</a></li>
                <li class="active"><a href="admin.php?page=taskpress_task_list">Takenlijst</a></li>
                <li><a href="admin.php?page=taskpress_add_task">Taak toevoegen</a></li>
                <li><a href="admin.php?page=taskpress_task_progress">Taak Vooruitgang</a></li>
                <li><a href="admin.php?page=taskpress_my_tasks">Mijn Taken</a></li>
        </ul>
    </div>
    <div id="alertMsgTable" class="taskpress-notice">
        <div class="taskpress-notice-text"><p class="notice-text"></p><span class="dashicons dashicons-no-alt remove-notice"></span></div>
    </div>
    <div class="ajax-loader"></div>
    <div id="taskListTable" class="taskpress-list-table taskpress-d-none"></div>
</div>
<div class="modal update-task-modal">
    <div id="formEditResponseMsg" class="taskpress-notice">
        <div class="taskpress-notice-text margin-0"><p class="notice-text"></p><span class="dashicons dashicons-no-alt remove-notice"></span></div>
    </div>
    <div class="taskpress-form">
        <form id="editTaskpressTask" action="" method="post">
            <div class="task-form-group">
                <label for="update_task">Taak Details <span class="req">*</span> </label>
                <textarea class="large-text code" name="task" id="update_task" cols="30" rows="5" placeholder="Please enter task details here"></textarea>
            </div>
            <!-- <div class="task-form-group">
                <label for="update_assign_date">Selecteer Datum toewijzen <span class="req">*</span> </label>
                <input name="assign_date" type="text" id="update_assign_date" class="update_assign_date regular-text" autocomplete="off" placeholder="Select assign date">
            </div> -->
            <div class="task-form-group">
                <input type="hidden" name="action" value="taskpress_update_task">
                <input type="hidden" name="task_id">
                <button type="submit" id="submit" class="button button-primary taskpress-btn"><span>Taak bijwerken</span></button>
            </div>
        </form>
    </div>
</div>
<script>
function loadAllTask(){
    jQuery(document).ready(function($){
        //Ajax Get all Task
        var form_data = jQuery( this ).serializeArray();

        $.ajax({
            url : '<?php echo admin_url( "admin-ajax.php" ); ?>',
            type : 'post',
            data : {
                action: 'taskpress_all_task',
                security: taskpress_ajax_nonce
            },
            beforeSend: function () {
                $('.taskpress-d-none').fadeOut();
                $('.ajax-loader').addClass('btn-loading');
            },
            success : function( response ) {
                $('#formResponseMsg .taskpress-notice-text').removeClass('notice-success notice'); //remove notice class
                $('#taskListTable').html(response);
                $('.taskpress-d-none').fadeIn();
            },
            fail : function( err ) {
                $('.taskpress-notice-text').removeClass('notice-success notice'); //remove notice class
                $('#formResponseMsg .notice-text').html('Something went wrong! Please try again later.'); //add response message
                $('.taskpress-notice-text').addClass('notice-error notice'); //add notice class
            },
            complete: function () {
                $('.ajax-loader').removeClass('btn-loading');
            },
        });
        
        // This return prevents the submit event to refresh the page.
        return false;
    });
}
loadAllTask(); //load all task
</script>
<script>
    jQuery(document).ready(function($){
        //Ajax Add Task
        $( '#editTaskpressTask' ).on( 'submit', function() {
            var form_data = jQuery( this ).serializeArray();
            
            // Here we add our nonce.
            form_data.push( { "name" : "security", "value" : taskpress_ajax_nonce } );

            $.ajax({
                url : '<?php echo admin_url( "admin-ajax.php" ); ?>',
                type : 'post',
                data : form_data,
                beforeSend: function () {
                    $('#editTaskpressTask button[type=submit]').addClass('btn-loading');
                },
                success : function( response ) {
                    var jsonResponse = $.parseJSON(response);
                    
                    //Output response
                    if(jsonResponse.type == 'error'){
                        $('#formEditResponseMsg .taskpress-notice-text').removeClass('notice-success notice'); //remove notice class
                        $('#formEditResponseMsg .notice-text').html(jsonResponse.text); //add response message
                        $('#formEditResponseMsg .taskpress-notice-text').addClass('notice-error notice'); //add notice class
                    }else{
                        $('#alertMsgTable .taskpress-notice-text').removeClass('notice-error notice'); //remove notice class
                        $('#alertMsgTable .notice-text').html(jsonResponse.text); //add response message
                        $('#alertMsgTable .taskpress-notice-text').addClass('notice-success notice'); //add notice class
                        loadAllTask();
                        $.modal.close();
                    }
                },
                fail : function( err ) {
                    $('#formEditResponseMsg .taskpress-notice-text').removeClass('notice-success notice'); //remove notice class
                    $('#formEditResponseMsg .notice-text').html('Something went wrong! Please try again later.'); //add response message
                    $('#formEditResponseMsg .taskpress-notice-text').addClass('notice-error notice'); //add notice class
                },
                complete: function () {
                    $('#editTaskpressTask button[type=submit]').removeClass('btn-loading');
                },
            });
            
            // This return prevents the submit event to refresh the page.
            return false;
        });
    });
</script>