<?php
    include_once 'sessao.php';

    include_once "controle_bd.php";
    include_once "doc_HTML.php";
    include_once "bd_encomenda.php";

    $conteudo = "";
    if ( count($_POST) > 0 ){
        $chave_acesso = 1;
        $json_str = k_url( "http://pagfacil.profricardoms.repl.co/registrando.php?key=".$chave_acesso );
        $json_obj = json_decode($json_str, true);
        if ( $json_obj["status"] == "OK" ){
            $BD = BD_Conectar();
            $mensagem = Confirmar( $BD, $_POST );
            BD_Desconectar( $BD );
            $conteudo .= "Confirmação realizada, agora PAGUE pelo produto!";
        }else{
            $conteudo .= "Erro no processamento da Encomenda.";
        }
    }else{
        $conteudo .= "Erro desconhecido!";
    }
   
    
    echo Monta_Doc_HTML( basename(__FILE__), $conteudo );

    function k_url( $p_URL ){
        $resposta = file_get_contents($p_URL);
        $resposta = str_replace("\n", '', $resposta);
        $resposta = str_replace("\r", '', $resposta);
        $resposta = str_replace(" ", '', $resposta);
        return $resposta;
    }

?>