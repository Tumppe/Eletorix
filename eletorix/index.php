<?php
if (!isset($_SESSION)) session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Eletorix - Kaiken elektroniikan kauppapaikka</title>
    <meta charset="UTF-8">    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="kuvat/logo.png" />
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="index.css">
</head>
<body>
     <header>
        <a href="index.php"><div id="logot">
        <img src="kuvat/logo.svg" alt="Eletorix logo" id="logo"/>        
            </div></a> 


        <div class='bannerTabs'>
            <button class="nayta">Valikko</button>
                <div class="nakyy">
<?php
if (isset($_SESSION['suosikit']) && sizeof($_SESSION['suosikit'])>0) {
    $suosikit = $_SESSION['suosikit'];
    $suosikitLkm = sizeof($suosikit);
} else { $suosikitLkm = 0; };

if (isset($_SESSION['userId'])) {
    $username = $_SESSION['userId'];
    $userID = $_SESSION['Id'];
    require_once("db-init.php");   
    $stmt = $db->prepare('SELECT tyyppi FROM kayttaja WHERE nimi = :username');
    $stmt->bindValue(':username', $username, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $tyyppi = $row['tyyppi'];

    $stmt = $db->prepare("SELECT COUNT(*) FROM viesti WHERE idvastaanottaja = '$userID' AND luettu = 0;");
    $stmt->execute();
    $uudetviestitLkm = $stmt->fetchColumn();
    if ($uudetviestitLkm == 0) {
        $uudetviestitLkm = "";
    }

    echo '
    <a href="lisaa.php"><button class="add">Ilmoita</button></a>';

    echo '
    <a href="suosikit.php"><button class="add">Suosikit<span class="sLkmHidden">'.$suosikitLkm.'</span><span class="suosikitLkm"></span></button></a>

    <a href="account.php"><button class="add">Eletorix-tili</button></a>
    <a href="viestikeskus.php"><button class="add">Viestit<span class="uudetviestitLkm">'.$uudetviestitLkm.'</span></button></a>';

    if ($tyyppi == "admin") echo '<a href="adminpage.php"><button class="add">Ylläpito</button></a>';

    echo '
    <a href="rekkirj/includes/logout.inc.php"><button class="add"><span style="color:darkblue;font-weight:bold;">'.$_SESSION["userId"].'</span><br>Kirjaudu ulos</button></a>';
    }
    else {
        echo '<a href="rekkirj/index.php"><button class="add">Kirjaudu sisään</button></a>';
}
?>
                </div>
        </div>  
	


    </header>
    
    <div class="popUp"><p><span class="popUpNimi"></span></p><img class="popUpImg" alt="popUp"/></div>
    
    <img src="kuvat/otsikko.svg" alt="Eletorix otsikko" id="otsikko"/>
    <h3 id="slogan">Kaiken elektroniikan kauppapaikka</h3>

        <form action="hae.php" method="post" id="hakudiv">
            <input type="text[]" name="tuotteennimi" id="haku">
            <select name="kategoria" id="kategoria">
                <option value="Kaikki">Kaikki</option>
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
            
            <button type="submit" id="hae_nappi">
                <img src="kuvat/haku.svg" alt="Hae" id="hakukuva"/>
            </button>
        </form>

	<div id="jako">        
        <div id="suosituimmat"><h4>Suosituimmat kategoriat: </h4></div>
        <div id="latestfrom">
            
            <form action="hae.php" method="post">
                <button class="tyyliton">
            <div id="puh"><p>Puhelimet</p><img src="kuvat/puhelin.svg" alt="Puhelimet"/></div>
            <input type='hidden' name='kategoria' value="1" />
            <input type='hidden' name='tuotteennimi' value="" />
                    </button>
            </form>
            
            <form action="hae.php" method="post">
                <button class="tyyliton">
            <div id="pc"><p>Tietokoneet</p><img src="kuvat/pc.svg" alt="Tietokoneet"/></div> 
            <input type='hidden' name='kategoria' value="2" />
            <input type='hidden' name='tuotteennimi' value="" />
                    </button>
            </form>
            
            <form action="hae.php" method="post">
                <button class="tyyliton">
            <div id="tv"><p>Televisiot</p><img src="kuvat/tv.svg" alt="Televisiot"/></div>
            <input type='hidden' name='kategoria' value="3" />
            <input type='hidden' name='tuotteennimi' value="" />
                    </button>
            </form>
            
            <form action="hae.php" method="post">
            <button class="tyyliton">
            <div id="cam"><p>Kamerat</p><img src="kuvat/kamera.svg" alt="Kamerat"/></div>
            <input type='hidden' name='kategoria' value="4" />
            <input type='hidden' name='tuotteennimi' value="" />
                </button>
            </form>
        </div>
        <div id="latest"><h4>Viimeisimmät ilmoitukset: </h4></div>
        <div id="ilmoitukset">    
        
     
<?php
require_once("db-init.php");
if (!isset($_SESSION)) session_start();
            
    $montaIlmoitustaNaytetaan = 8;          
    if (isset($_POST['naytaEnem'])) {
        $montaIlmoitustaNaytetaan = $_POST['montaNaytetaan'];
        $montaIlmoitustaNaytetaan += 8;
    }            
            
$userid = NULL;
        if (isset($_SESSION['Id'])) {
            $userid = $_SESSION['Id'];
        }            
$kayttajatyyppi = NULL;
if (isset($_SESSION['tyyppi'])) {
            $kayttajatyyppi = $_SESSION['tyyppi'];
        }


$sql = $db->query("SELECT * FROM ilmoitus INNER JOIN kategoria ON ilmoitus.kategoria_idkategoria=kategoria.idkategoria INNER JOIN kayttaja ON ilmoitus.kayttaja_idkayttaja=kayttaja.idkayttaja order by ilmoitus.idilmoitukset desc limit $montaIlmoitustaNaytetaan;");
          
while($row = $sql->fetch(PDO::FETCH_ASSOC)) {

    $id = $row['idilmoitukset'];
    $tuotteennimi = $row['tuotteennimi'];
    $paivays = $row['paivays'];
    $hinta = $row['hinta'];
    $tuotekuvaus = $row['tuotekuvaus'];
    $kuva = $row['kuva'];
    $kateg = $row['idkategoria'];
    $kategorianimi = $row['kategorianimi'];
    $kayttajaid = $row['kayttaja_idkayttaja'];
    $nimi = $row['nimi'];
    $email = $row['email'];
    $puhnum = $row['puhnum'];
    $osoite = $row['osoite'];
    $kaupunki = $row['kaupunki'];
    
    $ref = $_SERVER['PHP_SELF'];
    
    echo "
        <form action='ilmoitus.php?id=$id' method='post' class='ilmoitus'>
        <input type='hidden' name='id' value=" . $id . " />
        
        <button class='tyyliton'>
        </form>";
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
        <input value='Muokkaa' type='image' src='kuvat/cog.png' class='muokkaa cog'>
        </form>
        
        <form action='remove.php' method='post'>
        <input type='hidden' name='pois' value=" . $id . " />
        <input type='image' src='kuvat/roskis.png' class='rasti roskis'>
        </form> 
        ";
        }
    if (isset($_SESSION['Id'])) {
        echo "</div>";
    }
    echo"
        <img class='tuotekuva' src='kuvat/{$kuva}' alt='Ilmoituksen kuva'>
        <p class='tuotteennimi' class='otsikko'>{$tuotteennimi}</p>
        <p class='tuotehinta'>{$hinta}€</p>
        <p class='otsikko'>{$kategorianimi}</p>
        <p class='otsikko'>{$kaupunki}</p>
        </button type='submit'>
        
        ";
}

?>
         
</div>
        <div>
    <form method="post" action="index.php#enem">
        <input type="hidden" name="montaNaytetaan" value=" <?php echo $montaIlmoitustaNaytetaan ?>">
        <?php if ($montaIlmoitustaNaytetaan > 8) {
            echo "<input type='submit' name='naytaVah' value='Näytä vähemmän' class='hakusivunappi tyyliton2' >";
        }
        ?>
        <input type="submit" name="naytaEnem" value="Näytä lisää" class="hakusivunappi tyyliton2" id='enem'>
    </form>
 </div>
        
	</div>
    <?php
    require_once("footer.html"); 
    ?>
    <script src="javas.js"></script>
</body>
</html>