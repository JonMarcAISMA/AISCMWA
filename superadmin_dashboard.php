<?php
include 'db_connection.php';
$sql = "SELECT * FROM users";
$result = $conn->query($sql);


$users = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_user"])) {
    $user_id_to_delete = $_POST["delete_user"];

    
    $delete_query = "DELETE FROM users WHERE id = $user_id_to_delete";
    $conn->query($delete_query);

   
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}


if (isset($_POST["logout"])) {
 
    session_start();
    session_destroy();


    header("Location: loginpage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            margin: 0;
            background-color: #e0f7d8;
        }

        .desktop-screen {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
            background-color: white;
        }

        h1 {
            text-align: center;
            color: #4caf50; 
            margin-bottom: 20px;
        }

        .top-buttons {
            text-align: center;
            margin-bottom: 20px;
        }

        .top-buttons a {
            background-color: #007BFF; 
            color: white;
            padding: 10px 20px;
            margin-right: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .top-buttons a:hover {
            background-color: #0056b3; 
        }

        .user-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .user-table th,
        .user-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .user-table th {
            background-color: #007BFF; 
            color: white;
        }

        .user-table td {
            background-color: #f9f9f9; 
        }

        .user-table td .edit-button,
        .user-table td .delete-button {
            background-color: #007BFF;
            margin-right: 5px;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .user-table td .delete-button {
            background-color: #DC3545; 
        }

        .user-table td .edit-button:hover,
        .user-table td .delete-button:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <div class="desktop-screen">
        <h1>Welcome, Superadmin!</h1>

        <div class="top-buttons">
        
            <a href="subjectspage.php">Subjects Tab</a>
        </div>

     
        <form method="post">
            <button type="submit" class="top-buttons" name="logout">Logout</button>
        </form>

      
        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Actions</th> 
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['username'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td>
                     
                            <a href="edit.php?user_id=<?= $user['id'] ?>" class="edit-button">Edit</a>

                      
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="delete_user" value="<?= $user['id'] ?>">
                                <button type="submit" class="delete-button">Delete</button>
                            </form>

                         
                            <a href="view_info.php?user_id=<?= $user['id'] ?>" class="view-info-button">View Info</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
