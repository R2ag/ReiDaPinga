<?php

    // **************************************************************************************************************
    function k_URL( $p_URL = "", $p_DADOS = array() )
    {
        // POST (não está funcionando):
        $url = $p_URL;
        $post = k_POST($p_DADOS);

        // GET ($post será ignorado)
        $url = k_GET($p_URL, $p_DADOS);

        $resposta = k_EXEC( $url, $post );
        
        return $resposta;
    }

    // **************************************************************************************************************
    function k_EXEC( $p_URL = "", $p_PARAMS = array() )
    {
        $exec = file_get_contents($p_URL, false, $p_PARAMS);
        //$exec = fopen($p_URL, 'r', false, $p_PARAMS);

        return $exec;
    }

    // **************************************************************************************************************
    function k_GET( $p_URL = "", $p_DADOS = array() )
    {
        $url = $p_URL;
        foreach($p_DADOS as $chave=>$valor)
        {
            $url .= "&".$chave."=".base64_encode($valor);
        }
        echo "URL: ".$url."<hr>";
        
        return $url;
    }

    // **************************************************************************************************************
    function k_POST( $p_DADOS = array() )
    {
        // https://www.php.net/manual/en/function.http-build-query
        $data = http_build_query($p_DADOS);
        
        $tipo = "application/x-www-form-urlencoded";
        $parametros = array( 'http' => array(
                                                "header"  => "Content-type: ".$tipo."\r\n". 
                                                             "Content-Length: ".strlen($data)."\r\n",
                                                "method"  => "POST",
                                                "content" => $data
                                            )
                            );

        // https://www.php.net/manual/en/function.stream-context-create.php
        $post  = stream_context_create($parametros);

        return $post;
    }

    // **************************************************************************************************************
    function c_URL( $p_URL = "", $p_DADOS = array() )
    {
        $ch = curl_init();
        
        curl_setopt($ch,CURLOPT_URL,            $p_URL);
        curl_setopt($ch,CURLOPT_POST,           true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,     http_build_query($p_DADOS));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        
        //execute post
        $exec = curl_exec($ch);;
        curl_close($ch);
        
        return $exec;
    }

?>