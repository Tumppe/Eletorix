<?php
if (!isset($_SESSION)) 
    session_start();
if (isset($_POST['idsuosikki'])) {
    $idsuosikki = $_POST['idsuosikki'];
    
    if (isset($_SESSION['suosikit'])) {
        if (!in_array($idsuosikki, $_SESSION['suosikit']))
            array_push($_SESSION['suosikit'], $idsuosikki);
    } else {
        $_SESSION['suosikit'] = array();
        array_push($_SESSION['suosikit'], $idsuosikki);
    }
    
    //echo "Suosikit:";
    //foreach ($_SESSION['suosikit'] as $suosikki) echo " ".$suosikki.",";
    echo json_encode($_SESSION['suosikit']);
} 

//$ref = $_POST['referer'];
//echo "<br><br>Tuote-ID:$idsuosikki lisätty suosikkeihin.<br><br>Siirrytään sivulle: $ref ";

//if (isset($_POST['referer'])) 
    //header ('Location: ' . $ref );

//die;

?>