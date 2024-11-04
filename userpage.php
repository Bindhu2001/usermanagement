<?php
session_start();

include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

$username = $_SESSION['username'];
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';

$limit = 5; 
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $limit;

$result = $conn->query("SELECT COUNT(*) AS total FROM member");
$total_rows = $result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

$sql = "SELECT * FROM member LIMIT $start, $limit";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container pt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="welcome-heading">Welcome, <?php echo htmlspecialchars($username); ?>!<small class="text-muted">-<?php echo htmlspecialchars($user_role); ?></small></h3>
            <div>
                
                <a href="logout.php" class="btn btn-danger">
                    <i class="fa-solid fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <div>
            <div class="card-header pt-3">
                <h4 class="text-center">Member Details</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Father's Name</th>
                            <th>Designation</th>
                            <th>Address</th>
                            <th>Number</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row['member_id']) . "</td>
                                        <td><img src='images/" . htmlspecialchars($row['image']) . "' alt='No Image' width='50' height='50'></td>
                                        <td>" . htmlspecialchars($row['name']) . "</td>
                                        <td>" . htmlspecialchars($row['fname']) . "</td>
                                        <td>" . htmlspecialchars($row['designation']) . "</td>
                                        <td>" . htmlspecialchars($row['address']) . "</td>
                                        <td>" . htmlspecialchars($row['number']) . "</td>
                                        <td>
                                            <a href='view_member.php?id=" . $row['member_id'] . "' class='btn btn-info btn-sm'><i class='fa fa-eye'></i> View</a>
                                            
                                        </td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr>
                                    <td colspan='8' class='text-center'>No members found.</td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php
                    if ($page > 1) {
                        echo "<li class='page-item'><a class='page-link' href='userpage.php?page=" . ($page - 1) . "'>Previous</a></li>";
                    }

                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo "<li class='page-item " . ($i == $page ? 'active' : '') . "'><a class='page-link' href='userpage.php?page=" . $i . "'>$i</a></li>";
                    }

                    if ($page < $total_pages) {
                        echo "<li class='page-item'><a class='page-link' href='userpage.php?page=" . ($page + 1) . "'>Next</a></li>";
                    }
                    ?>
                </ul>
            </nav>
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
