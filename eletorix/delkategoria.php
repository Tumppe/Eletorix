<html>
    <head>
    <title>Eletorix - Kaiken elektroniikan kauppapaikka</title>
    <meta charset="UTF-8">    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="kuvat/logo.png" />
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">
    <link rel="stylesheet" href="banner.css">  
    <link rel="stylesheet" href="minisivut.css">    
</head>
<body>
    
<?php
require_once("banner.php");  
require_once("db-init.php");
if (!isset($_SESSION)) session_start();
if (isset($_SESSION['userId'])) {
    $username = $_SESSION['userId'];
    
    $stmt = $db->prepare('SELECT tyyppi FROM kayttaja WHERE nimi = :username');
    $stmt->bindValue(':username', $username, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $tyyppi = $row['tyyppi'];
    if ($tyyppi !== "admin") header("Location: index.php");
} 
else { header("Location: index.php"); }

if (isset($_POST['valikko2'])) {
    $valinta = $_POST['valikko2'];
    $stmt = $db->prepare("DELETE FROM kategoria where idkategoria = $valinta;");
    $stmt->execute();
}
    echo "<div class='muokkaa'>" ;  
    echo "<div class='ilmoitus'>" ; 
    echo "Kategoria-id: " . $valinta . " poistettu onnistuneesti.";
    echo "<a href='adminpage.php'><button class='takaisin'>Takaisin</button></a> ";
    echo "</div>";
    echo "</div>";    
    require_once("footer.html"); 
?>
<script src="javas.js"></script>
</body>
</html>