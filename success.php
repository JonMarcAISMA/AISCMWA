<?php
session_start();

include 'db_connection.php';
require_once('fdpdf/fpdf.php'); // Adjust the path accordingly

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['generate_pdf'])) {
        // Fetch data from the database
        $sql = "SELECT subject_name, subject_code, section, day, time_start, time_end FROM schedforstudents";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Create a new PDF document using FPDF
            $pdf = new FPDF();
            $pdf->AddPage();

            // Set font for the table header
            $pdf->SetFont('Arial', 'B', 10);

            // Add table header
            $pdf->Cell(40, 15, 'Subject Name', 1); // Increased height from 10 to 15
            $pdf->Cell(30, 15, 'Subject Code', 1); // Increased height from 10 to 15
            $pdf->Cell(20, 15, 'Section', 1); // Increased height from 10 to 15
            $pdf->Cell(20, 15, 'Day', 1); // Increased height from 10 to 15
            $pdf->Cell(30, 15, 'Time Start', 1); // Increased height from 10 to 15
            $pdf->Cell(30, 15, 'Time End', 1); // Increased height from 10 to 15
            $pdf->Ln(); // Move to the next line

            // Set font for the table data
            $pdf->SetFont('Arial', '', 10);

            // Add data to the PDF
            while ($row = $result->fetch_assoc()) {
                $pdf->Cell(40, 15, $row['subject_name'], 1); // Increased height from 10 to 15
                $pdf->Cell(30, 15, $row['subject_code'], 1); // Increased height from 10 to 15
                $pdf->Cell(20, 15, $row['section'], 1); // Increased height from 10 to 15
                $pdf->Cell(20, 15, $row['day'], 1); // Increased height from 10 to 15
                $pdf->Cell(30, 15, $row['time_start'], 1); // Increased height from 10 to 15
                $pdf->Cell(30, 15, $row['time_end'], 1); // Increased height from 10 to 15
                $pdf->Ln(); // Move to the next line
            }

            // Output the PDF to the browser
            $pdf->Output('schedule_export.pdf', 'D'); // 'D' will force download

            // Delete all data in the schedforstudents table
            $deleteSql = "DELETE FROM schedforstudents";
            $conn->query($deleteSql);

            exit(); // Ensure that no further code is executed after PDF generation
        } else {
            $_SESSION['error'] = "No data available to generate PDF.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            padding: 20px;
            margin: 0;
            background-color: #e0f7d8; /* Light green background color */
        }

        h1 {
            text-align: center;
            color: #4caf50; /* Green color */
        }

        .success-message {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border: 2px solid #4caf50; /* Green border */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        p {
            text-align: center;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #4caf50; /* Green color */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h1>Success!</h1>

    <div class="success-message">
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p>{$_SESSION['error']}</p>";
            unset($_SESSION['error']);
        } else {
            echo "<p>Your schedule has been generated successfully.</p>";
            echo "<p>You can download the schedule PDF using the link below:</p>";
            echo "<a href='#' onclick='generatePDF()'>Download Schedule PDF</a>";
        }
        ?>
    </div>

    <script>
        function generatePDF() {
            // Trigger the form submission to generate the PDF
            document.getElementById('pdfForm').submit();
        }
    </script>

    <!-- Add a hidden form to trigger PDF generation -->
    <form id="pdfForm" method="post">
        <input type="hidden" name="generate_pdf" value="1">
    </form>
</body>

</html>
