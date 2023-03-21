<?php
    ini_set('default_charset','utf-8');
    session_start();
    
    if(!empty($_SESSION["uid"])) {
        echo "uid: " . $_SESSION["uid"].'<br>';
    } else {
        echo "uid is no value.";
    }

    foreach($_SESSION as $key => $value) {
        echo $key . " = " . $value . "<br>";
    }
?>