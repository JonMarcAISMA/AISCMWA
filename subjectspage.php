<?php
include 'db_connection.php';

$sql = "SELECT id, subject_code, subject_name, section, year FROM subjects ORDER BY year, subject_code";
$result = $conn->query($sql);


$subjects = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subjects[$row['year']][$row['subject_code']][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Viewer</title>
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

        .year-group {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .subject-group {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            position: relative; 
        }

        .subject-group h3 {
            color: #007BFF; 
            margin-bottom: 10px;
        }

        .edit-subjects-button {
            background-color: #007BFF; 
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            position: absolute;
            top: 5px;
            right: 5px;
            text-decoration: none;
        }

        .edit-subjects-button:hover {
            background-color: #0056b3; 
        }

        .delete-button {
            background-color: #dc3545; 
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .delete-button:hover {
            background-color: #c82333; 
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        .top-buttons {
            text-align: center;
            margin-bottom: 20px;
        }

        .top-buttons a, .top-buttons button {
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

        .top-buttons a:hover, .top-buttons button:hover {
            background-color: #0056b3; 
        }
    </style>
</head>

<body>
    <div class="desktop-screen">
        <h1>Schedule Viewer</h1>

        <div class="top-buttons">
            <a href="addsubjects.php">Add Subjects</a>
            <a href="superadmin_dashboard.php">Users</a>
        </div>
      
        <?php if (!empty($subjects)): ?>
            <?php foreach ($subjects as $year => $yearSubjects): ?>
                <div class="year-group">
                    <h2>Year <?= $year ?></h2>

                    <?php foreach ($yearSubjects as $subjectCode => $subjectGroup): ?>
                        <div class="subject-group">
                            <h3><?= $subjectCode ?> - Subjects</h3>
                            <a href="edit_subjects.php?subject_code=<?= $subjectCode ?>" class="edit-subjects-button">Edit Subjects</a>

                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Subject Name</th>
                                        <th>Section</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($subjectGroup as $subject): ?>
                                        <tr>
                                            <td><?= $subject['id'] ?></td>
                                            <td><?= $subject['subject_name'] ?></td>
                                            <td><?= $subject['section'] ?></td>
                                          
                                            <td><a href="delete_subject.php?id=<?= $subject['id'] ?>" class="delete-button" >Delete</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No subjects available</p>
        <?php endif; ?>
    </div>
</body>

</html>
