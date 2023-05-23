<?php
    include_once 'sessao.php';
    include_once "controle_bd.php";
    include_once "doc_HTML.php";
    include_once "bd_produto.php";

    $id = ( array_key_exists("produto", $_GET)? $_GET["produto"]: 0);

    $BD = BD_Conectar();        
    $produto = Detalhar( $BD, $id );
    BD_Desconectar($BD);

    echo Monta_Doc_HTML( basename(__FILE__), $produto );

?>