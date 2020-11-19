<html>
    <head>
    <title>Eletorix - Kaiken elektroniikan kauppapaikka</title>
    <meta charset="UTF-8">    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="kuvat/logo.png" />
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">
    <link rel="stylesheet" href="banner.css">  
    <link rel="stylesheet" href="mod.css">  
</head>
<body>


<?php
require_once("banner.php");  
require_once("db-init.php");
    
if (!isset($_SESSION['userId'])) header ("Location: index.php");
    
$id =  $_POST['id'];
$paivays = $_POST['paivays'];
$tuotteennimi = $_POST['tuotteennimi'];
$hinta =  $_POST['hinta'];
$tuotekuvaus = $_POST['tuotekuvaus'];
$kuva =  $_POST['kuva'];
$kateg = $_POST['kategoria'];
 echo "<div class='muokkaa'>" ;  
    echo "<div class='ilmoitus'>" ; 
echo "<h3 style='border-bottom: 3px solid white;'>Muokkaa ilmoitusta ".$id."</h3>
<form enctype='multipart/form-data' action='update.php' method='post'>
<input type='hidden' name='id' value=" . $id . ">
<p>Nimi:</p> <input type='text' name='tuotteennimi' value='" . $tuotteennimi . "'><p>Kategoria:</p><select name='kategoria' style='cursor:pointer'>";
    $sql = $db->query('SELECT * FROM kategoria;');
    while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        $kategoria = $row['idkategoria'];
        $kategorianimi = $row['kategorianimi'];
        echo "<option value=".$kategoria;
        if($kateg==$kategoria){echo " selected";}
        echo ">".$kategorianimi."</option>";
    };            
echo "</select>
<p>Hinta:</p> <input type='text' name='hinta' value=" . $hinta . "><br>
<p>Kuvaus:</p> <textarea name='tuotekuvaus' cols='40' rows='10'>". $tuotekuvaus ."</textarea><br>
<p>Kuva:</p> <input type='file' name='kuva' class='kuvanlis' ><p><small>Sallitut kuvamuodot ovat jpg, jpeg ja png.</small></p><p><small>Kuvan maksimi koko on 2 mb.</small></p><br>
<input value='Päivitä' type='submit' class='paivita'>
</form>";
    echo "</div>";
    echo "</div>";
    require_once("footer.html"); 
    
?>
<script src="javas.js"></script>
</body>
</html>