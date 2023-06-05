    <?php
    include_once "../doc_HTML.php";

    try {
        $db = new PDO('sqlite:database.sqlite');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->exec("DROP TABLE IF EXISTS sessao");
        $db->exec(
            "CREATE TABLE IF NOT EXISTS sessao (
                id    INTEGER PRIMARY KEY AUTOINCREMENT,
                login    DATETIME,
                utilizando    DATETIME,
                logout    DATETIME,
                expirou   DATETIME,
                sid    TEXT,
                id_cliente    SMALLINT,
                
                FOREIGN KEY(id_cliente) REFERENCES cliente(id)
            );"
        );

        $stmt = $db->prepare(
            "INSERT INTO sessao (sid, id_cliente) 
            VALUES (:sid, :id_cliente)"
        );

        $stmt->execute([
            ':sid' => 'Rdp-SID1',
            ':id_cliente' => 1
        ]);

        $REGISTROS = $db->query("SELECT * FROM sessao LEFT JOIN cliente ON id = id_cliente;");

        $listagem = "<h1>Sessao</h1>";

        foreach ($REGISTROS as $registro) {
            $listagem .= '<h4>' . $registro['sid'] . '</h4>';
        }

        $db = null;
        Monta_Doc_HTML("", $listagem);
    } catch (PDOException $ex) {
        echo $ex->getMessage();
        die("X- FIM -X");
    }

?>