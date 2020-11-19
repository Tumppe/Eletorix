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
    <link rel="stylesheet" href="account.css">    
</head>
<body>    
    
<?php
    require_once("banner.php");  
    require_once("db-init.php"); 
    if (!isset($_SESSION['userId'])) header ("Location: index.php");
?>
<div class="popUp"><p><span class="popUpNimi"></span></p><img class="popUpImg" alt="popUp"/></div>
<div class="omatTiedot">
    <div class="navAccount">
    <?php
    $username = $_SESSION['userId'];
    echo "<h1>Hei $username.</h1>"
        ?>
        <button class="add" id="btnOmatIlmoitukset">Omat ilmoitukset</button>
        <button class="add" id="btnMuokkaaTietoja">Muokkaa tietoja</button>
        <button class="add" id="btnVaihdaSalasana">Vaihda salasana</button>
        <a href="#"><img class="goUp" src="kuvat/arrow2.png" alt="Takaisin ylös"></a>
    </div>
    <div class="muokkaaTietoja">
        <?php
            $username = $_SESSION['userId'];
        
            $stmt = $db->prepare("SELECT * FROM kayttaja WHERE nimi = :username");
            $stmt->bindValue(':username', $username, PDO::PARAM_INT);
            $stmt->execute();
        
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $nimi = $row['nimi'];
            $email = $row['email'];
            $puhnum = $row['puhnum'];
            $osoite = $row['osoite'];
            $kaupunki = $row['kaupunki'];
            $salasana = $row['salasana'];
            
            echo "
                <form id='updateAccForm' method='post'>
                <div class='dirRow'><h2>Muokkaa tietoja</h2><div class='statusMessageDiv'><p id='statusMessage2'></div></div>
                    <p>Email: <input type='text' name='email' value='$email'></p>
                    <p>Puhelinnumero: <input type='text' name='puhnum' value='$puhnum'></p>
                    <p>Osoite: <input type='text' name='osoite' value='$osoite'></p>
                    <p>Kaupunki: <input type='text' name='kaupunki' value='$kaupunki'></p>
                    <p><input type='submit' class='add' value='Lähetä'></p>
                </form>
            ";
        ?>
    </div>
    
    <div class="omatIlmoitukset">
        <h2>Omat ilmoitukset</h2>
        <?php
            $stmt = $db->prepare("SELECT * FROM ilmoitus INNER JOIN kayttaja ON ilmoitus.kayttaja_idkayttaja = kayttaja.idkayttaja WHERE kayttaja.nimi =:username;");
            $stmt->bindValue(':username', $username, PDO::PARAM_INT);
            $stmt->execute();
        
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                
            $id = $row['idilmoitukset'];
            $tuotteennimi = $row['tuotteennimi'];
            $paivays = $row['paivays'];
            $hinta = $row['hinta'];
            $tuotekuvaus = $row['tuotekuvaus'];
            $kuva = $row['kuva'];
            $kategoria = $row['kategoria_idkategoria'];
            //$kategorianimi = $row['kategorianimi'];
            $nimi = $row['nimi'];
            $email = $row['email'];
            $puhnum = $row['puhnum'];
            $osoite = $row['osoite'];
            $kaupunki = $row['kaupunki'];
            $ref = $_SERVER['PHP_SELF'];
                
            echo "
            <div class='ilmoitus'>
            <form action='ilmoitus.php?id=$id' method='post'>
                <input type='hidden' name='id' value=" . $id . " />
                <button class='tyyliton' type='submit'>
            </form>
            
            <div class='ilmoitus_banner'>
            <form class='addSuosikkiForm' method='post'>
                <input type='hidden' name='pois' value=" . $id . " />
                <input type='hidden' name='referer' value='$ref'>
                <input type='hidden' name='idsuosikki' value=" . $id . " />
                <input value='addsuosikki' type='image' src='kuvat/sydan.png' class='rasti sydan'>
            </form>  
            
            <form action='mod.php' method='post'>
                <input type='hidden' name='id' value=" . $id . " />
                <input type='hidden' name='paivays' value=" . $paivays . " />
                <input type='hidden' name='tuotteennimi' value=" . $tuotteennimi . " />
                <input type='hidden' name='hinta' value=" . $hinta . " />
                <input type='hidden' name='tuotekuvaus' value=" . $tuotekuvaus . " />
                <input type='hidden' name='kuva' value=" . $kuva . " />
                <input type='hidden' name='kategoria' value=" . $kategoria . " />
                ";
                        
                
                
                //<input type='hidden' name='kategorianimi' value=" . $kategorianimi . " />
            echo "
                <input type='hidden' name='nimi' value=" . $nimi . " />
                <input type='hidden' name='email' value=" . $email . " />
                <input type='hidden' name='puhnum' value=" . $puhnum . " />
                <input type='hidden' name='osoite' value=" . $osoite . " />
                <input type='hidden' name='kaupunki' value=" . $kaupunki . " />
                <input value='Muokkaa' type='image' src='kuvat/cog.png' class='muokkaa'>
            </form>
            
            <form action='remove.php' method='post'>
                <input type='hidden' name='pois' value=" . $id . " />
                <input value='Poista' type='image' src='kuvat/roskis.png' class='rasti'>
            </form> 
            </div>
            
    
            <img src='kuvat/{$kuva}' alt='Ilmoituksen kuva' class='tuotekuva'>
            <p class='tuotteennimi'>{$tuotteennimi}</p>
            <p class='tuotehinta'>{$hinta}</p>"; 
            //<p class='otsikko'>{$kategorianimi}</p> 
                echo "
            <p class='otsikko'>{$kaupunki}</p>
            </button>
            </div>
        ";
        } // while end
        ?>
    </div>
    <div class="vaihdaSalasana">
        <?php
        echo "
        <form method='post' id='vaihdaSalasanaForm'>
            <div class='dirRow'><h2>Vaihda salasana</h2><div class='statusMessageDiv'><p id='statusMessage'></p></div></div>
                <p>Uusi salasana: <input type='password' name='uusi_sn'></p>
                <p>Uusi salasana uudelleen: <input type='password' name='uusi_sn_uudelleen'></p><br>
                <p>Nykyinen salasana: <input type='password' name='nykyinen_sn'></p>
                <input type='submit' class='add' value='Vaihda salasana'>
        </form>";
        ?>
    </div>
</div> 

<script src="javas.js"></script>
<script>
$(document).ready(function(){  
    $(window).scroll(function(){
    if ($(this).scrollTop() > 100) {
        $(".goUp").show();
    } else {
        $(".goUp").hide();
    }
});
});
</script>

<?php
    require_once("footer.html");
?>
    
</body>
</html>