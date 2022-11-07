<?php

require_once('../composer/vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$helper = new Sample();
if ($helper->isCli()) {
    $helper->log('This example should only be run from a Web Browser' . PHP_EOL);

    return;
}
// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
    ->setLastModifiedBy('Maarten Balliauw')
    ->setTitle('Office 2007 XLSX Test Document')
    ->setSubject('Office 2007 XLSX Test Document')
    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
    ->setKeywords('office 2007 openxml php')
    ->setCategory('Test result file');

// Add Column Dimensions
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5.5);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(40);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30.00);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);


// Add Row Dimensions
 
$spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(22.50);
$spreadsheet->getActiveSheet()->getRowDimension('3')->setRowHeight(22.50);
$spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(22.50);
$spreadsheet->getActiveSheet()->getRowDimension('5')->setRowHeight(22.50);
$spreadsheet->getActiveSheet()->getRowDimension('6')->setRowHeight(22.50);
$spreadsheet->getActiveSheet()->getRowDimension('7')->setRowHeight(22.50);
$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(46.00);


// Add some data
$spreadsheet->getActiveSheet()->setCellValue('C1', 'Store Wallet History Reports');
$spreadsheet->getActiveSheet()->getStyle('C1')->getFont()->setSize(26)->setBold(true)->setName("Adobe Arabic");
$spreadsheet->getActiveSheet()->mergeCells('C1:K1');
$spreadsheet->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('B5', 'Date Printed');
$spreadsheet->getActiveSheet()->getStyle('B5')->getFont()->setSize(14)->setName("Calibri");
$spreadsheet->getActiveSheet()->setCellValue('C5', date("Y-m-d"));
$spreadsheet->getActiveSheet()->getStyle('C5')->getFont()->setSize(14)->setName("Calibri");


//Title
$styleArray = [
      'borders' => [
    
       'top' => [
        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
        'bottom' => [
        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
        'left' => [
        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
        'right' => [
        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ]
        
    ],

];  


$spreadsheet->getActiveSheet()->setCellValue('B8', 'Description');
$spreadsheet->getActiveSheet()->getStyle('B8')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('B8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('B8')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('C8', 'Amount');
$spreadsheet->getActiveSheet()->getStyle('C8')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('C8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('C8')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('D8', 'Date Added');
$spreadsheet->getActiveSheet()->getStyle('D8')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('D8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('D8')->getAlignment()->setHorizontal('center');

    unset($styleArray);

   $styleArray = [
      'borders' => [
    
        'left' => [
        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
        'right' => [
        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
        
    ],

];  

$rowCount = 9;
$rowCount = $rowCount-1;
$styleArray2 = [
  'borders' => [

   'top' => [
    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    ],
    'bottom' => [
    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    ],
    'left' => [
    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    ],
    'right' => [
    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    ],
    
    ],

];  

$spreadsheet->getActiveSheet()->getStyle("B".$rowCount.":D".$rowCount)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Store Wallet History Reports');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Seller_wallet_rep.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;


foreach ($sellerTotalWalletList as $result) {
    $spreadsheet->getActiveSheet()->getStyle("B".$rowCount)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->setCellValue('B'.$rowCount, $result['desc']);
    $spreadsheet->getActiveSheet()->getStyle('B'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('B'.$rowCount)->getAlignment()->setHorizontal('left'); 

    $spreadsheet->getActiveSheet()->getStyle("C".$rowCount)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->setCellValue('C'.$rowCount, $result['total']);
    $spreadsheet->getActiveSheet()->getStyle('C'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('C'.$rowCount)->getNumberFormat()->setFormatCode('#,##0.00');

    $spreadsheet->getActiveSheet()->getStyle("J".$rowCount)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->setCellValue('J'.$rowCount, $result['date']);
    $spreadsheet->getActiveSheet()->getStyle('J'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('J'.$rowCount)->getAlignment()->setHorizontal('left');
    $rowCount++;
 }