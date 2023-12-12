<?php
include 'db_connection.php';
if (isset($_GET['user_id'])) {
    $user_id_to_edit = $_GET['user_id'];


    $sql = "SELECT * FROM users WHERE id = $user_id_to_edit";
    $result = $conn->query($sql);


    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {

        header("Location: superadmin_dashboard.php");
        exit();
    }
} else {

    header("Location: superadmin_dashboard.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_user"])) {

    $new_username = htmlspecialchars($_POST["new_username"]);
    $new_email = htmlspecialchars($_POST["new_email"]);
    $new_password = $_POST["new_password"];
    $new_college_year = htmlspecialchars($_POST["new_college_year"]);
    $new_course = htmlspecialchars($_POST["new_course"]);


    $update_query = "UPDATE users SET username = '$new_username', email = '$new_email', password = '$new_password', college_year = '$new_college_year', course = '$new_course' WHERE id = $user_id_to_edit";
    $conn->query($update_query);


    header("Location: superadmin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            margin: 0;
            background-color: #e0f7d8; 
        }

        h1 {
            text-align: center;
            color: #007BFF; 
            margin-bottom: 20px;
        }

        .edit-form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            color: #333;
        }

        .update-button {
            background-color: #007BFF; 
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .update-button:hover {
            background-color: #0056b3; 
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Edit User</h1>

     
        <form method="post" class="edit-form">
            <div class="form-group">
                <label for="new_username">New Username</label>
                <input type="text" class="form-control" name="new_username" value="<?= $user['username'] ?>" required>
            </div>

            <div class="form-group">
                <label for="new_email">New Email</label>
                <input type="email" class="form-control" name="new_email" value="<?= $user['email'] ?>" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password (optional)</label>
                <input type="password" class="form-control" name="new_password">
            </div>

            <div class="form-group">
                <label for="new_college_year">New College Year</label>
                <input type="text" class="form-control" name="new_college_year" value="<?= $user['college_year'] ?>" required>
            </div>

            <div class="form-group">
                <label for="new_course">New Course</label>
                <input type="text" class="form-control" name="new_course" value="<?= $user['course'] ?>" required>
            </div>

            <button type="submit" class="btn btn-primary update-button" name="update_user">Update User</button>
        </form>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
