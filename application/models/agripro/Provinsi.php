<?php

/**
 * Provinsi Model
 *
 */
class Provinsi extends Abstract_model {

    public $table           = "provinsi";
    public $pkey            = "prov_id";
    public $alias           = "prov";

    public $fields          = array(
                                'prov_id'             => array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'ID Provinsi'),
                                'prov_code'           => array('nullable' => true, 'type' => 'str', 'unique' => true, 'display' => 'Kode'),
                                'created_date'      => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Created Date'),
                                'created_by'        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By'),
                                'updated_date'      => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
                                'updated_by'        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By'),

                            );

    public $selectClause    = "prov.prov_id, prov.prov_code, to_char(prov.created_date,'yyyy-mm-dd') as created_date, prov.created_by,
                                    to_char(prov.updated_date,'yyyy-mm-dd') as updated_date, prov.updated_by";
    public $fromClause      = "provinsi prov";

    public $refs            = array('kota' => 'prov_id',
                                    'plantation' => 'prov_id');

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