<?php
  
  include_once 'sessao.php';
  include_once "controle_bd.php";
  include_once "doc_HTML.php";
  include_once "bd_encomenda.php";
  include_once "k_url.php";

  $conteudo = "";
  if ( count($_POST) > 0 ){
  
    $chave_acesso = "Abre-te_Sésamo";
    $url = "http://pagfacil.profricardoms.repl.co/registrando.php?Token=".$chave_acesso;

    $dados_encomenda = array();
    $dados_encomenda["Loja"] = "Rei da Pinga";
    $dados_encomenda["Encomenda"]  = $_POST["id"]; // chave primária;
    $dados_encomenda["Produto"]    = $_POST["nome"]; // nome do produto;
    $dados_encomenda["Preco"]      = $_POST["preco"]; // preço;
    $dados_encomenda["Pagamento"]  = $_POST["pagamento"];

    do{
      $json_str = k_URL( $url, $dados_encomenda );
      $json_obj = json_decode($json_str, true);
      
    }while($json_obj["status"] == "DELAY");
      
    if ( $json_obj["status"] == "OK"){
      if($json_obj["response"] == "Aceito" ){
        $BD = BD_Conectar();
        $mensagem = Confirmar( $BD, $_POST );
        BD_Desconectar( $BD );
        $conteudo .= "Confirmação realizada, agora PAGUE pelo produto!";
      
      }else{
        $conteudo .= "Erro no processamento da Encomenda.";
      }
    }else{
      if ( $json_obj["status"] == "MAINTENANCE"){
        $conteudo .= "Serviço de pagamento em manutenção!.";
      }else{
        $conteudo .= "Erro desconhecido!";  
      }
      
    }
   
  }  
  
  echo Monta_Doc_HTML( basename(__FILE__), $conteudo );

?>