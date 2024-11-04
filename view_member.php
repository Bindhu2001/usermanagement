<?php
session_start(); 

include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Member Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container pt-5">
        <div class="card">
            <div class="card-header">
                <h4>Member Details</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="No Image" width='250' height='280'>
                    </div>
                    <div class="col-md-8">
                        <p><strong>ID:</strong> <?php echo htmlspecialchars($row['member_id']); ?></p>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
                        <p><strong>Father's Name:</strong> <?php echo htmlspecialchars($row['fname']); ?></p>
                        <p><strong>Designation:</strong> <?php echo htmlspecialchars($row['designation']); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?></p>
                        <p><strong>Number:</strong> <?php echo htmlspecialchars($row['number']); ?></p>

                        <a href="<?php echo $user_role === 'admin' ? 'welcome.php' : 'userpage.php'; ?>" class="btn btn-primary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
