<?php
    include_once 'sessao.php';

    include_once "controle_bd.php";
    include_once "doc_HTML.php";
    include_once "bd_produto.php";
    
    $BD = BD_Conectar();        
    $listagem = P_Consultar( $BD );
    BD_Desconectar($BD);
    
    echo Monta_Doc_HTML( basename(__FILE__), $listagem );

?>