<?php
include "connects.php";
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->mergeCells('A1:C1'); 
$sheet->setCellValue('A1', 'Employee Time In Record');
$sheet->getStyle('A1')->getFont()->setSize(15); // Change font size for merged cell
$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
$sheet->setCellValue('A2', 'Name');
$sheet->setCellValue('B2', 'Date and Time');
$sheet->setCellValue('C2', 'Location');

$sheet->mergeCells('E1:H1');
$sheet->setCellValue('E1', 'Employee Time Out Record');
$sheet->getStyle('E1')->getFont()->setSize(15); // Change font size for E1
$sheet->getStyle('E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
$sheet->getColumnDimension('E')->setWidth(60); // Width for column E1
$sheet->setCellValue('E2', 'Name');
$sheet->setCellValue('F2', 'Date and Time');
$sheet->setCellValue('G2', 'Overtime');
$sheet->setCellValue('H2', 'Hours');

$sheet->getColumnDimension('A')->setWidth(30); // Width for columns A2 and beyond
$sheet->getColumnDimension('B')->setWidth(25); // Date and Time
$sheet->getColumnDimension('C')->setWidth(10); // Location
$sheet->getColumnDimension('E')->setWidth(30); // Width for columns E2 and beyond
$sheet->getColumnDimension('F')->setWidth(25); // Date and Time
$sheet->getColumnDimension('G')->setWidth(10); // Overtime
$sheet->getColumnDimension('H')->setWidth(15); // Hours

$timein_sql = "SELECT t.name, t.datetime, t.location 
           FROM time_in t
           INNER JOIN users u ON t.name = u.name
           WHERE u.position = 'employee'
           ORDER BY t.datetime DESC";
$timein_result = mysqli_query($conn, $timein_sql);

$timeout_sql = "SELECT t.name, t.datetime, t.overtime, t.hours 
            FROM time_out t
            INNER JOIN users u ON t.name = u.name
            WHERE u.position = 'employee'
            ORDER BY t.datetime DESC";
$timeout_result = mysqli_query($conn, $timeout_sql);

$row = 3; // Start from the third row for data

while ($rowIn = mysqli_fetch_assoc($timein_result)) {
    $sheet->setCellValue('A' . $row, $rowIn['name']);
    $sheet->setCellValue('B' . $row, $rowIn['datetime']);
    $sheet->setCellValue('C' . $row, $rowIn['location']);
    $row++;
}

$row = 3; // Start from the third row for data

while ($rowOut = mysqli_fetch_assoc($timeout_result)) {
    $sheet->setCellValue('E' . $row, $rowOut['name']);
    $sheet->setCellValue('F' . $row, $rowOut['datetime']);
    $sheet->setCellValue('G' . $row, $rowOut['overtime']);
    $sheet->setCellValue('H' . $row, $rowOut['hours']);
    $row++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Employee Attendance Record.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>