<?php
session_start();
include_once "ses_funcoes.php";

// Quando clica em algum item no menu, carrega o arquivo "sessao.php"
// dentro do arquivo "sessao.php" redireciona para o "ses_login.php",
// mas, se clicar no menu "Login", deve mostrar a tela de login
    if ($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        // Quando processa o arquivo "sessao.php" verifica o login
        $ses = array_key_exists("SES", $_GET) ? $_GET["SES"] : "";
        if ( ! $ses ) { include_once 'sessao.php'; }
    }

// Quando tenta acessar a página de Login depois de fazer o login, redireciona:
    if (isset($_SESSION['SES_Login'])) 
    {
        header('Location: list_produto.php');
        exit();
    }

// Quando acessa a tela de login pela primeira vez:
    include_once "controle_bd.php";    
    include_once "bd_cliente.php";
    include_once "doc_HTML.php";
    
    $login_falhou = "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $login = $_POST['login'];
        $senha = $_POST['Senha'];

        $bd = BD_Conectar();
        if ( C_Autorizar( $bd, $login, $senha ) ) 
        {
            SES_Fez_Login($login);
            
            header('Location: list_produto.php');
            exit();
        } 
        else 
        {
            $login_falhou = 'Logiin ou Senha está errado';
        }
        BD_Desconectar($bd);
    }
    
    $login = C_Login( $login_falhou );
    
    echo Monta_Doc_HTML( basename(__FILE__), $login );
?>
