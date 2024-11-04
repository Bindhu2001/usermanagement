<?php
session_start(); 

include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

if (isset($_GET['id'])) {
    $member_id = intval($_GET['id']);

    $sql = "SELECT * FROM member WHERE member_id = $member_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p class='text-center'>No member found with this ID.</p>";
        exit();
    }
} else {
    echo "<p class='text-center'>No ID provided.</p>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $fname = $_POST['fname'];
    $designation = $_POST['designation'];
    $address = $_POST['address'];
    $number = $_POST['number'];
    $image = $_FILES['image']['name'];
    $target_dir = "images/";

    if (!empty($image)) {
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    } else {
        $image = $row['image']; 
    }

    $sql = "UPDATE member SET 
                name = '$name', 
                fname = '$fname', 
                designation = '$designation', 
                address = '$address', 
                number = '$number', 
                image = '$image' 
            WHERE member_id = $member_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: welcome.php?update=success");
        exit();
    } else {
        echo "<p class='text-center'>Error updating record: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member Details</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <style>
        .card {
            max-width: 600px;
            margin: 20px auto;
        }
        .card-header {
            background-color: #f8f9fa;
        }
        .card-body {
            padding: 20px;
        }
        .form-control-file {
            padding: 3px;
        }
        .btn-secondary {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container pt-5">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center">Edit Member Details</h4>
            </div>
            <div class="card-body">
                <form action="edit_member.php?id=<?php echo $member_id; ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="fname">Father's Name</label>
                        <input type="text" name="fname" class="form-control" id="fname" value="<?php echo htmlspecialchars($row['fname']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="designation">Designation</label>
                        <input type="text" name="designation" class="form-control" id="designation" value="<?php echo htmlspecialchars($row['designation']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" class="form-control" id="address" required><?php echo htmlspecialchars($row['address']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="number">Number</label>
                        <input type="text" name="number" class="form-control" id="number" value="<?php echo htmlspecialchars($row['number']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" name="image" class="form-control-file" id="image">
                        <p class="mt-2"><img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="No Image" width="100"></p>
                        <small>Leave blank if you don't want to update the image.</small>
                    </div>
                    <button type="submit" class="btn btn-success">Update Member</button>
                    <a href="welcome.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

 
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy5S8e6XxggEr10Vf6eb8AI6vWg9We8hWl5jZ4U2b" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9n5xE7rD5H6rD7x1cRvcAXvI2XPjY3zT6GSAK9G5gZ4/S0GvC+z" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-3d1g2blgeiXjzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous"></script>
</body>
</html>

<?php
$conn->close(); 
?>
