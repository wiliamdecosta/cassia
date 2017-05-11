<?php

/**
 * Farmer Model
 *
 */
class Cassia_purchasing extends Abstract_model
{

    public $table = "purchasing";
    public $pkey = "purchasing_id";
    public $alias = "a";

    public $fields = array(
        'purchasing_id' => array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'Purchasing ID'),
        'fm_id' => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'farmer id'),
        'plt_id' => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'plantation id'),
        'product_id' => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Product ID'),
        'wh_id' => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'Warehouse id'),
        'purchasing_date' => array('nullable' => false, 'type' => 'date', 'unique' => false, 'display' => 'purchasing date'),
        'trx_code' => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Transaction Code'),
        'po_number' => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'PO Number'),
        'payment_type' => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Payment Type'),
        'batch_total' => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'Batch Total'),
        'purchasing_weight_init' => array('nullable' => true, 'type' => 'float', 'unique' => false, 'display' => 'Weight Init'),
        'purchasing_weight' => array('nullable' => false, 'type' => 'float', 'unique' => false, 'display' => 'Weight'),
        'rate' => array('nullable' => false, 'type' => 'float', 'unique' => false, 'display' => 'Rate'),
        'harvest_date' => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Harvest Date'),
        'harvest_method' => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'harvest_method'),
        'region' => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'district'),

        'created_date' => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Created Date'),
        'created_by' => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By'),
        'updated_date' => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
        'updated_by' => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By')

    );

    public $selectClause = "a.purchasing_id, a.fm_id, to_char(a.purchasing_date,'dd-Mon-yyyy') as purchasing_date, a.trx_code, a.payment_type,
                                    a.po_number, a.batch_total, a.purchasing_weight_init, a.purchasing_weight, a.rate, a.total_price,
                                    to_char(a.harvest_date,'dd-Mon-yyyy') as harvest_date,
                                    a.harvest_method, plt.plt_code, a.plt_id, a.wh_id,                 
                                    to_char(a.created_date,'dd-Mon-yyyy') as created_date, a.created_by,
                                    to_char(a.updated_date,'dd-Mon-yyyy') as updated_date, a.updated_by,
                                    fm.fm_code, fm.fm_name,
                                    a.product_id,pr.product_code";
    public $fromClause = " purchasing a
                                inner join farmer as fm on a.fm_id = fm.fm_id
                                left join plantation as plt on a.plt_id = plt.plt_id
                                left join product as pr on a.product_id = pr.product_id
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
            $this->record['trx_code'] = $this->getSerialNumber();
            $this->record['total_price'] = $this->getTotalPrice();
            $this->record['purchasing_weight_init'] = $this->record['purchasing_weight'];
            $this->record['created_date'] = date('Y-m-d');
            $this->record['created_by'] = $userdata->username;
            $this->record['updated_date'] = date('Y-m-d');
            $this->record['updated_by'] = $userdata->username;
            $this->record['wh_id'] = $userdata->wh_id;
            if ($this->record['harvest_date'] == "") {
                $this->record['harvest_date'] = NULL;
            }
            if ($this->record['plt_id'] == "") {
                $this->record['plt_id'] = NULL;
            }
            if ($this->record['batch_total'] == "") {
                $this->record['batch_total'] = NULL;
            }
        } else {
            //do something
            //example:
            $this->record['trx_code'] = $this->setSerialNumber();
            $this->record['total_price'] = $this->getTotalPrice();
            $this->record['purchasing_weight_init'] = $this->record['purchasing_weight'];
            $this->record['updated_date'] = date('Y-m-d');
            $this->record['updated_by'] = $userdata->username;
            if ($this->record['harvest_date'] == "") {
                $this->record['harvest_date'] = NULL;
            }
            if ($this->record['plt_id'] == "") {
                $this->record['plt_id'] = NULL;
            }
            if ($this->record['batch_total'] == "") {
                $this->record['batch_total'] = NULL;
            }

           /* if ($this->checkPurchasingInDrying($this->record['purchasing_id']) > 0) {
                throw new Exception('Can not edit this record ! This record has been used in the production.');
            }*/

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

    function setSerialNumber()
    {
        $id = $this->record['purchasing_id'];
        $sql = "select to_char(purchasing_date,'yyyy-Mon-dd') as  purchasing_date,trx_code from purchasing
                    where purchasing_id = ".$id;
        $query = $this->db->query($sql)->row_array();

        if($query['purchasing_date'] == $this->record['purchasing_date']){
            return $query['trx_code'];
        }else{
            return $this->getSerialNumber();
        }

    }

    function getTotalPrice()
    {

        $price = $this->record['rate'];
        $qty = $this->record['purchasing_weight'];
        $total_price = $price * $qty;
        return $total_price;
    }

    function insertStock($rmp)
    {
        $ci = &get_instance();

        $ci->load->model('agripro/stock');
        $tStock = $ci->stock;
        $tStock->actionType = 'CREATE';

        $ci->load->model('agripro/stock_category');
        $tStockCategory = $ci->stock_category;

        $record_stock = array();
        $record_stock['wh_id'] = $rmp['wh_id'];
        $record_stock['product_id'] = 1;
        $record_stock['sc_id'] = $tStockCategory->getIDByCode('RAW_MATERIAL_STOCK');
        $record_stock['stock_tgl_masuk'] = $rmp['purchasing_date'];; //base on packing_tgl
        $record_stock['stock_kg'] = $rmp['purchasing_weight'];
        $record_stock['stock_ref_id'] = $rmp['purchasing_id'];
        $record_stock['stock_ref_code'] = 'RAW MATERIAL IN';

        $tStock->setRecord($record_stock);
        $tStock->create();
    }

    function updateStock($rmp)
    {
        $ci = &get_instance();

        $ci->load->model('agripro/stock');
        $tStock = $ci->stock;
        $tStock->actionType = 'UPDATE';

        $ci->load->model('agripro/stock_category');
        $tStockCategory = $ci->stock_category;

        $record_stock = array();
        $record_stock['product_id'] = 1;
        $record_stock['sc_id'] = $tStockCategory->getIDByCode('RAW_MATERIAL_STOCK');
        $record_stock['stock_tgl_masuk'] = $rmp['purchasing_date']; //base on packing_tgl
        $record_stock['stock_kg'] = $rmp['purchasing_weight'];

        $this->db->where(array(
            'stock_ref_id' => $rmp['purchasing_id'],
            'sc_id' => $tStockCategory->getIDByCode('RAW_MATERIAL_STOCK')
        ));
        $this->db->update($tStock->table, $record_stock);

    }

    function deleteStock($items)
    {
        $ci = &get_instance();

        $ci->load->model('agripro/stock');
        $tStock = $ci->stock;

        $ci->load->model('agripro/stock_category');
        $tStockCategory = $ci->stock_category;

        $ref = array(
            'stock_ref_id' => $items,
            'stock_ref_code' => 'RAW MATERIAL IN',
            'sc_id' => $tStockCategory->getIDByCode('RAW_MATERIAL_STOCK')
        );
        $tStock->deleteByReference2($ref);
    }

    function checkPurchasingInDrying($purchasing_id)
    {
        $this->db->where('purchasing_id', $purchasing_id);
        $query = $this->db->get('cassia_drying');

        return $query->num_rows();
    }


}

/* End of file Groups.php */