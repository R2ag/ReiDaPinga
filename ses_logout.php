<?php
    session_start();

    if( session_status() == PHP_SESSION_ACTIVE ){
        unset($_SESSION);
        $_SESSION = array();
        session_destroy();
    }


    include_once "doc_HTML.php";

    $logout = "Tú tá fóra!";

    echo Monta_Doc_HTML( basename(__FILE__), $logout );

?>
