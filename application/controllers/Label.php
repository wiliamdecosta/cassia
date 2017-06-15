<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require('fpdf/fpdf.php');
class Label extends CI_Controller
{
    function __construct() {
        parent::__construct();
    }

    function packing_label() {

        $id = $this->input->get('id');
        $this->load->model('agripro/packing');
        $tPacking = $this->packing;

        $item = $tPacking->get($id);

        $pdf = new FPDF("P","mm",array(150,150));
        $pdf->SetTitle('Label Packing');
        $pdf->AddPage();
        $pdf->Image('assets/image/label_packing_header.png',35,6,80);

        $pdf->Ln(20);

        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(130,8,'ORGANIC TRUE CASSIA KOERINTJI','',0,'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',9);

        $pdf->Ln(10);
        $pdf->Cell(5,6,'','',0,'C');
        $pdf->Cell(40,6,'CU NUMBER','',0,'L');
        $pdf->Cell(5,6,':','',0,'C');
        $pdf->Cell(70,6,'CU-847073','',0,'L');
        $pdf->Ln();
        $pdf->Cell(5,6,'','',0,'C');
        $pdf->Cell(40,6,'ITEM','',0,'L');
        $pdf->Cell(5,6,':','',0,'C');
        $pdf->Cell(70,6,$item['product_name'],'',0,'L');

        $pdf->Ln();
        $pdf->Cell(120,6,'','',0,'C');
        $pdf->Ln();

        $pdf->Cell(5,6,'','',0,'C');
        $pdf->Cell(40,6,'SERIAL NUMBER','',0,'L');
        $pdf->Cell(5,6,':','',0,'C');
        $pdf->Cell(70,6,$item['packing_serial'],'',0,'L');
        $pdf->Ln();
        $pdf->Cell(5,6,'','',0,'C');
        $pdf->Cell(40,6,'BATCH NUMBER','',0,'L');
        $pdf->Cell(5,6,':','',0,'C');
        $pdf->Cell(70,6,$item['packing_batch_number'],'',0,'L');
        $pdf->Ln();
        $pdf->Cell(5,6,'','',0,'C');
        $pdf->Cell(40,6,'ORIGIN','',0,'L');
        $pdf->Cell(5,6,':','',0,'C');
        $pdf->Cell(70,6,'KERINCI','',0,'L');

        $pdf->Ln();
        $pdf->Cell(120,6,'','',0,'C');
        $pdf->Ln();

        $pdf->Cell(5,6,'','',0,'C');
        $pdf->Cell(40,6,'PACKAGING DATE','',0,'L');
        $pdf->Cell(5,6,':','',0,'C');
        $pdf->Cell(70,6,$item['packing_date'],'',0,'L');

        $pdf->Ln();
        $pdf->Cell(120,6,'','',0,'C');
        $pdf->Ln();

        $pdf->Cell(5,6,'','',0,'C');
        $pdf->Cell(40,6,'GROSS WEIGHT','',0,'L');
        $pdf->Cell(5,6,':','',0,'C');
        $pdf->Cell(70,6,$item['packing_weight'].' Kg','',0,'L');
        $pdf->Ln();
        $pdf->Cell(5,6,'','',0,'C');
        $pdf->Cell(40,6,'BEST BEFORE','',0,'L');
        $pdf->Cell(5,6,':','',0,'C');
        $pdf->Cell(70,6,'-','',0,'L');



        $pdf->Image('assets/image/agripro.gif',110,75,30);
        $pdf->Image(base_url().'label/barcode?val='.$item['packing_serial'],110,110,30,12,'PNG');
        $pdf->Image('assets/image/label_packing_footer.png',8,130,130);


        $pdf->Output();
    }


    function packing_label_bizhub() {

        $id = $this->input->get('id');
        $this->load->model('agripro/packing_bizhub');
        $tPacking = $this->packing_bizhub;

        $item = $tPacking->get($id);

        $pdf = new FPDF("P","mm",array(150,150));
        $pdf->SetTitle('Label Packing');
        $pdf->AddPage();
        $pdf->Image('assets/image/label_packing_header.png',35,6,80);

        $pdf->Ln(20);

        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(130,8,'ORGANIC TRUE CASSIA KOERINTJI','',0,'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',9);

        $pdf->Ln(10);
        $pdf->Cell(5,6,'','',0,'C');
        $pdf->Cell(40,6,'CU NUMBER','',0,'L');
        $pdf->Cell(5,6,':','',0,'C');
        $pdf->Cell(70,6,'CU-847073','',0,'L');
        $pdf->Ln();
        $pdf->Cell(5,6,'','',0,'C');
        $pdf->Cell(40,6,'ITEM','',0,'L');
        $pdf->Cell(5,6,':','',0,'C');
        $pdf->Cell(70,6,$item['product_name'],'',0,'L');

        $pdf->Ln();
        $pdf->Cell(120,6,'','',0,'C');
        $pdf->Ln();

        $pdf->Cell(5,6,'','',0,'C');
        $pdf->Cell(40,6,'SERIAL NUMBER','',0,'L');
        $pdf->Cell(5,6,':','',0,'C');
        $pdf->Cell(70,6,$item['packing_bizhub_batch_number'],'',0,'L');
        $pdf->Ln();
        $pdf->Cell(5,6,'','',0,'C');
        $pdf->Cell(40,6,'BATCH NUMBER','',0,'L');
        $pdf->Cell(5,6,':','',0,'C');
        $pdf->Cell(70,6,$item['packing_bizhub_serial'],'',0,'L');
        $pdf->Ln();
        $pdf->Cell(5,6,'','',0,'C');
        $pdf->Cell(40,6,'ORIGIN','',0,'L');
        $pdf->Cell(5,6,':','',0,'C');
        $pdf->Cell(70,6,'Jakarta','',0,'L');

        $pdf->Ln();
        $pdf->Cell(120,6,'','',0,'C');
        $pdf->Ln();

        $pdf->Cell(5,6,'','',0,'C');
        $pdf->Cell(40,6,'PRODUCTION DATE','',0,'L');
        $pdf->Cell(5,6,':','',0,'C');
        $pdf->Cell(70,6,$item['packing_bizhub_date'],'',0,'L');

        $pdf->Ln();
        $pdf->Cell(120,6,'','',0,'C');
        $pdf->Ln();

        $pdf->Cell(5,6,'','',0,'C');
        $pdf->Cell(40,6,'GROSS WEIGHT','',0,'L');
        $pdf->Cell(5,6,':','',0,'C');
        $pdf->Cell(70,6,$item['packing_bizhub_kg'].' Kg','',0,'L');
        $pdf->Ln();
        $pdf->Cell(5,6,'','',0,'C');
        $pdf->Cell(40,6,'BEST BEFORE','',0,'L');
        $pdf->Cell(5,6,':','',0,'C');
        $pdf->Cell(70,6,'-','',0,'L');

        $pdf->Image('assets/image/agripro.gif',110,75,30);
        $pdf->Image(base_url().'label/barcode?val='.$item['packing_bizhub_batch_number'],110,110,30,12,'PNG');
        $pdf->Image('assets/image/label_packing_footer.png',8,130,130);

        $pdf->Output();
    }


    public function barcode() {
        $value = $this->input->get('val');

        require('brcgenerator/BarcodeGenerator.php');
        require('brcgenerator/BarcodeGeneratorPNG.php');

        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        header("Content-type: image/png");
        echo $generator->getBarcode($value, $generator::TYPE_CODE_128, 1,50);
    }
}

/* End of file pages.php */
/* Location: ./application/controllers/portal.php */