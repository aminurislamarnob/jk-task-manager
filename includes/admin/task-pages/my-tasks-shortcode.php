<div class="my-task-wrapper">
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