<?php

/**
 * Groups Model
 *
 */
class Menus extends Abstract_model {

    public $table           = "app_menu";
    public $pkey            = "menu_id";
    public $alias           = "mn";

    public $fields          = array(
                                'menu_id'      => array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'Menu ID'),
                                'menu_parent'  => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'Menu Parent'),
                                'menu_name'    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Nama Menu'),
                                'menu_icon'    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Icon'),
                                'menu_desc'    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Description'),
                                'menu_link'    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Link'),
                                'file_name'    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Filename'),
                                'listing_no'    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'No. Urut')
                            );

    public $selectClause    = "mn.*";
    public $fromClause      = "app_menu mn";

    // public $refs            = array('users_groups' => 'group_id');
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

    function groupParentMenus($user_id){
        $result = array();
        $sql = "SELECT DISTINCT a.*
                FROM app_menu a, app_menu_groups b, users_groups c
                WHERE a.menu_id = b.menu_id
                AND b.group_id = c.group_id
                AND a.menu_parent = 0
                AND c.user_id = $user_id order by listing_no ASC";

        $q = $this->db->query($sql);
        if($q->num_rows() > 0) $result = $q->result();

        return $result;
    }

    function groupChildMenus($menu_id, $user_id){
        $result = array();
        $sql = "SELECT DISTINCT a.*
                FROM app_menu a, app_menu_groups b, users_groups c
                WHERE a.menu_id = b.menu_id
                AND b.group_id = c.group_id
                AND a.menu_parent = $menu_id
                AND c.user_id = $user_id order by listing_no ASC";

        $q = $this->db->query($sql);
        if($q->num_rows() > 0) $result = $q->result();

        return $result;
    }

}

/* End of file Groups.php */