<?php 
include_once('./tools/MySqlOperation.php');
$msq = new MySqlOperation('ht_finance');
// $result =  $msq -> select('*').
//            $msq -> where('time', '20160808').
//            $msq -> between('time', '20160808', '20160810').
//            $msq ->  orderby('time');
$result = update('*') . insert('person', ['time', '4324'], ['20160808', '34235']) . between('time', '20160808', '20160810') .  orderby('time');
echo $result;
$thisArray = [1,2,3,4];
// echo $mso -> where(['time', 'name'], ['20160808', 'Tom']);
?>