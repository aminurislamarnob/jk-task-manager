<?php
/**
 * Trigger on plugin active
 */

//Includes db table
require TASKPRESS_DIR . 'includes/admin/plugin-func/taskpress-db.php';
taskpress_task_table();
taskpress_completed_tasks_table();