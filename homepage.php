<?php
session_start();
include("connect.php");

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>BookMart</title>
</head>
<body>

    <div class="container">
        <div>
            <img src="images/logo 1.png" alt="BookMart Logo" class="logo">
        </div>
        <nav>
            <ul>
                <li><a href="#"><span style="color: yellow;">Home</span></a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Books</a></li>
      <li>  
    <?php 
       
       if(isset($_SESSION['email'])){
        $email=$_SESSION['email'];
        $query=mysqli_query($conn, "SELECT users.* FROM `users` WHERE users.email='$email'");
        while($row=mysqli_fetch_array($query)){
            echo $row['firstName'].' '.$row['lastName'];
        }
       }
       ?>
    </li>
      <a href="logout.php"><span style="color:yellow;">Logout</span></a>
            </ul>
        </nav>
    </div>
    <main >
    <div class="upload-img"></div>

    <h2>Upload an Image</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="fileToUpload">Select image to upload:</label>
        <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*">
        <input type="submit" value="Upload Image" name="submit">
    </form>
    </main>

</body>
</html>