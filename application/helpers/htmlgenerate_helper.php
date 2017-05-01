<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('generatehtml')) {

    function inputan($type, $names, $class, $placeholder, $required, $values, $tags)
    {
        if (empty($tags)) {
            $tagtemp = "";
        } else {
            $tagtemp = "";
            foreach ($tags as $name => $tag) {
                $tagtemp = $tagtemp . " $name='$tag' ";
            }
        }
        $requred = $required == 0 ? '' : "required='required'";
        return "<div class='$class'><input type='$type' name='$names' placeholder='$placeholder' class='form-control' $requred value='$values' $tagtemp></div>";
    }


    // ---------------------------------- Textarea --------------------------------------------
    function textarea($name, $id, $class, $rows, $values)
    {
        return "<div class='$class'><textarea name='" . $name . "' id='" . $id . "' rows='" . $rows . "' class='form-control'>" . $values . "</textarea></div>";
    }


    function email($name, $placeholder, $required, $value)
    {
        $requred = $required == 0 ? '' : "required='required'";
        return "<input type='email' placeholder='$placeholder' name='$name' $required class='input-large' value='$value'>";
    }

    function combodumy($name, $id)
    {
        return "<select name='$name' id='$id' class='form-control'><option value='0'>Pilih data</option></select>";
    }

    function bulan($default_select, $selected)
    {
        $arr_bulan = array('01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember');

        echo "<select name='bulan' id='bulan' class='form-control'>";
        if ($default_select != "") {
            echo "<option value=''> " . $default_select . " </option> ";
        }
        foreach ($arr_bulan as $key => $value) {
            if ($selected == $key) {
                echo "<option value=" . $key . " selected>" . $value . "</option>";
            } else {
                echo "<option value=" . $key . ">" . $value . "</option>";
            }

        }
        echo "</select>";
    }

    function tahun($default_select, $selected)
    {
        echo "<select name='tahun' id='tahun' class='form-control'>";

        if ($default_select != "") {
            echo "<option value=''> " . $default_select . " </option> ";
        }

        $year = date("Y");
        for ($i = ($year); $i >= $year - 5; $i--) {

            if ($selected == $i) {
                echo "<option value=" . $i . " selected>" . $i . "</option>";
            } else {
                echo "<option value=" . $i . ">" . $i . "</option>";
            }

        }
        echo "</select>";


    }


    function buatcombo($nama, $id, $table, $field, $pk, $kondisi, $default_select)
    {
        $CI =& get_instance();
        $CI->load->model('mcrud');

        if ($kondisi == null) {
            $data = $CI->mcrud->getCombo($table, $field, $pk)->result();
        } else {
            $data = $CI->mcrud->getComboByID($table, $field, $pk, $kondisi)->result();
        }
        echo "<select name='" . $nama . "' id='" . $id . "'  class='form-control'>";

        if ($default_select != "") {
            echo "<option value=''> " . $default_select . " </option> ";
        }

        foreach ($data as $r) {
            echo " <option value=" . $r->$pk . ">" . strtoupper($r->$field) . "</option>";
        }
        echo "</select>";
    }

    function selectList($nama, $id, $table, $field, $pk, $kondisi, $default_select,$order_by,$order_type)
    {
        $CI =& get_instance();
        $CI->load->model('mcrud');

        if ($kondisi == null) {
            $data = $CI->mcrud->getComboNew($table, $field, $pk,$order_by,$order_type)->result();
        } else {
            $data = $CI->mcrud->getComboByIDNew($table, $field, $pk, $kondisi,$order_by,$order_type)->result();
        }
        echo "<select name='" . $nama . "' id='" . $id . "'  class='form-control'>";

        if ($default_select != "") {
            echo "<option value=''> " . $default_select . " </option> ";
        }

        foreach ($data as $r) {
            echo " <option value=" . $r->$pk . ">" . strtoupper($r->$field) . "</option>";
        }
        echo "</select>";
    }

    function editcombo($nama, $table, $class, $field, $pk, $kondisi, $tags, $value)
    {
        $CI =& get_instance();
        $CI->load->model('mcrud');
        if (empty($tags)) {
            $tagtemp = "";
        } else {
            $tagtemp = "";
            foreach ($tags as $name => $tag) {
                $tagtemp = $tagtemp . " $name='$tag' ";
            }
        }
        if ($kondisi == null) {
            $data = $CI->mcrud->getAll($table)->result();
        } else {
            $data = $CI->db->get_where($table, $kondisi)->result();
        }
        echo "<div class='$class'><select class='form-control' name='" . $nama . "' $tagtemp>";
        foreach ($data as $r) {
            echo "<option value='" . $r->$pk . "' ";
            echo $r->$pk == $value ? "selected='selected'" : "";
            echo ">" . strtoupper($r->$field) . "</option>";
        }
        echo "</select></div>";
    }

    function getStringMonth($bulan)
    {
        $arr_bulan = array('01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember');

        return $arr_bulan[$bulan];
    }
}
