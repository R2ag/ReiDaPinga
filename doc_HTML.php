<?php
    include "bd_encomenda.php";

    // #########################
    // 2º) Programa:
        function Monta_Doc_HTML( $Origem, $p_Conteudo, $p_Limpar_Sessao = false )
        {
            $html = "";
            $html .= "<!doctype html> <html>";
            $html .= "<head>";
            $html .= "<meta charset='utf-8'><title>Loja de Carros F1</title>";
            $html .= "<style>";
            $html .= "* { margin: 0px; padding: 0px; box-sizing: border-box; font-size: 16px; line-height: 1.35; } ";
            $html .= " table, tr, td { vertical-align: top; padding: 10px; } ";
            $html .= " h1 { font-size: 3rem; } ";
            $html .= " h2 { font-size: 2.5rem; } ";
            $html .= " h3 { font-size: 2rem; } ";
            $html .= ".conteudo { vertical-align: top; padding: 5px; margin: 5px; border: 3px ridge gray; } ";
            $html .= ".esq { display: none; width: 500px; } ";
            $html .= ".dir { width: 1300px; } ";
            $html .= ".menu { display: inline-block; width: 90px; height: 55px; ";
            $html .= "        border-radius: 5px; text-align: center; vertical-align: middle; ";
			$html .= "        text-decoration: none; margin: 5px; padding: 5px; } ";
            $html .= ".erro     { background-color: red; color: yellow; font-weight: 800; } ";
            $html .= ".amarelo  { background-color: yellow;  } ";
            $html .= ".azul     { background-color: DarkBlue; color: white; } ";
            $html .= ".laranja  { background-color: orange;  } ";
            $html .= ".vermelho { background-color: red;  } ";
            $html .= ".verde    { background-color: limegreen;  } ";
            $html .= ".roxo     { background-color: purple; color: white; } ";
            $html .= "#cont_encomenda { background-color: red; color: white; border-radius: 10px; ";
            $html .= "                  display: inline-block; padding: 2px; font-size: 12px; line-height: 1.2; } ";
			$html .= "#cookies  { background-color: #aaffaa; color: black; } ";
            $html .= "</style>";
            $html .= "</head>";
            $html .= "<body><center>";
            
            $html .= "<table><tr>";
            $html .= "<td class='conteudo esq' id='controle'>";
			$html .= "<hr>".Cookies()."<hr>";

            $html .= "<hr>get_included_files(): <br><pre>";
            $html .= print_r(get_included_files(),true)."</pre><hr>";
            
            $html .= "<hr>_FILES: <br><pre>";
            $html .= print_r($_FILES,true)."</pre><hr>";
            
            $html .= "<hr>_SERVER: <br><pre>";
            $html .= print_r($_SERVER,true)."</pre><hr>";
            
            $html .= "</td>";
            
            $html .= "<td class='conteudo dir'>";
            $html .= Menu($Origem);
            $html .= Javascript($p_Limpar_Sessao);
            $html .= "<center>".$p_Conteudo."</center>";
            $html .= "</td>";
            
            $html .= "</tr></table>";
            
            $html .= "</center></body>";
            $html .= "</html>";
            
            return $html;
        }

		// ************************************************************************************
        function Menu( $p_Origem="" )
        {
            $Menu_Principal = [ "📱 List. Produtos" => "list_produto.php", 
                                "📝 Cad. Produto"   =>  "cad_produto.php", 
                                "👨‍👩‍👧‍👦 List. Cliente"  => "list_cliente.php", 
                                "🪪 Cad. Cliente"   => "cad_cliente.php", 
                                "🛒 Compras"        => "list_encomenda.php", 
                                "🛃 Login"          => "ses_login.php", 
                                "⚽ Sair"           => "ses_logout.php" ];
            
            $menu = "<br><br><center>";
            $menu .= "<span class='menu roxo' onclick='Mostrar()'> ⬅️ </span>";
            $menu .= "<a class='menu azul' href='#'>⚒️ V0720</a>";
            foreach( $Menu_Principal as $link=>$arquivo )
            {
                $cor = ( $p_Origem=="" ? 'vermelho' : ($p_Origem==$arquivo ? "verde" : "amarelo") );

                $contador = "";
                if ( $link == "🛒 Compras" )
                {
                    $contador .= "&nbsp;<span id='cont_encomenda'>";
                    //$contador .= E_Quant_Encomendas();
                    $contador .= "</span>";
                }
                
                $xp_cliente =  ( $link == "🛃 Login" ? "<br>".$_SESSION['SES_Login'] : "" );
                $menu .= "<a class='menu ".$cor."' href='".$arquivo."'>".$link.$contador.$xp_cliente."</a>";
            }
            $menu .= "</center><br><br>";
            
            return $menu;            
        }
        
		// ************************************************************************************
        function Javascript( $p_Limpar_Sessao = false )
        {
            $js = "";
            $js .= "<script>";
            $js .= " function Mostrar() { var controle=document.getElementById('controle'); controle.style.display = ( controle.style.display=='block' ? 'none' : 'block' ); } ";
			//if($p_Limpar_Sessao) { $js .= " localStorage.clear(); "; }
            $js .= "</script>"; 
            
            return $js;
            
        }

		// ************************************************************************************
		function Cookies()
		{
			$ck = "";
			$ck .= "Cookies: <br><div id='cookies'> Cookies </div>";
			$ck .= "<script>";
			$ck .= "var theCookies = document.cookie.split(';'); ";
			$ck .= "var aString = ''; ";
			$ck .= "for (var i = 1 ; i <= theCookies.length; i++) { ";
			$ck .= "	aString += i + ') ' + theCookies[i-1] + '<br>'; ";
			$ck .= "} ";
			$ck .= "document.getElementById('cookies').innerHTML = aString; ";
			$ck .= "</script>";
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