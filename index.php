<?php
// including the config file.
include_once "config.php";

// creating connection with the database.
$connection = mysqli_connect($host, $user, $password, $database);
if(!$connection){
    echo "Cannot Connect to the Database";
}else{
    // query for seeing all incomplete tasks.
    $query = "SELECT * FROM tasks WHERE complete = 0 ORDER BY id";
    $result = mysqli_query($connection,$query);

    // query for seeing all completed tasks.
    $Completequery = "SELECT * FROM tasks WHERE complete = 1 ORDER BY id";
    $Completeresult = mysqli_query($connection,$Completequery);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tasks Project</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">

    <style>
        #main {
            padding: 50px 150px 50px 150px;
            font-size: 14px;
        }
        #action {
            width: 150px;
        }
    </style>
</head>
<body>
<div class="container" id="main">
    <h1>Tasks Manager</h1>
    <p>This is a sample Project for managing our Daily tasks. and we will use many things here</p>

    <?php
    // all completed tasks.
    if(mysqli_num_rows($Completeresult)>0){
    ?>
    <h4>Completed Tasks</h4>
        <form action="tasks.php" method="post">
    <table>
        <thead>
        <tr>
            <th></th>
            <th>Id</th>
            <th>Task</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($cdata = mysqli_fetch_assoc($Completeresult)){
            // getting the date.
            $timestamp = strtotime($cdata['date']);
            $cdate = date("jS M, Y", $timestamp);
            ?>
            <tr>
                <td><input type="checkbox" name="taskids[]" value="<?php echo $cdata['id']?>"></td>
                <td><?php echo $cdata['id']?></td>
                <td><?php echo $cdata['task']?></td>
                <td><?php echo $cdate?></td>
                <td><a href="#" class="delete" data-taskid="<?php echo $cdata['id']?>">Delete</a> | <a href="#" class="incomplete" data-taskid="<?php echo $cdata['id']?>">Mark Incomplete</a></td>
            </tr>

            <?php
        }
        ?>
        </tbody>
    </table>
        <p>......</p>
        <?php
        }
        ?>

    <?php
    if(mysqli_num_rows($result)==0){
        echo "No Upcomming Tasks Found";
    }else{

        // Showing the incomplete tasks.
    ?>
        <h4>Upcomming Tasks</h4>
    <table>
        <thead>
        <tr>
            <th></th>
            <th>Id</th>
            <th>Task</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        </thead>
       <tbody>
       <?php
       while ($data = mysqli_fetch_assoc($result)){
           $timestamp = strtotime($data['date']);
           $date = date("jS M, Y", $timestamp);
       ?>
       <tr>
           <td><input type="checkbox" name="taskids[]" value="<?php echo $data['id']?>"></td>
           <td><?php echo $data['id']?></td>
           <td><?php echo $data['task']?></td>
           <td><?php echo $date?></td>
           <td><a href="#" class="delete" data-taskid="<?php echo $data['id']?>">Delete</a> | <a href="#" class="complete" data-taskid="<?php echo $data['id']?>">Complete</a></td>
       </tr>
       <?php
       }
           ?>
       </tbody>
    </table>
        <select id="action" name="action">
            <option value="0">With Selected</option>
            <option value="bulkdelete">Delete</option>
            <option value="bulkcomplete">Mark as Complete</option>
            <option value="bulkincomplete">Mark as Incomplete</option>
        </select>
        <input type="submit" value="submit" class="button-primary" id="bulksubmit">
        </form>
    <?php
    }
        ?>
    <p>.....</p>
    <?php
    //new task adding result.
        $added = $_GET['added'];
        if($added){
            echo "Task Successfully Added";
        }
    ?>

    <!-- New task adding form-->
    <h4>Add Tasks</h4>
    <form action="tasks.php" method="post">
        <label for="details">Task</label>
        <input type="text" id="details" placeholder="Task Details" name="task">

        <label for="date">Date</label>
        <input type="text" id="date" placeholder="Task Date" name="date">
        <input type="submit" value="add task" class="button-primary">
        <input type="hidden" value="add" name="action">
    </form>
</div>

<!-- form for making a task as completed-->
<form action="tasks.php" method="post" id="completeform">
    <input type="hidden" value="complete" name="action">
    <input type="hidden" id="taskid" name="taskid">
</form>

<!-- form for deleting a task-->
<form action="tasks.php" method="post" id="deleteform">
    <input type="hidden" value="delete" name="action">
    <input type="hidden" id="dtaskid" name="taskid">
</form>

<!-- form for making a task as incomplete-->
<form action="tasks.php" method="post" id="incompleteform">
    <input type="hidden" value="incomplete" name="action">
    <input type="hidden" id="itaskid" name="taskid">
</form>

</body>
<!-- jQuery and scripts-->
<script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"></script>
<script>
    ;(function($){
        $(document).ready(function(){
            $(".complete").on('click',function(){
               var id = $(this).data('taskid');
               $("#taskid").val(id);
               $("#completeform").submit();
            });
            $(".delete").on('click',function(){
                if(confirm("Are You Surely want to Delete the Task?")){
                var id = $(this).data('taskid');
                $("#dtaskid").val(id);
                $("#deleteform").submit();
                }
            });
            $(".incomplete").on('click',function(){
                var id = $(this).data('taskid');
                $("#itaskid").val(id);
                $("#incompleteform").submit();
            });
            $("#bulksubmit").on('click',function(){
                if($("#action").val()=='bulkdelete'){
                    if(!confirm("Are you Really wants to Delete Multiple Tasks?")){
                        return false;
                    }
                }
            });
        });
    })(jQuery);
</script>
</html>