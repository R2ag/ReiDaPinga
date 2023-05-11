<?php 
    include_once "../doc_HTML.php";

    try {
    $db = new PDO('sqlite:database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $res = $db->exec(
            "drop table if exists cliente;
            CREATE TABLE IF NOT EXISTS cliente(
                id       SMALLINT     AUTO_INCREMENT,
                nome     TEXT         NOT NULL,
                cpf      VARCHAR(11)  NOT NULL,
                cep      VARCHAR(8),
                email    TEXT         NOT NULL,
                login    TEXT         NOT NULL,
                senha    TEXT         NOT NULL,
                avatar   TEXT,
                
                PRIMARY KEY(id) );"
        );

        $stmt = $db->prepare(
            "INSERT INTO cliente (nome, cpf, cep, email, login, senha)
            VALUES(:nome, :cpf, :cep, :email, :login, :senha);"
        );

        $stmt->bindValue(':nome', 'Rafael', SQLITE3_TEXT);
        $stmt->bindValue(':cpf', '12345678910', SQLITE3_TEXT);
        $stmt->bindValue(':cep', '12345678', SQLITE3_TEXT);
        $stmt->bindValue(':email', 'rafael@rlag.com', SQLITE3_TEXT);
        $stmt->bindValue(':login', 'rafael', SQLITE3_TEXT);
        $stmt->bindValue(':senha', 'rafael', SQLITE3_TEXT);

        $stmt->execute();

        $REGISTROS = $db->query("SELECT * FROM cliente;");
        $listagem = "<h1>Clientes</h1>";
        foreach($REGISTROS as $registro){
            $listagem .= '<h4>' . $registro['nome'] . '</h4>';
            $listagem .= $registro['email']."<br>";
            $listagem .= $registro['login']."<br>";
            $listagem .= '<hr>';
        }

        $db = null;

        echo Monta_doc_HTML(__FILE__, $listagem);

    } catch (PDOException $ex){
        echo $ex->getMessage();
        die("X- FIM -X");
    }

?>