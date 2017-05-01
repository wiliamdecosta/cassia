<?php

/**
 * Groups Model
 *
 */
class Users_groups extends Abstract_model {

    public $table           = "users_groups";
    public $pkey            = "id";
    public $alias           = "ug";

    public $fields          = array(
                                'id'          => array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'ID Group'),
                                'user_id'     => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'User ID'),
                                'group_id'    => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Group ID')
                            );

    public $selectClause    = "ug.*, grp.name";
    public $fromClause      = "users_groups ug
                               LEFT JOIN groups grp ON ug.group_id = grp.id";

    public $refs            = array();

    function __construct() {

        parent::__construct();
    }

    function validate() {

        if($this->actionType == 'CREATE') {
            //do something
            // example :
            //$this->record['created_date'] = date('Y-m-d');
            //$this->record['updated_date'] = date('Y-m-d');
        }else {
            //do something
            //example:
            //$this->record['updated_date'] = date('Y-m-d');
            //if false please throw new Exception
        }
        return true;
    }

}

/* End of file Groups.php */