<?php
if (!isset($_SESSION)) 
    session_start();

if (isset($_POST['idsuosikki'])) {
    $idsuosikki = $_POST['idsuosikki'];
    //echo $idsuosikki . "<br>";
    
    if (isset($_SESSION['suosikit'])) {
        $i = array_search($idsuosikki, $_SESSION['suosikit']);
        //echo $_SESSION['suosikit'][$i] . " poistettu. ";
        unset($_SESSION['suosikit'][$i]);
        $_SESSION['suosikit'] = array_values($_SESSION['suosikit']);
        //print_r($_SESSION['suosikit']); 
    }
    
    echo json_encode($_SESSION['suosikit']);
    //echo "Suosikit:";
    //foreach ($_SESSION['suosikit'] as $suosikki) echo " ".$suosikki.",";
}

header ('Location: suosikit.php');

die;

?>