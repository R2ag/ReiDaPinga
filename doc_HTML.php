<?php

function Monta_Doc_HTML($Origem, $p_Conteudo, $p_Limpar_Sessao = false){
    $html = <<<HTML
        <!doctype html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Loja de Carros F1</title>
            <style>
                * {
                    margin: 0px;
                    padding: 0px;
                    box-sizing: border-box;
                    font-size: 16px;
                    line-height: 1.35;
                }

                .conteudo {
                    padding: 5px;
                    margin: 5px;
                    border: 3px ridge gray;
                }

                .esq {
                    display: none;
                    width: 500px;
                }

                .dir {
                    width: 1300px;
                }

                .menu {
                    display: inline-block;
                    width: 90px;
                    height: 55px;
                    border-radius: 5px;
                    text-align: center;
                    vertical-align: middle;
                    text-decoration: none;
                    margin: 5px;
                    padding: 5px;
                }

                .erro {
                    background-color: red;
                    color: yellow;
                    font-weight: 800;
                }

                .amarelo {
                    background-color: yellow;
                }

                .azul {
                    background-color: DarkBlue;
                    color: white;
                }

                .laranja {
                    background-color: orange;
                }

                .vermelho {
                    background-color: red;
                }

                .verde {
                    background-color: limegreen;
                }

                .roxo {
                    background-color: purple;
                    color: white;
                }

                #cont_encomenda {
                    background-color: red;
                    color: white;
                    border-radius: 10px;
                    display: inline-block;
                    padding: 2px;
                    font-size: 12px;
                    line-height: 1.2;
                }

                #cookies {
                    background-color: #aaffaa;
                    color: black;
                }
            </style>
        </head>
        <body>
        <div class="conteudo esq" id="controle">
            <hr>
            {Cookies()}
            <hr>
            get_included_files(): <br>
            <pre>{print_r(get_included_files(), true)}</pre>
            <hr>
            _FILES: <br>
            <pre>{print_r($_FILES, true)}</pre>
            <hr>
            _SERVER: <br>
            <pre>{print_r($_SERVER, true)}</pre>
            <hr>
        </div>
        <div class="conteudo dir">
            {Menu($Origem)}
            {Javascript($p_Limpar_Sessao)}
            <center>{$p_Conteudo}</center>
        </div>
        </body>
        </html>
        HTML;

    return $html;
}



function Menu($p_Origem = ""){
    $Menu_Principal = [
        "📱 List. Produtos" => "list_produto.php",
        "📝 Cad. Produto" => "cad_produto.php",
        "👨‍👩‍👧‍👦 List. Cliente" => "list_cliente.php",
        "🪪 Cad. Cliente" => "cad_cliente.php",
        "🛒 Compras" => "list_encomenda.php",
        "🛃 Login" => "ses_login.php",
        "⚽ Sair" => "ses_logout.php"
    ];

    $menu = "<br><br><center>";
    $menu .= "<span class='menu roxo' onclick='Mostrar()'>⬅️</span>";
    $menu .= "<a class='menu azul' href='#'>⚒️ V0720</a>";
    foreach ($Menu_Principal as $link => $arquivo) {
        $cor = ($p_Origem == "" ? 'vermelho' : ($p_Origem == $arquivo ? "verde" : "amarelo"));

        $contador = "";
        if ($link == "🛒 Compras") {
            $contador .= "&nbsp;<span id='cont_encomenda'>";
            //$contador .= E_Quant_Encomendas();
            $contador .= "</span>";
        }

        $xp_cliente = ($link == "🛃 Login" ? "<br>{$_SESSION['SES_Login']}" : "");
        $menu .= "<a class='menu {$cor}' href='{$arquivo}'>{$link}{$contador}{$xp_cliente}</a>";
    }
    $menu .= "</center><br><br>";

    return $menu;
}

        
		
    function Javascript( $p_Limpar_Sessao = false ){
        $js = "";
        $js .= "\n<script>";
        $js .= "\n function Mostrar() { var controle=document.getElementById('controle'); controle.style.display = ( controle.style.display=='block' ? 'none' : 'block' ); } ";
        $js .= "\n</script>"; 
        
        return $js;
        
    }

	function Cookies(){
        $ck = "";
        $ck .= "\nCookies: <br><div id='cookies'> Cookies </div>";
        $ck .= "\n<script>";
        $ck .= "\nvar theCookies = document.cookie.split(';'); ";
        $ck .= "\nvar aString = ''; ";
        $ck .= "\nfor (var i = 1 ; i <= theCookies.length; i++) { ";
        $ck .= "\n	aString += i + ') ' + theCookies[i-1] + '<br>'; ";
        $ck .= "\n} ";
        $ck .= "\ndocument.getElementById('cookies').innerHTML = aString; ";
        $ck .= "\n</script>";
        return $ck;
    }
        
	function Versao(){
        return (strval(intval(date("H"))-5) . date("i"));
    }

	function Criar_BD(){
        $criar_banco = "";
        $criar_banco .= "<a href='index.php'> INDEX </a> &nbsp;&nbsp";
        $criar_banco .= "<a href='banco/tab_produto.php'> Tabela Produto </a> &nbsp;&nbsp";
        $criar_banco .= "<a href='banco/tab_cliente.php'> Tabela Cliente </a> &nbsp;&nbsp";
        $criar_banco .= "<a href='banco/tab_sessao.php'> Tabela Sessao </a> &nbsp;&nbsp";

        return $criar_banco;
    }
?>