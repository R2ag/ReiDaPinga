<?php
function k_url($p_URL)
{
    $curl = curl_init($p_URL);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}

$curl = curl_init();

$chave_acesso = "Abre-de-Cézamo";
curl_setopt($curl, CURLOPT_URL, "http://pagfacil.profricardoms.repl.co/registrando.php?key=".$chave_acesso);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$json_str = curl_exec($curl);
$json_obj = json_decode($json_str, true);

$conteudo = "<h3> Dados Recebidos do PagFácil </h3><hr>";
$conteudo .= "json_str: ".$json_str."<hr>";
$conteudo .= "json_obj: ".json_encode($json_obj)."<hr>";

$response = curl_exec($curl);
$conteudo .= "<h3> Resposta do PagFácil </h3><hr>";
$conteudo .= $response;

curl_close($curl);

echo $conteudo;
?>
