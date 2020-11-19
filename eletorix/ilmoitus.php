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
    </head>
    
<body>    

<?php
require_once("banner.php");  
require_once("db-init.php");
?>
    
<div class="popUp"><p><span class="popUpNimi"></span></p><img class="popUpImg" alt="popUp"/></div>
    
<?php
   
if (isset($_POST['id'])) {
    $id = $_POST['id'];
} 
elseif (isset($_GET['id'])) {
    $id = $_GET['id'];
} 
else {
    header ('Location: index.php');
}

$stmt = $db->prepare("SELECT * FROM ilmoitus INNER JOIN kategoria ON ilmoitus.kategoria_idkategoria=kategoria.idkategoria INNER JOIN kayttaja ON ilmoitus.kayttaja_idkayttaja=kayttaja.idkayttaja WHERE idilmoitukset=:id");
$stmt->bindValue(':id', $id);
$stmt->execute();    
    
    
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $row['idilmoitukset'];
    $tuotteennimi = $row['tuotteennimi'];
    
    //$paivays = $row['paivays'];                 //vanha  
    $paivays_koko = $row['paivays'];                      //TÄHÄN LISÄTTY
    $paivays = "";                                        //TÄHÄN LISÄTTY
    $paivays .= substr($paivays_koko,8,2).".";  //päivä   //TÄHÄN LISÄTTY
    $paivays .= substr($paivays_koko,5,2).".";  //kk      //TÄHÄN LISÄTTY
    $paivays .= substr($paivays_koko,0,4);      //vuosi   //TÄHÄN LISÄTTY
    $paivaysklo = "";
    $paivaysklo .= substr($paivays_koko,10,6);

     $kategoria = $row['idkategoria'];   
    $hinta = $row['hinta'];
    $tuotekuvaus = $row['tuotekuvaus'];
    $kuva = $row['kuva'];
    $kateg = $row['idkategoria'];
    $kategorianimi = $row['kategorianimi'];
    $nimi = $row['nimi'];
    $email = $row['email'];
    $puhnum = $row['puhnum'];
    $osoite = $row['osoite'];
    $kaupunki = $row['kaupunki'];
    $kayttajaid = $row['kayttaja_idkayttaja'];
    $ref = $_SERVER['PHP_SELF'] . "?id=$id";

    echo "
<div class='ilmoitusContainer'>
    <div class='ilmoitus2 tyyliton'>";
        $userid = NULL;
        if (isset($_SESSION['Id'])) {
            $userid = $_SESSION['Id'];
        } 
    $kayttajatyyppi = NULL;
if (isset($_SESSION['tyyppi'])) {
            $kayttajatyyppi = $_SESSION['tyyppi'];
        }
        $favo[0] = "paska";
    if(isset($_SESSION['suosikit'])){
        $favo = $_SESSION['suosikit'];
    }
    if (isset($_SESSION['Id']) && !in_array($id, $favo)) {
    echo "
        <div class='ilmoitus_banner'>
        <form method='post' class='addSuosikkiForm'>
            <input type='hidden' name='pois' value=" . $id . " />
            <input type='hidden' name='referer' value='$ref'>
            <input type='hidden' name='idsuosikki' value=" . $id . " />
            <input value='addsuosikki' type='image' src='kuvat/sydan.png' class='rasti sydan'>
        </form>  
         ";
    }
     if (isset($_SESSION['Id']) && isset($_SESSION['suosikit']) && in_array($id, $_SESSION['suosikit']) ) {  
         echo "
        <div class='ilmoitus_banner'>
        <form method='post' class='addSuosikkiForm'>
            <input type='hidden' name='pois' value=" . $id . " />
            <input type='hidden' name='referer' value='$ref'>
            <input type='hidden' name='idsuosikki' value=" . $id . " />
            <input value='addsuosikki' type='image' src='kuvat/pois_sydan.png' class='rasti sydan'>
        </form>  
         ";
     }
        
        if ($userid==$kayttajaid or $kayttajatyyppi=="admin"){
        echo "
        
        
        <form action='mod.php' method='post'>
        <input type='hidden' name='id' value='" . $id . "' />
        <input type='hidden' name='paivays' value='" . $paivays . "' />
        <input type='hidden' name='tuotteennimi' value='" . $tuotteennimi . "' />
        <input type='hidden' name='hinta' value='" . $hinta . "' />
        <input type='hidden' name='tuotekuvaus' value='" . $tuotekuvaus . "' />
        <input type='hidden' name='kuva' value='" . $kuva . "' />
        <input type='hidden' name='kategoria' value='" . $kateg . "' />
        <input type='hidden' name='kategorianimi' value='" . $kategorianimi . "' />
        <input type='hidden' name='nimi' value='" . $nimi . "' />
        <input type='hidden' name='email' value='" . $email . "' />
        <input type='hidden' name='puhnum' value='" . $puhnum . "' />
        <input type='hidden' name='osoite' value='" . $osoite . "' />
        <input type='hidden' name='kaupunki' value='" . $kaupunki . "' />
        <input value='Muokkaa' type='image' src='kuvat/cog.png' class='muokkaa'>
        </form>
        
        <form action='remove.php' method='post'>
        <input type='hidden' name='pois' value=" . $id . " />
        <input value='addSuosikki' type='image' src='kuvat/roskis.png' class='rasti'>
        </form> 
        ";
        }
    if (isset($_SESSION['Id'])) {
        echo "</div>";
    }
    echo "    
        <div class='ilmoitusOtsikko'>
        <h1 class='tuotteennimi'>{$tuotteennimi}</h1><h1>{$hinta} &#8364</h1>
        <p>{$kategorianimi}</p>
        <p>Ilmoitus j&#228;tetty: {$paivays} klo {$paivaysklo}</p>            
        </div>
        
        <div class='ilmoitusOsio oikeaIlmoitus'>
        <img src='kuvat/{$kuva}' alt='Ilmoituksen kuva' class='tuotekuva'>
        </div>
        
        <div class='ilmoitusOsio'>
        <h2>Kuvaus</h2>
        <p>{$tuotekuvaus}</p>
        </div>
        
        <div class='ilmoitusOsio'>";
    echo utf8_encode("<h2>Myyjä</h2>
        <p>{$kaupunki}</p>
        <p>{$nimi}</p>
        <p>{$puhnum}</p>
        <form action='uusiviesti.php' method='post'>
            <input name='idtuote' type='hidden' value='{$id}'>
            <input name='vastaanottaja' type='hidden' value='{$nimi}'>
            <input type='submit' value='Ota yhteyttä myyjään' class='add'>
        </form>
        </div>

        <div class='ilmoitusOsio'>
        <h2>Kommentit</h2>");
        $kommenttiteksti = "";
        $nickname = "";
        $kommenttiError = "";
        $nicknameError = "";
    
        if (isset($_POST["kommenttiteksti"])){
            $kommenttiteksti = $_POST['kommenttiteksti'];
        }
        if (isset($_POST["nickname"])){
            $nickname = $_POST['nickname'];
        }
    
        if (isset($_POST['id'])) { $idilmoitus = $_POST['id']; }
        elseif (isset($_GET['id'])) { $idilmoitus = $_GET['id']; }
        else { echo "Ilmoitus-ID:tä ei löytynyt."; }
    
        $sql = "SELECT * from kommentti WHERE kommenttiteksti = '$kommenttiteksti' AND nickname = '$nickname';";
        $result = $db->prepare($sql);
        $result->execute();
        $rowCount = $result->fetchColumn();
            
        if ($rowCount < 1 && $kommenttiteksti !== "" && $nickname !== "") {  
            $sql ="INSERT into kommentti (kommenttiteksti, nickname, kommenttipaivays, ilmoitus_idilmoitukset) values ('$kommenttiteksti', '$nickname', NOW(), '$idilmoitus');
            ";
            $db->exec("$sql");
        } 
        elseif (isset($_POST['nappi'])) {
            if ($kommenttiteksti == "") {
                $kommenttiError = "<span style='color:red'>(*)</span>";
            } 
            if ($nickname == "") {
                $nicknameError = "<span style='color:red'>(*)</span>";
            }
        }
               
        $stmt = $db->prepare("SELECT * from kommentti WHERE ilmoitus_idilmoitukset = $id ORDER BY idkommentti ASC;");
        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $idkommentti = $row['idkommentti'];
            
            $stmt2 = $db->prepare("SELECT COUNT(*) FROM kommentti_peukut WHERE kommentti_idkommentti = '$idkommentti';");
            $stmt2->execute();
            $peukut = $stmt2->fetchColumn();
            if ($peukut == 0) $peukut = "";
            
            $kommentti = $row['kommenttiteksti'];
            $nickname = $row['nickname'];
            $kommenttipaivays_koko = $row['kommenttipaivays'];
            $kommenttipaivays = "";                                      
            $kommenttipaivays .= substr($kommenttipaivays_koko,8,2).".";  //päivä 
            $kommenttipaivays .= substr($kommenttipaivays_koko,5,2).".";  //kk    
            $kommenttipaivays .= substr($kommenttipaivays_koko,0,4);      //vuosi 
            $kommenttipaivaysklo = "";
            $kommenttipaivaysklo .= substr($kommenttipaivays_koko,10,6);
            echo "<div class='kommentti'>$kommenttipaivays klo $kommenttipaivaysklo <br><br> $kommentti<br> <i>-$nickname</i><form method='post' class='peukkuDiv'><input type='hidden' name='idkommentti' value='$idkommentti'><span class='peukkuLkm'>$peukut </span><input type='image' class='peukku' src='kuvat/peukku.png' /></form></div>";
        }
        
        echo "
            <div class='kommentti' id='kommenttisubmit'>
                <form action='ilmoitus.php' method='post'>
                Kommentti: $kommenttiError <br> 
                <textarea name='kommenttiteksti' rows='4' cols='50'></textarea><br>
                Nimimerkki: $nicknameError <br><input type='text' name='nickname'><br>
                <input type='hidden' name='id' value='$id'><br>";
        echo utf8_encode("<input class='add' type='submit' name='nappi' value='Lähetä'>");
        echo "
                </form>
            </div>
        </div>
    </div>
</div>
        ";
}
require_once("footer.html"); 
?>

<script src="javas.js"></script>  
</body>
</html>