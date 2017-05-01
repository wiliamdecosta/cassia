<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{

    function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'language'));
    }

    function index() {
        check_login();
        $ci = & get_instance();
        $ci->load->model('administration/menus');
        $result = $ci->menus->groupParentMenus($this->ion_auth->user()->row()->id);

        $menu = '';
        foreach($result as $data){
            $menu .= $this->get_menu($data);
        }

        $this->menu = $menu;

        $this->load->view('home/index');
    }

    function get_menu($data){
        $html = "";
        if(isset($data)){
            $ci = & get_instance();
            $ci->load->model('administration/menus');
            $result = $ci->menus->groupChildMenus($data->menu_id, $this->ion_auth->user()->row()->id);

            if($result){
                $html .= "<li class='nav-item' data-source=''>";
            }else{
                $html .= "<li class='nav-item' data-source='blank'>";
            }

            $html .= "<a href='".$data->menu_link."' class='nav-link nav-toggle'>";
            $html .= "<i class='".(empty($data->menu_icon) ? 'fa fa-folder-o' : $data->menu_icon)."'></i>";
            $html .= "<span class='title'> ".$data->menu_name."</span>";

            if($result){
                $html .= "<span class='arrow'></span>";
            }

            $html .= "</a>";


            if($result){
                $html .= "<ul class='sub-menu'>";
                foreach ($result as $row) {
                    $html .= "<li class='nav-item' data-source='".$row->file_name."'>";
                    $html .= "<a href='#' class='nav-link'>";
                    $html .= "<span class='title'> ".$row->menu_name."</span>";
                    $html .= "</a>";
                    $html .= "</li>";
                }
                $html .= "</ul>";
            }

            $html .= "</li>";

            return $html;
        }else{
            return false;
        }

    }

    function load_content($id) {
        try {
            $file_exist = true;
            check_login();
            $id = str_replace('.php','',$id);
            $file = explode(".", $id);
            $url_file = "";
            if(count($file) > 1) {
                if(strtolower(substr($file[1],-4)) != ".php")
                    $file[1] .= ".php";
                if(file_exists(APPPATH."views/".$file[0].'/'.$file[1])) {
                    $this->load->view($file[0].'/'.$file[1]);
                }else {
                    $file_exist = false;
                }

                $url_file = APPPATH."views/".$file[0].'/'.$file[1];
            }else {
                if(strtolower(substr($id,-4)) != ".php")
                    $id .= ".php";

                if(file_exists(APPPATH."views/".$id)) {
                    $this->load->view($id);
                }else {
                    $file_exist = false;
                }

                $url_file = APPPATH."views/".$id;
            }

            if(!$file_exist) {
                $this->load->view("error_404.php");
            }

        }catch(Exception $e) {
            echo "
                <script>
                    swal({
                      title: 'Session Timeout',
                      text: '".$e->getMessage()."',
                      html: true,
                      type: 'error'
                    });
                </script>
            ";
            exit;
        }
    }

}