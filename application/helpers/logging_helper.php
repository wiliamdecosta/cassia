<?php
    function logging($message='') {

        $ci =& get_instance();
        $ci->load->model('administration/logs');
        $table = $ci->logs;
        $userdata = $ci->ion_auth->user()->row();

        $table->actionType = 'CREATE';
        $table->setRecord(
            array('log_desc' => $userdata->username.' '.$message.' - Time : '.date('d-m-Y H:i:s'),
                  'log_user' => $userdata->username)
        );
        $table->create();
    }
?>