<?php
include 'db_connection.php';
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;


$user_details = [];
if ($user_id) {
    $sql = "SELECT * FROM users WHERE id = $user_id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user_details = $result->fetch_assoc();
    }
}


if (empty($user_details)) {
 
    header("Location: superadmin_dashboard.php");
    exit();
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
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
        }

        .user-info-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
        }

        .user-info-label {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .user-info-value {
            margin-bottom: 20px;
        }

        .back-button {
            background-color: #007BFF; 
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h1>User Information</h1>


    <div class="user-info-container">
        <div class="user-info-label">User ID:</div>
        <div class="user-info-value"><?= $user_details['id'] ?></div>

        <div class="user-info-label">Username:</div>
        <div class="user-info-value"><?= $user_details['username'] ?></div>

        <div class="user-info-label">Email:</div>
        <div class="user-info-value"><?= $user_details['email'] ?></div>


        <div class="user-info-label">College Year:</div>
        <div class="user-info-value"><?= $user_details['college_year'] ?></div>

        <div class="user-info-label">Course:</div>
        <div class="user-info-value"><?= $user_details['course'] ?></div>


        <a href="superadmin_dashboard.php" class="back-button">Return</a>
    </div>
</body>

</html>
