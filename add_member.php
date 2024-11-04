<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['member_details'])) {
    $name = $_POST['name'];
    $fname = $_POST['fname'];
    $designation = $_POST['designation'];
    $address = $_POST['address'];
    $number = $_POST['number'];

    // Handle the image upload
    $target_dir = "images/";
    $image = $_FILES['image']['name'];
    $target_file = $target_dir . basename($image);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    $check = getimagesize($_FILES['image']['tmp_name']);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<div class='alert alert-danger'>File is not an image.</div>";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "<div class='alert alert-danger'>Sorry, file already exists.</div>";
        $uploadOk = 0;
    }

    // Check file size (limit is 5MB)
    if ($_FILES['image']['size'] > 5000000) {
        echo "<div class='alert alert-danger'>Sorry, your file is too large.</div>";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "<div class='alert alert-danger'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<div class='alert alert-danger'>Sorry, your file was not uploaded.</div>";
    // If everything is ok, try to upload the file
    } else {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Insert the form data and image file name into the database
            $sql = "INSERT INTO member (name, fname, designation, address, number, image) 
                    VALUES ('$name', '$fname', '$designation', '$address', '$number', '$image')";

            if ($conn->query($sql) === TRUE) {
                // Redirect to welcome.php after successful insertion
                header("Location: welcome.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- bootstrap link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" />
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <div class="container mt-3">
        <h2 class="text-center">Add Member</h2>
        <!-- form -->
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-outline mb-4">
                <label for="name" class="form-label">Name</label>
                <input class="w-100" type="text" name="name" id="name" class="form-control" placeholder="Enter Member Name" autocomplete="off" required="required">
            </div>

            <div class="form-outline mb-4">
                <label for="fname" class="form-label">Father's Name</label>
                <input class="w-100" type="text" name="fname" id="fname" class="form-control" placeholder="Enter Member Father's Name" autocomplete="off" required="required">
            </div>

            <div class="form-outline mb-4">
                <label for="designation" class="form-label">Designation</label>
                <input class="w-100" type="text" name="designation" id="designation" class="form-control" placeholder="Enter Member Designation" autocomplete="off" required="required">
            </div>

            <div class="form-outline mb-4">
                <label for="address" class="form-label">Address</label>
                <textarea class="w-100" name="address" id="address" placeholder="Enter Member Address" required="required"></textarea>
            </div>

            <div class="form-outline mb-4">
                <label for="number" class="form-label">Number</label>
                <input class="w-100" type="number" name="number" id="number" class="form-control" placeholder="Enter Member Number" autocomplete="off" required="required">
            </div>

            <div class="form-outline mb-4">
                <label for="image" class="form-label">Upload Image</label>
                <input class="w-100" type="file" name="image" id="image" class="form-control" required="required">
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="form-outline">
                    <input type="submit" name="member_details" class="btn btn-info mb-3 px-3" value="Submit">
                </div>
                <a href="welcome.php" class="btn btn-danger">Back</a>
            </div>


        </form>
    </div>
</body>
</html>
