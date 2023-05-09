<?php
    include_once 'sessao.php';

    include_once "controle_bd.php";
    include_once "doc_HTML.php";
    include_once "bd_produto.php";

    $conteudo = "Página de cadastro de produtos";
    
    if ( count($_POST) > 0 )
    {
        $BD = BD_Conectar();
        P_Inserir( $BD, $_POST );
        BD_Desconectar( $BD );
    }
    
    $conteudo .= P_Exibir_Formulario();
    
    echo Monta_Doc_HTML( basename(__FILE__), $conteudo );

?>