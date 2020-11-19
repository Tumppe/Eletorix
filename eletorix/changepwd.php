<?php
require_once("db-init.php");
if (!isset($_SESSION)) session_start();
        
if (isset($_SESSION['userId'])) {
//if (isset($_POST['submitSN'])) {
    $uusi_sn = $_POST['uusi_sn'];
    $uusi_sn_uudelleen = $_POST['uusi_sn_uudelleen'];
    $nykyinen_sn = $_POST['nykyinen_sn'];
    
    //echo "userId: " . $_SESSION['userId'] . "<br>"; 
    $username = $_SESSION['userId'];
    
    $stmt = $db->prepare("SELECT salasana FROM kayttaja WHERE nimi = :username");
    $stmt->bindValue(':username', $username, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $nykyinen_sn_db = $row['salasana'];
    //echo "syötetty nykyinen sn: ". md5($nykyinen_sn)."<br>nykyinen sn tietokannassa: ". $nykyinen_sn_db . "<br>";
    
    if (md5($nykyinen_sn) == $nykyinen_sn_db) {
    //echo "Nykyinen salasana oikein<";
        if (strlen($uusi_sn) >= 6) {
            if ($uusi_sn == $uusi_sn_uudelleen) {
                //kun salasanat ovat samat
                
                $stmt = $db->prepare("SELECT idkayttaja FROM kayttaja WHERE nimi = :username");
                $stmt->bindValue(':username', $username, PDO::PARAM_INT);
                $stmt->execute();
                
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $idkayttaja = $row['idkayttaja'];
                
                //echo "uusi sn: ".$uusi_sn." <br>idkayttaja:" . $idkayttaja."<br><br><br>";
            
                $sql = "UPDATE kayttaja SET salasana=md5('$uusi_sn') WHERE idkayttaja = '$idkayttaja'";
                $db->exec("$sql");
                
                echo json_encode("Salasana vaihdettu onnistuneesti.");
                //header ("Location: account.php?status=success");
        
            } else { 
                echo json_encode("Uudet salasanat eivät ole samat.");
                //header ("Location: account.php?status=pwdnotsame"); 
                }
        } else { 
            echo json_encode("Salasanan on oltava vähintään 6 merkkiä pitkä.");
            //header ("Location: account.php?status=pwdtooshort"); 
            }
            
    } else { 
        echo json_encode("Nykyinen salasana väärin."); 
        //header ("Location: account.php?status=pwdwrong");
         }
//} else  { 
    //echo "Unohdit painaa nappia.";
    //}
} else {
    echo json_encode("Ongelma istunnon kanssa.");
    //header ("Location: account.php?status=sessionproblem");
}
?>

