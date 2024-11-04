<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user_admin = $_POST['user_admin'];

    $sql_check = "SELECT * FROM user_data WHERE username = '$username'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Username already registered. Please sign in.";
        header("Location: signin.php?already_registered=1");
        exit();
    } else {
        if ($password === $confirm_password) {
        
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $sql_insert = "INSERT INTO user_data (username, password, user_admin) VALUES ('$username', '$hashed_password', '$user_admin')";

            if ($conn->query($sql_insert) === TRUE) {
                $_SESSION['message'] = "Registration successful! Please sign in.";
                header("Location: signin.php?registered=1");
                exit();
            } else {
                $_SESSION['error'] = "Error: " . $conn->error;
            }
        } else {
            $_SESSION['error'] = "Passwords do not match!";
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="card" style="min-height: 80vh;">
     
            <div class="card-header">
                <?php
                if (isset($_SESSION['error'])) {
                    echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
                    unset($_SESSION['error']);
                }
                ?>
                <h3 class="text-center text-light">Sign Up</h3>
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
                        required autocomplete="off" name="password" aria-label="password" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" class="form-control" placeholder="Confirm your password" 
                        required autocomplete="off" name="confirm_password" aria-label="confirm_password" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user-cog"></i></span>
                        <select class="form-control" name="user_admin" aria-label="user_admin" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <input type="submit" name="register" value="Sign Up" class="btn signup_btn btn-block">
                    </div>
                </form>
            </div>
            <div class="card-footer text-center signup text-light">
                Already have an account? <a href="signin.php">Sign in</a>
            </div>
        </div>
    </div>
</body>
</html>
