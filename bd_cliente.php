<?php 
    include_once "controle_bd.php";

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

        if ($Msg){
            $form .= "<span class='erro'>".$Msg."</span>";
        }

        return $form;
    }

    function Inserir($Conexao, $DADOS = []){
        $avatar = "";
        $error = "";
        
        if (count($_FILES) > 0){
            $error = Salvar_Imagem($_FILES);
            $avatar = $_FILES["Avatar"]["name"];
        }
        
        if ($error == "") {
            if(email_not_found($Conexao, $DADOS["Email"])){
                $sql = "INSERT INTO cliente (nome, cpf, cep, email, login, senha, avatar) ";
                $sql .= "VALUES(:nome, :cpf, :cep, :email, :login, :senha, :avatar);";
                
                $stmt = $Conexao->prepare($sql);
    
                $stmt->bindValue(':nome', $DADOS["Nome"], PDO::PARAM_STR);
                $stmt->bindValue(':cpf', $DADOS["Cpf"], PDO::PARAM_STR);
                $stmt->bindValue(':cep', $DADOS["Cep"], PDO::PARAM_STR);
                $stmt->bindValue(':email', $DADOS["Email"], PDO::PARAM_STR);
                $stmt->bindValue(':login', $DADOS["Login"], PDO::PARAM_STR);
                $stmt->bindValue(':senha', $DADOS["Senha"], PDO::PARAM_STR);
                $stmt->bindValue(':avatar', $avatar, PDO::PARAM_STR);
            
                $stmt->execute();
        
            }else{
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
        $form = "";

        $form .= "<form action='ses_login.php' method='post'>";

        $form .= "<table>";
        $form .= "<tr><td>Login: </td><td> <input type='text' name='login'> </td></tr>";
        $form .= "<tr><td>Senha: </td><td> <input type='password' name='senha'> </td></tr>";
        $form .= "<tr><td></td><td> <input type='submit' value='Enviar'>";
        $form .= " <input type='reset' value='Cancelar'></td></tr>";
        $form .= "</table>";
        
        $form .= "</form>";
        $form .= $Mensagem;

        return $form;
    }

    function Autorizar($Conexao, $Login, $Senha){
        $sql = "SELECT * FROM cliente WHERE login = :login AND senha = :senha;";
        $comando = $Conexao->prepare($sql);
        $comando->bindValue(':login', $Login, PDO::PARAM_STR);
        $comando->bindValue(':senha', $Senha, PDO::PARAM_STR);
        $comando->execute();

        $REGISTRO = $comando->fetchAll(PDO::FETCH_ASSOC);
        
        return (count($REGISTRO) == 1);
    }

    function email_not_found($Conexao, $Email){
        $sql = "SELECT * FROM cliente WHERE email = :email;";

        $stmt = $Conexao->prepare($sql);
        $stmt->bindValue(':email', $Email, PDO::PARAM_STR);
        $stmt->execute();

        $REGISTRO = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return (count($REGISTRO) == 0); 
    }

    function Verifica_Duplicidade($Conexao, $Login, $Email){
        $sql  = "SELECT * FROM cliente WHERE email = :email OR login = :login;";
        
        $comando = $Conexao->prepare($sql);
        $comando->bindValue(':login', $Login, PDO::PARAM_STR);
        $comando->bindValue(':email', $Email, PDO::PARAM_STR);
        $comando->execute();

        $REGISTRO = $comando->fetchAll(PDO::FETCH_ASSOC);

        return (count($REGISTRO) > 0); 
    }

    function Salvar_Imagem($IMAGENS){
        $msg_erro = "";
        $gravar_arquivo = true;

        foreach($IMAGENS as $imagem){
            if ($imagem["name"] != ""){
                $destino = "imagens/cliente/" . basename($imagem["name"]);

                if (file_exists($destino)){
                    $msg_erro = "A imagem: '".basename($imagem["name"])."', já existe.";
                    $gravar_arquivo = false;
                    
                } 

                if (filesize($imagem["tmp_name"]) > 512*1024){
                    $msg_erro = "A imagem: '".basename($imagem["name"])."', deve ter no máximo 512KB.";
                    $gravar_arquivo = false;
                }
                
                if ($gravar_arquivo){
                    if (!move_uploaded_file($imagem["tmp_name"], $destino)){
                        $msg_erro = "Não foi possível salvar a imagem: '".basename($imagem["name"])."'.";
                    }
                }
            }
        }
        return $msg_erro;
    }

?>