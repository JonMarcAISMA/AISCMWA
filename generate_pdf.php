<?php
require_once 'tcpdf/tcpdf.php';


include 'db_connection.php';


function hasConflict($class, $schedule) {
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


function generatePDF($scheduleByDay, $conflictErrors) {

    $pdf = new TCPDF();


    $pdf->SetCreator('Your Name');
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Schedule Export');
    $pdf->SetSubject('Schedule');


    $pdf->AddPage();


    $pdf->SetFont('times', '', 12);


    foreach ($scheduleByDay as $day => $daySchedule) {

        $pdf->Cell(0, 10, $day, 0, 1, 'C');
        

        $pdf->Cell(40, 10, 'Subject Name', 1);
        $pdf->Cell(40, 10, 'Section', 1);
        $pdf->Cell(60, 10, 'Time', 1);
        $pdf->Ln();


        foreach ($daySchedule as $class) {

            $pdf->Cell(40, 10, $class['subject_name'], 1);
            $pdf->Cell(40, 10, $class['section'], 1);
            $pdf->Cell(60, 10, "{$class['time_start']} - {$class['time_end']}", 1);
            $pdf->Ln();
        }

        if (!empty($conflictErrors[$day])) {
            $pdf->Ln(); 
            $pdf->Cell(0, 10, 'Conflicts:', 0, 1, 'L');


            foreach ($conflictErrors[$day] as $section => $conflicts) {
                foreach ($conflicts as $conflict) {
          
                    $pdf->Cell(0, 10, "Conflict in section $section: {$conflict['subject_name']}", 0, 1, 'L');
                }
            }
        }


        $pdf->Ln(10);
    }


    $pdf->Output('schedule_export.pdf', 'F');
}




if ($_SERVER["REQUEST_METHOD"] == "POST") {



    generatePDF($scheduleByDay, $conflictErrors);


    header("Location: success.php");
    exit();
}
?>
