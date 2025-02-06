<?php include 'inc/header.php'; ?>
<?php 
$name=$birth_date=$Target_dir= $File_Temp='';
$allowed_ext= array('png','jpg','jpeg','gif');
if(isset($_POST['submit'])){

    if(!empty($_POST['name'])){
        $name=filter_input(
            INPUT_POST,
            'name',
            FILTER_SANITIZE_FULL_SPECIAL_CHARS
        );
    }
    if(!empty($_POST['birthdate'])){
        $birth_date=date('Y,m,d',strtotime($_POST['birthdate']));
    }
    if(!empty($_FILES['img']['name'])){
        // print_r($_FILES);
        $File_Name=$_FILES['img']['name'];
        $File_Temp=$_FILES['img']['tmp_name'];
        $File_Type=$_FILES['img']['type'];
        $Target_dir="upload_img/{$File_Name}";
        // git file ext
        $File_ext=explode('.',$File_Name);
         $File_ext=strtolower(end($File_ext));
        
    }
    
    $sql="INSERT INTO users (name,img_dir,date_of_birth) VALUES ('$name',' $Target_dir','$birth_date')";
    if(in_array($File_ext,$allowed_ext)){
        
        if(mysqli_query($conn,$sql)){

                            
            if (move_uploaded_file($File_Temp, $Target_dir)) {
                header('Location: Users.php');
                exit();
            } else {
                echo '<p style="color:red;">Error uploading file.</p>';
            }
                  
        }else{
    
            echo 'ERROR:' .  mysqli_error($conn);
        }
               
        
    }else{
        $message='<p style="color: red;">Invalid file Type</p>';
    }



  

}

 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php echo $message??null;?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">User Information Form</h2>

                        <!-- Form to collect user data -->
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data" >
                            <!-- Name field -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" value="<?php echo $name?>" name="name" placeholder="Enter your name" required>
                            </div>

                            <!-- Profile Image field -->
                            <div class="mb-3">
                                <label for="img" class="form-label">Profile Image</label>
                                <input type="file" class="form-control" id="img" name="img" required>
                            </div>

                            <!-- Birthdate field -->
                            <div class="mb-3">
                                <label for="birthdate" class="form-label">Birthdate</label>
                                <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                            </div>

                            <!-- Submit button -->
                            <div class="d-grid">
                            <input type="submit" name="submit" value="Add" class="btn btn-primary">
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


                <!-- git  data from database -->
                <?php 
                    $sql='SELECT * FROM users';
                   $result=mysqli_query($conn,$sql);
                    $users=mysqli_fetch_all($result,MYSQLI_ASSOC);
                ?>

                <?php if (empty($users)): ?>
                    <p class="row justify-content-center mt-5" >There is no Tasks</p>
                <?php endif; ?>

                    <?php foreach($users as $card): ?>

                        <div class="row justify-content-center mt-5">
                        <div class="col-md-6">

                            <!-- Profile Image Card -->
                            <div class="card profile-card shadow-lg mb-4" style="border-radius: 20px; overflow: hidden; background: #ffffff; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                <div class="card-body d-flex align-items-center p-4">

                                    <!-- Profile Image - Left (Square) -->
                                    <div class="flex-shrink-0" style="flex-basis: 25%;">
                                        <img src="<?php echo $card['img_dir']; ?>" alt="Profile Image" class="border w-100" style="aspect-ratio: 1/1; object-fit: cover; border-radius: 10px;">
                                    </div>

                                    <!-- User Info - Right -->
                                    <div class="ms-4" style="flex-basis: 75%; display: flex; flex-direction: column; justify-content: center;">
                                        <!-- Name and Value -->
                                        <div class="d-flex align-items-center mb-3">
                                            <label class="text-muted me-3" style="font-size: 1.2rem;">Name:</label>
                                            <p class="mb-0 text-primary" style="font-size: 1.5rem; font-weight: bold;"><?php echo $card['name']; ?></p>
                                        </div>

                                        <!-- Birthdate and Value -->
                                        <div class="d-flex align-items-center mb-3">
                                            <label class="text-muted me-3" style="font-size: 1.2rem;">Birthdate:</label>
                                            <p class="mb-0 text-success" style="font-size: 1.5rem; font-weight: bold;"><?php echo $card['date_of_birth']; ?></p>
                                        </div>

                                        <!-- Edit and Delete Links -->
                                        <div class="d-flex justify-content-end mt-3">
                                            <!-- Edit Link -->
                                            <a href="updateUsers.php?id=<?php echo $card['id']; ?>" class="btn btn-warning me-2">Edit</a>

                                            <!-- Delete Link -->
                                            <a href="deleteUser.php?id=<?php echo $card['id']; ?>" class="btn btn-danger">Delete</a>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Add this CSS inside a style tag or in your CSS file -->
                    <style>
                        .profile-card:hover {
                            transform: translateY(-10px); /* Move up slightly on hover */
                            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15); /* Add a stronger shadow */
                        }

                        .profile-card img {
                            transition: transform 0.3s ease; /* Smooth transition for image */
                        }

                        .profile-card:hover img {
                            transform: scale(1.05); /* Slightly enlarge image on hover */
                        }

                        .btn {
                            border-radius: 10px;
                        }
                    </style>




                    <?php endforeach;?>



    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

