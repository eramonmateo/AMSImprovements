<?php
ob_start();

include "connects.php";
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

$spreadsheet = new Spreadsheet();

$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];
$name = $_GET['name'];
$department = $_GET['department'];
$position = $_GET['position'];

$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Daily Time Record');

// For the DTR
$timein_sql = "SELECT u.name, t.datetime AS timein, o.datetime AS timeout, o.tasks
               FROM users u
               JOIN time_in t ON u.name = t.name
               JOIN time_out o ON u.name = o.name
               WHERE u.name = '$name'
               AND DATE(t.datetime) = DATE(o.datetime)
               ORDER BY t.datetime ASC";

$data = mysqli_query($conn, $timein_sql);

$position_sql = "SELECT position FROM users WHERE name = '$name'";
$position_result = mysqli_query($conn, $position_sql);

if ($position_result) {
   
    $position_data = mysqli_fetch_assoc($position_result);
    $position = ucwords($position_data['position']);
} else {
    $position = 'No Record';
}
$data = mysqli_query($conn, $timein_sql);

$sheet->setCellValue('A2', 'Name');
$sheet->setCellValue('A3', 'Department');
$sheet->setCellValue('A4', 'Position');
$sheet->getStyle('A')->getFont()->getColor()->setARGB('76933C');

$sheet->setCellValue('B6', 'Date');
$sheet->setCellValue('C6', 'Time In');
$sheet->setCellValue('D6', 'Time Out');
$sheet->setCellValue('E6', 'Activities Done');

$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);

$sheet->getStyle('B6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('C6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('D6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('B6:E6')->getFill()->setFillType(Fill::FILL_SOLID);
$sheet->getStyle('B6:E6')->getFill()->getStartColor()->setARGB('92D050');

$sheet->setCellValue('B2', $name);
$sheet->setCellValue('B3', $department);
$sheet->setCellValue('B4', $position);

$styleArray = [
    'borders' => [
        'outline' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
];

$sheet->getStyle('B6')->applyFromArray($styleArray);
$sheet->getStyle('C6')->applyFromArray($styleArray);
$sheet->getStyle('D6')->applyFromArray($styleArray);
$sheet->getStyle('E6')->applyFromArray($styleArray);

$row = 7; 
foreach ($data as $rowValues) {
    $col = 'B'; 
    foreach ($rowValues as $value) {
        if ($col == 'B') {
        } else if ($col == 'C') {
            $dateTime = new DateTime($value);
            $time = $dateTime->format('H:i');
            $date = $dateTime->format('Y/m/d');

            $sheet->setCellValue('B' . $row, $date);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getStyle('B' . $row)->applyFromArray($styleArray);

            $sheet->setCellValue($col . $row, $time);
        } else if ($col == 'D') {
            $dateTime = new DateTime($value);
            $time = $dateTime->format('H:i:s');
            $sheet->setCellValue($col . $row, $time);
        } else {
            $sheet->setCellValue($col . $row, $value);
        }
    
        if($col == 'E'){
            $sheet->getColumnDimension($col)->setWidth(180);

        }
        else{
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getStyle($col)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($col)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle($col . $row)->applyFromArray($styleArray);
        $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $col++;
    }
    $row++;
}
$sheet->getStyle('E')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('E')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle('E')->getAlignment()->setWrapText(true);
$sheet->getStyle('E6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);



// For the Leaves
$leaves_sql = "SELECT l.type, l.startdate, l.enddate, l.approvedby
               FROM users u
               JOIN filed_leaves l ON u.name = l.name
               WHERE u.name = '$name' AND l.status='Approved'
               ORDER BY l.leave_id ASC";

$leaves_data = mysqli_query($conn, $leaves_sql);

$sheet->setCellValue('G6', 'Approved Leaves');
$sheet->setCellValue('H6', 'Start Date');
$sheet->setCellValue('I6', 'End Date');
$sheet->setCellValue('J6', 'Approved By');

// padding
$sheet->getColumnDimension('G')->setAutoSize(true);
$sheet->getColumnDimension('J')->setAutoSize(true);

// align
$sheet->getStyle('G6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('H6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('I6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('J6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('G6:J6')->getFill()->setFillType(Fill::FILL_SOLID);
$sheet->getStyle('G6:J6')->getFill()->getStartColor()->setARGB('92D050');

// add border to headers
$sheet->getStyle('G6')->applyFromArray($styleArray);
$sheet->getStyle('H6')->applyFromArray($styleArray);
$sheet->getStyle('I6')->applyFromArray($styleArray);
$sheet->getStyle('J6')->applyFromArray($styleArray);

$row = 7; 
foreach ($leaves_data as $rowValues) {
    $col = 'G';
    foreach ($rowValues as $value) {
        if ($col === 'G') {
            $sheet->setCellValue($col . $row, $value);
        } elseif ($col === 'H') {
            $sheet->setCellValue($col . $row, $value);
        } elseif ($col === 'I') {
            $sheet->setCellValue($col . $row, $value);
        } elseif ($col === 'J') {
            $sheet->setCellValue($col . $row, $value);
        }
        $sheet->getColumnDimension($col)->setAutoSize(true);
        $sheet->getStyle($col . $row)->applyFromArray($styleArray);
        $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $col++;
    }
    $row++;
}

$writer = new Xlsx($spreadsheet);
$filename = $name . " - DTR.xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');

ob_end_flush();
exit;
?>
