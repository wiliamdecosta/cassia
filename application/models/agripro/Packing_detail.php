<?php

/**
 * Raw Material Model
 *
 */
class Packing_detail extends Abstract_model {

    public $table           = "packing_detail";
    public $pkey            = "packing_id";
    public $alias           = "pd";

    public $fields          = array(
                                'pd_id'             => array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'ID Packaging'),
                                'packing_id'        => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'Packing ID'),
                                'selection_id'      => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'Selection ID'),
                                'pd_kg'             => array('nullable' => true, 'type' => 'float', 'unique' => false, 'display' => 'Kg'),
                                'created_date'      => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Created Date'),
                                'created_by'        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By'),
                                'updated_date'      => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
                                'updated_by'        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By'),

                            );


    public $selectClause    = "pd.*, prod.product_code, prod.product_name";
    public $fromClause      = "packing_detail as pd
                                left join selection on pd.selection_id = selection.selection_id
                                left join product as prod on selection.product_id = prod.product_id";

    public $refs            = array();

    function __construct() {
        parent::__construct();
    }

    function validate() {
        $ci =& get_instance();
        $userdata = $ci->ion_auth->user()->row();

        if($this->actionType == 'CREATE') {
            //do something
            // example :

            $this->record['created_date'] = date('Y-m-d');
            $this->record['created_by'] = $userdata->username;
            $this->record['updated_date'] = date('Y-m-d');
            $this->record['updated_by'] = $userdata->username;

            //$this->record['pkg_serial_number'] = $this->getSerialNumber();
            //$this->record['pkg_batch_number'] = $this->getBatchNumber($this->record['pkg_serial_number'] );
        }else {
            //do something
            //example:
            $this->record['updated_date'] = date('Y-m-d');
            $this->record['updated_by'] = $userdata->username;
            //if false please throw new Exception
        }
        return true;
    }

}
/* End of file Groups.php */