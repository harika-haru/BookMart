<?php
session_start();
include("connect.php"); // Include the connection script

// Handle the file upload and save details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "uploads/";

    // Ensure the uploads directory exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $original_filename = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $original_filename;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo json_encode(["success" => false, "message" => "File is not an image."]);
        $uploadOk = 0;
    }

    // Check if file already exists, and if it does, rename it
    if (file_exists($target_file)) {
        $i = 1;
        $new_target_file = $target_dir . pathinfo($original_filename, PATHINFO_FILENAME) . "_$i." . $imageFileType;
        while (file_exists($new_target_file)) {
            $i++;
            $new_target_file = $target_dir . pathinfo($original_filename, PATHINFO_FILENAME) . "_$i." . $imageFileType;
        }
        $target_file = $new_target_file;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) { // 500 KB
        echo json_encode(["success" => false, "message" => "Sorry, your file is too large."]);
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo json_encode(["success" => false, "message" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed."]);
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo json_encode(["success" => false, "message" => "Sorry, your file was not uploaded."]);
    // If everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $filename = htmlspecialchars($_POST['filename']);
            $edition = htmlspecialchars($_POST['edition']);
            $filepath = $target_file;
            $stmt = $conn->prepare("INSERT INTO users (filename, filepath, edition, uploaded_at) VALUES (?, ?, ?, NOW())");

            // Check if prepare() failed
            if ($stmt === false) {
                echo json_encode(["success" => false, "message" => 'Prepare failed: ' . htmlspecialchars($conn->error)]);
                die();
            }

            $stmt->bind_param("sss", $filename, $filepath, $edition);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "File information saved to the database.", "filename" => $filename, "edition" => $edition, "filepath" => $filepath]);
            } else {
                echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
            }

            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "Sorry, there was an error uploading your file."]);
        }
    }

    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
    <title>BookMart</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   
</head>
<body>
<div class="container">
        <div>
            <img src="images/logo 1.png" alt="BookMart Logo" class="logo">
        </div>
        <nav>
            <ul>
                <li><a href="index.html"><span style="color: yellow;">Home</span></a></li>
                <li><a href="#About">About</a></li>
                <li><a href="#ExploreMore">Books</a></li>
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


    <div class="form-container">
        <h2>Book Information Form</h2>
        <form id="bookForm" method="POST" action="upload.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="bookName">Book Name:</label>
            <input type="text" id="bookName" name="bookName" required>
            
            <label for="edition">Edition:</label>
            <input type="text" id="edition" name="edition" required>
            
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" required>
            
            <button type="submit">Submit</button>
        </form>
    </div><br> <br>
    <section>
        <div class="container-4 fade-in">
            <h1><b>Explore More</b></h1>
            <div class="yellow-line" style="margin: 5px 580px; height: 5px; "></div>
            <div>
                <div class="books ">
                    <div class="book1 fade-in">
                        <img src="images/book1.png" alt="Book 1" class="book-img">
                        <span class="book-title">A Million To One</span>
                        <span class="order-now">Explore More</span>
                    </div>
                    <div class="book1 fade-in">
                        <img src="images/book2.png" alt="Book 2" class="book-img">
                        <span class="book-title">Soul</span>
                        <span class="order-now">Explore More</span>
                    </div>
                    <div class="book1 fade-in">
                        <img src="images/book3.png" alt="Book 3" class="book-img">
                        <span class="book-title">Sword</span>
                        <span class="order-now">Explore More</span>
                    </div>
                    <div class="book1 fade-in">
                        <img src="images/book4.png" alt="Book 3" class="book-img">
                        <span class="book-title">The Past Is Rising</span>
                        <span class="order-now">Explore More</span>
                    </div>
                </div>
                <div class="books">
                    <div class="book1 fade-in">
                        <img src="images/book1.png" alt="Book 1" class="book-img">
                        <span class="book-title">Book Name</span>
                        <span class="order-now">Explore More</span>
                    </div>
                    <div class="book1 fade-in">
                        <img src="images/book2.png" alt="Book 2" class="book-img">
                        <span class="book-title">Book Name</span>
                        <span class="order-now">Explore More</span>
                    </div>
                    <div class="book1 fade-in">
                        <img src="images/book3.png" alt="Book 3" class="book-img">
                        <span class="book-title">Book Name</span>
                        <span class="order-now">Explore More</span>
                    </div>
                    <div class="book1 fade-in">
                        <img src="images/book4.png" alt="Book 3" class="book-img">
                        <span class="book-title">Book Name</span>
                        <span class="order-now">Explore More</span>
                    </div>
                </div>
        </div>
    </section>
<style>

.form-container {
    color: black;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 1000px;
    margin-left: 250px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"], input[type="number"] {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #5cb85c;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: #4cae4c;
}
</style>
    <script>
   document.getElementById('bookForm').addEventListener('submit', function(event) {
    // Example client-side validation (could be expanded as needed)
    const username = document.getElementById('username').value;
    const bookName = document.getElementById('bookName').value;
    const edition = document.getElementById('edition').value;
    const price = document.getElementById('price').value;

    if (!username || !bookName || !edition || !price) {
        alert('Please fill out all fields.');
        event.preventDefault(); // Prevent form submission
    }
});
</script>
</body>
</html>

