<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

 
    $sql = "SELECT * FROM user_data WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

     
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['user_admin'];

            if ($row['user_admin'] == 'admin') {
                header("Location: welcome.php"); 
            } else {
                header("Location: userpage.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid username or password!";
        }
    } else {
        $_SESSION['error'] = "No user found with this username!";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="card">
            <div class="card-header">
                <?php
                if (isset($_SESSION['error'])) {
                    echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
                    unset($_SESSION['error']);
                }elseif (isset($_GET['registered']) && $_GET['registered'] == 1) {
                    echo "<div class='alert alert-success'>Registration successful! Please sign in.</div>";
                }
                ?>
                <h3 class="text-center text-light">Sign In</h3>
            </div>
           
            <div class="card-body">
                <form action="" method="post">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
                        <input type="text" class="form-control" placeholder="Enter your Username" 
                               required autocomplete="off" name="username" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-key"></i></span>
                        <input type="password" class="form-control" placeholder="Enter your password" 
                               required autocomplete="off" name="password" aria-label="Password" aria-describedby="basic-addon1">
                    </div>
                    <div class="form-group">
                        <input type="submit" name="signin" value="Sign In" class="btn signup_btn btn-block">
                    </div>
                </form>
            </div>
            <div class="card-footer text-center text-light signup">
                Don't have an account? <a href="register.php">Sign Up</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"></script>
</body>
</html>
