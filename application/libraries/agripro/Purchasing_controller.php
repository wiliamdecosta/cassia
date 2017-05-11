<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Json library
 * @class Stock_material_controller
 * @version 07/05/2015 12:18:00
 */
class Purchasing_controller
{

    function read()
    {

        $page = getVarClean('page', 'int', 1);
        $limit = getVarClean('rows', 'int', 5);
        $sidx = getVarClean('sidx', 'str', 'purchasing_id');
        $sord = getVarClean('sord', 'str', 'desc');

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        $is_sortir = getVarClean('is_sortir', 'str', 0);
        $purchasing = getVarClean('purchasing', 'int', 0);

        try {

            $ci = &get_instance();
            $ci->load->model('agripro/cassia_purchasing');
            $table = $ci->cassia_purchasing;

            $req_param = array(
                "sort_by" => $sidx,
                "sord" => $sord,
                "limit" => null,
                "field" => null,
                "where" => null,
                "where_in" => null,
                "where_not_in" => null,
                "search" => $_REQUEST['_search'],
                "search_field" => isset($_REQUEST['searchField']) ? $_REQUEST['searchField'] : null,
                "search_operator" => isset($_REQUEST['searchOper']) ? $_REQUEST['searchOper'] : null,
                "search_str" => isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : null
            );


            $start = getVarClean('inStart', 'date', '');
            $end = getVarClean('inEnd', 'date', '');

            if ($start && !$end) {
                $req_param['where'] = array("a.purchasing_date = '" . $start . "'::date");
            }
            if ($start && $end) {
                $req_param['where'] = array("a.purchasing_date >= '" . $start . "'::date and a.purchasing_date <= '" . $end . "'::date");
            }

            /*$history = getVarClean('history', 'int', 0);
            if ($history == 0) {
                $req_param['where'] = array("purchasing_id not in (select purchasing_id from cassia_drying)");
            }*/


            $table->setJQGridParam($req_param);
            $count = $table->countAll();

            if ($count > 0) $total_pages = ceil($count / $limit);
            else $total_pages = 1;

            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - ($limit); // do not put $limit*($page - 1)

            $req_param['limit'] = array(
                'start' => $start,
                'end' => $limit
            );

            $table->setJQGridParam($req_param);

            if ($page == 0) $data['page'] = 1;
            else $data['page'] = $page;

            $data['total'] = $total_pages;
            $data['records'] = $count;

            $data['rows'] = $table->getAll();
            $data['success'] = true;
            logging('View Data Purchasing');

        } catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    function readLov()
    {
        permission_check('view-tracking');

        $start = getVarClean('current', 'int', 0);
        $limit = getVarClean('rowCount', 'int', 5);

        $sort = getVarClean('sort', 'str', 'purchasing_id');
        $dir = getVarClean('dir', 'str', 'asc');

        $searchPhrase = getVarClean('searchPhrase', 'str', '');

        $data = array('rows' => array(), 'success' => false, 'message' => '', 'current' => $start, 'rowCount' => $limit, 'total' => 0);

        try {

            $ci = &get_instance();
            $ci->load->model('agripro/cassia_purchasing');
            $table = $ci->cassia_purchasing;

            if (!empty($searchPhrase)) {
                $table->setCriteria("(purchasing_id ilike '%" . $searchPhrase . "%' or trx_code ilike '%" . $searchPhrase . "%')
                                     ");
            }


            $table->setCriteria("purchasing_id not in (select purchasing_id from cassia_drying)");


            $start = ($start - 1) * $limit;
            $items = $table->getAll($start, $limit, $sort, $dir);
            $totalcount = $table->countAll();

            $data['rows'] = $items;
            $data['success'] = true;
            $data['total'] = $totalcount;

        } catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    function readLov_sortir()
    {
        permission_check('view-tracking');

        $start = getVarClean('current', 'int', 0);
        $limit = getVarClean('rowCount', 'int', 5);

        $sort = getVarClean('sort', 'str', 'purchasing_id');
        $dir = getVarClean('dir', 'str', 'asc');

        $searchPhrase = getVarClean('searchPhrase', 'str', '');

        $data = array('rows' => array(), 'success' => false, 'message' => '', 'current' => $start, 'rowCount' => $limit, 'total' => 0);

        try {

            $ci = &get_instance();
            $ci->load->model('agripro/stock_material');
            $table = $ci->stock_material;

            $table->setCriteria(" sm_qty_bersih > 0 AND sm_id not in (select distinct sm_id from sortir where sm_id is not null) ");
            if (!empty($searchPhrase)) {
                $table->setCriteria(" (sm_id ilike '%" . $searchPhrase . "%' or sm_no_trans ilike '%" . $searchPhrase . "%')");
            }

            $start = ($start - 1) * $limit;
            $items = $table->getAll($start, $limit, $sort, $dir);
            $totalcount = $table->countAll();

            $data['rows'] = $items;
            $data['success'] = true;
            $data['total'] = $totalcount;

        } catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    function crud()
    {

        $data = array();
        $oper = getVarClean('oper', 'str', '');
        switch ($oper) {
            case 'add' :
                permission_check('add-tracking');
                $data = $this->create();
                break;

            case 'edit' :
                permission_check('edit-tracking');
                $data = $this->update();
                break;

            case 'del' :
                permission_check('delete-tracking');
                $data = $this->destroy();
                break;

            default :
                permission_check('view-tracking');
                $data = $this->read();
                break;
        }

        return $data;
    }


    function create()
    {

        $ci = &get_instance();
        $ci->load->model('agripro/cassia_purchasing');
        $table = $ci->cassia_purchasing;

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        $jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);

        if (!is_array($items)) {
            $data['message'] = 'Invalid items parameter';
            return $data;
        }

        $table->actionType = 'CREATE';
        $errors = array();
        if (isset($items[0])) {
            $numItems = count($items);
            for ($i = 0; $i < $numItems; $i++) {
                try {

                    $table->db->trans_begin(); //Begin Trans

                    $table->setRecord($items[$i]);
                    $table->create();

                    $table->db->trans_commit(); //Commit Trans

                } catch (Exception $e) {

                    $table->db->trans_rollback(); //Rollback Trans
                    $errors[] = $e->getMessage();
                }
            }

            $numErrors = count($errors);
            if ($numErrors > 0) {
                $data['message'] = $numErrors . " from " . $numItems . " record(s) failed to be saved.<br/><br/><b>System Response:</b><br/>- " . implode("<br/>- ", $errors) . "";
            } else {
                $data['success'] = true;
                $data['message'] = 'Data added successfully';
            }
            $data['rows'] = $items;
        } else {

            try {
                $table->db->trans_begin(); //Begin Trans

                $table->setRecord($items);
                $table->record[$table->pkey] = $table->generate_id($table->table, $table->pkey);
                $table->create();


                ##############################
                ## Insert Stock
                ##############################
              //  $table->insertStock($table->record);

                $table->db->trans_commit(); //Commit Trans

                $data['success'] = true;
                $data['message'] = 'Data added successfully';
                logging('Add Data Purchasing');

            } catch (Exception $e) {
                $table->db->trans_rollback(); //Rollback Trans

                $data['message'] = $e->getMessage();
                $data['rows'] = $items;
            }

        }
        return $data;

    }

    function update()
    {

        $ci = &get_instance();
        $ci->load->model('agripro/cassia_purchasing');
        $table = $ci->cassia_purchasing;

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        $jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);

        if (!is_array($items)) {
            $data['message'] = 'Invalid items parameter';
            return $data;
        }

        $table->actionType = 'UPDATE';

        if (isset($items[0])) {
            $errors = array();
            $numItems = count($items);
            for ($i = 0; $i < $numItems; $i++) {
                try {
                    $table->db->trans_begin(); //Begin Trans

                    $table->setRecord($items[$i]);
                    $table->update();

                    $table->db->trans_commit(); //Commit Trans

                    $items[$i] = $table->get($items[$i][$table->pkey]);
                } catch (Exception $e) {
                    $table->db->trans_rollback(); //Rollback Trans

                    $errors[] = $e->getMessage();
                }
            }

            $numErrors = count($errors);
            if ($numErrors > 0) {
                $data['message'] = $numErrors . " from " . $numItems . " record(s) failed to be saved.<br/><br/><b>System Response:</b><br/>- " . implode("<br/>- ", $errors) . "";
            } else {
                $data['success'] = true;
                $data['message'] = 'Data update successfully';
            }
            $data['rows'] = $items;
        } else {

            try {
                $table->db->trans_begin(); //Begin Trans


                $table->setRecord($items);
                $table->update();

                ##############################
                ## Update Stock
                ##############################
                //$table->updateStock($table->record);

                $table->db->trans_commit(); //Commit Trans

                $data['success'] = true;
                $data['message'] = 'Data update successfully';

                $data['rows'] = $table->get($items[$table->pkey]);
                logging('Edit Data Purchasing');
            } catch (Exception $e) {
                $table->db->trans_rollback(); //Rollback Trans

                $data['message'] = $e->getMessage();
                $data['rows'] = $items;
            }

        }
        return $data;

    }

    function destroy()
    {
        $ci = &get_instance();
        $ci->load->model('agripro/cassia_purchasing');
        $table = $ci->cassia_purchasing;

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        $jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);

        try {
            $table->db->trans_begin(); //Begin Trans

            $total = 0;
            if (is_array($items)) {
                foreach ($items as $key => $value) {
                    if (empty($value)) throw new Exception('Empty parameter');

                    $table->remove($value);
                    $data['rows'][] = array($table->pkey => $value);
                    $total++;
                }
            } else {
                $items = (int)$items;
                if (empty($items)) {
                    throw new Exception('Empty parameter');
                };

                $table->remove($items);
                ##############################
                ## Update Stock
                ##############################
                //$table->deleteStock($items);

                $data['rows'][] = array($table->pkey => $items);
                $data['total'] = $total = 1;
                logging('Delete Data Purchasing');
            }

            $data['success'] = true;
            $data['message'] = $total . ' Data deleted successfully';

            $table->db->trans_commit(); //Commit Trans

        } catch (Exception $e) {
            $table->db->trans_rollback(); //Rollback Trans
            $data['message'] = $e->getMessage();
            $data['rows'] = array();
            $data['total'] = 0;
        }
        return $data;
    }


    function summary_stock()
    {

        $ci = &get_instance();
        $ci->load->model('agripro/cassia_purchasing');
        $table = $ci->cassia_purchasing;

        $sql = "select prod.product_code, prod.product_name, wh.wh_code, sum(a.weight) as qty
                from cassia_purchasing as a
                left join product as prod on a.product_id = prod.product_id
                left join warehouse as wh on a.wh_id = wh.wh_id
                group by prod.product_code, prod.product_name, wh.wh_code
                order by prod.product_code";

        $query = $table->db->query($sql);
        $items = $query->result_array();

        $no = 1;
        $output = "";
        foreach ($items as $item) {
            $output .= '
                <tr>
                    <td>' . $no++ . '</td>
                    <td>' . $item['product_code'] . '</td>
                    <td>' . $item['product_name'] . '</td>
                    <td>' . $item['wh_code'] . '</td>
                    <td align="right">' . $item['qty'] . '</td>
                </tr>
            ';
        }
        echo $output;
        exit;
    }

    // Sheet Output
    public function exportExcel()
    {
        $ci = &get_instance();
        // Set unlimited usage memory for big data
        ini_set('memory_limit', '-1');
        $ci->load->library("phpexcel");
        $filename = "raw_material_purchasing.xls";
        $ci->phpexcel->getProperties()->setCreator("Agripro Tridaya Nusantara")
            ->setLastModifiedBy("Agripro Tridaya Nusantara")
            ->setTitle("REPORT")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Raw Material Purchasing");
        $ci->phpexcel->setActiveSheetIndex(0);
        $sh = &$ci->phpexcel->getActiveSheet();
        $sh->setCellValue('A1', 'TRANSACTION CODE')
            ->setCellValue('B1', 'FARMER CODE')
            ->setCellValue('C1', 'FARMER NAME')
            ->setCellValue('D1', 'RAW MATERIAL')
            ->setCellValue('E1', 'TOTAL WEIGHT (Kg)')
            ->setCellValue('F1', 'PRICE (RP/ Kg)')
            ->setCellValue('G1', 'TOTAL PRICE')
            ->setCellValue('H1', 'BATCH')
            ->setCellValue('I1', 'PAYMENT')
            ->setCellValue('J1', 'TRANSACTION DATE')
            ->setCellValue('K1', 'HARVEST DATE')
            ->setCellValue('L1', 'PO NUMBER');

        $sh->getStyle('A1:L1')->getFont()->setBold(TRUE);
        $sh->getColumnDimension('A')->setAutoSize(TRUE);
        $sh->getColumnDimension('B')->setAutoSize(TRUE);
        $sh->getColumnDimension('C')->setAutoSize(TRUE);
        $sh->getColumnDimension('D')->setAutoSize(TRUE);
        $sh->getColumnDimension('E')->setAutoSize(TRUE);
        $sh->getColumnDimension('F')->setAutoSize(TRUE);
        $sh->getColumnDimension('G')->setAutoSize(TRUE);
        $sh->getColumnDimension('H')->setAutoSize(TRUE);
        $sh->getColumnDimension('I')->setAutoSize(TRUE);
        $sh->getColumnDimension('J')->setAutoSize(TRUE);
        $sh->getColumnDimension('K')->setAutoSize(TRUE);
        $sh->getColumnDimension('L')->setAutoSize(TRUE);
        $sh->getStyle('A1:L1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('A1:L1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
        $sh->getStyle('A1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('B1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('C1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('D1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('E1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('F1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('G1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('H1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('I1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('J1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('K1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('L1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('L1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        /*$x = 2;
        $dt = $this->cm->excelRinta($period, $pgl_id, $ten_id);
        $no = 1;
        foreach($dt as $k => $r) {
            $sh->getCell('A'.$x)->setValueExplicit($r->ND1, PHPExcel_Cell_DataType::TYPE_STRING);
            $sh->setCellValue('B'.$x, @$r->NOM);
            $sh->setCellValue('C'.$x, @$r->ABONEMEN);
            $sh->setCellValue('D'.$x, @$r->MNT_TCK_C);
            $sh->setCellValue('E'.$x, @$r->MNT_TCK_D);
            $sh->setCellValue('F'.$x, @$r->LOKAL);
            $sh->setCellValue('G'.$x, @$r->INTERLOKAL);
            $sh->setCellValue('H'.$x, @$r->SLJJ);
            $sh->setCellValue('I'.$x, @$r->SLI007);
            $sh->setCellValue('J'.$x, @$r->SLI001);
            $sh->setCellValue('K'.$x, @$r->SLI008);
            $sh->setCellValue('L'.$x, @$r->SLI009);
            $sh->setCellValue('M'.$x, @$r->SLI_017);
            $sh->setCellValue('N'.$x, @$r->TELKOMNET_INSTAN);
            $sh->setCellValue('O'.$x, @$r->TELKOMSAVE);
            $sh->setCellValue('P'.$x, @$r->STB);
            //add STB
            $sh->setCellValue('Q'.$x, @$r->STB_TSL);
            $sh->setCellValue('R'.$x, @$r->STB_EXL);
            $sh->setCellValue('S'.$x, @$r->STB_HCP);
            $sh->setCellValue('T'.$x, @$r->STB_INM);
            $sh->setCellValue('U'.$x, @$r->STB_OTHERS);
            // End
            $sh->setCellValue('V'.$x, @$r->EXPENSE_SLI);
            $sh->setCellValue('W'.$x, @$r->EXPENSE_IN);
            $sh->setCellValue('X'.$x, @$r->PAY_TV);

            $sh->setCellValue('Y'.$x, @$r->JAPATI);
            $sh->setCellValue('Z'.$x, @$r->USAGE_SPEEDY);
            $sh->setCellValue('AA'.$x, @$r->NON_JASTEL);
            $sh->setCellValue('AB'.$x, @$r->ISDN_DATA);
            $sh->setCellValue('AC'.$x, @$r->ISDN_VOICE);
            $sh->setCellValue('AD'.$x, @$r->KONTEN);
            $sh->setCellValue('AE'.$x, @$r->PORTWHOLESALES);
            $sh->setCellValue('AF'.$x, @$r->METERAI);
            $sh->setCellValue('AG'.$x, @$r->PPN);

            $sh->setCellValue('AH'.$x, @$r->LAIN_LAIN);

            $sh->setCellValue('AI'.$x, @$r->TOTAL);
            $sh->setCellValue('AJ'.$x, @$r->GRAND_TOTAL);

            $sh->getCell('AK'.$x)->setValueExplicit($r->KURS, PHPExcel_Cell_DataType::TYPE_STRING);

            $sh->getCell('AL'.$x)->setValueExplicit($r->STATUS_PEMBAYARAN, PHPExcel_Cell_DataType::TYPE_STRING);
            $sh->getCell('AM'.$x)->setValueExplicit($r->TGL_BYR, PHPExcel_Cell_DataType::TYPE_STRING);

            $sh->getCell('AN'.$x)->setValueExplicit($r->DIVISI, PHPExcel_Cell_DataType::TYPE_STRING);
            $sh->getCell('AO'.$x)->setValueExplicit($r->FLAG, PHPExcel_Cell_DataType::TYPE_STRING);

            $no++;
            $x++;
        }
        $sh->getStyle('A2:A'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('B2:B'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('C2:C'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('D2:D'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('E2:E'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('F2:F'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('G2:G'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('H2:H'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('I2:I'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('J2:J'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('K2:K'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('L2:L'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('M2:M'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('N2:N'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('O2:O'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('P2:P'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('Q2:Q'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('R2:R'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('S2:S'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('T2:T'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('U2:U'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('V2:V'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('W2:W'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('X2:X'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('Y2:Y'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('Z2:Z'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AA2:AA'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AB2:AB'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AC2:AC'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AD2:AD'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AE2:AE'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AF2:AF'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AG2:AG'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AH2:AH'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $sh->getStyle('AI2:AI'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AJ2:AJ'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AK2:AK'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AL2:AL'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AM2:AM'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AN2:AN'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AO2:AO'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AO2:AO'.$x)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $sh->getStyle('C2'.':AJ'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
        $sh->getStyle('A'.$x.':AO'.$x)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('A'.$x.':AO'.$x)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $sh->setCellValue('A'.$x, 'TOTAL');
        //$sh->setCellValue('B'.$x, "=SUM(B2:B".($x-1).")");
        //$sh->getStyle('B'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
        $sh->setCellValue('C'.$x, "=SUM(C2:C".($x-1).")");
        $sh->getStyle('C'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('D'.$x, "=SUM(D2:D".($x-1).")");
        $sh->getStyle('D'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('E'.$x, "=SUM(E2:E".($x-1).")");
        $sh->getStyle('E'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('F'.$x, "=SUM(F2:F".($x-1).")");
        $sh->getStyle('F'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('G'.$x, "=SUM(G2:G".($x-1).")");
        $sh->getStyle('G'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('H'.$x, "=SUM(H2:H".($x-1).")");
        $sh->getStyle('H'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('I'.$x, "=SUM(I2:I".($x-1).")");
        $sh->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('J'.$x, "=SUM(J2:J".($x-1).")");
        $sh->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('K'.$x, "=SUM(K2:K".($x-1).")");
        $sh->getStyle('K'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('L'.$x, "=SUM(L2:L".($x-1).")");
        $sh->getStyle('L'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('M'.$x, "=SUM(M2:M".($x-1).")");
        $sh->getStyle('M'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('N'.$x, "=SUM(N2:N".($x-1).")");
        $sh->getStyle('N'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('O'.$x, "=SUM(O2:O".($x-1).")");
        $sh->getStyle('O'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('P'.$x, "=SUM(P2:P".($x-1).")");
        $sh->getStyle('P'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('Q'.$x, "=SUM(Q2:Q".($x-1).")");
        $sh->getStyle('Q'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('R'.$x, "=SUM(R2:R".($x-1).")");
        $sh->getStyle('R'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('S'.$x, "=SUM(S2:S".($x-1).")");
        $sh->getStyle('S'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('T'.$x, "=SUM(T2:T".($x-1).")");
        $sh->getStyle('T'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('U'.$x, "=SUM(U2:U".($x-1).")");
        $sh->getStyle('U'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('V'.$x, "=SUM(V2:V".($x-1).")");
        $sh->getStyle('V'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('W'.$x, "=SUM(W2:W".($x-1).")");
        $sh->getStyle('W'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('X'.$x, "=SUM(X2:X".($x-1).")");
        $sh->getStyle('X'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('Y'.$x, "=SUM(Y2:Y".($x-1).")");
        $sh->getStyle('Y'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('Z'.$x, "=SUM(Z2:Z".($x-1).")");
        $sh->getStyle('Z'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('AA'.$x, "=SUM(AA2:AA".($x-1).")");
        $sh->getStyle('AA'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('AB'.$x, "=SUM(AB2:AB".($x-1).")");
        $sh->getStyle('AB'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('AC'.$x, "=SUM(AC2:AC".($x-1).")");
        $sh->getStyle('AC'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('AD'.$x, "=SUM(AD2:AD".($x-1).")");
        $sh->getStyle('AD'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('AE'.$x, "=SUM(AE2:AE".($x-1).")");
        $sh->getStyle('AE'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('AF'.$x, "=SUM(AF2:AF".($x-1).")");
        $sh->getStyle('AF'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('AG'.$x, "=SUM(AG2:AG".($x-1).")");
        $sh->getStyle('AG'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('AH'.$x, "=SUM(AH2:AH".($x-1).")");
        $sh->getStyle('AH'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('AI'.$x, "=SUM(AI2:AI".($x-1).")");
        $sh->getStyle('AI'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('AJ'.$x, "=SUM(AJ2:AJ".($x-1).")");
        $sh->getStyle('AJ'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('AK'.$x, "");
        $sh->getStyle('AK'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('AL'.$x, "");
        $sh->getStyle('AL'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('AM'.$x, "");
        $sh->getStyle('AM'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

        $sh->setCellValue('A'.$x, '');


        $sh->getStyle('A'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('B'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('C'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('D'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('E'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('F'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('G'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('H'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('I'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('J'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('K'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('L'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('M'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('N'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('O'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('P'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('Q'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('R'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('S'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('T'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('U'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('V'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('W'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('X'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('Y'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('Z'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AA'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AB'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AC'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AD'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AE'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AF'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AG'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AH'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $sh->getStyle('AI'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AJ'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AK'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AL'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AM'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AN'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('AO'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $sh->getStyle('AO'.$x)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sh->getStyle('A'.$x.':AO'.$x)->getFont()->setBold(TRUE);*/

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save(dirname(__FILE__) . '/../third_party/report/' . $filename);
        // Write file to the browser
        // $objWriter->save('php://output');
        //redirect($this->config->config['base_url'].'application/third_party/report/'.$filename, 'location', 301);
        $data['redirect'] = "true";
        $data['redirect_url'] = $this->config->config['base_url'] . 'application/third_party/report/' . $filename;

        echo json_encode($data);
    }
}

/* End of file Warehouse_controller.php */