<?php
session_start();
include 'db_connect.php';

// Check if the user is an admin and if the ID is set
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: signin.php");
    exit();
}

if (isset($_GET['id'])) {
    $member_id = intval($_GET['id']);
    
    // Fetch the member's image file name from the database
    $sql = "SELECT image FROM member WHERE member_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image = $row['image'];

        // Delete the member record from the database
        $delete_sql = "DELETE FROM member WHERE member_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $member_id);
        
        if ($delete_stmt->execute()) {
            // Delete the image file from the 'images' folder, if it exists
            if (file_exists("images/" . $image)) {
                unlink("images/" . $image);
            }
            // Redirect to the admin page with a success message
            header("Location: welcome.php?msg=Member deleted successfully");
        } else {
            echo "Error deleting member: " . $conn->error;
        }
    } else {
        echo "No member found with that ID.";
    }
    $stmt->close();
}

$conn->close();
?>
