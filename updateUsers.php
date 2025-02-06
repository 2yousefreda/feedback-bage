<?php include 'inc/header.php'; ?>
 <?php
$id=$name=$birth_date=$Target_dir= $File_Temp='';
$allowed_ext= array('png','jpg','jpeg','gif');
if($_SERVER['REQUEST_METHOD']=='GET'){
    //GET method :show the ddate of the client
    if(!isset($_GET["id"])){
        header("Location: /Users.php");
        exit;
    }
    $id=$_GET["id"];
    //read the row of the selected client form database table
    $sql="SELECT * FROM users WHERE id=$id";
    $result=mysqli_query($conn,$sql);
    $row=mysqli_fetch_assoc($result);
    if(!$row){
        header("Location: Users.php");
        exit;
    }
    // print_r($row);
    $id=$row["id"];
    $name=$row["name"];
    $birth_date=(string)$row["date_of_birth"];
}else{
    $idnew='';
    if(isset($_POST['Update_Users'])){
        if(isset($_GET["id_new"])){
            $idnew=$_GET["id_new"];
        }
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
       
        $sql = "UPDATE users SET name ='$name', img_dir='$Target_dir', date_of_birth='$birth_date' WHERE id=$idnew";
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
}
?>
         <?php echo $message??null;?>
             <div class="container mt-5">
                 <div class="row justify-content-center">
                     <div class="col-md-6">
                         <div class="card">
                             <div class="card-body">
                                 <h2 class="card-title text-center mb-4">User Information Form</h2>

                                 <!-- Form to collect user data -->
                                 <form action="updateUsers.php?id_new=<?php echo $id ?>" method="POST" enctype="multipart/form-data" >
                                   

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
                                         <input type="date" class="form-control"  id="birthdate" name="birthdate" required>
                                     </div>

                                     <!-- Submit button -->
                                     <div class="d-grid">
                                     <input type="submit" name="Update_Users" value="Update" class="btn btn-primary">
                                        
                                     </div>
                                 </form>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>


