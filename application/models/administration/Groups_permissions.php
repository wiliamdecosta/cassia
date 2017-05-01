<?php

/**
 * Groups Model
 *
 */
class Groups_permissions extends Abstract_model {

    public $table           = "groups_permissions";
    public $pkey            = "id";
    public $alias           = "gp";
	public $group_id 		= '';
    public $fields          = array(
                                'id'          	=> array('pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => true, 'display' => 'ID Group'),
                                'permission_id' => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Permission ID'),
                                'group_id'    	=> array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Group ID'),
								'status'    	=> array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Status')
                            );

    public $selectClause    = " (coalesce(gp.group_id,0) || '.' || pr.permission_id) AS groups_permissions_id,
								pr.permission_id, gp.group_id, pr.permission_name, pr.permission_description, gp.id, gp.status,
								CASE gp.status
									WHEN 'Y' THEN 'Yes'
									WHEN 'N' THEN 'No'
									ELSE ''
								END AS status_permission
								";
    public $fromClause      = "permissions pr
							   LEFT JOIN groups_permissions gp ON pr.permission_id = gp.permission_id %s
							   ";
							   //AND gp.group_id =1

    public $refs            = array();

    function __construct($group_id = ''){
		if (!empty($group_id)){
			$this->group_id = (int) $group_id;
			$this->fromClause = sprintf($this->fromClause, 'AND gp.group_id = '.$this->group_id);
		}else{
			$this->fromClause = sprintf($this->fromClause, '');
		}

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

	function getItem($permission_id = '', $group_id = '') {

	}

	function update_groups_permissions($permission_id, $group_id, $status) {
        $sql = "UPDATE GROUPS_PERMISSIONS SET STATUS = '". $status ."'
				WHERE GROUP_ID = ". $group_id ." AND
				PERMISSION_ID = ". $permission_id ;
        $query = $this->db->query($sql);

		return true;
    }

	function delete_groups_permissions($permission_id, $group_id) {
        $sql = "DELETE FROM GROUPS_PERMISSIONS
				WHERE GROUP_ID = ". $group_id ." AND
				PERMISSION_ID = ". $permission_id ;
        $query = $this->db->query($sql);

		return true;
    }

}

/* End of file Groups.php */