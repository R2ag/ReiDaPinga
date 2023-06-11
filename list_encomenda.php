<?php 
    include_once "controle_bd.php";
    include_once "doc_HTML.php";
    include_once "bd_encomenda.php";

    $BD = BD_Conectar();
    $listagem = Consult($BD);
    BD_Desconectar($BD);

    echo Monta_Doc_HTML(basename(__FILE__), $listagem);


?>