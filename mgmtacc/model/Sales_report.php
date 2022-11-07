<?php
require_once "../include/database.php";
class Sales_report{
        private $conn;   
        public function __construct()
        {
                $this->conn = new Database();
                $this->conn = $this->conn->getmyDB();
        }

	public function get_sales_report() {
        
        $s = $this->conn->prepare("SELECT oc.order_id,oc.wr,CONCAT(oc.firstname, ' ', oc.lastname) AS fullname ,oc.order_status_id,ocs.name,oc.payment_method,oc.total,oct.title,oct.value ,oc.date_added,oc.date_modified FROM oc_order oc INNER JOIN oc_order_status ocs ON oc.order_status_id=ocs.order_status_id LEFT JOIN oc_order_total oct ON oct.order_id=oc.order_id and oct.title='(OP) system charges' order by oc.date_modified desc");
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
   
    }
    public function get_sales_report_new() {
        $salesReportVal=array();
        $s = $this->conn->prepare("SELECT *,CONCAT(oc.firstname, ' ', oc.lastname) AS fullname,ocs.name as statusName,
                                    ot.value as grandTotal 
                                    FROM oc_order oc 
                                    INNER JOIN oc_order_status ocs ON oc.order_status_id=ocs.order_status_id
                                    INNER JOIN oc_order_total ot ON ot.order_id=oc.order_id
                                    where ot.title='Total' and oc.order_status_id > 0
                                    order by oc.date_modified desc");

        $s->execute();
        $dataval = $s->fetchAll(PDO::FETCH_ASSOC);        
        foreach ($dataval as $row) {
            $opSystemCharge=0;
            if($row['payment_code']=="maxx_payment" || $row['payment_code']=="credit_card"){
                $opSystemCharge=$this->SalesReportSubTotaL($row['order_id'],"Convenience Fee");
            }
            $salesReportVal[] = array(
                'opSystemCharge' =>$opSystemCharge,
                'order_id' => $row['order_id'],
                'fullname' => $row['fullname'],
                'statusName' => $row['statusName'],
                'payment_method' => $row['payment_method'],
                'total' => $row['grandTotal'],
                'date_modified' => $row['date_modified'],
                'date_added' => $row['date_added'],
                'order_status_id' => $row['order_status_id'],
                'wr' => $row['wr'],
            );
        }
        return $salesReportVal; 
   
    }
    public function get_serial($order_id) {        
        $s = $this->conn->prepare("SELECT * from oc_product_serial WHERE order_id=:order_id");
        $s->bindValue(':order_id', $order_id);
        $s->execute();
        $status = $s->fetchAll(PDO::FETCH_ASSOC);
        return $status;
    }
    public function SalesReportSubTotaL($order_id,$title) {  
        $data=array();      
        $s = $this->conn->prepare("SELECT * from oc_order_total  WHERE order_id=:order_id and title=:title");
        $s->bindValue(':order_id', $order_id);
        $s->bindValue(':title', $title);
        $s->execute();
        $data = $s->fetch(PDO::FETCH_ASSOC);
        return $data['value'];
    }

    public function print() {
        
    ini_set('max_execution_time', 0);
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
    date_default_timezone_set('Europe/London');

    if (PHP_SAPI == 'cli')
        die('This example should only be run from a Web Browser');

    /** Include PHPExcel */
    require_once '../PHPExcel.php';
    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    
    // Set document properties
    $objPHPExcel->getProperties()->setCreator("receive")
                                 ->setLastModifiedBy("receive")
                                 ->setTitle("Office 2007 XLSX Test Document")
                                 ->setSubject("Office 2007 XLSX Test Document")
                                 ->setDescription("Cash Excel")
                                 ->setKeywords("office 2007 openxml php")
                                 ->setCategory("excel result file");

    //$gdImage = imagecreatefromjpeg('accenthub.jpg');
    $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
    //$objDrawing->setName('Sample image');
    //$objDrawing->setDescription('Sample image');
    //$objDrawing->setImageResource($gdImage);
    $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
    $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
    $objDrawing->setHeight(70);
    $objDrawing->setCoordinates('B1');
    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save(str_replace('.php', '.xlsx', __FILE__));

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5.5);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30.00);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(36);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15.00);

    $objPHPExcel->getActiveSheet()->getRowDimension('A2')->setRowHeight(22.50);
    $objPHPExcel->getActiveSheet()->getRowDimension('A3')->setRowHeight(22.50);
    $objPHPExcel->getActiveSheet()->getRowDimension('A4')->setRowHeight(22.50);
    $objPHPExcel->getActiveSheet()->getRowDimension('A5')->setRowHeight(22.50);
    $objPHPExcel->getActiveSheet()->getRowDimension('A6')->setRowHeight(22.50);
    $objPHPExcel->getActiveSheet()->getRowDimension('A7')->setRowHeight(22.50);

    $objPHPExcel->getActiveSheet()->getRowDimension('C1')->setRowHeight(46.00);

        $objPHPExcel->setActiveSheetIndex()->setCellValue('C1', 'PESO Sales Report ');
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setSize(26)->setBold(true)->setName("Adobe Arabic");
        $objPHPExcel->getActiveSheet()->mergeCells('C1:K1');
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->applyFromArray(
            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
        );


    $objPHPExcel->setActiveSheetIndex()->setCellValue('B5', 'Date Printed');
        $objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setSize(14)->setName("Calibri");
    $objPHPExcel->setActiveSheetIndex()->setCellValue('C5', date("Y-m-d"));
        $objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setSize(14)->setName("Calibri");

    //Title
    $styleArray = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN
        )
      )
    );  

        $objPHPExcel->setActiveSheetIndex()->setCellValue('B8', 'Order ID');
        $objPHPExcel->getActiveSheet()->getStyle('B8:L8')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('B8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
        $objPHPExcel->getActiveSheet()->getStyle('B8')->getAlignment()->applyFromArray(
            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
        );
    $objPHPExcel->setActiveSheetIndex()->setCellValue('C8', 'Customer Name');
        $objPHPExcel->getActiveSheet()->getStyle('C8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
        $objPHPExcel->getActiveSheet()->getStyle('C8')->getAlignment()->applyFromArray(
            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
        );
    $objPHPExcel->setActiveSheetIndex()->setCellValue('D8', 'Status');
        $objPHPExcel->getActiveSheet()->getStyle('D8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
        $objPHPExcel->getActiveSheet()->getStyle('D8')->getAlignment()->applyFromArray(
            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
        );
    $objPHPExcel->setActiveSheetIndex()->setCellValue('E8', 'Mode of Payment');
        $objPHPExcel->getActiveSheet()->getStyle('E8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
        $objPHPExcel->getActiveSheet()->getStyle('E8')->getAlignment()->applyFromArray(
            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
        );
    $objPHPExcel->setActiveSheetIndex()->setCellValue('F8', 'Total');
        $objPHPExcel->getActiveSheet()->getStyle('F8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
        $objPHPExcel->getActiveSheet()->getStyle('F8')->getAlignment()->applyFromArray(
            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
        );
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G8', ' Successful Sale');
        $objPHPExcel->getActiveSheet()->getStyle('G8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
        $objPHPExcel->getActiveSheet()->getStyle('G8')->getAlignment()->applyFromArray(
            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
        );
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H8', ' Bank Transaction');
        $objPHPExcel->getActiveSheet()->getStyle('H8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
        $objPHPExcel->getActiveSheet()->getStyle('H8')->getAlignment()->applyFromArray(
            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
        );
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I8', '  OP System charge ');
        $objPHPExcel->getActiveSheet()->getStyle('I8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
        $objPHPExcel->getActiveSheet()->getStyle('I8')->getAlignment()->applyFromArray(
            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
        );
        $objPHPExcel->setActiveSheetIndex()->setCellValue('J8', ' Date Added ');
        $objPHPExcel->getActiveSheet()->getStyle('J8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
        $objPHPExcel->getActiveSheet()->getStyle('J8')->getAlignment()->applyFromArray(
            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
        );  
        $objPHPExcel->setActiveSheetIndex()->setCellValue('K8', 'Date Modified ');
        $objPHPExcel->getActiveSheet()->getStyle('K8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
        $objPHPExcel->getActiveSheet()->getStyle('K8')->getAlignment()->applyFromArray(
            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
        );
        $objPHPExcel->setActiveSheetIndex()->setCellValue('L8', 'Remarks');
        $objPHPExcel->getActiveSheet()->getStyle('L8')->getFont()->setSize(14)->setBold(false)->setName("Calibri");
        $objPHPExcel->getActiveSheet()->getStyle('L8')->getAlignment()->applyFromArray(
            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
        );

        unset($styleArray);

        $TOTAL_Transations=0;
        $Successful_Transactions=0;
        $Total_Charges=0;
        $Bank_Transaction=0;
        $rowCount = 9;

        
        $results = $this->get_sales_report();
        foreach ($results as $result) {
            $CHVAL=$this->currency->format($result['value']);
            $TOTAL_Transations+=$result['total'];
            if($result['name']=="Order Complete/Received by EU"){ $Successful_Transactions+=$result['total']; }
            if($CHVAL!="₱0.00"){ $Bank_Transaction+=$result['total']; $Total_Charges+=$result['value'];} 

            $styleArray = array(
                'borders' => array(
                    'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    )
                )
            );

            $objPHPExcel->getActiveSheet()->getStyle("B".$rowCount)->applyFromArray($styleArray);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$rowCount, $result['order_id']);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
            $objPHPExcel->getActiveSheet()->getStyle('B'.$rowCount)->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
            );

            $objPHPExcel->getActiveSheet()->getStyle("C".$rowCount)->applyFromArray($styleArray);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$rowCount, $result['fullname']);
            $objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
            $objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
            );
            $objPHPExcel->getActiveSheet()->getStyle("D".$rowCount)->applyFromArray($styleArray);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$rowCount, $result['name']);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
            $objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
            );
            $objPHPExcel->getActiveSheet()->getStyle("E".$rowCount)->applyFromArray($styleArray);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$rowCount, $result['payment_method']);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
            $objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
            );
            $objPHPExcel->getActiveSheet()->getStyle("F".$rowCount)->applyFromArray($styleArray);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$rowCount, $result['total']);
            $objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
            $objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->getNumberFormat()->setFormatCode('#,##0.00');
        
            if($result['name']=="Order Complete/Received by EU"){ 
                $objPHPExcel->getActiveSheet()->getStyle("G".$rowCount)->applyFromArray($styleArray);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$rowCount, $result['total']);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
                $objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->getNumberFormat()->setFormatCode('#,##0.00');
                
            } else{
                $objPHPExcel->getActiveSheet()->getStyle("G".$rowCount)->applyFromArray($styleArray);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$rowCount, "");
                $objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
                
            }
             if($CHVAL!="₱0.00"){
                $objPHPExcel->getActiveSheet()->getStyle("H".$rowCount)->applyFromArray($styleArray);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$rowCount, $result['total']);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->getNumberFormat()->setFormatCode('#,##0.00');
                $objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
                
                $objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->applyFromArray($styleArray);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$rowCount, $result['value']);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->getNumberFormat()->setFormatCode('#,##0.00');
                $objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
                
              } else{ 
                $objPHPExcel->getActiveSheet()->getStyle("H".$rowCount)->applyFromArray($styleArray);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$rowCount, "");
                $objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
                
                $objPHPExcel->getActiveSheet()->getStyle("I".$rowCount)->applyFromArray($styleArray);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$rowCount, "");
                $objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
                
              }

              $objPHPExcel->getActiveSheet()->getStyle("J".$rowCount)->applyFromArray($styleArray);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$rowCount,  $result['date_added']);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
                $objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
                );

                 $objPHPExcel->getActiveSheet()->getStyle("K".$rowCount)->applyFromArray($styleArray);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('K'.$rowCount,  $result['date_modified']);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
                $objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
                );
                $objPHPExcel->getActiveSheet()->getStyle("L".$rowCount)->applyFromArray($styleArray);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('L'.$rowCount, "");
                $objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->getFont()->setSize(11)->setBold(false)->setName("Calibri");
               $objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
               );


            $rowCount ++;
        }
        $rowCount = $rowCount-1;

        $styleArray2 = array(
             'borders' => array(
                'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
                )
              )
        );
        $objPHPExcel->getActiveSheet()->getStyle("F5")->applyFromArray($styleArray2);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F5', "TOTAL Transations");
        $objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setSize(14)->setBold(false)->setName("Calibri");

        $objPHPExcel->getActiveSheet()->getStyle("G5")->applyFromArray($styleArray2);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G5', " Successful Transactions ");
        $objPHPExcel->getActiveSheet()->getStyle('G5')->getFont()->setSize(14)->setBold(false)->setName("Calibri");

        $objPHPExcel->getActiveSheet()->getStyle("H5")->applyFromArray($styleArray2);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H5', " Bank Transaction ");
        $objPHPExcel->getActiveSheet()->getStyle('H5')->getFont()->setSize(14)->setBold(false)->setName("Calibri");

        $objPHPExcel->getActiveSheet()->getStyle("I5")->applyFromArray($styleArray2);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I5', "Total Charges");
        $objPHPExcel->getActiveSheet()->getStyle('I5')->getFont()->setSize(14)->setBold(false)->setName("Calibri");


        $styleArray = array(
            'borders' => array(
                'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'left' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'right' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    )
            )
        );
    $objPHPExcel->getActiveSheet()->getStyle("F6")->applyFromArray($styleArray);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('F6', '=SUBTOTAL(9,F9:F'.($rowCount).')');
    $objPHPExcel->getActiveSheet()->getStyle('F6')->getFont()->setSize(18)->setBold(false)->setName("Calibri");
    $objPHPExcel->getActiveSheet()->getStyle('F6')->getNumberFormat()->setFormatCode('#,##0.00');

    $objPHPExcel->getActiveSheet()->getStyle("G6")->applyFromArray($styleArray);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G6', '=SUBTOTAL(9,G9:G'.($rowCount).')');
    $objPHPExcel->getActiveSheet()->getStyle('G6')->getFont()->setSize(18)->setBold(false)->setName("Calibri");
    $objPHPExcel->getActiveSheet()->getStyle('G6')->getNumberFormat()->setFormatCode('#,##0.00');

    $objPHPExcel->getActiveSheet()->getStyle("H6")->applyFromArray($styleArray);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('H6', '=SUBTOTAL(9,H9:H'.($rowCount).')');
    $objPHPExcel->getActiveSheet()->getStyle('H6')->getFont()->setSize(18)->setBold(false)->setName("Calibri");
    $objPHPExcel->getActiveSheet()->getStyle('H6')->getNumberFormat()->setFormatCode('#,##0.00');

    $objPHPExcel->getActiveSheet()->getStyle("I6")->applyFromArray($styleArray);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('I6', '=SUBTOTAL(9,I9:I'.($rowCount).')');
    $objPHPExcel->getActiveSheet()->getStyle('I6')->getFont()->setSize(18)->setBold(false)->setName("Calibri");
    $objPHPExcel->getActiveSheet()->getStyle('I6')->getNumberFormat()->setFormatCode('#,##0.00');

    $objPHPExcel->getActiveSheet()->getStyle("F6:I6")->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle("B".$rowCount.":L".$rowCount)->applyFromArray($styleArray);


    $title = "PESO Sales Report.xlsx";


    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle( $title );
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename='.$title);
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
            
        }


    
	
}