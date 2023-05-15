<?php 
	include_once "controle_bd.php";

	function Criar_Tabela(){
		$BD = BD_Conectar();

		$res = $BD->exec(
			"DROP TABLE IF EXISTS produto;
			CREATE TABLE IF NOT EXISTS produto(
				id      INT    AUTO_INCREMENT,
				nome    TEXT   NOT NULL,
				desc    TEXT   NOT NULL,
				preco   DECIMAL(10,2)    NOT NULL,
				graduacao   DECIMAL(4,2)    NOT NULL,
				ano_fab INT    NOT NULL,
				imagem1 TEXT,
				imagem2 TEXT,
				imagem3 TEXT,

				PRIMARY KEY(id) );"
		);

		BD_Desconectar($BD);
	}

	function Exibir_Formulario( $Mensagem){
		$form = "";
		$form .= "<form action='cad_produto.php' method='post' enctype='multipart/form-data'>";

		$form .= "Nome: <input type='text' name='Nome'> <br>";
	$form .= "Preço: <input type='text' name='Preco'><br>";
	$form .= "Descrição: <input type='text' name='Desc'><br>";
	$form .= "Graduação Alcoolica: <input type='text' name='Grad_Alc'><br>";
	$form .= "Ano de Fabricação: <input type='number' name='Ano_Fab'><br>";
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

	function Inserir( $Conexao, $DADOS = [] ){

		$imagem1 = "";
		$imagem2 = "";
		$imagem3 = "";
		$error = "";

		if (count($_FILES)>0) {
			$error = Salvar_Imagem($_FILES);
			$imagem1 = $_FILES["Imagem1"]["name"];
		$imagem2 = $_FILES["Imagem2"]["name"];
		$imagem3 = $_FILES["Imagem3"]["name"];
		}
		
		if ($error == "") {
			$sql = "INSERT INTO produto (nome, desc, preco, graduacao, ano_fab, imagem1, imagem2, imagem3) 
				VALUES (:nome, :descricao , :preco, :graduacao, :ano_fab, imagem1, imagem2, imagem3);";

			$stmt = $Conexao->prepare($sql);

			$stmt->bindValue(':nome', $DADOS["Nome"], SQLITE3_TEXT);	
			$stmt->bindValue(':descricao', $DADOS["Desc"], SQLITE3_TEXT);
			$stmt->bindValue(':preco', $DADOS["Preco"], SQLITE3_FLOAT);
			$stmt->bindValue(':graduacao', $DADOS["Grad_Alc"], SQLITE3_FLOAT);
			$stmt->bindValue(':ano_fab', $DADOS["Ano_Fab"], SQLITE3_TEXT);
			$stmt->bindValue(':imagem1', $imagem1, SQLITE3_TEXT);
			$stmt->bindValue(':imagem2', $imagem2, SQLITE3_TEXT);
			$stmt->bindValue(':imagem3', $imagem3, SQLITE3_TEXT);

			
			$stmt->execute();
			
		
		}

		return $error;

	}

	function Consultar( $Conexao ){
		$REGISTROS = $Conexao->query("SELECT * FROM produto;");

		$listagem = "<h1>Produtos</h1>";
		
		foreach ($REGISTROS as $registro){

			$listagem .= "<a href='desc_produto.php?produto=".$registro['nome'] ."'>";
			$listagem .= "<img src='imagens/produtos/".$registro['imagem1']."' width='200' height='150'>";
			$listagem .= '<h4>' . $registro['nome'] . '</h4>';
			$listagem .= "R$ ".$registro['preco']."<br>";
			$listagem .= "</a>";  
			$listagem .= '<hr>';
		}

		return $listagem;
		
	}

	function Detalhar($Conexao, $Nome){
		$sql = "SELECT * FROM produto WHERE nome = :nome;";
		$stmt = $Conexao->prepare($sql);
		$stmt->bindValue(':nome', $Nome, SQLITE3_TEXT);
		$stmt->execute();

		$REGISTROS = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$listagem = "<h1>Produto: </h1>";

		foreach($REGISTROS as $registro){
			$listagem .= '<h4>'.$registro['nome'].'</h4>';
			$listagem .= $registro['desc']."<br>";
			$listagem .= $registro['graduacao']."<br>";
			$listagem .= $registro['ano_fab']."<br>";
			$listagem .= $registro['preco']."<br>";

			if ($registro['imagem1']){
				$listagem .= "<img src='imagens/produtos/".$registro['imagem1']."' height='384'> <br>";
			}

			if ($registro['imagem2']){
				$listagem .= "<img src='imagens/produtos/".$registro['imagem2']."' height='384'> <br>";
			}

			if ($registro['imagem3']){
				$listagem .= "<img src='imagens/produtos/".$registro['imagem3']."' height='384'> <br>";
			}		
		}

		return $listagem;
	}

	function Salvar_Imagem( $IMAGENS){
		$msg_erro = "";
		$gravar_arquivo = true;

		foreach( $IMAGENS as $imagem ){
				if ( $imagem["name"] != "" ){
						$destino = "imagens/produto/" . basename($imagem["name"]);

						if (file_exists($destino)){
								$msg_erro = "A imagem: '".basename($imagem["name"])."', já existe.";
								$gravar_arquivo = false;
								
						} 

						if ( filesize( $imagem["tmp_name"] ) > 512*1024 ){
								$msg_erro = "A imagem: '".basename($imagem["name"])."', deve ter no máximo 512KB.";
								$gravar_arquivo = false;
						}
						
						if ( $gravar_arquivo ){
								if ( ! move_uploaded_file($imagem["tmp_name"], $destino) ){
										$msg_erro = "Não foi possível salvar a imagem: '".basename($imagem["name"])."'.";
								}
						}
				}
		
		}

		return $msg_erro;
	}

?>