<?php
    session_start();
    include_once "ses_funcoes.php";
    include_once "controle_bd.php";    
    include_once "bd_cliente.php";
    include_once "doc_HTML.php";


    if ($_SERVER['REQUEST_METHOD'] != 'POST'){
        $ses = array_key_exists("SES", $_GET) ? $_GET["SES"] : "";
        if ( ! $ses ) { 
            include_once 'sessao.php'; 
        }
    }

    if (isset($_SESSION['SES_Login'])){
        header('Location: list_produto.php');
        exit();
    }


    $login_falhou = "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $login = $_POST['login'];
        $senha = $_POST['senha'];

        $bd = BD_Conectar();
        $id_cliente = Autorizar( $bd, $login, $senha ); 
        if ($id_cliente > 0){
            SES_Fez_Login($id_cliente);
            header('Location: list_produto.php');
            exit();
        } else {
            $login_falhou = 'Login ou Senha está errado';
        }
        BD_Desconectar($bd);
    }

    $login = Login( $login_falhou );

    echo Monta_Doc_HTML( basename(__FILE__), $login );
?>
