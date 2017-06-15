<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require('fpdf/fpdf.php');
class Trucking_list extends CI_Controller
{
    function __construct() {
        parent::__construct();
    }

    function pdf() {
        $shipping_id = $this->input->get('id');
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->Image('assets/image/agp.png',20,6,20);

        $this->load->model('agripro/shipping');
        $tShipping = $this->shipping;
        $itemShipping = $tShipping->get($shipping_id);

        $this->load->model('agripro/shipping_detail');
        $tShippingDetail = $this->shipping_detail;
        $tShippingDetail->setCriteria('shipdet.shipping_id = '.$shipping_id);
        $itemsDetail = $tShippingDetail->getAll(0,-1);

        $pdf->SetFont('Arial','B',20);
        $pdf->Cell(190,15,'PACKING LIST','',0,'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',12);

        $pdf->Ln(10);
        $pdf->Cell(11,8,'','',0,'L');
        $pdf->Cell(30,8,'Driver Name ','',0,'L');
        $pdf->Cell(5,8,':','',0,'C');
        $pdf->Cell(50,8,$itemShipping['shipping_driver_name'],'',0,'L');
        $pdf->Cell(35,8,'Trucking Date ','',0,'L');
        $pdf->Cell(5,8,':','',0,'C');
        $pdf->Cell(40,8,$itemShipping['shipping_date'],'',0,'L');
        $pdf->Ln();

        $pdf->Cell(11,8,'','',0,'L');
        $pdf->Cell(30,8,'Police No ','',0,'L');
        $pdf->Cell(5,8,':','',0,'C');
        $pdf->Cell(40,8,$itemShipping['shipping_police_no'],'',0,'L');

        $pdf->Ln(15);
        $pdf->SetFont('Arial','B',15);
        $pdf->SetLineWidth(.5);
        $pdf->Cell(190,7,'Items List : ','TB',0,'L');

        $pdf->Ln(15);
        $pdf->SetFont('Arial','B',10);
        $pdf->SetLineWidth(.1);

        $header = array('#', 'Product Code', 'Serial', 'Batch Number', 'Weight (Kg)', 'Warehouse');
        $position = array('C', 'L', 'L', 'L', 'R', 'L');
        $width = array(10, 40, 60, 30, 25, 25);
        for($i = 0; $i < count($header); $i++)
            $pdf->Cell($width[$i],7,$header[$i],'LBRT',0,'L');
        $pdf->Ln();

        $i = 1;
        $pdf->SetFont('Arial','',9);
        foreach($itemsDetail as $detail) {
            $pdf->Cell($width[0],5,$i++,'LRTB',0,$position[0]);
            $pdf->Cell($width[1],5,$detail['product_code'],'LRTB',0,$position[1]);
            $pdf->Cell($width[2],5,$detail['packing_serial'],'LRTB',0,$position[2]);
            $pdf->Cell($width[3],5,$detail['packing_batch_number'],'LRTB',0,$position[3]);
            $pdf->Cell($width[4],5,$detail['packing_weight'],'LRTB',0,$position[4]);
            $pdf->Cell($width[5],5,$detail['wh_code'],'LRTB',0,$position[5]);
            $pdf->Ln();
        }


        $pdf->Output();
    }

}

/* End of file trucking_list.php */
/* Location: ./application/controllers/Trucking_list.php */