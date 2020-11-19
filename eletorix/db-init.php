<?php
$db = new PDO('mysql:host=mysql.labranet.jamk.fi;dbname=L5275_3;charset=utf8',
              'L5275', 'DBgh88InVPP853vKpheEsbtQMqNAUiTH');
//$db = new PDO('mysql:host=localhost;dbname=eletorix;charset=utf8',
//              'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
header('Cache-Control: no cache');
?>