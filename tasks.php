<?php
include_once "config.php";

// connecting to the database.
$connection = mysqli_connect($host, $user, $password, $database);
if(!$connection){
    echo "Cannot Connect to the Database";
}else{
    $action = $_POST['action'] ? $_POST['action'] : '';
    if(!$action){
        header('Location: index.php');
        die();
    }elseif($action == 'add'){

        // query for adding a new task
        $task = $_POST['task'];
        $date = $_POST['date'];

        if($task && $date){
            $query = "INSERT INTO ".DB_TABLE."(task,date) VALUES ('$task', '$date')";
            mysqli_query($connection, $query);
            header('Location: index.php?added=true');

        }
    }
    // query for making a task as completed.
    elseif ($action == 'complete'){
        $taskid = $_POST['taskid'];
        if ($taskid){
            $query = "UPDATE tasks SET complete = 1 WHERE id = {$taskid} LIMIT 1";
            mysqli_query($connection,$query);
        }
        header('Location: index.php');
    }
    // query for deleting a task.
    elseif ($action == 'delete'){
        $taskid = $_POST['taskid'];
        if ($taskid){
            $query = "DELETE FROM tasks WHERE id = {$taskid} LIMIT 1";
            mysqli_query($connection,$query);
        }
        header('Location: index.php');
    }
    // query for making incomplete a task.
    elseif ($action == 'incomplete'){
        $taskid = $_POST['taskid'];
        if ($taskid){
            $query = "UPDATE tasks SET complete = 0 WHERE id = {$taskid} LIMIT 1";
            mysqli_query($connection,$query);
        }
        header('Location: index.php');
    }
    // query for making multiple tasks as incomplete.
    elseif ($action == 'bulkincomplete'){
        $taskids = $_POST['taskids'];
        $_taskids = join(",", $taskids);
        if ($taskids){
            $query = "UPDATE tasks SET complete = 0 WHERE id IN ($_taskids)";
            mysqli_query($connection,$query);
        }
        header('Location: index.php');
    }
    // query for making multiple tasks as complete.
    elseif ($action == 'bulkcomplete'){
        $taskids = $_POST['taskids'];
        $_taskids = join(",", $taskids);
        if ($taskids){
            $query = "UPDATE tasks SET complete = 1 WHERE id IN ($_taskids)";
            mysqli_query($connection,$query);
        }
        header('Location: index.php');
    }
    // query for deleting multiple tasks.
    elseif ($action == 'bulkdelete'){
        $taskids = $_POST['taskids'];
        $_taskids = join(",", $taskids);
        if ($taskids){
            $query = "DELETE FROM tasks WHERE id IN ($_taskids)";
            mysqli_query($connection,$query);
        }
        header('Location: index.php');
    }
}
