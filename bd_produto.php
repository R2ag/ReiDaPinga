<?php 
	include_once "controle_bd.php";

    function Exibir_Formulario($Mensagem) {
		$form = <<<HTML
			<form action="cad_produto.php" method="post" enctype="multipart/form-data">
				Nome: <input type="text" name="Nome"> <br>
				Preço: <input type="text" name="Preco"><br>
				Descrição: <input type="text" name="Desc"><br>
				Graduação Alcoólica: <input type="text" name="Grad_Alc"><br>
				Ano de Fabricação: <input type="text" name="Ano_Fab"><br>
				Imagem: <input type="file" name="Imagem1"><br>
				Imagem: <input type="file" name="Imagem2"><br>
				Imagem: <input type="file" name="Imagem3"><br>
		
				<input type="submit" value="Enviar">
				<input type="reset" value="Cancelar">
			</form>
		HTML;
	
		if ($Mensagem) {
			$form .= "<span class='erro'>" . $Mensagem . "</span>";
		}
	
		return $form;
	}

	function Inserir($Conexao, $DADOS = []) {
		$imagens = [];
		$error = "";
	
		if (!empty($_FILES)) {
			$error = Salvar_Imagem($_FILES);
			$imagens = [
				$_FILES["Imagem1"]["name"],
				$_FILES["Imagem2"]["name"],
				$_FILES["Imagem3"]["name"]
			];
		}
	
		if ($error == "") {
			$sql = "INSERT INTO produto (nome, descricao, preco, graduacao, ano_fab, imagem1, imagem2, imagem3) ";
			$sql .= "VALUES (:nome, :descricao, :preco, :graduacao, :ano_fab, :imagem1, :imagem2, :imagem3);";
	
			$stmt = $Conexao->prepare($sql);
	
			$stmt->execute([
				':nome' => $DADOS["Nome"],
				':descricao' => $DADOS["Desc"],
				':preco' => $DADOS["Preco"],
				':graduacao' => $DADOS["Grad_Alc"],
				':ano_fab' => $DADOS["Ano_Fab"],
				':imagem1' => $imagens[0] ?? '',
				':imagem2' => $imagens[1] ?? '',
				':imagem3' => $imagens[2] ?? ''
			]);
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
		$stmt->execute([ ':id_produto' => $id_produto]);

		$REGISTROS = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $listagem = "";
        
		if(count ($REGISTROS) == 1){
            
            $registro = $REGISTROS[0];

            $listagem .= "<h1>Produto: </h1>";
            
            $listagem .= '<h4>'.$registro['nome'].'</h4>';
			$listagem .= $registro['descricao']."<br>";
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
