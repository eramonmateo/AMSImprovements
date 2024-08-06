<?php
include "connects.php"; // Include the database connection script
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$tasks_activities_sql = "SELECT id, name, department, task_name, task_date_assigned, task_deadline, task_from 
                         FROM tasks_activities";
$tasks_activities_result = mysqli_query($conn, $tasks_activities_sql);

if ($tasks_activities_result) {
    $data = [];
    while ($row = mysqli_fetch_assoc($tasks_activities_result)) {
        $data[] = [
            $row['id'],
            $row['name'],
            $row['department'],
            $row['task_name'],
            $row['task_date_assigned'],
            $row['task_deadline'],
            $row['task_from'],
        ];
    }
    
    mysqli_free_result($tasks_activities_result);

    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Name');
    $sheet->setCellValue('C1', 'Department');
    $sheet->setCellValue('D1', 'Task Name');
    $sheet->setCellValue('E1', 'Task Date Assigned');
    $sheet->setCellValue('F1', 'Task Deadline');
    $sheet->setCellValue('G1', 'Task From');


   

    $row = 2;
    foreach ($data as $rowData) {
        $col = 'A';
        foreach ($rowData as $cellValue) {
            $sheet->setCellValue($col . $row, $cellValue);
            $col++;
        }
        $row++;
    }
      // Automatically adjust column widths based on content length
      foreach (range('A', 'G') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Tasks and Activities.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
} else {
    echo "Error fetching data: " . mysqli_error($conn);
}
?>