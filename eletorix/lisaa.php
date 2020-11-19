<html>
<head>
    <title>Eletorix - Kaiken elektroniikan kauppapaikka</title>
    <meta charset="UTF-8">    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="kuvat/logo.png" />
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">
    <link rel="stylesheet" href="banner.css">  
    <link rel="stylesheet" href="lisaa.css">   
</head>
<body>
<?php
    require_once("banner.php");  
    
    if (!isset($_SESSION['userId'])) header ("Location: index.php");

    ?>
<div class='muokkaa'> 
<div class='ilmoitus'>
<h3 style="border-bottom: 3px solid white;">Lisää uusi ilmoitus</h3>
<form enctype="multipart/form-data" action="insert.php" method="post">
<p>Tuotteennimi:</p> <input type="text" name="tuotteennimi"><br>
<p>Kategoria:</p><select name='kategoria' style="cursor:pointer">
                <?php
                    require_once("db-init.php");
                    $sql = $db->query('SELECT * FROM kategoria;');
                    while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                        $kategoria = $row['idkategoria'];
                        $kategorianimi = $row['kategorianimi'];
                        echo "<option value=".$kategoria.">".$kategorianimi."</option>";
                    }                
                ?>
</select>
<p>Hinta:</p> <input type="number" name="hinta"><br>
<p>Tuotekuvaus:</p> <textarea name='tuotekuvaus' cols='40' rows='10'></textarea><br>
    <p>Kuva:</p><input type="file" name="kuva" value=""><p><small>Sallitut kuvamuodot ovat jpg, jpeg ja png.</small></p><p><small>Kuvan maksimi koko on 2 mb.</small></p><br>
<input value="Lisää" type="submit" class="paivita">
</form>
    </div>
    </div>
   <?php
    require_once("footer.html"); 
    ?>
<script src="javas.js"></script>
</body>
</html>