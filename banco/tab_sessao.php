<?php 
include_once "../doc_html.php";

try {
  $db = new PDO('sqlite:database.sqlite');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $res = $db->exec(
    "DROP TABLE IF EXISTS;
    CREATE TABLE IF NOT EXISTS sessao(
        id    INT    AUTO_INCREMENT,
        login    DATETIME,
        utilizando    DATETIME,
        logout    DATETIME,
        expirou   DATETIME,
        sid    TEXT,
        id_cliente    SMALLINT,
        
        PRIMARY KEY(id),
        FOREING KEY(id_cliente) REFERENCES cliente(id));"
    );

    $stmt = $db->prepare(
        "INSERT INTO sessao (sid, id_cliente) 
        VALUES (:sid, :id_cliente)"
    );
    
    $stmt->bindValue(':sid', 'Rdp-SID1', SQLITE3_TEXT);
    $stmt->bindValue(':id_cliente', 1, SQLITE3_INTEGER);
    
    $stmt->execute();

    $REGISTROS = $db->query("SELECT * FROM sessao left join cliente on id = id_cliente;");

    $listagem = "<h1>Sessao</h1>";
    
    foreach ($REGISTROS as $registro){ 
        $listagem .= '<h4>' . $registro['sid'] . '</h4>';
    }

    $db = null;
    echo doc_HTML( $listagem );
    
} catch (PDOException $ex){
  echo $ex->getMessage();
  die("X- FIM -X");
}

?>