<?php

    include_once "../doc_HTML.php";

    try {
        $db = new PDO('sqlite:database.sqlite');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->exec("DROP TABLE IF EXISTS encomenda");
        $db->exec(
            "CREATE TABLE IF NOT EXISTS encomenda(
                id  INTEGER PRIMARY KEY AUTOINCREMENT,
                id_cliente  INTEGER,
                id_produto  INTEGER,
                nome_produto    TEXT,
                id_encomenda    INTEGER,
                data_encomenda  DATE,
                pagamento   TEXT,
                finalizada  BOOL
            );"
        );

        echo Monta_Doc_HTML(__FILE__, "Tabela Encomenda criada com sucesso!");
    } catch (PDOException $ex) {
        echo $ex->getMessage();
        die("X- FIM -X");
    }
?>