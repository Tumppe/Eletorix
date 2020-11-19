<!DOCTYPE html>
<html>
    <head>
    <title>Eletorix - Kaiken elektroniikan kauppapaikka</title>
    <link rel="icon" href="kuvat/logo.png" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Cuprum" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>   
    <link rel="stylesheet" href="ilmoitus.css">
    <link rel="stylesheet" href="banner.css"> 
    <link rel="stylesheet" href="viestit.css">
</head>
<body>    
    
<?php
    require_once("banner.php");  
    require_once("db-init.php"); 
    if (!isset($_SESSION['userId'])) header ("Location: index.php");
    $vas = $_SESSION['userId'];
?>

<nav>
<div>
<a href="send.php">Lähetä viesti</a>
<a href="read.php">Lue viestejä</a>
</div>
    
</nav>
    
Saapuneet viestit:
<?php
    $stmt = $db->prepare("SELECT * FROM kayttaja WHERE nimi = '$vas';");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $vasID = $row['idkayttaja'];
    
    $stmt = $db->prepare("SELECT * FROM viesti WHERE idvastaanottaja = '$vasID' ORDER BY idviesti DESC LIMIT 5;");
    $stmt->execute();
                
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id = $row['idviesti'];
        $lahID = $row['idlahettaja'];
        
        $stmt2 = $db->prepare("SELECT nimi FROM kayttaja WHERE idkayttaja = '$lahID';");
        $stmt2->execute();
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $lah = $row2['nimi'];
        
        $sis = $row['sisalto'];
        $pvm = $row['pvm'];
        $lue = $row['luettu'];
        
        echo "<div>viesti-id:$id <br>vastaanottaja:$vas <br>lahettaja:$lah<br>sisalto:$sis<br>pvm:$pvm<br>luettu:$lue<br>";
        
        $actionpage = $_SERVER['PHP_SELF'];
        echo "
        <form action='$actionpage' method='post'>
            <input name='idviesti' type='text' value='$id'>
            <input name='btn' type='submit' value='Merkitse luetuksi'>
        </form>
        </div>";
    }
    if (isset($_POST['idviesti'])) {
        
        $idviesti = $_POST['idviesti'];

        $sql = ("UPDATE viesti SET luettu = 1 WHERE idviesti = '$idviesti';");
        $db->exec("$sql");
        
    }
    
?>
</body>

</html>