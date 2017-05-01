<?php

/**
 * Provinsi Model
 *
 */
class Kota extends Abstract_model {

    public $table           = "kota";
    public $pkey            = "kota_id";
    public $alias           = "";

    public $fields          = array(
                                'kota_id'           => array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'ID Provinsi'),
                                'prov_id'           => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Provinsi'),
                                'kota_name'         => array('nullable' => true, 'type' => 'str', 'unique' => true, 'display' => 'Name'),
                                'created_date'      => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Created Date'),
                                'created_by'        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By'),
                                'updated_date'      => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
                                'updated_by'        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By'),
                            );

    public $selectClause    = "kota_id, kota_name, prov_id, to_char(created_date,'yyyy-mm-dd') as created_date, created_by,
                                    to_char(updated_date,'yyyy-mm-dd') as updated_date, updated_by";
    public $fromClause      = "kota";

    public $refs            = array('plantation' => 'kota_id');

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