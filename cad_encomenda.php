<?php
    include_once "controle_bd.php";
    include_once "doc_HTML.php";
    include_once "bd_encomenda.php";

    $conteudo = "";

    if (count($_POST) > 0) {
        $BD = BD_Conectar();
        $mensagem = Inserir($BD, $_POST);
        BD_Desconectar($BD);
        $conteudo .= "Encomenda Realizada com sucesso!";
    }else{
        $conteudo .= "Encomenda não Realizada!";
    }

    echo Monta_Doc_HTML(basename(__FILE__), $conteudo);

?>