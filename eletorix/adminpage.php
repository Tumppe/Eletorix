<html>
    <head>
    <title>Eletorix - Kaiken elektroniikan kauppapaikka</title>
    <link rel="icon" href="kuvat/logo.png" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="banner.css">  
    <link rel="stylesheet" href="ilmoitus.css">   
    <link rel="stylesheet" href="adminpage.css">    
</head>
<body>
<?php		
require_once("banner.php");
if (!isset($_SESSION)) session_start();
if (isset($_SESSION['userId'])) {
    $username = $_SESSION['userId'];
    require_once("db-init.php");

    $stmt = $db->prepare('SELECT tyyppi FROM kayttaja WHERE nimi = :username');
    $stmt->bindValue(':username', $username, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $tyyppi = $row['tyyppi'];
    if ($tyyppi !== "admin") header("Location: index.php");
} 
else { header("Location: index.php"); }
?>
<div class="sivunYllapito">
<div class="teeMuutoksia">
<h1>Sivun ylläpito</h1>
<form onSubmit="if(!confirm('Haluatko varmasti lisätä uuden kategorian?')){return false;}" action="addkategoria.php" method="post">
    <h2>Lisää kategoria</h2>
    <p>Kategorian nimi: <input type="text" name="nimi"></p>
   <p>Kategoriakuvaus: <textarea name="kuvaus" rows="4" cols="50"></textarea></p>

    <p><input type="submit" class="add" value="Lisää kategoria"></p>
</form>
<form onSubmit="if(!confirm('Haluatko varmasti poistaa käyttäjän?')){return false;}" action="delkayttaja.php" method="post">
<h2>Poista käyttäjä</h2>
<p>Käyttäjä:
<select name="valikko">
<?php
    $stmt = $db->prepare("SELECT nimi, idkayttaja FROM kayttaja;");
    $stmt->execute();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $nimi = $row['nimi'];
        $idkayttaja = $row['idkayttaja'];
        echo "
            <option value=".$idkayttaja.">".$nimi." [ID:".$idkayttaja."]</option>
        ";
    }
?>
</select></p>
<p><input type="submit" class="add" value="Poista käyttäjä"></p>
</form>
    
<form onSubmit="if(!confirm('Haluatko varmasti poistaa kategorian?')){return false;}" action="delkategoria.php" method="post">
<h2>Poista kategoria</h2>
<p>Kategoria:
<select name="valikko2">
<?php
    $stmt = $db->prepare("SELECT * FROM kategoria;");
    $stmt->execute();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $kategorianimi = $row['kategorianimi'];
        $idkategoria = $row['idkategoria'];
        echo "
            <option value=".$idkategoria.">".$kategorianimi." [ID:".$idkategoria."]</option>
        ";
    }
?>
</select></p>
<p><input type="submit" class="add" value="Poista kategoria"></p>
</form>
    
    
    
</div>
</div>   
<?php
require_once("footer.html");
?>
<script src="javas.js"></script>
</body>
</html>