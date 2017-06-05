<?php

/**
 * Farmer Model
 *
 */
class Cassia_production extends Abstract_model
{

    public $table = "production";
    public $pkey = "production_id";
    public $alias = "a";

    public $fields = array (
        'production_id' => array ( 'pkey' => true , 'type' => 'int' , 'nullable' => true , 'unique' => true , 'display' => 'production id'),
        'production_code' => array ( 'type' => 'str' , 'nullable' => true , 'unique' =>  false, 'display' => 'production code'),
        'production_date' => array ( 'type' => 'date' , 'nullable' => false , 'unique' =>false  , 'display' => 'production date'),
        'created_date' => array ( 'type' => 'date' , 'nullable' => true , 'unique' =>false  , 'display' => 'created date'),
        'created_by' => array ( 'type' => 'str'  , 'nullable' => true , 'unique' => false , 'display' => 'created by'),
        'updated_date' => array ( 'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' => 'updated date'),
        'updated_by' => array ('type' => 'str'  , 'nullable' => true , 'unique' =>false  , 'display' => 'updated by')
    );

    public $selectClause = "    a.production_id,
                                a.production_code,
                                to_char(a.production_date,'dd-Mon-yyyy') as production_date,
                                to_char(a.created_date,'dd-Mon-yyyy') as created_date,
                                a.created_by,
                                to_char(a.updated_date,'dd-Mon-yyyy') as updated_date,
                                a.updated_by 
                           ";
    public $fromClause = " production a 
                              ";

    public $refs = array();

    function __construct()
    {
        parent::__construct();
    }

    function validate()
    {
        $ci =& get_instance();
        $userdata = $ci->ion_auth->user()->row();

       if ($this->actionType == 'CREATE') {

            $this->record['created_date'] = date('Y-m-d');
            $this->record['created_by'] = $userdata->username;
          
        } else {
            //do something
            //example:
            $this->record['updated_date'] = date('Y-m-d');
            $this->record['updated_by'] = $userdata->username;


            //if false please throw new Exception
        }
        return true;
    }

    function getProductByParent($parent){
        
        $sql = "    select 'a' so , parent_id, product_id, product_code from product where parent_id in (".$parent.")
                    union all
                    select 'b', parent_id, product_id, product_code from product where product_code = 'LOST'
                    order by 1, 3
                ";
        $q = $this->db->query($sql);
        return $q->result_array();
    }

    function getDataMaterial($production_id){
        
        $ci = &get_instance();

        $ci->load->model('agripro/cassia_production_detail');
        $tProductionDetail = $ci->cassia_production_detail;

        $tProductionDetail->setCriteria('production_id = ' . $production_id);
        return $tProductionDetail->getAll();
    }
    
    function getDataProduct($production_id){
        
        $ci = &get_instance();

        $ci->load->model('agripro/cassia_selection');
        $tSelection = $ci->cassia_selection;

        $tSelection->setCriteria('production_id = ' . $production_id);
        return $tSelection->getAll();
    }

    function genProductionCode($date){

        $sql = "select max(substring(production_code, 5 )) as total from production
                    where to_char(production_date,'yyyymm') = '" . substr(str_replace('-', '', $date), 0, 6) . "'";

        $query = $this->db->query($sql);

        $row = $query->row_array();
        if (empty($row)) {
            $row = array('total' => 0);
        }

        $production_code = substr(str_replace('-', '', $date), 2, 4) . "" . str_pad(($row['total'] + 1), 4, '0', STR_PAD_LEFT);
        return $production_code;
    }

    function SubmitData($data){
        


    }

    public function insertStock($data){
        $ci = &get_instance();

        $ci->load->model('agripro/stock');
        $tStock = $ci->stock;



    }

    public function updateStock(){


    }

    public function deleteStock(){

    }

    public function removeProduction($production_id)
    {

        $ci = &get_instance();

        $ci->load->model('agripro/stock');
        $tStock = $ci->stock;

        $ci->load->model('agripro/cassia_production_detail');
        $tProductionDetail = $ci->cassia_production_detail;

        $ci->load->model('agripro/cassia_selection');
        $tSelection = $ci->cassia_selection;

        $ci->load->model('agripro/stock_material');
        $tSM = $ci->stock_material;

        /*
         * Steps to Delete Production
         * 0. Remove Stock (PRODUCTION STOCK )
         * 1. Delete Selection
         * 2. Delete Production Detail
         * 3. Restore Qty Stock Material/Purchasing
         * 4. Delete Production
         * 5. 
         */

        // remove stock 
        $tStock->deleteByReference($production_id, 'PRODUCTION_IN');

        /**
         * Delete selection 
         */

        $tSelection->setCriteria('production_id = ' . $production_id);
        $selectionData = $tSelection->getAll();
        
        $loop = 0;
        foreach ($selectionData as $selData) {
           $tSelection->remove($selData['selection_id']);
        }

        /**
         * Delete material / production Detail
         */

        $tProductionDetail->setCriteria('production_id = ' . $production_id);
        $prodDetailData = $tProductionDetail->getAll();

        $loop = 0;
        foreach ($prodDetailData as $proDetData) {

            /*$stData[$loop]['purchasing_id'] = $proDetData['purchasing_id'];
            $stData[$loop]['restore_store_qty'] = $proDetData['production_detail_qty'];
            $loop++;*/

            $tProductionDetail->remove($proDetData['production_detail_id']);
        }

        /**
         * Delete data master packing
         */
        $this->remove($production_id);

        /**
         * restore qty to purchasing 
         */
        /*foreach ($stData as $row) {
            //delete data stock by sm_id
            $tStock->deleteByReference($row['purchasing_id'], 'PURCHASING_OUT');

            //restore store qty
            $increase_kg = (float)$row['restore_store_qty'];
            $sql = "UPDATE stock_material SET sm_qty_bersih = sm_qty_bersih + " . $increase_kg . "
                        WHERE sm_id = " . $row['sm_id'];

            $tSM->db->query($sql);

        }*/

        

    }
}
/* End of file Groups.php */