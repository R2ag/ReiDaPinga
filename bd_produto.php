<?php 
	include_once "controle_bd.php";

    function Exibir_Formulario($Mensagem){
        $form = "";
        $form .= "<form action='cad_produto.php' method='post' enctype='multipart/form-data'>";
    
        $form .= "Nome: <input type='text' name='Nome'> <br>";
        $form .= "Preço: <input type='text' name='Preco'><br>";
        $form .= "Descrição: <input type='text' name='Desc'><br>";
        $form .= "Graduação Alcoólica: <input type='text' name='Grad_Alc'><br>";
        $form .= "Ano de Fabricação: <input type='text' name='Ano_Fab'><br>";
        $form .= "Imagem: <input type='file' name='Imagem1'><br>";
        $form .= "Imagem: <input type='file' name='Imagem2'><br>";
        $form .= "Imagem: <input type='file' name='Imagem3'><br>";
    
        $form .= "<input type='submit' value='Enviar'>";
        $form .= "<input type='reset' value='Cancelar'>";
    
        $form .= "</form>";
    
        if($Mensagem){
            $form .= "<span class='erro'>".$Mensagem."</span>";
        }
        
        return $form;
	}

	function Inserir($Conexao, $DADOS = []){

		$imagem1 = "";
		$imagem2 = "";
		$imagem3 = "";
		$error = "";

		if (count($_FILES) > 0) {
			$error = Salvar_Imagem($_FILES);
			$imagem1 = $_FILES["Imagem1"]["name"];
			$imagem2 = $_FILES["Imagem2"]["name"];
			$imagem3 = $_FILES["Imagem3"]["name"];
		}
		
		if ($error == "") {
			$sql = "INSERT INTO produto (nome, desc, preco, graduacao, ano_fab, imagem1, imagem2, imagem3) ";
			$sql .=	"VALUES (:nome, :desc , :preco, :graduacao, :ano_fab, :imagem1, :imagem2, :imagem3);";

			$stmt = $Conexao->prepare($sql);

			$stmt->bindValue(':nome', $DADOS["Nome"], PDO::PARAM_STR);	
			$stmt->bindValue(':desc', $DADOS["Desc"], PDO::PARAM_STR);
			$stmt->bindValue(':preco', $DADOS["Preco"], PDO::PARAM_STR);
			$stmt->bindValue(':graduacao', $DADOS["Grad_Alc"], PDO::PARAM_STR);
			$stmt->bindValue(':ano_fab', $DADOS["Ano_Fab"], PDO::PARAM_INT);
			$stmt->bindValue(':imagem1', $imagem1, PDO::PARAM_STR);
			$stmt->bindValue(':imagem2', $imagem2, PDO::PARAM_STR);
			$stmt->bindValue(':imagem3', $imagem3, PDO::PARAM_STR);

			$stmt->execute();		
		}

		return $error;
	}

	function Consultar($Conexao){
		$REGISTROS = $Conexao->query("SELECT * FROM produto;");

		$listagem = "<h1>Produtos</h1>";
		
		foreach ($REGISTROS as $registro){
			$listagem .= "<a href='desc_produto.php?produto=".urlencode($registro['id'])."'>";
			$listagem .= "<img src='imagens/produto/".$registro['imagem1']."' width='200' height='150'>";
			$listagem .= '<h4>' . $registro['nome'] . '</h4>';
			$listagem .= "R$ ".$registro['preco']."<br>";
			$listagem .= "</a>";  
			$listagem .= '<hr>';
		}

		return $listagem;
	}

	function Detalhar($Conexao, $id_produto){
		$sql = "SELECT * FROM produto WHERE id = :id_produto;";
		$stmt = $Conexao->prepare($sql);
		$stmt->bindValue(':id_produto', $id_produto, PDO::PARAM_INT);
		$stmt->execute();

		$REGISTROS = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $listagem = "";
        
		if(count ($REGISTROS) == 1){
            
            $registro = $REGISTROS[0];

            $listagem .= "<h1>Produto: </h1>";
            
            $listagem .= '<h4>'.$registro['nome'].'</h4>';
			$listagem .= $registro['desc']."<br>";
			$listagem .= $registro['graduacao']."<br>";
			$listagem .= $registro['ano_fab']."<br>";
			$listagem .= $registro['preco']."<br>";

            $listagem .= "<a href='cad_encomenda.php?produto=".$registro['id']."'> Comprar </a>";

			if ($registro['imagem1']){
				$listagem .= "<img src='imagens/produto/".$registro['imagem1']."' height='384'> <br>";
			}

			if ($registro['imagem2']){
				$listagem .= "<img src='imagens/produto/".$registro['imagem2']."' height='384'> <br>";
			}

			if ($registro['imagem3']){
				$listagem .= "<img src='imagens/produto/".$registro['imagem3']."' height='384'> <br>";
			}		
		}else{
            $listagem .= "<h1>Não foi possivel Localiazar o produto</h1>";
        }

		return $listagem;
	}

	function Salvar_Imagem($IMAGENS){
		$msg_erro = "";

		foreach ($IMAGENS as $imagem){
			if ($imagem["name"] != ""){
				$destino = "imagens/produto/" . basename($imagem["name"]);

				if (file_exists($destino)){
					$msg_erro = "A imagem: '".basename($imagem["name"])."', já existe.";
					break;
				} 

				if (filesize($imagem["tmp_name"]) > 512 * 1024){
					$msg_erro = "A imagem: '".basename($imagem["name"])."', deve ter no máximo 512KB.";
					break;
				}
						
				if (!move_uploaded_file($imagem["tmp_name"], $destino)){
					$msg_erro = "Não foi possível salvar a imagem: '".basename($imagem["name"])."'.";
					break;
				}
			}		
		}

		return $msg_erro;
	}
?>
