<?php
    //include_once 'sessao.php';

    include_once "doc_HTML.php";

    $conteudo = "<h3> Dados Enviado para o MercadoPago </h3>";

    $curl = curl_init();

    // Configurando a conexão:
        $chave_acesso = "Abre-de-Cézamo";
        curl_setopt($curl, CURLOPT_URL, "https://api.mercadopago.com/?key=".$chave_acesso);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    // Passa as informações da compra....
        $compra = array();
        $compra["XP_Encomenda"] = 1;
        $compra["Produto"] = $P_Nome;
        $compra["Preco"] = $P_Preco;
        $compra["Pagamento"] = "Visa ou PIX ou Boleto ou....";
        curl_setopt($curl, CURLOPT_POSTFIELDS, $compra);
    
        $conteudo .= "<pre>".print_r($curl,true)."</pre><hr>";

    $json_str = K_url("http://pagfacil.profricardoms.repl.co/registrando.php?key=".$chave_acesso);
    $json_obj = json_decode($json_str, true);

    $conteudo .= "<h3> Dados Recebidos do pagfacil </h3><hr>";
    
    if ($json_obj["status"]=="OK") {
        $conteudo .= "Compra realizada com sucesso!";
    }else{
        $conteudo .= "Erro no processamento da encomenda";
    }
    echo Monta_Doc_HTML( basename(__FILE__), $conteudo );

    function k_url($p_URL){
        $curl = curl_init($p_URL);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
?>