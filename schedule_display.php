<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Chart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            margin: 0;
        }

        h1 {
            text-align: center;
        }

        .schedule-table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .subject {
            background-color: #4CAF50;
            color: #FFFFFF;
        }

        .conflict {
            background-color: #FFEB3B;
            color: #000000;
            font-weight: bold;
        }

        .conflict-error-container {
            border: 2px solid #FF0000;
            border-radius: 10px;
            padding: 10px;
            margin-top: 20px;
        }

        .conflict-error {
            font-style: italic;
            color: #FF0000;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Schedule Chart</h1>

    <?php

    include 'db_connection.php';


    session_start();
    $userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;


    if (!$userID) {
        header("Location: login.php");
        exit();
    }


    $sql = "SELECT * FROM schedforstudents WHERE user_id = $userID";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        ?>
        <h2>Schedule for User ID: <?= $userID ?></h2>
        <table class="schedule-table">
            <tr>
                <th>Subject Name</th>
                <th>Section</th>
                <th>Day</th>
                <th>Time</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['subject_name'] ?></td>
                    <td><?= $row['section'] ?></td>
                    <td><?= $row['day'] ?></td>
                    <td><?= "{$row['time_start']} - {$row['time_end']}" ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <?php
    } else {
        ?>
        <p>No classes scheduled for this user.</p>
        <?php
    }


    $conn->close();
    ?>
</body>
</html>
