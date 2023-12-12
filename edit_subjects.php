<?php
include 'db_connection.php';
if (!isset($_GET['subject_code'])) {
    echo 'Subject Code not provided.';
    exit;
}

$subjectCode = $_GET['subject_code'];

$sql = "SELECT * FROM subjects WHERE subject_code = '$subjectCode'";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    echo 'Subjects not found.';
    exit;
}


$subjects = [];
while ($row = $result->fetch_assoc()) {
    $subjects[$row['section']][] = $row;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $newSubjectName = $_POST['subject_name'];
    $newStartTime = $_POST['start_time'];
    $newEndTime = $_POST['end_time'];

    foreach ($subjects as $sectionSubjects) {
        foreach ($sectionSubjects as $subject) {
            $subjectId = $subject['id'];
            $updateSql = "UPDATE subjects SET 
                subject_name = '$newSubjectName', 
                time_start = '$newStartTime',
                time_end = '$newEndTime' 
                WHERE id = $subjectId";
            $updateResult = $conn->query($updateSql);

            if (!$updateResult) {
                echo 'Error updating subjects: ' . $conn->error; 
                exit;
            }
        }
    }

    echo 'Subjects updated successfully.';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subjects</title>
    <style>
      body {
    font-family: 'Arial', sans-serif;
    background-color: #d9f9d9; 
    margin: 0;
    padding: 50px;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

        h1 {
            text-align: center;
            color: #007BFF;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="time"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }
        .return-button {
    background-color: #dc3545;
    margin-top: auto;
    width: calc(10% - 22px);
    align-self: center; 
}

        .return-button:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <?php foreach ($subjects as $section => $sectionSubjects): ?>
        <h1>Edit Subjects: <?= $subjectCode ?> - Section <?= $section ?></h1>

        <form method="POST" action="">
            <label for="subject_name">Subject Name:</label>
            <input type="text" id="subject_name" name="subject_name" value="<?= $sectionSubjects[0]['subject_name'] ?? '' ?>" required>

            <label for="start_time">Start Time:</label>
            <input type="time" id="start_time" name="start_time" value="<?= $sectionSubjects[0]['time_start'] ?? '' ?>" required>

            <label for="end_time">End Time:</label>
            <input type="time" id="end_time" name="end_time" value="<?= $sectionSubjects[0]['time_end'] ?? '' ?>" required>

            <button type="submit">Update Subjects</button>
        </form>
    <?php endforeach; ?>

    <button class="return-button" onclick="goBack()">Return</button>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>
