<?php

/**
 * Farmer Model
 *
 */
class Cassia_selection extends Abstract_model
{

    public $table = "selection";
    public $pkey = "selection_id";
    public $alias = "a";

    public $fields = array(
        'selection_id' => array ( 'pkey' => true , 'type' => 'int' , 'nullable' => true , 'unique' => true , 'display' => 'selection id'),
        'production_id' => array ( 'type' => 'int' , 'nullable' => true , 'unique' => false , 'display' => 'production id'),
        'product_id' => array ( 'type' => 'int' , 'nullable' => true , 'unique' => false , 'display' => 'product id'),
        'selection_qty' => array ( 'type' => 'float' , 'nullable' => true , 'unique' =>false , 'display' => 'selection qty'),
        'selection_date' => array ( 'type' => 'date' , 'nullable' => false , 'unique' => false , 'display' => 'selection date'),
        'created_date' => array ( 'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' => 'created date'),
        'created_by' => array ( 'type' => 'str' , 'nullable' => true , 'unique' =>false  , 'display' => 'created by'),
        'updated_date' => array ( 'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' => 'updated date'),
        'updated_by' => array ( 'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' => 'updated by')

    );

    public $selectClause = "a.selection_id,
                            a.production_id,
                            a.product_id,
                            b.product_code,
                            b.product_name,
                            a.selection_qty,
                            to_char(a.selection_date,'dd-Mon-yyyy') as selection_date,
                            to_char(a.created_date,'dd-Mon-yyyy') as created_date,
                            a.created_by,
                            to_char(a.updated_date,'dd-Mon-yyyy') as updated_date,
                            a.updated_by,
                            ";
    public $fromClause = " selection a 
                            JOIN product b ON a.product_id = b.product_id
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
            $this->record['updated_date'] = date('Y-m-d');
            $this->record['updated_by'] = $userdata->username;

           
        } else {
            //do something
            $this->record['updated_date'] = date('Y-m-d');
            $this->record['updated_by'] = $userdata->username;
            
            //if false please throw new Exception
        }
        return true;
    }

    function getSerialNumber()
    {
        $format_serial = 'RMP-DATE-XXXX';

        $sql = "select max(substr(trx_code, length(trx_code)-3 )::integer) as total from purchasing
                    where to_char(purchasing_date,'yyyyMondd') = '" . str_replace('-', '', $this->record['purchasing_date']) . "'";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        if (empty($row)) {
            $row = array('total' => 0);
        }

        $format_serial = str_replace('DATE', str_replace('-', '', date("dmY", strtotime($this->record['purchasing_date'])) ), $format_serial);
        $format_serial = str_replace('XXXX', str_pad(($row['total'] + 1), 4, '0', STR_PAD_LEFT), $format_serial);

        return $format_serial;
    }

    


}

/* End of file Groups.php */