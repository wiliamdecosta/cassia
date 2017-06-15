<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Shipping_controller
* @version 07/05/2015 12:18:00
*/
class Shipping_controller {

    function read() {

        $page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',5);
        $sidx = getVarClean('sidx','str','shipping_id');
        $sord = getVarClean('sord','str','asc');

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        try {

            $ci = & get_instance();
            $ci->load->model('agripro/shipping');
            $table = $ci->shipping;

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
            $req_param['where'] = array('');

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

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    function readInputPacking() {

        $page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',1000);
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
        $sidx = getVarClean('sidx','str','shipping_id');
        $sord = getVarClean('sord','str','asc');

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        try {

            $ci = & get_instance();
            $ci->load->model('agripro/shipping');
            $table = $ci->shipping;

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

            $data['rows'] = $table->getAllItems();
            $data['success'] = true;

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
        $ci->load->model('agripro/shipping');
        $table = $ci->shipping;

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
        $ci->load->model('agripro/shipping');
        $table = $ci->shipping;

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
        $ci->load->model('agripro/shipping');
        $table = $ci->shipping;

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        $jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);

        try{
            $table->db->trans_begin(); //Begin Trans

            $total = 0;
            if (is_array($items)){
                foreach ($items as $key => $value){
                    if (empty($value)) throw new Exception('Empty parameter');

                    $table->removeShipping($value);
                    $data['rows'][] = array($table->pkey => $value);
                    $total++;
                }
            }else{
                $items = (int) $items;
                if (empty($items)){
                    throw new Exception('Empty parameter');
                };

                $table->removeShipping($items);
                $data['rows'][] = array($table->pkey => $items);
                $data['total'] = $total = 1;
            }

            $data['success'] = true;
            $data['message'] = $total.' Data deleted successfully';

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
        $ci->load->model('agripro/shipping');
        $table = $ci->shipping;

        $data = array('success' => false, 'message' => '');
        $table->actionType = 'CREATE';

        /**
         * Data master
         */
        $shipping_date = getVarClean('shipping_date','str','');
        $shipping_driver_name = getVarClean('shipping_driver_name','str','');
        $shipping_notes = getVarClean('shipping_notes','str','');
        $shipping_police_no = getVarClean('shipping_police_no','str','');



        /**
         * Data details
         */
        $packing_ids = $ci->input->post('packing_id');
        $packing_ids = explode(",", $packing_ids);

        try{

            $table->db->trans_begin(); //Begin Trans

                /**
                 * Upload file license first
                 */
                $config = array();
                $config['upload_path'] = 'trucking_license/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png|xls|xlsx|doc';
                $config['overwrite'] = 1;
                $ci->load->library('upload', $config);
                $fileName = date('Ymd').'_'.$_FILES['shipping_license']['name'];
                $config['file_name'] = $fileName;

                $ci->upload->initialize($config);

                if (!$ci->upload->do_upload('shipping_license')) {
                    $errors = $ci->upload->display_errors();
                    throw new Exception($errors);
                }

                $items = array(
                    'shipping_date' => $shipping_date,
                    'shipping_driver_name' => $shipping_driver_name,
                    'shipping_notes' => $shipping_notes,
                    'shipping_police_no' => $shipping_police_no,
                    'shipping_license' => $fileName
                );

                $table->setRecord($items);


                $record_detail = array();
                $ci->load->model('agripro/shipping_detail');
                $tableDetail = $ci->shipping_detail;
                $tableDetail->actionType = 'CREATE';

                $shipping_id = $table->create();
                for($i = 0; $i < count($packing_ids); $i++) {
                    $record_detail[] = array(
                        'shipping_id' => $shipping_id,
                        'packing_id' => $packing_ids[$i]
                    );
                }

                foreach($record_detail as $item_detail) {
                    $tableDetail->setRecord($item_detail);
                    //$tableDetail->record[$tableDetail->pkey] = $tableDetail->generate_id($tableDetail->table,$tableDetail->pkey);
                    $tableDetail->create();
                    //$tableDetail->insertStock($tableDetail->record, $table->record);
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


    function updateForm() {

        $ci = & get_instance();
        $ci->load->model('agripro/shipping');
        $table = $ci->shipping;

        $data = array('success' => false, 'message' => '');
        $table->actionType = 'UDATE';

        /**
         * Data master
         */
        $shipping_id = getVarClean('shipping_id','int',0);
        $shipping_date = getVarClean('shipping_date','str','');
        $shipping_driver_name = getVarClean('shipping_driver_name','str','');
        $shipping_notes = getVarClean('shipping_notes','str','');
        $shipping_police_no = getVarClean('shipping_police_no','str','');

        /**
         * Data details
         */
        $shipdet_ids = (array)$ci->input->post('shipdet_id');
        $packing_ids = (array)$ci->input->post('packing_id');

        try{

            $table->db->trans_begin(); //Begin Trans

                /**
                 * Upload file license first
                 */
                if(!empty($_FILES['shipping_license']['name'])) {
                    $config = array();
                    $config['upload_path'] = 'trucking_license/';
                    $config['allowed_types'] = 'gif|jpg|jpeg|png|xls|xlsx|doc';
                    $config['overwrite'] = 1;
                    $ci->load->library('upload', $config);
                    $fileName = date('Ymd').'_'.$_FILES['shipping_license']['name'];
                    $config['file_name'] = $fileName;

                    $ci->upload->initialize($config);

                    if (!$ci->upload->do_upload('shipping_license')) {
                        $errors = $ci->upload->display_errors();
                        throw new Exception($errors);
                    }
                }

                $items = array(
                    'shipping_id' => $shipping_id,
                    'shipping_date' => $shipping_date,
                    'shipping_driver_name' => $shipping_driver_name,
                    'shipping_notes' => $shipping_notes,
                    'shipping_police_no' => $shipping_police_no
                );
                if(!empty($_FILES['shipping_license']['name'])) {
                    $items['shipping_license'] = $fileName;
                }
                $table->setRecord($items);

                $record_detail = array();
                $ci->load->model('agripro/shipping_detail');
                $tableDetail = $ci->shipping_detail;
                $tableDetail->actionType = 'CREATE';

                for($i = 0; $i < count($packing_ids); $i++) {
                    if($shipdet_ids[$i] == "") {
                        $record_detail[] = array(
                            'shipping_id' => $shipping_id,
                            'packing_id' => $packing_ids[$i]
                        );
                    }
                }

                $table->update();

                foreach($record_detail as $item_detail) {
                    $tableDetail->setRecord($item_detail);
                    $tableDetail->record[$tableDetail->pkey] = $tableDetail->generate_id($tableDetail->table,$tableDetail->pkey);
                    $tableDetail->create();

                    $tableDetail->insertStock($tableDetail->record, $table->record);
                }

                //$table->insertStock($table->record);

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

}

/* End of file Shipping_controller.php */