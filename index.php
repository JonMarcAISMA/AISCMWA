<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Maker</title>
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

        form {
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
        }

        label {
            display: block;
            margin-bottom: 15px;
            text-align: left;
        }

        input[type="checkbox"] {
            margin-right: 5px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .logout-button {
            background-color: #DC3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }

        .logout-button:hover {
            background-color: #C82333;
        }
    </style>
    <script>
      
        function handleCheckboxChange(subjectCode, checkbox) {
            const checkboxes = document.querySelectorAll(`[data-subject="${subjectCode}"]`);
            checkboxes.forEach(cb => {
                if (cb !== checkbox) {
                    cb.checked = false;
                }
            });
        }

      
        function validateForm() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
            if (checkboxes.length === 0) {
                alert('Please select at least one class.');
                return false;
            }

           
            const userConfirmation = confirm('Are you sure you want to generate the schedule? This action cannot be undone.');
            return userConfirmation; 
        }
    </script>
</head>

<body>
    <div class="desktop-screen">
        <h1>Schedule Maker</h1>
        <form method="post">

        </form>

        <form action="schedule_generation.php" method="post" onsubmit="return validateForm();">
            <?php

         
            include 'db_connection.php';

           
            $sql = "SELECT id, subject_code, subject_name, section, year FROM subjects ORDER BY year, subject_code, section";
            $result = $conn->query($sql);

         
            if ($result->num_rows > 0) {
                $currentYear = '';
                $currentSubjectCode = '';

            
                while ($row = $result->fetch_assoc()) {
                   
                    if ($row['year'] !== $currentYear) {
                        if ($currentYear !== '') {
                            
                            echo '</fieldset>';
                        }

                        echo '<fieldset>';
                        echo '<legend>Year ' . $row['year'] . '</legend>';

                    
                        $currentYear = $row['year'];

                       
                        $currentSubjectCode = '';
                    }

                  
                    if ($row['subject_code'] !== $currentSubjectCode) {
                       
                        if ($currentSubjectCode !== '') {
                            echo '</div>';
                        }

                   
                        echo '<div>';
                        echo '<h3>' . $row['subject_code'] . ' - Subjects</h3>';

                       
                        $currentSubjectCode = $row['subject_code'];
                    }

                   
                    echo '<label>';
                    echo '<input type="checkbox" name="subjects[]" value="' . $row['id'] . '" data-subject="' . $row['subject_code'] . '" onchange="handleCheckboxChange(\'' . $row['subject_code'] . '\', this)">';
                    echo $row['subject_code'] . ' - ' . $row['subject_name'] . ' - Section ' . $row['section'];
                    echo '</label>';
                }

             
                echo '</div>';
                echo '</fieldset>';
            } else {
                echo '<p>No subjects available</p>';
            }

           
            $conn->close();
            ?>

            <button type="submit">Generate Schedule</button>
            <a href="loginpage.php" class="logout-button">Logout</a>
        </form>
    </div>
</body>

</html>
