<?php
include 'db_connection.php';
function hasTimeConflict($schedule, $newSubject)
{

    $classStartTime = strtotime('08:00:00');
    $classEndTime = strtotime('17:00:00');
    $lunchStartTime = strtotime('12:00:00');
    $lunchEndTime = strtotime('13:00:00');

    $conflictingSubjects = [];

    foreach ($schedule as $existingSubject) {
        if ($existingSubject['day'] === $newSubject['day']) {

            if (
                isset($existingSubject['time_start'], $existingSubject['time_end'], $newSubject['time_start'], $newSubject['time_end'])
            ) {
                $existingStart = strtotime($existingSubject['time_start']);
                $existingEnd = strtotime($existingSubject['time_end']);
                $newStart = strtotime($newSubject['time_start']);
                $newEnd = strtotime($newSubject['time_end']);


                $isNewSubjectFiveHour = ($newEnd - $newStart) > 60 * 60 * 4; 
                $conflict = ($newStart >= $existingStart && $newStart < $existingEnd) ||
                            ($newEnd > $existingStart && $newEnd <= $existingEnd) ||
                            ($existingStart >= $newStart && $existingStart < $newEnd) ||
                            ($existingEnd > $newStart && $existingEnd <= $newEnd);

                if ($conflict) {

                    if (!$isNewSubjectFiveHour && ($existingEnd - $existingStart) <= 60 * 60 * 4) {
                        $conflictingSubjects[] = $existingSubject;
                    }

                    if (
                        ($newStart < $classStartTime || $newEnd > $classEndTime) ||
                        ($newStart < $lunchEndTime && $newEnd > $lunchStartTime)
                    ) {
                        $conflictingSubjects[] = $existingSubject;
                    }
                }
            }
        }
    }

    return $conflictingSubjects; 
}

function getSubjectDetails($subjectId, $conn)
{
    $sql = "SELECT * FROM subjects WHERE id = $subjectId";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return [];
}


function saveSubjectToDatabase($subjectDetails, $userID, $conn)
{
    $subjectName = $conn->real_escape_string($subjectDetails['subject_name']);
    $subjectCode = $conn->real_escape_string($subjectDetails['subject_code']);
    $section = $conn->real_escape_string($subjectDetails['section']);
    $day = $conn->real_escape_string($subjectDetails['day']);
    $timeStart = $conn->real_escape_string($subjectDetails['time_start']);
    $timeEnd = $conn->real_escape_string($subjectDetails['time_end']);

    $sql = "INSERT INTO schedforstudents (user_id, subject_name, subject_code, section, day, time_start, time_end) 
            VALUES ('$userID', '$subjectName', '$subjectCode', '$section', '$day', '$timeStart', '$timeEnd')";

    $conn->query($sql);
}


function hasConflict($class, $schedule)
{
    foreach ($schedule as $otherClass) {
        if ($class['day'] === $otherClass['day'] && $class !== $otherClass) {

            if (
                isset($class['time_start'], $class['time_end'], $otherClass['time_start'], $otherClass['time_end'])
            ) {
                $classStart = strtotime($class['time_start']);
                $classEnd = strtotime($class['time_end']);
                $otherStart = strtotime($otherClass['time_start']);
                $otherEnd = strtotime($otherClass['time_end']);


                $conflict = ($classStart >= $otherStart && $classStart < $otherEnd) ||
                            ($classEnd > $otherStart && $classEnd <= $otherEnd) ||
                            ($otherStart >= $classStart && $otherStart < $classEnd) ||
                            ($otherEnd > $classStart && $otherEnd <= $classEnd);

                if ($conflict) {
                    return true;
                }
            }
        }
    }
    return false;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $selectedSubjects = isset($_POST['subjects']) ? $_POST['subjects'] : [];


    $scheduleByDay = [
        'Monday' => [],
        'Tuesday' => [],
        'Wednesday' => [],
        'Thursday' => [],
        'Friday' => [],
    ];


    $conflictErrors = [];


    session_start();
    $userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;


    if (!$userID) {
        header("Location: login.php");
        exit();
    }


    foreach ($selectedSubjects as $subjectId) {
        $subjectDetails = getSubjectDetails($subjectId, $conn);

        if ($subjectDetails && isset($subjectDetails['day'])) {
            $day = $subjectDetails['day'];

            if (!empty($day) && isset($scheduleByDay[$day])) {
                $conflictingSubjects = hasTimeConflict($scheduleByDay[$day], $subjectDetails);

                $subjectDetails['conflict'] = $conflictingSubjects;


                $conflictsBySection = [];
                foreach ($conflictingSubjects as $conflictSubject) {
                    $section = $conflictSubject['section'];
                    $conflictsBySection[$section][] = $conflictSubject;
                }


                $scheduleByDay[$day][] = $subjectDetails;

                if (!empty($conflictingSubjects)) {
                    foreach ($conflictsBySection as $section => $conflicts) {
                        if (!isset($conflictErrors[$day][$section])) {
                            $conflictErrors[$day][$section] = [];
                        }
                        $conflictErrors[$day][$section] = array_merge($conflictErrors[$day][$section], $conflicts);
                    }
                }


                saveSubjectToDatabase($subjectDetails, $userID, $conn);
            }
        }
    }


    if (isset($_POST['export'])) {

        header("Location: success.php");
        exit();
    }


    if (isset($_POST['retry'])) {

        $deleteSql = "DELETE FROM schedforstudents";
        if ($conn->query($deleteSql)) {

            header("Location: index.php");
            exit();
        } else {

            echo "Error deleting data: " . $conn->error;
        }
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Chart</title>
    <style>
     body {
        font-family: 'Poppins', sans-serif;
        padding: 20px;
        margin: 0;
        background-color: #e0f7d8;
        color: white; 
    }

    h1, h2 {
        text-align: center;
        color: white;
    }
    h1 {
        text-align: center;
        color: green; 
        margin-bottom: 20px; 
        font-size: 32px; 
        font-weight: bold; 
    }
    h2 {
        text-align: center;
        color: black;
        margin-top: 20px; 
    }
    


        .schedule-table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            border: 2px solid #4caf50; 
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #4caf50; 
            color: white;
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

        .desktop-screen {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
            background-color: white;
            border: 2px solid #4caf50; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form {
            margin-top: 20px;
            text-align: center;
        }

        fieldset {
            border: 1px solid #ddd;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 8px;
        }

        legend {
            font-weight: bold;
            margin-bottom: 10px;
            color: #4caf50;
        }

        label {
            display: block;
            margin-bottom: 15px;
        }

        input[type="checkbox"] {
            margin-right: 5px;
        }

        button {
            background-color: #4caf50; 
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049; 
            color: white;
        }

        .save-button {
            display: block;
            margin: 0 auto;
        }

.retry-button {
    background-color: #FF0000; 
    color: white;
    padding: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.retry-button:hover {
    background-color: #CC0000; 
    color: white;
}
.no-classes-message {
        text-align: center;
        color: black; 
        margin-top: 10px;
        font-size: 40px;
    }
    </style>
</head>
<body>
    <h1>Schedule Chart</h1>

    <?php foreach ($scheduleByDay as $day => $daySchedule): ?>
        <?php if (is_array($daySchedule) && !empty($daySchedule)): ?>
            <h2><?= $day ?></h2>
            <table class="schedule-table">
                <tr>
                    <th>Subject Name</th>
                    <th>Section</th>
                    <th>Time</th>
                </tr>
                <?php foreach ($daySchedule as $class): ?>
                    <tr class="<?= hasConflict($class, $daySchedule) ? 'conflict' : 'subject' ?>">
                        <td><?= $class['subject_name'] ?></td>
                        <td><?= $class['section'] ?></td>
                        <td><?= "{$class['time_start']} - {$class['time_end']}" ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <?php // For display errr ?>
            <?php if (!empty($conflictErrors[$day])): ?>
                <div class="conflict-error-container">
                    <?php foreach ($conflictErrors[$day] as $section => $conflicts): ?>
                        <div class="conflict-error">
                            <h3>Conflicts on <?= $day ?></h3>
                            <?php foreach ($conflicts as $conflict): ?>
                                <!-- Display errors seperately-->
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <p class="no-classes-message">No classes scheduled for <?= $day ?></p>
        <?php endif; ?>
    <?php endforeach; ?>


    <form method="post" action="">
    <?php
    ?>
    <button type="submit" name="export" class="save-button">Export</button>

<button type="submit" name="retry" class="retry-button">Retry</button>
</form>
</form>
</body>
</html>
