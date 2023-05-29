<?php

    include_once "../doc_HTML.php";

    try{
        $db = new PDO('sqlite:database.sqlite');
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        $res = $db->exec(
            "DROP TABLE IF EXISTS encomenda;
            CREATE TABLE IF NOT EXISTS encomenda(
                id  INTEGER PRIMARY KEY AUTOINCREMENT,
                id_cliente  INTEGER,
                id_produto  INTEGER,
                nome_produto    TEXT,
                id_encomenda    INTEGER,
                data_encomenda  DATE,
                pagamento   TEXT
            );"
        );
        echo Monta_Doc_HTML(__FILE__, "Tabela Encomenda cirada com sucesso!");
    } catch (PDOException $ex) {
        echo $ex->getMessage();
        die("X- FIM -X");
    }

?>