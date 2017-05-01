<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MPortal extends Abstract_model
{

    public function getTracking()
    {
        $serial = trim(strtoupper($this->input->post('input_txt')));
        $this->db->where('UPPER(packing_batch_number)', $serial);
        $query = $this->db->get('v_packing');
        return $query;

    }

    public function getDetailPackaging($pck_id){
        $this->db->where('packing_id', $pck_id);
        $query = $this->db->get('v_detail_packing');
        return $query->result();
    }


}