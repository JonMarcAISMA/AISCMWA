<?php
include 'db_connection.php';


if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $sql = "DELETE FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        header("Location: subjectspage.php");
        exit();
    } else {
        echo "Error deleting subject: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Invalid request. Please provide an ID.";
}

$conn->close();
?>
