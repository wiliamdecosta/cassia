<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Cassia_selection_controller
* @version 07/05/2015 12:18:00
*/

class Cassia_selection_controller {

    function readLov() {
        permission_check('view-tracking');

        $start = getVarClean('current','int',0);
        $limit = getVarClean('rowCount','int',5);

        $sort = getVarClean('sort','str','product_id');
        $dir  = getVarClean('dir','str','asc');

        $searchPhrase = getVarClean('searchPhrase', 'str', '');

        $data = array('rows' => array(), 'success' => false, 'message' => '', 'current' => $start, 'rowCount' => $limit, 'total' => 0);

        try {

            $ci = & get_instance();
            $ci->load->model('agripro/cassia_selection');
            $table = $ci->cassia_selection;

            if(!empty($searchPhrase)) {
                $table->setCriteria("(b.product_code ilike '%".$searchPhrase."%' or b.product_name ilike '%".$searchPhrase."%')");
            }

            $table->setCriteria("a.selection_qty > 0");

            $start = ($start-1) * $limit;
            $items = $table->getAll($start, $limit, $sort, $dir);
            $totalcount = $table->countAll();

            $data['rows'] = $items;
            $data['success'] = true;
            $data['total'] = $totalcount;

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

}

/* End of file Warehouse_controller.php */