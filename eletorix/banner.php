    <head>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>    
</head>
<header>
            <a href="index.php"><div id="logot">
            <img src="kuvat/logo.svg" alt="Eletorix logo" id="logo"/>
            <img src="kuvat/otsikko.svg" alt="Eletorix otsikko" id="otsikko"/>
                </div></a>
            <form action="hae.php" method="post">

            <div  id="hakudiv">
                <input type="text[]" name="tuotteennimi" id="haku">
                <button type="submit" id="hae_nappi">
                <img src="kuvat/haku.svg" alt="Hae" id="hakukuva"/>
                </button>
            </div>
        </form>
        <div class='bannerTabs'>
            <button class="nayta" type="button">Valikko</button>
                <div class="nakyy">
            <?php
            if (!isset($_SESSION)) session_start();
                if (isset($_SESSION['suosikit']) && sizeof($_SESSION['suosikit'])>0) {
                    $suosikit = $_SESSION['suosikit'];
                    $suosikitLkm = sizeof($suosikit);
                } else { $suosikitLkm = 0; };

		//if (isset($_SESSION['userId'])) {
		//      echo	'
        //      <a href="account.php"><button class="add" type="button"><i class="fa fa-home fa-1x" aria-hidden="true"></i>'." " .$_SESSION["userId"]. '</button></a>
        //      <a href="lisaa.php"><button class="add" type="button">Jätä ilmoitus</button></a>
        //      <a href="suosikit.php"><button class="add" type="button">Suosikit' .$suosikitLkm . '</button></a>
        //      <a href="rekkirj/includes/logout.inc.php"><button class="add" type="button">Kirjaudu ulos</button></a>';
        //}
        //else {
        //    echo '<a href="rekkirj/index.php"><button class="add" type="button">Kirjaudu sisään</button></a>';
        //}
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
