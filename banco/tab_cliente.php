<?php 
    include_once "../doc_HTML.php";

    try {
        $db = new PDO('sqlite:database.sqlite');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $db->exec(
            "DROP TABLE IF EXISTS cliente;
            CREATE TABLE IF NOT EXISTS cliente(
                id       INTEGER    PRIMARY KEY      AUTOINCREMENT,
                nome     TEXT         NOT NULL,
                cpf      VARCHAR(11)  NOT NULL,
                cep      VARCHAR(8),
                email    TEXT         NOT NULL,
                usuario    TEXT         NOT NULL,
                senha    TEXT         NOT NULL,
                avatar   TEXT);"
        );
    
        $stmt = $db->prepare(
            "INSERT INTO cliente (nome, cpf, cep, email, usuario, senha)
            VALUES(:nome, :cpf, :cep, :email, :usuario, :senha);"
        );
    
        $dadosCliente = [
            ':nome' => 'Rafael',
            ':cpf' => '12345678910',
            ':cep' => '12345678',
            ':email' => 'rafael@rlag.com',
            ':usuario' => 'rafael',
            ':senha' => 'rafael'
        ];
    
        $stmt->execute($dadosCliente);
    
        $REGISTROS = $db->query("SELECT * FROM cliente;");
        $listagem = "<h1>Clientes</h1>";
        foreach ($REGISTROS as $registro) {
            $listagem .= '<h4>' . $registro['nome'] . '</h4>';
            $listagem .= $registro['email'] . "<br>";
            $listagem .= $registro['usuario'] . "<br>";
            $listagem .= '<hr>';
        }
    
        $db = null;
    
        echo Monta_doc_HTML(__FILE__, $listagem);
    } catch (PDOException $ex) {
        echo $ex->getMessage();
        die("X- FIM -X");
    }

?>