<div class="wrap taskpress-dashboard">
<div class="task-title-wrap">
   <h1 class="wp-heading-inline">Taakbeheer-dashboard</h1>
</div>
<div class="taskpress-nav">
   <ul>
         <li class="active"><a href="admin.php?page=taskpress_tasks">Taakbeheer</a></li>
         <?php if(current_user_can('administrator')){ ?>
         <li><a href="admin.php?page=taskpress_task_list">Takenlijst</a></li>
         <li><a href="admin.php?page=taskpress_add_task">Taak toevoegen</a></li>
         <li><a href="admin.php?page=taskpress_task_progress">Taak toevoegen</a></li>
         <?php } ?>
         <li><a href="admin.php?page=taskpress_my_tasks">Mijn Taken</a></li>
   </ul>
</div>
<?php
global $wpdb;
$taskpress_task_table_name = $wpdb->prefix.'taskpress_tasks';
$taskpress_completed_taks_tbl = $wpdb->prefix.'taskpress_completed_tasks';

//get total tasks
$taskpress_all_tasks = $wpdb->get_results("SELECT id FROM $taskpress_task_table_name");
$taskpress_total_tasks = count($taskpress_all_tasks);

//get total today tasks
$taskpress_today_date = date("Y-m-d");
$taskpress_today_tasks = $wpdb->get_results("SELECT id FROM $taskpress_task_table_name");
$taskpress_total_today_task = count($taskpress_today_tasks);

//count registered user
$taskpress_user_count = count_users();

//get loggedin user total completed tasks
$loggedin_user_id = get_current_user_id();
$taskpress_completed_task = $wpdb->get_results("SELECT task_id FROM $taskpress_completed_taks_tbl WHERE user_id = $loggedin_user_id AND status = 1");

?>
<div id="dashboard-widgets" class="metabox-holder">
   <div id="postbox-container-1" class="postbox-container">
      <div id="normal-sortables" class="meta-box-sortables ui-sortable">
         <div id="dashboard_right_now" class="postbox">
            <div class="postbox-header">
               <h2 class="hndle ui-sortable-handle">In een oogopslag</h2>
            </div>
            <div class="inside">
               <div class="main">
                  <ul class="icon-list-taskpress">
                     <?php if(current_user_can('administrator')){ ?>
                     <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                           <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                           <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
                           <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                        </svg>
                        <?php echo $taskpress_user_count['total_users']; ?> Users
                     </li>
                     <!-- <li class="page-count">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                        <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                        <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/>
                        </svg>
                        <?php //echo $taskpress_total_today_task; ?> Tasks Today
                     </li> -->
                     <li class="post-count">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hdd-rack" viewBox="0 0 16 16">
                           <path d="M4.5 5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1zM3 4.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm2 7a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm-2.5.5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                           <path d="M2 2a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h1v2H2a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-1a2 2 0 0 0-2-2h-1V7h1a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2zm13 2v1a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1zm0 7v1a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1zm-3-4v2H4V7h8z"/>
                        </svg>
                        <?php echo $taskpress_total_tasks; ?> Total Tasks
                     </li>
                     <?php }else{ ?>
                     <li class="post-count">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hdd-rack" viewBox="0 0 16 16">
                           <path d="M4.5 5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1zM3 4.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm2 7a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm-2.5.5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                           <path d="M2 2a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h1v2H2a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-1a2 2 0 0 0-2-2h-1V7h1a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2zm13 2v1a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1zm0 7v1a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1zm-3-4v2H4V7h8z"/>
                        </svg>
                        <?php echo $taskpress_total_today_task; ?> Total Tasks
                     </li>
                     <li class="page-count">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                        <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                        <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/>
                        </svg>
                        <?php echo count($taskpress_completed_task); ?> Tasks Completed
                     </li>
                     <li class="post-count">
                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-x" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M6.146 7.146a.5.5 0 0 1 .708 0L8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 0 1 0-.708z"/>
                        <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                        <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                     </svg>
                     <?php echo ($taskpress_total_today_task - count($taskpress_completed_task)); ?> Tasks Uncomplete
                     </li>
                     <?php } ?>
                  </ul>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div id="postbox-container-2" class="postbox-container">
      <div id="normal-sortables" class="meta-box-sortables ui-sortable">
         <div id="dashboard_right_now" class="postbox">
            <div class="postbox-header">
               <h2 class="hndle ui-sortable-handle">Snelle links</h2>
            </div>
            <div class="inside">
               <div class="main">
                  <ul>
                     <?php if(current_user_can('administrator')){ ?>
                     <li class="page-count"><a href="admin.php?page=taskpress_add_task">Taak toevoegen</a></li>
                     <li class="page-count"><a href="admin.php?page=taskpress_task_list">Takenlijst</a></li>
                     <li class="page-count"><a href="admin.php?page=taskpress_task_progress">Taak Vooruitgang</a></li>
                     <?php } ?>
                     <li class="page-count"><a href="admin.php?page=taskpress_my_tasks">Mijn taak</a></li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div id="postbox-container-3" class="postbox-container">
      <div id="normal-sortables" class="meta-box-sortables ui-sortable">

      </div>
   </div>
   <div id="postbox-container-4" class="postbox-container">
      <div id="normal-sortables" class="meta-box-sortables ui-sortable">

      </div>
   </div>
</div>
</div>