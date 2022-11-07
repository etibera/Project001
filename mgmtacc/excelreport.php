<?php
// require_once('PhpSpreadsheet/Psr/autoloader.php');
// require_once('PhpSpreadsheet/autoloader.php');

session_start();

    if(!isset($_SESSION['user_id']))  //check unauthorize user not access in "print.php" page
    {
        header("location: index.php");
    }

include "model/Sales_report.php";

$model = new Sales_report();

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
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30.00);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(36);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(30);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(30);
     $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(30);
      $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(30);
      $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(30);
      $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(30);


// Add Row Dimensions
 
    $spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(22.50);
    $spreadsheet->getActiveSheet()->getRowDimension('3')->setRowHeight(22.50);
    $spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(22.50);
    $spreadsheet->getActiveSheet()->getRowDimension('5')->setRowHeight(22.50);
    $spreadsheet->getActiveSheet()->getRowDimension('6')->setRowHeight(22.50);
    $spreadsheet->getActiveSheet()->getRowDimension('7')->setRowHeight(22.50);
    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(46.00);


// Add some data
$spreadsheet->getActiveSheet()->setCellValue('C1', 'PESO Sales Report ');
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


    $spreadsheet->getActiveSheet()->setCellValue('B8', 'Order ID');
    $spreadsheet->getActiveSheet()->getStyle('B8')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('B8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('B8')->getAlignment()->setHorizontal('center');

    $spreadsheet->getActiveSheet()->setCellValue('C8', 'Customer Name');
    $spreadsheet->getActiveSheet()->getStyle('C8')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('C8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('C8')->getAlignment()->setHorizontal('center');

    $spreadsheet->getActiveSheet()->setCellValue('D8', 'Status');
    $spreadsheet->getActiveSheet()->getStyle('D8')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('D8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('D8')->getAlignment()->setHorizontal('center');

    $spreadsheet->getActiveSheet()->setCellValue('E8', 'Mode of Payment');
    $spreadsheet->getActiveSheet()->getStyle('E8')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('E8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('E8')->getAlignment()->setHorizontal('center');

    $spreadsheet->getActiveSheet()->setCellValue('F8', 'Total');
    $spreadsheet->getActiveSheet()->getStyle('F8')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('F8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('F8')->getAlignment()->setHorizontal('center');

    $spreadsheet->getActiveSheet()->setCellValue('G8', 'Successful Sale');
    $spreadsheet->getActiveSheet()->getStyle('G8')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('G8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('G8')->getAlignment()->setHorizontal('center');

    $spreadsheet->getActiveSheet()->setCellValue('H8', 'Bank Transaction');
    $spreadsheet->getActiveSheet()->getStyle('H8')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('H8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('H8')->getAlignment()->setHorizontal('center');

    $spreadsheet->getActiveSheet()->setCellValue('I8', 'OP System Charge');
    $spreadsheet->getActiveSheet()->getStyle('I8')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('I8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('I8')->getAlignment()->setHorizontal('center');

    $spreadsheet->getActiveSheet()->setCellValue('J8', 'Date Added');
    $spreadsheet->getActiveSheet()->getStyle('J8')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('J8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('J8')->getAlignment()->setHorizontal('center');

    $spreadsheet->getActiveSheet()->setCellValue('K8', 'Date of Sales');
    $spreadsheet->getActiveSheet()->getStyle('K8')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('K8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('K8')->getAlignment()->setHorizontal('center');

    $spreadsheet->getActiveSheet()->setCellValue('L8', 'Seller Receipt No');
    $spreadsheet->getActiveSheet()->getStyle('L8')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('L8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('L8')->getAlignment()->setHorizontal('center');

    $spreadsheet->getActiveSheet()->setCellValue('M8', 'Serial No.');
    $spreadsheet->getActiveSheet()->getStyle('M8')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('M8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('M8')->getAlignment()->setHorizontal('center');

    $spreadsheet->getActiveSheet()->setCellValue('N8', 'Remarks');
    $spreadsheet->getActiveSheet()->getStyle('N8')->applyFromArray($styleArray);
    $spreadsheet->getActiveSheet()->getStyle('N8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
    $spreadsheet->getActiveSheet()->getStyle('N8')->getAlignment()->setHorizontal('center');

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

     $TOTAL_Transations=0;
        $Successful_Transactions=0;
        $Total_Charges=0;
        $Bank_Transaction=0;
        $rowCount = 9;

        
        $results = $model->get_sales_report_new();
        foreach ($results as $result) {
            $CHVAL=$result['opSystemCharge'];
            $TOTAL_Transations+=$result['total'];
            if($result['order_status_id']=="20" || $result['order_status_id']=="49"){ $Successful_Transactions+=$result['total']; }
            if($CHVAL!="0"){ $Bank_Transaction+=$result['total']; $Total_Charges+=$result['opSystemCharge'];}

        $spreadsheet->getActiveSheet()->getStyle("B".$rowCount)->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->setCellValue('B'.$rowCount, $result['order_id']);
        $spreadsheet->getActiveSheet()->getStyle('B'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
        $spreadsheet->getActiveSheet()->getStyle('B'.$rowCount)->getAlignment()->setHorizontal('left'); 

        $spreadsheet->getActiveSheet()->getStyle("C".$rowCount)->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->setCellValue('C'.$rowCount, $result['fullname']);
        $spreadsheet->getActiveSheet()->getStyle('C'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
        $spreadsheet->getActiveSheet()->getStyle('C'.$rowCount)->getAlignment()->setHorizontal('left'); 

        $spreadsheet->getActiveSheet()->getStyle("D".$rowCount)->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->setCellValue('D'.$rowCount, $result['statusName']);
        $spreadsheet->getActiveSheet()->getStyle('D'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
        $spreadsheet->getActiveSheet()->getStyle('D'.$rowCount)->getAlignment()->setHorizontal('left'); 

        $spreadsheet->getActiveSheet()->getStyle("E".$rowCount)->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->setCellValue('E'.$rowCount, $result['payment_method']);
        $spreadsheet->getActiveSheet()->getStyle('E'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
        $spreadsheet->getActiveSheet()->getStyle('E'.$rowCount)->getAlignment()->setHorizontal('left');

        $spreadsheet->getActiveSheet()->getStyle("F".$rowCount)->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->setCellValue('F'.$rowCount, $result['total']);
        $spreadsheet->getActiveSheet()->getStyle('F'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
        $spreadsheet->getActiveSheet()->getStyle('F'.$rowCount)->getAlignment()->setHorizontal('left');

        $spreadsheet->getActiveSheet()->getStyle("F".$rowCount)->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->setCellValue('F'.$rowCount, $result['total']);
        $spreadsheet->getActiveSheet()->getStyle('F'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
        $spreadsheet->getActiveSheet()->getStyle('F'.$rowCount)->getNumberFormat()->setFormatCode('#,##0.00');

        if($result['order_status_id']=="20" || $result['order_status_id']=="49"){ 
            $spreadsheet->getActiveSheet()->getStyle("G".$rowCount)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->setCellValue('G'.$rowCount, $result['total']);
            $spreadsheet->getActiveSheet()->getStyle('G'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
            $spreadsheet->getActiveSheet()->getStyle('G'.$rowCount)->getNumberFormat()->setFormatCode('#,##0.00');
        } else{
            $spreadsheet->getActiveSheet()->getStyle("G".$rowCount)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->setCellValue('G'.$rowCount, "");
            $spreadsheet->getActiveSheet()->getStyle('G'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
           
        }

         if($CHVAL!="0"){
             $spreadsheet->getActiveSheet()->getStyle("H".$rowCount)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->setCellValue('H'.$rowCount, $result['total']);
            $spreadsheet->getActiveSheet()->getStyle('H'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
            $spreadsheet->getActiveSheet()->getStyle('H'.$rowCount)->getNumberFormat()->setFormatCode('#,##0.00');

            $spreadsheet->getActiveSheet()->getStyle("I".$rowCount)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->setCellValue('I'.$rowCount, $result['opSystemCharge']);
            $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
            $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('#,##0.00');
        }else {
            $spreadsheet->getActiveSheet()->getStyle("H".$rowCount)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->setCellValue('H'.$rowCount, "");
            $spreadsheet->getActiveSheet()->getStyle('H'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
           

             $spreadsheet->getActiveSheet()->getStyle("I".$rowCount)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->setCellValue('I'.$rowCount, "");
            $spreadsheet->getActiveSheet()->getStyle('I'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
            
        }

        $spreadsheet->getActiveSheet()->getStyle("J".$rowCount)->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->setCellValue('J'.$rowCount, $result['date_added']);
        $spreadsheet->getActiveSheet()->getStyle('J'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
        $spreadsheet->getActiveSheet()->getStyle('J'.$rowCount)->getAlignment()->setHorizontal('left');
        if($result['order_status_id']=="20" || $result['order_status_id']=="49"){ 
            $spreadsheet->getActiveSheet()->getStyle("K".$rowCount)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->setCellValue('K'.$rowCount, $result['date_modified']);
            $spreadsheet->getActiveSheet()->getStyle('K'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
            $spreadsheet->getActiveSheet()->getStyle('K'.$rowCount)->getAlignment()->setHorizontal('left');
        } else{
            $spreadsheet->getActiveSheet()->getStyle("K".$rowCount)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->setCellValue('K'.$rowCount, "");
            $spreadsheet->getActiveSheet()->getStyle('K'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
            $spreadsheet->getActiveSheet()->getStyle('K'.$rowCount)->getAlignment()->setHorizontal('left');

        }
        $spreadsheet->getActiveSheet()->getStyle("L".$rowCount)->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->setCellValue('L'.$rowCount, $result['wr']);
        $spreadsheet->getActiveSheet()->getStyle('L'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
        $spreadsheet->getActiveSheet()->getStyle('L'.$rowCount)->getAlignment()->setHorizontal('left');
        $serial_nos="";
        foreach ($model->get_serial($result['order_id']) as $serial) { 
                $serial_nos.= $serial['serial'].' ,';            
        }
        $spreadsheet->getActiveSheet()->getStyle("M".$rowCount)->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->setCellValue('M'.$rowCount,  $serial_nos);
        $spreadsheet->getActiveSheet()->getStyle('M'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
        $spreadsheet->getActiveSheet()->getStyle('M'.$rowCount)->getAlignment()->setHorizontal('left');

        $spreadsheet->getActiveSheet()->getStyle("N".$rowCount)->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->setCellValue('N'.$rowCount, "");
        $spreadsheet->getActiveSheet()->getStyle('N'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
        $spreadsheet->getActiveSheet()->getStyle('N'.$rowCount)->getAlignment()->setHorizontal('right');

          $rowCount ++;
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

        $spreadsheet->getActiveSheet()->setCellValue('F5', 'TOTAL Transactions');
        $spreadsheet->getActiveSheet()->getStyle('F5')->applyFromArray($styleArray2);
        $spreadsheet->getActiveSheet()->getStyle('F5')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
        $spreadsheet->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal('center');    

        $spreadsheet->getActiveSheet()->setCellValue('G5', 'Successful Transactions');
        $spreadsheet->getActiveSheet()->getStyle('G5')->applyFromArray($styleArray2);
        $spreadsheet->getActiveSheet()->getStyle('G5')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
        $spreadsheet->getActiveSheet()->getStyle('G5')->getAlignment()->setHorizontal('center');    

        $spreadsheet->getActiveSheet()->setCellValue('H5', 'Bank Transactions');
        $spreadsheet->getActiveSheet()->getStyle('H5')->applyFromArray($styleArray2);
        $spreadsheet->getActiveSheet()->getStyle('H5')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
        $spreadsheet->getActiveSheet()->getStyle('H5')->getAlignment()->setHorizontal('center');  

        $spreadsheet->getActiveSheet()->setCellValue('I5', 'Total Charges');
        $spreadsheet->getActiveSheet()->getStyle('I5')->applyFromArray($styleArray2);
        $spreadsheet->getActiveSheet()->getStyle('I5')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
        $spreadsheet->getActiveSheet()->getStyle('I5')->getAlignment()->setHorizontal('center'); 


        $spreadsheet->getActiveSheet()->getStyle("F6")->applyFromArray($styleArray2);
        $spreadsheet->getActiveSheet()->setCellValue('F6', '=SUBTOTAL(9,F9:F'.($rowCount).')');
        $spreadsheet->getActiveSheet()->getStyle('F6')->getFont()->setSize(18)->setBold(false)->setName("Calibri");
        $spreadsheet->getActiveSheet()->getStyle('F6')->getNumberFormat()->setFormatCode('#,##0.00'); 

        $spreadsheet->getActiveSheet()->getStyle("G6")->applyFromArray($styleArray2);
        $spreadsheet->getActiveSheet()->setCellValue('G6', '=SUBTOTAL(9,G9:G'.($rowCount).')');
        $spreadsheet->getActiveSheet()->getStyle('G6')->getFont()->setSize(18)->setBold(false)->setName("Calibri");
        $spreadsheet->getActiveSheet()->getStyle('G6')->getNumberFormat()->setFormatCode('#,##0.00'); 

        $spreadsheet->getActiveSheet()->getStyle("H6")->applyFromArray($styleArray2);
        $spreadsheet->getActiveSheet()->setCellValue('H6', '=SUBTOTAL(9,H9:H'.($rowCount).')');
        $spreadsheet->getActiveSheet()->getStyle('H6')->getFont()->setSize(18)->setBold(false)->setName("Calibri");
        $spreadsheet->getActiveSheet()->getStyle('H6')->getNumberFormat()->setFormatCode('#,##0.00'); 

        $spreadsheet->getActiveSheet()->getStyle("I6")->applyFromArray($styleArray2);
        $spreadsheet->getActiveSheet()->setCellValue('I6', '=SUBTOTAL(9,I9:I'.($rowCount).')');
        $spreadsheet->getActiveSheet()->getStyle('I6')->getFont()->setSize(18)->setBold(false)->setName("Calibri");
        $spreadsheet->getActiveSheet()->getStyle('I6')->getNumberFormat()->setFormatCode('#,##0.00'); 


        $spreadsheet->getActiveSheet()->getStyle("F6:I6")->applyFromArray($styleArray2);

        $spreadsheet->getActiveSheet()->getStyle("B".$rowCount.":N".$rowCount)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



   
// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('PESO Sales Report');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="PESO Sales Report.xlsx"');
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


