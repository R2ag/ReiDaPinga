<?php 
include_once "controle_bd.php";

// **************************************************************************************
function P_Criar_Tabela()
{
	$BD = BD_Conectar();

	$res = $BD->exec(
		"CREATE TABLE IF NOT EXISTS produto(
        id SMALLINT AUTO_INCREMENT PRIMARY KEY,
        nome TEXT NOT NULL,
        descricao TEXT NOT NULL,
        preco DECIMAL(10,2) NOT NULL,
        teor_alcoolico DECIMAL(10,2) NOT NULL,
        ano_fabricacao INT NOT NULL
        );"
	);

	BD_Desconectar($BD);
}

function P_Exibir_Formulario()
{
	$form = "";
	$form .= "<form action='cad_produto.php' method='post'>";

	$form .= "Nome: <input id='id_Nome' name='nm_Nome' type='text'> <br>";
    $form .= "Preço: <input id='id_Preco' name='nm_Preco' type='number'><br>";
    $form .= "Descrição: <input id='id_Desc' name='nm_Desc' type='text'><br>";
    $form .= "Teor Alcoolico: <input id='id_teor_alcoolico' name='nm_Teor_alcoolico' type='number'><br>";
    $form .= "Ano de Fabricação: <input id='id_Ano' name='nm_Ano' type='number'><br>";

	$form .= "<input type='submit' value='Enviar'>";
	$form .= "<input type='reset' value='Cancelar'>";

	$form .= "</form>";
	
	return $form;
}

// **************************************************************************************
function P_Inserir( $p_Conexao, $DADOS = [] )
{
	$comando = $p_Conexao->prepare(
	   "INSERT INTO messages produto(nome, descricao, preco, teor_alcoolico, ano_fabricacao ) 
        VALUES (:nome, :descricao, :preco, :teor_alcoolico, :ano_fabricacao);"
	);

	// Bind values directly to statement variables
    $stmt->bindValue(':nome', $_POST["nm_Nome"], SQLITE3_TEXT);
    $stmt->bindValue(':descricao', $_POST["nm_Desc"], SQLITE3_TEXT);
    $stmt->bindValue(':preco',  $_POST["nm_Preco"], SQLITE3_FLOAT);
    $stmt->bindValue(':teor_alcoolico', $_POST["nm_Teor_alcoolico"], SQLITE3_FLOAT);
    $stmt->bindValue(':ano_fabricacao',$_POST["nm_Ano"], SQLITE3_INTEGER);
    
	// Format unix time to timestamp
	//$formatted_time = date('Y-m-d H:i:s');
	//$stmt->bindValue(':time', $formatted_time, SQLITE3_TEXT);

	// Execute statement
	$comando->execute();
}

// **************************************************************************************
function P_Consultar( $p_Conexao )
{
	$REGISTROS = $p_Conexao->query("SELECT * FROM produto;");

	$listagem = "<h1>Produtos</h1>";
	
	foreach ($REGISTROS as $registro) 
	{ 
		$listagem .= '<h4>' . $registro['nome'] . '</h4>';
		$listagem .= $registro['descricao']."<br>";
        $listagem .= $registro['teor_alcoolico']."<br>";  
        $listagem .= $registro['ano_fabricacao']."<br>";  
		$listagem .= "R$ ".$registro['preco']."<br>";  
		$listagem .= '<hr>';
	}

	return $listagem;
	
}

?>