<!DOCTYPE html>
<?php
    session_start();
        $hakusana = "";   
        $kategoria = "Kaikki";
        $kategorianimi = $kategoria;
        if (isset($_POST["tuotteennimi"]) && !empty($_POST["tuotteennimi"])){
		$hakusana = $_POST["tuotteennimi"];  
        };
        if (isset($_POST["kategoria"]) && !empty($_POST["kategoria"])){
        $kategoria = $_POST["kategoria"];    
            $kategorianimi = $kategoria;
            
            if ($kategorianimi != "Kaikki"){
                require_once("db-init.php");
                $kat = $db->prepare("SELECT * FROM kategoria WHERE idkategoria=:kategoria;");
                $kat->bindValue(':kategoria', $kategoria, PDO::PARAM_INT);
                $kat->execute(); 
                while($row = $kat->fetch(PDO::FETCH_ASSOC)) {
                $kategorianimi = $row['kategorianimi'];
                }
            }
        
        };
        
    ?>

<html>
    <head>
    <title>Eletorix - Kaiken elektroniikan kauppapaikka</title>
    <link rel="icon" href="kuvat/logo.png" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">        
    <link rel="stylesheet" href="hae.css"> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script>
            //Sivun vaihtoa varten
            function sivu(arvo){
         document.getElementById("sivu").value = arvo;       
        document.getElementById('hakuform').submit();
            }
            //Hakutulosten määrän näyttöön
            function maara(arvo){
         document.getElementById("maara").innerHTML = arvo;
            }
        </script>
</head>
<body>
    <form action="hae.php" method="post" id="hakuform">
    <header>
        <a href="index.php"><div id="logot">
        <img src="kuvat/logo.svg" alt="Eletorix logo" id="logo"/>
        <img src="kuvat/otsikko.svg" alt="Eletorix otsikko" id="otsikko"/>
            </div></a>
        <div  id="hakudiv">
            <input type="text" name="tuotteennimi" id="haku" value="<?php echo $hakusana; ?>">
            <button type="submit" id="hae_nappi">
            <img src="kuvat/haku.svg" alt="Hae" id="hakukuva"/>
            </button>
        </div>
        <div class='bannerTabs'>
            <button class="nayta" type="button">Valikko</button>
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
<a href="lisaa.php"><button class="add" type="button">Ilmoita</button></a>';

echo '
<a href="suosikit.php"><button class="add" type="button">Suosikit<span class="sLkmHidden">'.$suosikitLkm.'</span><span class="suosikitLkm"></span></button></a>

<a href="account.php"><button class="add" type="button">Eletorix-tili</button></a>

<a href="viestikeskus.php"><button class="add" type="button">Viestit<span class="uudetviestitLkm">'.$uudetviestitLkm.'</span></button></a>';

if ($tyyppi == "admin") echo '<a href="adminpage.php"><button class="add" type="button">Ylläpito</button></a>';

echo '
<a href="rekkirj/includes/logout.inc.php"><button class="add" type="button"><span style="color:darkblue;font-weight:bold;">'.$_SESSION["userId"].'</span><br>Kirjaudu ulos</button></a>';
}
else {
echo '<a href="rekkirj/index.php"><button class="add" type="button">Kirjaudu sisään</button></a>';
}
                    
    ?>
                </div>
        </div> 
    </header>
        <div id="tuloksia"> <strong><p id="maara"></p></strong><p id="hifistelya">Hakutulosta sanalle:  <p/><p><strong><?php echo $hakusana;?></strong> | Kategoriassa:  <strong><?php echo $kategorianimi;?></strong></p></div>
            <div class="popUp"><p><span class="popUpNimi"></span> lisätty suosikkeihin!</p><img class="popUpImg" alt="" src=""/></div>
	<div id="jako">        
        <div id="kategoria">
            <button id="nayta" type="button">Rajaa hakua</button>
            <div id="kategoria_title">Rajaukset</div>
            <div id="nakyy">
                <div id="kategoria_div">
                <select name="kategoria" id="kategoria">
                    <option value="Kaikki">Kategoria</option>
                    <?php
                        require_once("db-init.php");
                    $kateg = "";
                    if (isset($_POST["kategoria"]) && !empty($_POST["kategoria"])){
                    $kateg = $_POST['kategoria'];
                    }
                        $sql = $db->query('SELECT * FROM kategoria;');
                        while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                            $idkategoria = $row['idkategoria'];
                            $kategorianimi = $row['kategorianimi'];                            
                            echo "<option value=".$idkategoria;
                            if($kateg==$idkategoria){echo " selected";}
                            echo ">".$kategorianimi."</option>";
                        }
                    ?>
                </select>
                </div>
                <div class="kaupunki_div">
                    <input type="text" name="kaupunki" class="kaupunki" placeholder="Kaupunki" value="<?php if (isset($_POST["kaupunki"]) && !empty($_POST["kaupunki"])){echo $_POST["kaupunki"];}; ?>">
                </div>
                <div class="kaupunki_div">
                <input type="text" name="m_hinta" class="kaupunki" placeholder="Max hinta" value="<?php if (isset($_POST["m_hinta"]) && !empty($_POST["m_hinta"])){echo $_POST["m_hinta"];}; ?>">
                </div>
                <div class="kaupunki_div">
                <input type="text" name="p_hinta" class="kaupunki" placeholder="Min hinta" value="<?php if (isset($_POST["p_hinta"]) && !empty($_POST["p_hinta"])){echo $_POST["p_hinta"];}; ?>">
                </div>
                <button type="submit" id="rajaa">Rajaa</button>
            </div>
            
        </div>
        <input type="hidden" name="page" id="sivu" value='1' />
        </form>
        <div id="ilmoitukset">    
        


<?php
require_once("db-init.php");  
if (!isset($_SESSION)) session_start(); 
            
$userid = NULL;
        if (isset($_SESSION['Id'])) {
            $userid = $_SESSION['Id'];
        }            
$kayttajatyyppi = NULL;
if (isset($_SESSION['tyyppi'])) {
            $kayttajatyyppi = $_SESSION['tyyppi'];
        }        
$tuotteennimi = NULL;   
$kategoria = "Kaikki";
$kaupunki = NULL;
$m_hinta = NULL;
$p_hinta = NULL;
            
if (isset($_POST["tuotteennimi"]) && !empty($_POST["tuotteennimi"])){
    $tuotteennimi = "%".$_POST["tuotteennimi"]."%";  
};
if (isset($_POST["kategoria"]) && !empty($_POST["kategoria"])){
    $kategoria = $_POST["kategoria"];
};
if (isset($_POST["kaupunki"]) && !empty($_POST["kaupunki"])){
    $kaupunki = "%".$_POST["kaupunki"]."%";  
};
if (isset($_POST["m_hinta"]) && !empty($_POST["m_hinta"])){
    $m_hinta = $_POST["m_hinta"];  
};   
if (isset($_POST["p_hinta"]) && !empty($_POST["p_hinta"])){
    $p_hinta = $_POST["p_hinta"];  
}; 

if($kategoria=="Kaikki"){
    $kategoria=NULL;
}  

               
  $limit = 18;
$query = "SELECT * FROM ilmoitus INNER JOIN kategoria ON ilmoitus.kategoria_idkategoria=kategoria.idkategoria INNER JOIN kayttaja ON ilmoitus.kayttaja_idkayttaja=kayttaja.idkayttaja WHERE tuotteennimi like CASE WHEN :tuotteennimi IS NULL THEN tuotteennimi  ELSE  :tuotteennimi2 END AND idkategoria= CASE WHEN :kategoria IS NULL THEN idkategoria ELSE :kategoria2 END AND kaupunki like CASE WHEN :kaupunki IS NULL THEN kaupunki ELSE :kaupunki2 END AND hinta <= CASE WHEN :m_hinta IS NULL THEN hinta ELSE :m_hinta2 END AND hinta >= CASE WHEN :p_hinta IS NULL THEN hinta ELSE :p_hinta2 END order by ilmoitus.idilmoitukset desc";

$s = $db->prepare($query);
$s->bindValue(':tuotteennimi', $tuotteennimi);
$s->bindValue(':tuotteennimi2', $tuotteennimi);
$s->bindValue(':kategoria', $kategoria);
$s->bindValue(':kategoria2', $kategoria);
$s->bindValue(':kaupunki', $kaupunki);
$s->bindValue(':kaupunki2', $kaupunki);
$s->bindValue(':m_hinta', $m_hinta);
$s->bindValue(':m_hinta2', $m_hinta);
$s->bindValue(':p_hinta', $p_hinta);
$s->bindValue(':p_hinta2', $p_hinta);
$s->execute();
$total_results = $s->rowCount();
$total_pages = ceil($total_results/$limit);

if (!isset($_POST['page'])) {
    $page = 1;
} else{
    $page = $_POST['page'];
}
 $starting_limit = ($page-1)*$limit; 

echo '<script type="text/javascript">maara('.$total_results.');</script>';
            
$stmt = $db->prepare("SELECT * FROM ilmoitus INNER JOIN kategoria ON ilmoitus.kategoria_idkategoria=kategoria.idkategoria INNER JOIN kayttaja ON ilmoitus.kayttaja_idkayttaja=kayttaja.idkayttaja WHERE tuotteennimi like CASE WHEN :tuotteennimi IS NULL THEN tuotteennimi  ELSE  :tuotteennimi2 END AND idkategoria= CASE WHEN :kategoria IS NULL THEN idkategoria ELSE :kategoria2 END AND kaupunki like CASE WHEN :kaupunki IS NULL THEN kaupunki ELSE :kaupunki2 END AND hinta <= CASE WHEN :m_hinta IS NULL THEN hinta ELSE :m_hinta2 END AND hinta >= CASE WHEN :p_hinta IS NULL THEN hinta ELSE :p_hinta2 END order by ilmoitus.idilmoitukset desc LIMIT $starting_limit, $limit;");
$stmt->bindValue(':tuotteennimi', $tuotteennimi);
$stmt->bindValue(':tuotteennimi2', $tuotteennimi);
$stmt->bindValue(':kategoria', $kategoria);
$stmt->bindValue(':kategoria2', $kategoria);
$stmt->bindValue(':kaupunki', $kaupunki);
$stmt->bindValue(':kaupunki2', $kaupunki);
$stmt->bindValue(':m_hinta', $m_hinta);
$stmt->bindValue(':m_hinta2', $m_hinta);
$stmt->bindValue(':p_hinta', $p_hinta);
$stmt->bindValue(':p_hinta2', $p_hinta);
$stmt->execute();   

  
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
        
</div>
<div class="sivunumerot">
    <p class="sivuteksti">Sivut:</p>
    <?php
for ($page=1; $page <= $total_pages ; $page++):?>

<input id="siv" class="hakusivunappi" onclick="sivu(<?php echo $page?>)" value="<?php echo $page?>"/>
    

<?php endfor; ?>
   </div> 
    
    <?php
    require_once("footer.html"); 
    ?>
    <script src="javas.js"></script>
</body>
</html>