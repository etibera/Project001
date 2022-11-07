<?php

    require_once('../composer/vendor/autoload.php');
    use PhpOffice\PhpSpreadsheet\Helper\Sample;
    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    require_once "model/SellerPendingPayables.php";
    $model=new SPayables;
    if(isset($_GET['date_to'])){
     $date_to=$_GET['date_to'];
    }else{
        $date_to="notset";
    }
    if(isset($_GET['date_from'])){
        $date_from=$_GET['date_from'];
    }else{
        $date_from="notset";
    }
    $status=0;
    $data=array();
    if(isset($_GET['stats'])){
        $status=$_GET['stats'];
        if($status=="All"){
            $data=$model->getSPP_listAll($date_from,$date_to);
        }else{
            $data=$model->getSPP_list($status,$date_from,$date_to);
        }
    }else{
        $data=$model->getSPP_list(0,$date_from,$date_to);
    }
    
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
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(40);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(40);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(25);
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(30);
$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(30);
$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(30);


// Add Row Dimensions
 
$spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(22.50);
$spreadsheet->getActiveSheet()->getRowDimension('3')->setRowHeight(22.50);
$spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(22.50);
$spreadsheet->getActiveSheet()->getRowDimension('5')->setRowHeight(22.50);
$spreadsheet->getActiveSheet()->getRowDimension('6')->setRowHeight(22.50);
$spreadsheet->getActiveSheet()->getRowDimension('7')->setRowHeight(22.50);
$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(46.00);


// Add some data
$spreadsheet->getActiveSheet()->setCellValue('C1', ' Payables Reports');
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


$spreadsheet->getActiveSheet()->setCellValue('B8', 'Order Id');
$spreadsheet->getActiveSheet()->getStyle('B8')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('B8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('B8')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('C8', 'Store name');
$spreadsheet->getActiveSheet()->getStyle('C8')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('C8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('C8')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('D8', 'Bank Name');
$spreadsheet->getActiveSheet()->getStyle('D8')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('D8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('D8')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('E8', 'Bank Account Name');
$spreadsheet->getActiveSheet()->getStyle('E8')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('E8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('E8')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('F8', 'Bank Account No');
$spreadsheet->getActiveSheet()->getStyle('F8')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('F8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('F8')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('G8', 'Amount');
$spreadsheet->getActiveSheet()->getStyle('G8')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('G8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('G8')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('H8', 'Reference Number');
$spreadsheet->getActiveSheet()->getStyle('H8')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('H8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('H8')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('I8', 'Status');
$spreadsheet->getActiveSheet()->getStyle('I8')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('I8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('I8')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->setCellValue('J8', 'Date Added');
$spreadsheet->getActiveSheet()->getStyle('J8')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('J8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
$spreadsheet->getActiveSheet()->getStyle('J8')->getAlignment()->setHorizontal('center');
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
foreach ($data as $result) {
    $spreadsheet->getActiveSheet()->getStyle("B".$rowCount)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->setCellValue('B'.$rowCount, $result['order_id']);
    $spreadsheet->getActiveSheet()->getStyle('B'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('B'.$rowCount)->getAlignment()->setHorizontal('center'); 

    $spreadsheet->getActiveSheet()->getStyle("C".$rowCount)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->setCellValue('C'.$rowCount, $result['shop_name']);
    $spreadsheet->getActiveSheet()->getStyle('C'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('C'.$rowCount)->getAlignment()->setHorizontal('left'); 

    $spreadsheet->getActiveSheet()->getStyle("D".$rowCount)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->setCellValue('D'.$rowCount, $result['bank_name']);
    $spreadsheet->getActiveSheet()->getStyle('D'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('D'.$rowCount)->getAlignment()->setHorizontal('left'); 

    $spreadsheet->getActiveSheet()->getStyle("E".$rowCount)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->setCellValue('E'.$rowCount, $result['bank_account_name']);
    $spreadsheet->getActiveSheet()->getStyle('E'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('E'.$rowCount)->getAlignment()->setHorizontal('left'); 

    $spreadsheet->getActiveSheet()->getStyle("F".$rowCount)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->setCellValue('F'.$rowCount, $result['bank_account_no']);
    $spreadsheet->getActiveSheet()->getStyle('F'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('F'.$rowCount)->getAlignment()->setHorizontal('left'); 

    $spreadsheet->getActiveSheet()->getStyle("G".$rowCount)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->setCellValue('G'.$rowCount, $result['amount']);
    $spreadsheet->getActiveSheet()->getStyle('G'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('G'.$rowCount)->getNumberFormat()->setFormatCode('#,##0.00');
       
    $spreadsheet->getActiveSheet()->getStyle("H".$rowCount)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->setCellValue('H'.$rowCount,$result['reference_number']);
    $spreadsheet->getActiveSheet()->getStyle('H'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('H'.$rowCount)->getAlignment()->setHorizontal('left'); 
    if($result['status']=="0"){ 
        $pStatus="Pending";
    }else{ 
         $pStatus="Paid";
    } 
    $spreadsheet->getActiveSheet()->getStyle("I".$rowCount)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->setCellValue('I'.$rowCount, $pStatus);
    $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getAlignment()->setHorizontal('left'); 

    $spreadsheet->getActiveSheet()->getStyle("J".$rowCount)->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->setCellValue('J'.$rowCount, $result['date']);
    $spreadsheet->getActiveSheet()->getStyle('J'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('J'.$rowCount)->getAlignment()->setHorizontal('left'); 

    $rowCount++;
}
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

$spreadsheet->getActiveSheet()->getStyle("B".$rowCount.":J".$rowCount)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Store Wallet History Reports');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="PayablesReport.xlsx"');
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