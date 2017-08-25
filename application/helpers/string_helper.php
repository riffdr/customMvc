<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 11/03/2015
 * Time: 16:35
 */

function get_string_between($string, $start, $end){
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}

function is_odd($number){
    return ($number % 2 != 0) ? true : false;
}


function nullifier($value = null){
    return (empty($value) || is_null($value))? 'NULL' : "'".$value."'";
}

function filter_date($value){
    return is_numeric($value) ? true : false;
}

function filter_time($value){
    return !is_numeric($value) ? true : false;
}

function contains_number($str){
   return (preg_match('#[0-9]#',$str))? true : false;
}


