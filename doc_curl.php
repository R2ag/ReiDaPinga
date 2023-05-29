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

    $json_str = curl_exec($curl);
    $json_obj = json_decode($json_str, true);

    $conteudo .= "<h3> Dados Recebidos do MercadoPago </h3><hr>";
    $conteudo .= "json_str <br><pre>".$json_str."</pre><hr>";
    $conteudo .= "json_obj <br><pre>".print_r($json_obj,true)."</pre><hr>";
    $cont = 1;
    foreach( $json_obj as $campo=>$conteudo)
    {
        //$conteudo .= "<b>".($cont++).") ".$campo."</b>: ".$conteudo."<hr>";
    }
    
    echo Monta_Doc_HTML( basename(__FILE__), $conteudo );

?>