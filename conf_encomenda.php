<?php
    include_once "sessao.php";
    include_once "controle_bd.php";
    include_once "bd_encomenda.php";

    $conteudo = "";

    if ( count($_GET) > 0){
        $BD = BD_Conectar();
        $mensagem = Inserir($BD, $_GET);
        BD_Desconectar($BD);
        $conteudo .="Encomenda feita com sucesso!";
    }else{
        $conteudo .="Encomenda não realizada!";
    }

    echo Monta_Doc_HTML(basename(__FILE__), $conteudo);
?>