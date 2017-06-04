<?php

/**
 * Farmer Model
 *
 */
class Cassia_production_detail extends Abstract_model
{

    public $table = "production_detail";
    public $pkey = "production_detail_id";
    public $alias = "a";

    public $fields = array(
        'production_detail_id' => array ( 'pkey' => true , 'type' => 'int' , 'nullable' => true , 'unique' => true , 'display' => 'production detail id'),
        'production_id' => array (  'type' => 'int' , 'nullable' => true , 'unique' => false , 'display' => 'production id'),
        'purchasing_id' => array (  'type' => 'int' , 'nullable' => true , 'unique' => false , 'display' => 'purchasing id'),
        'production_detail_qty' => array (  'type' => 'float' , 'nullable' => false , 'unique' => false , 'display' => 'production detail qty'),
        'description' => array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' => 'description'),
        'created_date' => array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' => 'created date'),
        'created_by' => array ('type' => 'str' , 'nullable' => true , 'unique' =>false  , 'display' => 'created by'),
        'updated_date' => array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' => 'updated date'),
        'updated_by' => array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' => 'updated by')
    );

    public $selectClause = "a.production_detail_id,
                            a.production_id,
                            a.purchasing_id,
                            a.production_detail_qty,
                            a.description,
                            to_char(a.created_date,'dd-Mon-yyyy') as created_date,
                            a.created_by,
                            to_char(a.updated_date,'dd-Mon-yyyy') as updated_date,
                            a.updated_by
                            ";
    public $fromClause = " production_detail a
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




}

/* End of file Groups.php */