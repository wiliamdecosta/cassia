<?php

/**
 * Groups Model
 *
 */
class Groups extends Abstract_model {

    public $table           = "groups";
    public $pkey            = "id";
    public $alias           = "grp";

    public $fields          = array(
                                'id'             => array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'ID Group'),
                                'name'           => array('nullable' => false, 'type' => 'str', 'unique' => true, 'display' => 'Role Name'),
                                'description'    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Description'),
                            );

    public $selectClause    = "grp.*";
    public $fromClause      = "groups grp";

    public $refs            = array('users_groups' => 'group_id',
									'groups_permissions' => 'group_id');

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

	function remove_foreign_primary($f_key){
		$sql ="DELETE FROM GROUPS_PERMISSIONS
				WHERE GROUP_ID = ". $f_key;
		$query = $this->db->query($sql);
		$sql ="DELETE FROM GROUPS
				WHERE ID = ". $f_key;
		$query = $this->db->query($sql);

		return true;
	}

    function getMenuGroup($menu, $group){
        $sql = "SELECT * FROM APP_MENU_GROUPS WHERE MENU_ID = $menu AND GROUP_ID = $group";
        $query = $this->db->query($sql);
        $row = $query->row_array();

        return $row;
    }

    function insMenuProf(){
        $menu_id = $this->input->post('check_val');
        $menu_id2 = $this->input->post('uncheck_val');
        $group_id = $this->input->post('group_id');

        $this->load->model('administration/menus');

        $this->db->trans_begin();
        if ($group_id != "" || $group_id != null) {
            $user = $this->session->userdata('d_user_name');

            // Check List
            if($menu_id){
                for ($i = 0; $i < count($menu_id); $i++) {
                    $cek = $this->getMenuGroup($menu_id[$i], $group_id);
                    if($cek == 0){
                        $data = array(
                            //"app_menu_group_id" => $this->menus->generate_id('app_menu_groups'),
                            "menu_id" => $menu_id[$i],
                            "group_id" => $group_id
                        );

                        $this->db->insert("app_menu_groups", $data);
                    }
                }
            }

            if($menu_id2){
                for ($j = 0; $j < count($menu_id2); $j++) {
                    $cek = $this->getMenuGroup($menu_id2[$j], $group_id);
                    if($cek > 0){
                         $this->db->where("menu_id", $menu_id2[$j]);
                        $this->db->where("group_id", $group_id);
                        $this->db->delete("app_menu_groups");

                    }
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

        } else {
            $this->db->trans_commit();
        }

    }

}

/* End of file Groups.php */