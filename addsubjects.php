<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $year = $_POST["year"];
    $subjectCode = $_POST["subject_code"];
    $subjectName = $_POST["subject_name"];
    $section = $_POST["section"];
    $day = $_POST["day"];
    $timeStart = $_POST["time_start"];
    $timeEnd = $_POST["time_end"];

  
    $sql = "INSERT INTO subjects (year, subject_code, subject_name, section, day, time_start, time_end) 
            VALUES ('$year', '$subjectCode', '$subjectName', '$section', '$day', '$timeStart', '$timeEnd')";
    $conn->query($sql);

   
    header("Location: subjectspage.php");
    exit();
}


$conn->close();
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

        h1 {
            text-align: center;
            color: #007BFF; 
        }

        .logout-button {
            float: right;
            background-color: #DC3545; 
            color: white;
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .logout-button:hover {
            background-color: #C82333; 
        }

        .add-subjects-form {
            max-width: 400px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .add-subjects-form h2 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 20px;
        }

        .add-subjects-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .add-subjects-form input,
        .add-subjects-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        .add-subjects-form button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .add-subjects-form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h1>Welcome, Superadmin!</h1>

    
    <form method="post" action="superadmin_dashboard.php">
      
    </form>

   
    <div class="add-subjects-form">
        <h2>Add Subjects</h2>
        <form method="post" action="addsubjects.php">
            <label for="year">Year:</label>
            <select id="year" name="year" required>
                <option value="1">1st Year</option>
                <option value="2">2nd Year</option>
                <option value="3">3rd Year</option>
                <option value="4">4th Year</option>
            </select>

            <label for="subject_code">Subject Code:</label>
            <input type="text" id="subject_code" name="subject_code" required>

            <label for="subject_name">Subject Name:</label>
            <input type="text" id="subject_name" name="subject_name" required>

            <label for="section">Section:</label>
            <input type="text" id="section" name="section" required>

            <label for="day">Day:</label>
            <select id="day" name="day" required>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
            </select>

            <label for="time_start">Time Start:</label>
            <input type="time" id="time_start" name="time_start" required>

            <label for="time_end">Time End:</label>
            <input type="time" id="time_end" name="time_end" required>

            <button type="submit">Add Subject</button>
        </form>
    </div>
</body>

</html>
