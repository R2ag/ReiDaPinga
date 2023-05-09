<?php 
include_once "controle_bd.php";

function Criar_Tabela(){
	$BD = BD_Conectar();

	$res = $BD->exec(
		"drop table if exists cliente;
        CREATE TABLE IF NOT EXISTS cliente(
            id       SMALLINT     AUTO_INCREMENT,
            nome     TEXT         NOT NULL,
            cpf      VARCHAR(11)  NOT NULL,
            cep      VARCHAR(8),
            email    TEXT         NOT NULL,
            login    VARCHAR(11)  NOT NULL,
            senha    TEXT         NOT NULL,
            avatar   TEXT,
            
            PRIMARY KEY(id) );"
	);

	BD_Desconectar($BD);
}

function Exibir_Formulario($Msg){
	$form = "";
	$form .= "<form action='cad_cliente.php' method='post' enctype='multipart/form-data'>";

	$form .= "Nome: <input type='text' name='Nome'> <br>";
    $form .= "CPF: <input type='text' name='Cpf'> <br>";
    $form .= "CEP: <input type='text' name='Cep'> <br>";
    $form .= "Email: <input type='email' name='Email'> <br>";
    $form .= "Login: <input type='text' name='Login'> <br>";
    $form .= "Senha: <input type='password' name='Senha'> <br>";
    $form .= "Avatar: <input type='file' name='Avatar'><br>";
    
	$form .= "<input type='submit' value='Enviar'>";
	$form .= "<input type='reset' value='Cancelar'>";

	$form .= "</form>";

    if ( $Msg ){
        $form .= "<span class='erro'>".$Msg."</span>";
    }

	return $form;
}

function Inserir( $Conexao, $DADOS = [] ){

    $avatar = "";
    if (count($_FILES) > 0){
        $msg_error = Salvar_Imagem($_FILES);
        $avatar = $_FILES["Avatar"]["Name"]
    }

    if(email_not_found($Conexao, $DADOS["Email"])){
        $stmt = $db->prepare(
            "INSERT INTO cliente (nome, cpf, cep, email, login, senha, avatar)
            VALUES(:nome, :cpf, :cep, :email, :login, :senha, :avatar);"
        );

        $stmt->bindValue(':nome', $DADOS["Nome"], SQLITE3_TEXT);
        $stmt->bindValue(':cpf', $DADOS["Cpf"], SQLITE3_TEXT);
        $stmt->bindValue(':cep', $DADOS["Cep"], SQLITE3_TEXT);
        $stmt->bindValue(':email', $DADOS["Email"], SQLITE3_TEXT);
        $stmt->bindValue(':login', $DADOS["Login"], SQLITE3_TEXT);
        $stmt->bindValue(':senha', $DADOS["Senha"], SQLITE3_TEXT);
        $stmt->bindValue(':avatar', $avatar, SQLITE3_TEXT);
    
        $stmt->execute();
	   
    }else{
        return "Já existe um cliente cadastrado com o email informado."
    }
    
}
function Consultar( $p_Conexao ){
	$REGISTROS = $p_Conexao->query("SELECT * FROM cliente;");

	$listagem = "<h1>Clientes</h1>";
	
	foreach ($REGISTROS as $registro){ 
		$listagem .= '<h4>' . $registro['nome'] . '</h4>';
        $listagem .= $registro['cpf'] . '<br>';
        $listagem .= $registro['cep'] . '<br>';
        $listagem .= $registro['email'] . '<br>';
        $listagem .= $registro['login'] . '<br>';
        $listagem .= $registro['senha'] . '<br>';
        $listagem .= "<img src='imagens/".$registro['avatar']."' width='200' height='150'>" '<br>';
	}

	return $listagem;
	
}

function Login( $Mensagem ){
    $form = "";

    $form .= "<form action='ses_login.php' method='post'>";

    $form .= "<table>";
    $form .= "<tr><td>Login: </td><td> <input type='text' name='login'> </td></tr>";
    $form .= "<tr><td>Senha: </td><td> <input type='password' name='Senha'> </td></tr>";
    $form .= "<tr><td></td><td> <input type='submit' value='Enviar'>";
    $form .= " <input type='reset' value='Cancelar'></td></tr>";
    $form .= "</table>";
    
    $form .= "</form>";
    $form .= $Mensagem;

    return $form;
}

function Autorizar( $Conexao, $Login, $Senha){
    $sql = "select * from cliente where login= :login AND senha = :senha;";
    $comando = $p_conexao->prepare($sql);
    $comando->bindValue(':email', $Login, SQLITE_TEXT);
    $comando->bindValue(':senha', $Senha, SQLITE_TEXT);
    $comando->execute();

    $REGISTRO = $comando->fetchAll(PDO::FETCH_ASSOC);

    return ( count($REGISTRO)  == 1 );
}

function email_not_found($Conexao, $Email){
    $sql = "SELECT * FROM cliente WHERE email = :email;";

    $stmt = $Conexao->prepare($sql);
    $stmt->bindValue(':email', $Email, SQLITE_TEXT);
    $stmt->execute();

    $REGISTRO = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return ( count($REGISTRO) > 0 ); 
}

?>