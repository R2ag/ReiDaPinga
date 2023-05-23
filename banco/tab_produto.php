<?php 
    include_once "../doc_HTML.php";

    try {
        $db = new PDO('sqlite:database.sqlite');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $res = $db->exec(
            "DROP TABLE IF EXISTS produto;
            CREATE TABLE IF NOT EXISTS produto(
                id      INTEGER    PRIMARY KEY AUTOINCREMENT,
                nome    TEXT   NOT NULL,
                desc    TEXT   NOT NULL,
                preco   DECIMAL(10,2)    NOT NULL,
                graduacao   DECIMAL(4,2)    NOT NULL,
                ano_fab INT    NOT NULL,
                imagem1 TEXT,
                imagem2 TEXT,
                imagem3 TEXT);"
        );

        $stmt = $db->prepare(
            "INSERT INTO produto(nome, desc, preco, graduacao, ano_fab) 
            VALUES (:nome, :desc, :preco, :graduacao, :ano_fab)"
        );
        
        $stmt->bindValue(':nome', 'Cachaça Teimosinha', SQLITE3_TEXT);
        $stmt->bindValue(':desc', 'Bebida alcoólica mista de cachaça.', SQLITE3_TEXT);
        $stmt->bindValue(':preco', 50.00, SQLITE3_FLOAT);
        $stmt->bindValue(':graduacao', 27.80, SQLITE3_FLOAT);
        $stmt->bindValue(':ano_fab', 2010, SQLITE3_INTEGER);
        
        $stmt->execute();

        $REGISTROS = $db->query("SELECT * FROM produto;");

        $listagem = "<h1>Produtos</h1>";
        
        foreach ($REGISTROS as $registro){ 
            $listagem .= '<h4>' . $registro['nome'] . '</h4>';
            $listagem .= "XP: ".$registro['id']."<br>"; 
            $listagem .= $registro['desc']."<br>";  
            $listagem .= "R$ ".$registro['preco']."<br>";
            $listagem .= $registro['graduacao']."%"."<br>";
            if ($registro['imagem1']){
				$listagem .= "<img src='imagens/produtos/".$registro['imagem1']."' height='384'> <br>";
			}
            $listagem .= '<hr>';
        }

        $db = null;
        echo Monta_Doc_HTML( "", $listagem );
        
    } catch (PDOException $ex){
    echo $ex->getMessage();
    die("X- FIM -X");
    }

?>