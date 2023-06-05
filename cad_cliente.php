<?php
    include_once "controle_bd.php";
    include_once "doc_HTML.php";
    include_once "bd_cliente.php";

    $conteudo = "Página de cadastro de Clientes";
    
    $msg = "";

    if ( count($_POST) > 0 ){
        $BD = BD_Conectar();
        $msg = Inserir( $BD, $_POST );
        BD_Desconectar( $BD );
    }
    
    $conteudo .= Exibir_Formulario($msg);
    
    echo Monta_Doc_HTML( basename(__FILE__), $conteudo );

?>