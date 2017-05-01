<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{

    function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'language'));
    }

    function index() {
        check_login();
    }

    public function menuTree()
    {
        $data['group_id'] = $this->input->post('group_id');
        $this->load->view('administration/menu_tree', $data);
    }

    public function getMenuTreeJson()
    {
        $ci = & get_instance();
        $ci->load->model('administration/menus');
        $table = $ci->menus;
        $result = $table->getAll(0,-1);


        $i = 0;
        $data = array();
        foreach ($result as $menu) {

            $tmp = array(
                'id' => $menu['menu_id'],
                'parentid' => $menu['menu_parent'],
                'text' => $menu['menu_name'],
                'value' => $menu['menu_id'],
                'expanded' => true

            );



            //Cek count di tabel menu profile untuk menu_id , jika >0 maka checked true
            $ci->load->model('administration/groups');
            $tmpCount = $ci->groups->getMenuGroup($menu['menu_id'], $this->uri->segment(3));

            $countMenu = count($tmpCount);

            if ($countMenu > 0) {
                $tmp = array_merge($tmp, array('checked' => true));
                $tmp = array_merge($tmp, array('app_menu_group_id' => $tmpCount['app_menu_group_id']));
            } else {
                $tmp = array_merge($tmp, array('app_menu_group_id' => ''));
            }

            $data[$i] = $tmp;
            $i = $i + 1;

        }
        echo json_encode($data);
    }

    public function updateProfile()
    {
        $ci = & get_instance();
        $ci->load->model('administration/groups');
        $ci->groups->insMenuProf();
        // $this->M_admin->insMenuProf();
        $data['group_id'] = $this->input->post('group_id');
        $this->load->view('administration/menu_tree', $data);
    }


}