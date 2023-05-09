<?php

    // #########################
    // 2º) Programa:
        function Monta_Doc_HTML( $Origem, $p_Conteudo, $p_Limpar_Sessao = false )
        {
            $html = "";
            $html .= "\n<!doctype html> <html>";
            $html .= "\n<head>";
            $html .= "\n<meta charset='utf-8'><title>Loja de Carros F1</title>";
            $html .= "\n<style>";
            $html .= "\n.conteudo { vertical-align: top; padding: 5px; margin: 5px; border: 3px ridge gray; } ";
            $html .= "\n.esq { display: none; width: 500px; } .dir { width: 1300px; } ";
            $html .= "\n.menu { display: inline-block; width: 120px; height: 30px; ";
            $html .= "\n        border-radius: 5px; text-align: center; vertical-align: middle; ";
			$html .= "\n        text-decoration: none; line-height: 30px; margin: 5px; } ";
            $html .= "\n.amarelo  { background-color: yellow;  } ";
            $html .= "\n.laranja  { background-color: orange;  } ";
            $html .= "\n.vermelho { background-color: red;  } ";
            $html .= "\n.verde    { background-color: limegreen;  } ";
            $html .= "\n.roxo     { background-color: purple; color: white; } ";
			$html .= "\n#cookies  { background-color: #aaffaa; color: black; } ";
            $html .= "\n</style>";
            $html .= "\n</head>";
            $html .= "\n<body><center>";
            
            $html .= "\n<table><tr>";
            $html .= "\n<td class='conteudo esq' id='controle'>";
			$html .= "\n<hr>".Cookies()."<hr>";

            $html .= "\n<hr>get_included_files(): <br><pre>";
            $html .= print_r(get_included_files(),true)."</pre><hr>";
            
            $html .= "\n<hr>GLOBALS: <br><pre>";
            $html .= print_r($GLOBALS,true)."</pre><hr>";
            
            $html .= "\n</td>";
            
            $html .= "\n<td class='conteudo dir'>";
            $html .= Menu($Origem);
            $html .= Javascript($p_Limpar_Sessao);
            $html .= $p_Conteudo;
            $html .= "\n</td>";
            
            $html .= "\n</tr></table>";
            
            $html .= "\n</center></body>";
            $html .= "\n</html>";
            
            return $html;
        }

		// ************************************************************************************
        function Menu( $p_Origem="" )
        {
            $Menu_Principal = [ "Lit. Produtos"=>"list_produto.php", "Cad. Produto" => "cad_produto.php", "List. Cliente"=>"list_cliente.php", "Cad. Cliente"=>"cad_cliente.php", "Login"=>"ses_login.php", "Sair"=>"ses_logout.php" ];
            
            $menu = "<br><br><center>";
            $menu .= "\n<span class='menu roxo' onclick='Mostrar()'><<<</span>";
            $menu .= "\n<a class='menu laranja' href='#'>V2107</a>";
            foreach( $Menu_Principal as $link=>$arquivo )
            {
                $cor = ( $p_Origem=="" ? 'vermelho' : ($p_Origem==$arquivo ? "verde" : "amarelo") );
                $menu .= "\n<a class='menu ".$cor."' href='".$arquivo."'>".$link."</a>";
            }
            $menu .= "\n</center><br><br>";
            
            return $menu;            
        }
        
		// ************************************************************************************
        function Javascript( $p_Limpar_Sessao = false )
        {
            $js = "";
            $js .= "\n<script>";
            $js .= "\n function Mostrar() { var controle=document.getElementById('controle'); controle.style.display = ( controle.style.display=='block' ? 'none' : 'block' ); } ";
			//if($p_Limpar_Sessao) { $js .= "\n localStorage.clear(); "; }
            $js .= "\n</script>"; 
            
            return $js;
            
        }

		// ************************************************************************************
		function Cookies()
		{
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
        
		// ************************************************************************************
        function Versao()
        {
            return (strval(intval(date("H"))-5) . date("i"));
        }

		// ************************************************************************************
		function Criar_BD()
		{
			$criar_banco = "";
			$criar_banco .= "<a href='index.php'> INDEX </a> &nbsp;&nbsp";
			$criar_banco .= "<a href='banco/tab_produto.php'> Tabela Produto </a> &nbsp;&nbsp";
			$criar_banco .= "<a href='banco/tab_cliente.php'> Tabela Cliente </a> &nbsp;&nbsp";
			$criar_banco .= "<a href='banco/tab_sessao.php'> Tabela Sessao </a> &nbsp;&nbsp";
	
			return $criar_banco;
		}

?>