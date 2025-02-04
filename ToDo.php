<?php include 'inc/header.php'; ?>
<?php
$subject=$description='';
$is_active=0;
if(isset($_POST['submit'])){
    if(!empty($_POST['subject'])){

        $subject=filter_input(
            INPUT_POST,
            'subject',
            FILTER_SANITIZE_FULL_SPECIAL_CHARS
        );
    }
    if(!empty($_POST['description'])){

        $description=filter_input(
            INPUT_POST,
            'description',
            FILTER_SANITIZE_FULL_SPECIAL_CHARS
        );
    }
    if(!empty($_POST['is_active'])){

        if(isset($_POST['is_active'])){
            $is_active=1;
        }
    }
    $sql="INSERT INTO todo (subject,description,is_active) VALUES ('$subject','$description','$is_active')";
    if(mysqli_query($conn,$sql)){
        header('Location: ToDo.php');
    }else{
        echo 'ERROR:' .  mysqli_error($conn);
    }
}

?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
    <!-- رابط Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h2>Task Management</h2>
            </div>
            <div class="card-body">
                <!-- Form to Add a New Task -->
                <form method="POST" action="<?php echo htmlspecialchars(
      $_SERVER['PHP_SELF']
    ); ?>" class="mb-4" >
                    <div class="mb-3">
                        <label for="subject" class="form-label" value="<?php echo $subject; ?>">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter subject">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter description"><?php echo $description; ?></textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1">
                        <label class="form-check-label" for="is_active">Is Active</label>
                    </div>
                    <input type="submit" name="submit" value="Add Task" class="btn btn-primary">
                    
                </form>

                <!-- Display Tasks -->
                    <!-- git information from database -->
                            <?php
                                $sql='SELECT * FROM todo';
                                $result = mysqli_query($conn, $sql);
                                $todo = mysqli_fetch_all($result, MYSQLI_ASSOC);
                            ?>



                        <h4>Task List</h4>
                            <?php if (empty($feedback)): ?>
                                <p class="lead mt-3">There is no Tasks</p>
                            <?php endif; ?>
                        <ul class="list-group">

                                

                            <?php foreach ($todo as $task ): ?>

                                <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5><?php echo $task['subject']; ?></h5>
                                        <p class="mb-0"><?php echo $task['description'] ?></p>
                                    </div>
                                    <div>
                                        <?php if($task['is_active']==1){
                                            echo '<small class="text-muted">Status: <span class="text-success">Active</span></small>';
                                        } else{
                                            echo '<small class="text-muted">Status: <span class="text-danger">Inactive</span></small>';
                                        }
                                        ?>
                                        
                                    </div>
                                </div>
                            </li>

                            <?php endforeach;?>

                        </ul>
            </div>
        </div>
    </div>

    <!-- رابط Bootstrap JS (اختياري إذا كنت تريد استخدام مكونات تفاعلية) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
