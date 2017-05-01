<?php

function jsonDecode($data) {

	if (empty($data)) return array();

    $items = json_decode($data, true);

    if ($items == NULL){
        throw new Exception('JSON items could not be decoded');
    }

    return $items;
}

function isValidEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
}

function html_spaces($number=1) {
    $result = "";
    for($i = 1; $i <= $number; $i++) {
        $result .= "&nbsp;";
    }
    return $result;
}

/**
 * compareDate
 *
 * Melakukan perbandingan dua tanggal
 *
 * @param string $from_date : batas tanggal awal dalam format yyyy-mm-dd
 * @param string $to_date   : batas tanggal akhir dalam format yyyy-mm-dd
 * @return 1 jika from_date < to_date, 2 jika from_date > to_date, 3 jika from_date = to_date
 */
function compareDate($from_date, $to_date) {
    $from_date = trim($from_date);
    $to_date = trim($to_date);

    if ($from_date == $to_date){
        return 3;
    }

    $waktu_awal = explode("-",$from_date);
    $waktu_akhir = explode("-",$to_date);
    if ($waktu_awal[0] > $waktu_akhir[0]){
        return 2;
    }else if ($waktu_awal[0] == $waktu_akhir[0]){
        if ($waktu_awal[1] > $waktu_akhir[1]){
            return 2;
        }else if ($waktu_awal[1] == $waktu_akhir[1]){
            if ($waktu_awal[2] > $waktu_akhir[2]){
                return 2;
            }
        }
    }
    return 1;
}

function numberFormat($number, $decimals =  2, $dec_point = ',' , $thousands_sep = '.'){
    return number_format($number, $decimals, $dec_point, $thousands_sep);
}
?>