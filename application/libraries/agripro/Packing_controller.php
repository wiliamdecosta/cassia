<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Provinsi_controller
* @version 07/05/2015 12:18:00
*/
class Packing_controller {

    function read() {

        $page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',5);
        $sidx = getVarClean('sidx','str','packing_id');
        $sord = getVarClean('sord','str','asc');

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        try {

            $ci = & get_instance();
            $ci->load->model('agripro/packing');
            $table = $ci->packing;

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

            // Filter Table
            $req_param['where'] = array();

            $table->setCriteria('pack.packing_id NOT IN (select packing_id from shipping_detail)');
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
            logging('view data packing');
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    function readHistory() {

        $page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',5);
        $sidx = getVarClean('sidx','str','packing_id');
        $sord = getVarClean('sord','str','asc');

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        try {

            $ci = & get_instance();
            $ci->load->model('agripro/packing');
            $table = $ci->packing;

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

            // Filter Table
            $req_param['where'] = array();

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
            logging('view data packing');
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    function crud() {

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


    function create() {

        $ci = & get_instance();
        $ci->load->model('agripro/packing');
        $table = $ci->packing;

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        $jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);

        if (!is_array($items)){
            $data['message'] = 'Invalid items parameter';
            return $data;
        }

        $table->actionType = 'CREATE';
        $errors = array();

        if (isset($items[0])){
            $numItems = count($items);
            for($i=0; $i < $numItems; $i++){
                try{

                    $table->db->trans_begin(); //Begin Trans

                        $table->setRecord($items[$i]);
                        $table->create();

                    $table->db->trans_commit(); //Commit Trans

                }catch(Exception $e){

                    $table->db->trans_rollback(); //Rollback Trans
                    $errors[] = $e->getMessage();
                }
            }

            $numErrors = count($errors);
            if ($numErrors > 0){
                $data['message'] = $numErrors." from ".$numItems." record(s) failed to be saved.<br/><br/><b>System Response:</b><br/>- ".implode("<br/>- ", $errors)."";
            }else{
                $data['success'] = true;
                $data['message'] = 'Data added successfully';
            }
            $data['rows'] =$items;
        }else {

            try{
                $table->db->trans_begin(); //Begin Trans

                    $table->setRecord($items);
                    $table->create();

                $table->db->trans_commit(); //Commit Trans

                $data['success'] = true;
                $data['message'] = 'Data added successfully';
                logging('create data packing');
            }catch (Exception $e) {
                $table->db->trans_rollback(); //Rollback Trans

                $data['message'] = $e->getMessage();
                $data['rows'] = $items;
            }

        }
        return $data;

    }

    function update() {

        $ci = & get_instance();
        $ci->load->model('agripro/packing');
        $table = $ci->packing;

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        $jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);

        if (!is_array($items)){
            $data['message'] = 'Invalid items parameter';
            return $data;
        }

        $table->actionType = 'UPDATE';

        if (isset($items[0])){
            $errors = array();
            $numItems = count($items);
            for($i=0; $i < $numItems; $i++){
                try{
                    $table->db->trans_begin(); //Begin Trans

                        $table->setRecord($items[$i]);
                        $table->update();

                    $table->db->trans_commit(); //Commit Trans

                    $items[$i] = $table->get($items[$i][$table->pkey]);
                }catch(Exception $e){
                    $table->db->trans_rollback(); //Rollback Trans

                    $errors[] = $e->getMessage();
                }
            }

            $numErrors = count($errors);
            if ($numErrors > 0){
                $data['message'] = $numErrors." from ".$numItems." record(s) failed to be saved.<br/><br/><b>System Response:</b><br/>- ".implode("<br/>- ", $errors)."";
            }else{
                $data['success'] = true;
                $data['message'] = 'Data update successfully';
            }
            $data['rows'] =$items;
        }else {

            try{
                $table->db->trans_begin(); //Begin Trans

                    $table->setRecord($items);
                    $table->update();

                $table->db->trans_commit(); //Commit Trans

                $data['success'] = true;
                $data['message'] = 'Data update successfully';
                logging('update data packing');
                $data['rows'] = $table->get($items[$table->pkey]);
            }catch (Exception $e) {
                $table->db->trans_rollback(); //Rollback Trans

                $data['message'] = $e->getMessage();
                $data['rows'] = $items;
            }

        }
        return $data;

    }

    function destroy() {
        $ci = & get_instance();
        $ci->load->model('agripro/packing');
        $table = $ci->packing;

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        $jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);

        try{
            $table->db->trans_begin(); //Begin Trans

            $total = 0;
            if (is_array($items)){
                foreach ($items as $key => $value){
                    if (empty($value)) throw new Exception('Empty parameter');

                    $table->remove($value);
                    $data['rows'][] = array($table->pkey => $value);
                    $total++;
                }
            }else{
                $items = (int) $items;
                if (empty($items)){
                    throw new Exception('Empty parameter');
                };

                $table->remove($items);
                $data['rows'][] = array($table->pkey => $items);
                $data['total'] = $total = 1;
            }

            $data['success'] = true;
            $data['message'] = $total.' Data deleted successfully';
            logging('delete data packing');
            $table->db->trans_commit(); //Commit Trans

        }catch (Exception $e) {
            $table->db->trans_rollback(); //Rollback Trans
            $data['message'] = $e->getMessage();
            $data['rows'] = array();
            $data['total'] = 0;
        }
        return $data;
    }

    function createForm() {

        $ci = & get_instance();
        $ci->load->model('agripro/packing');
        $table = $ci->packing;

        $ci->load->model('agripro/packing_detail');
        $tableDetail = $ci->packing_detail;

        $data = array('success' => false, 'message' => '');
        $table->actionType = 'CREATE';
        $tableDetail->actionType = 'CREATE';

        /**
         * Data head
         */
        $packed_by = getVarClean('packed_by','str','');
        $product_id = getVarClean('product_id','int',0);
        $packing_date = getVarClean('packing_date','str','');

        $userdata = $ci->ion_auth->user()->row();

        /**
         * Data details
         */
        $input_weight = (array)$ci->input->post('input_weight_sum');
        $input_serial = (array)$ci->input->post('input_serial');
        $input_batch_number = (array)$ci->input->post('input_batch_number');
        $input_sid = (array)$ci->input->post('input_sid');

        try{

            for($i = 0; $i < count($input_weight); $i++) {
                if($input_weight[$i] == "" or $input_weight[$i] == 0) {
                    throw new Exception('All input weight must be filled');
                }
            }

            for($i = 0; $i < count($input_serial); $i++) {
                if($input_serial[$i] == "") {
                    throw new Exception('All serial number must be filled');
                }
            }

            for($i = 0; $i < count($input_batch_number); $i++) {
                if($input_batch_number[$i] == "") {
                    throw new Exception('All batch number must be filled');
                }
            }

            for($i = 0; $i < count($input_sid); $i++) {
                if($input_sid[$i] == "") {
                    throw new Exception('All SID must be filled');
                }
            }

            if(count($input_sid) == 0) {
                throw new Exception('No Data to be saved');
            }

            $cek_duplicate_serial = array_count_values($input_serial);
            foreach($cek_duplicate_serial as $val) {
                if($val > 1) throw new Exception('Duplicate Serial Number In Input Serial');
            }

            $table->db->trans_begin(); //Begin Trans


            $record_packing = array();
            $record_pack_detail = array();
            $detail_inc = 0;
            for($i = 0; $i < count($input_weight); $i++) {

                /*master*/
                //$record_packing[$i]['packing_id'] = $table->generate_id('packing','packing_id');
                $record_packing[$i]['product_id'] = $product_id;
                $record_packing[$i]['packing_batch_number'] = $input_batch_number[$i];
                $record_packing[$i]['packing_serial'] = $input_serial[$i];
                $record_packing[$i]['packing_weight'] = $input_weight[$i];
                $record_packing[$i]['packed_by'] = $packed_by;
                $record_packing[$i]['warehouse_id'] = $userdata->wh_id;
                $record_packing[$i]['packing_date'] = $packing_date;

                /*detail*/
                $sid = explode(";", $input_sid[$i]);

                if(count($sid) > 0) {
                    $detail_inc = 0;
                    foreach($sid as $item) {
                        $sid_split = explode("|",$item);
                        if($sid_split[1] == 0) continue;

                        $record_packing[$i]['detail'][$detail_inc]['selection_id'] = $sid_split[0];
                        $record_packing[$i]['detail'][$detail_inc]['pd_kg'] = $sid_split[1];

                        $detail_inc++;
                    }
                }else {
                    $sid_split = explode($sid,"|");
                    if($sid_split[1] == 0) {
                        //do nothing
                    }else {
                        $record_packing[$i]['detail'][0]['selection_id'] = $sid_split[0];
                        $record_packing[$i]['detail'][0]['pd_kg'] = $sid_split[1];
                    }

                }
            }


            for($i = 0; $i < count($record_packing); $i++) {
                $table->setRecord($record_packing[$i]);
                $packing_id = $table->create();

                $detail = $record_packing[$i]['detail'];
                for($j = 0; $j < count($detail); $j++) {
                    $tableDetail->substractSelectionQty($detail[$j]['selection_id'],$detail[$j]['pd_kg'], $i);

                    $detail[$j]['packing_id'] = $packing_id;
                    $tableDetail->setRecord($detail[$j]);
                    $tableDetail->create();
                }
            }

            $table->db->trans_commit(); //Commit Trans


            $data['success'] = true;
            $data['message'] = 'Data added successfully';

        }catch (Exception $e) {
            $table->db->trans_rollback(); //Rollback Trans

            $data['message'] = $e->getMessage();
        }


        echo json_encode($data);
        exit;

    }


    function getSerialNumber() {

        $ci = & get_instance();
        $ci->load->model('agripro/packing');
        $table = $ci->packing;

        $data = array('success' => false, 'message' => '');
        try {

            $serial = $table->getBatchNumber();
            $data['serial_number'] = $serial['serial_number'];
            $data['batch_number'] = $serial['batch_number'];
            $data['total'] = $serial['total'];
            $data['success'] = true;

        }catch(Exception $e) {
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
        exit;
    }

}

/* End of file Warehouse_controller.php */