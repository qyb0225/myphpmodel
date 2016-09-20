<?php
/*
* author:dsmile
* contact:852353443@qq.com
* discription:这是一个数据库基本操作的类，包括增、删、查、改；
**/
include_once('./config.php');
class MySqlOperation{
    function MySqlOperation( $table ){
        $con = mysql_connect(HOST, USERNAME, PASSWORD);
            if (!$con)
            {
                die('Could not connect: ' . mysql_error());
            }
        mysql_select_db($table, $con);
    }
    
    function delete( $condition, $tableName){
        if($condition && $tableName){
            if( count($condition) == 2){
               $sql = "DELETE FROM $tableName WHERE $condition[0]='$condition[1]'";
                mysql_query($sql);
            }
        }

    }
}
function insert($table, $obj, $values) {
    $a = '';
    $b = "('".join( $values, "','")."')";
    if( $obj ) {
        $a = '('.join($obj, ',').')';
    }
    return "INSERT $table $a VALUES $b ";
}
function update($table) {
    return " update $table ";
}
function set($obj, $values) {
    $type = gettype($values);
    if($type === 'string') {
        return 'SET '.$obj.' = '."'$values' ";
    }else {
        $retunStr = ' SET ';
        foreach ($values as $key => $value) {
            $retunStr .= $values[$key].' = '."'$value',";
        }
        return substr($retunStr, 0, -1);
    }
}
function select($content) {
    return " SELECT $content ";
}
function between($obj, $start, $end) {
    return " $obj BETWEEN '$start' AND '$end' ";
}
function from( $table ) {
    return " FROM '$table' ";
}
function where($obj, $data) {
    $type = gettype($data);
    if($type === 'string') {
        return 'WHERE '.$obj.' = '."'$data'";
    }else {
        $retunStr = 'WHERE ';
        foreach ($data as $key => $value) {
            $retunStr .= $obj[$key].' = '."'$value' AND ";
        }
        return substr($retunStr, 0, -5);
    }
}
function orderby($obj) {
    $length = count($obj);
    $returnVal = " ORDER BY ";
    if($length <= 1) {
        $returnVal .= $obj;
    }else {
        foreach ($obj as $key => $value) {
            $returnVal .= "'$value',";
        }
        $returnVal = substr($returnVal, 0, -1);
    }
    return $returnVal;
}
?>
