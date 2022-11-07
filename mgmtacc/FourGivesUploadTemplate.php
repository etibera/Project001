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
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(40);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(40);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(50);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(50);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(50);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(50);
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(50);
$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(50);
$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(50);


// Add Row Dimensions
 
$spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(22.50);
$spreadsheet->getActiveSheet()->getRowDimension('3')->setRowHeight(22.50);
$spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(22.50);
$spreadsheet->getActiveSheet()->getRowDimension('5')->setRowHeight(22.50);
$spreadsheet->getActiveSheet()->getRowDimension('6')->setRowHeight(22.50);
$spreadsheet->getActiveSheet()->getRowDimension('7')->setRowHeight(22.50);
$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(46.00);


// Add some data
$spreadsheet->getActiveSheet()->setCellValue('A1', 'Customer Upload Template');
$spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setSize(26)->setBold(true)->setName("Adobe Arabic");
$spreadsheet->getActiveSheet()->mergeCells('A1:F1');
$spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');


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


$spreadsheet->getActiveSheet()->setCellValue('A2', 'First Name');
$spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('A2')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('B2', 'Last Name');
$spreadsheet->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('B2')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('C2', 'Email Address');
$spreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('C2')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('D2', 'Telephone (10 digts Only)');
$spreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('D2')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('E2', 'Birthday (yyyy-mm-dd)');
$spreadsheet->getActiveSheet()->getStyle('E2')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('E2')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('F2', 'House Number/Street/Building Name');
$spreadsheet->getActiveSheet()->getStyle('F2')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('F2')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('G2', 'Unit/Floor');
$spreadsheet->getActiveSheet()->getStyle('G2')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('G2')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('H2', 'Barangay/District');
$spreadsheet->getActiveSheet()->getStyle('H2')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('H2')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('I2', 'City/Municipality');
$spreadsheet->getActiveSheet()->getStyle('I2')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('I2')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('I2')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('J2', 'Region/Province');
$spreadsheet->getActiveSheet()->getStyle('J2')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('J2')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('J2')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('K2', 'Postal Code/Zip code');
$spreadsheet->getActiveSheet()->getStyle('K2')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('K2')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('K2')->getAlignment()->setHorizontal('center');




unset($styleArray);

// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Store Wallet History Reports');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="fourGivesCustomerUpload.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = IOFactory::createWriter($spreadsheet, 'Xls');
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