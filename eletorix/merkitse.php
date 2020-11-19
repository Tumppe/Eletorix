<?php    
require_once("db-init.php");
if (isset($_POST['idviesti'])) {
        $idviesti = $_POST['idviesti'];
        $sql = ("UPDATE viesti SET luettu = 1 WHERE idviesti = '$idviesti';");
        $db->exec("$sql");
}
echo "huhuu";
header ("Location: viestikeskus.php");
die;
?>