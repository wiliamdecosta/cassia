<?php

/**
 * Shipping Model
 *
 */
class Shipping extends Abstract_model {

    public $table           = "shipping";
    public $pkey            = "shipping_id";
    public $alias           = "ship";

    public $fields          = array(
                                'shipping_id'           => array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'ID Packaging'),
                                'shipping_date'         => array('nullable' => false, 'type' => 'date', 'unique' => false, 'display' => 'Shipping Date'),
                                'shipping_driver_name'  => array('nullable' => false, 'type' => 'str', 'unique' => false, 'display' => 'Driver Name'),
                                'shipping_notes'        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Notes'),
                                'shipping_police_no'    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'No Police'),
                                'shipping_license'      => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'License File'),

                                'created_date'          => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Created Date'),
                                'created_by'            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By'),
                                'updated_date'          => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
                                'updated_by'            => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By'),

                            );


    public $selectClause    = "ship.shipping_id, to_char(ship.shipping_date,'yyyy-mm-dd') as shipping_date,
                                ship.shipping_driver_name, ship.shipping_notes,
                                ship.shipping_police_no, ship.shipping_license, ship.shipping_license as shipping_license_view";
    public $fromClause      = "shipping as ship";

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
/* End of file Shipping.php */