<?php 
    include_once "controle_bd.php";

    function Exibir_Formulario($Msg)    {
        $form = <<<HTML
            <form action="cad_cliente.php" method="post" enctype="multipart/form-data">
                <label for="Nome">Nome:</label>
                <input type="text" name="Nome" id="Nome"> <br>
    
                <label for="Cpf">CPF:</label>
                <input type="text" name="Cpf" id="Cpf"> <br>
    
                <label for="Cep">CEP:</label>
                <input type="text" name="Cep" id="Cep"> <br>
    
                <label for="Email">Email:</label>
                <input type="email" name="Email" id="Email"> <br>
    
                <label for="Login">Login:</label>
                <input type="text" name="Login" id="Login"> <br>
    
                <label for="Senha">Senha:</label>
                <input type="password" name="Senha" id="Senha"> <br>
    
                <label for="Avatar">Avatar:</label>
                <input type="file" name="Avatar" id="Avatar"> <br>
    
                <input type="submit" value="Enviar">
                <input type="reset" value="Cancelar">
            </form>
        HTML;
    
        if ($Msg) {
            $form .= "<span class='erro'>$Msg</span>";
        }
    
        return $form;
    }

    function Inserir($Conexao, $DADOS = []){
        $avatar = "";
        $error = "";
        
        if (count($_FILES) > 0) {
            $error = Salvar_Imagem($_FILES);
            $avatar = $_FILES["Avatar"]["name"];
        }
        
        if ($error === "") {
            if (email_not_found($Conexao, $DADOS["Email"])) {
                $sql = "INSERT INTO cliente (nome, cpf, cep, email, usuario, senha, avatar) ";
                $sql .= "VALUES(:nome, :cpf, :cep, :email, :usuario, :senha, :avatar);";
                
                $stmt = $Conexao->prepare($sql);
    
                $stmt->execute([
                    ':nome' => $DADOS["Nome"],
                    ':cpf' => $DADOS["Cpf"],
                    ':cep' => $DADOS["Cep"],
                    ':email' => $DADOS["Email"],
                    ':usuario' => $DADOS["Login"],
                    ':senha' => $DADOS["Senha"],
                    ':avatar' => $avatar
                ]);
    
            } else {
                return "Já existe um cliente cadastrado com o email informado.";
            }
        }
    }

    function Consultar($p_Conexao){
        $REGISTROS = $p_Conexao->query("SELECT * FROM cliente;");

        $listagem = "<h1>Clientes</h1>";
        
        foreach ($REGISTROS as $registro){ 
            $listagem .= '<h4>' . $registro['nome'] . '</h4>';
            $listagem .= $registro['cpf'] . '<br>';
            $listagem .= $registro['cep'] . '<br>';
            $listagem .= $registro['email'] . '<br>';
            $listagem .= $registro['login'] . '<br>';
            $listagem .= $registro['senha'] . '<br>';
            if($registro['avatar']){
                $listagem .= "<img src='imagens/cliente/".$registro['avatar']."' width='200' height='150'>".'<br>';
            }
        }

        return $listagem;
        
    }

    function Login($Mensagem){
        $form = <<<HTML
            <form action="ses_login.php" method="post">
                <div>
                    <label for="login">Login:</label>
                    <input type="text" id="login" name="login">
                </div>
                <div>
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha">
                </div>
                <div>
                    <input type="submit" value="Enviar">
                    <input type="reset" value="Cancelar">
                </div>
            </form>
            $Mensagem
        HTML;

        return $form;
    }

    function Autorizar($Conexao, $Login, $Senha){
        $id_cliente = 0;

        $sql = "SELECT * FROM cliente WHERE usuario = :usuario AND senha = :senha;";
        $comando = $Conexao->prepare($sql);
        $comando->execute([
            ':usuario' => $Login,
            ':senha' => $Senha
        ]);

        $REGISTROS = $comando->fetchAll(PDO::FETCH_ASSOC);

        if (count($REGISTROS) == 1) {
            $registro = $REGISTROS[0];
            if (isset($registro["id"])) {
                $id_cliente = $registro["id"];
            }
        }

        return $id_cliente;
    }

    function email_not_found($Conexao, $Email){
        $sql = "SELECT * FROM cliente WHERE email = :email;";

        $stmt = $Conexao->prepare($sql);
        $stmt->execute([
            ':email' => $Email
        ]);

        $REGISTRO = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return (count($REGISTRO) == 0);
    }

    function Verifica_Duplicidade($Conexao, $Login, $Email){
        $sql  = "SELECT * FROM cliente WHERE email = :email OR usuario = :usuario;";
        
        $comando = $Conexao->prepare($sql);
        $comando->execute([
            ':email' => $Email,
            ':usuario' => $Login
        ]);

        $REGISTRO = $comando->fetchAll(PDO::FETCH_ASSOC);

        return (count($REGISTRO) > 0);
    }

    function Salvar_Imagem($IMAGENS){
        $msg_erro = "";
        $gravar_arquivo = true;

        foreach ($IMAGENS as $imagem) {
            if ($imagem["name"] != "") {
                $destino = "imagens/cliente/" . basename($imagem["name"]);

                if (file_exists($destino)) {
                    $msg_erro = "A imagem: '" . basename($imagem["name"]) . "', já existe.";
                    $gravar_arquivo = false;
                }

                if (filesize($imagem["tmp_name"]) > 512 * 1024) {
                    $msg_erro = "A imagem: '" . basename($imagem["name"]) . "', deve ter no máximo 512KB.";
                    $gravar_arquivo = false;
                }

                if ($gravar_arquivo) {
                    if (!move_uploaded_file($imagem["tmp_name"], $destino)) {
                        $msg_erro = "Não foi possível salvar a imagem: '" . basename($imagem["name"]) . "'.";
                    }
                }
            }
        }
        return $msg_erro;
    }


?>