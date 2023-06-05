<?php 
    include_once "../doc_HTML.php";

    try {
        $db = new PDO('sqlite:database.sqlite');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->exec("DROP TABLE IF EXISTS produto;");
        $db->exec(
            "CREATE TABLE IF NOT EXISTS produto(
                id      INTEGER    PRIMARY KEY AUTOINCREMENT,
                nome    TEXT   NOT NULL,
                descricao    TEXT   NOT NULL,
                preco   DECIMAL(10,2)    NOT NULL,
                graduacao   DECIMAL(4,2)    NOT NULL,
                ano_fab INT    NOT NULL,
                imagem1 TEXT,
                imagem2 TEXT,
                imagem3 TEXT);"
        );

        $stmt = $db->prepare(
            "INSERT INTO produto(nome, descricao, preco, graduacao, ano_fab) 
            VALUES (:nome, :descricao, :preco, :graduacao, :ano_fab)"
        );
        
        $stmt->execute([
            ':nome' => 'Cachaça Teimosinha',
            ':descricao' => 'Bebida alcoólica mista de cachaça.',
            ':preco' => 50.00,
            ':graduacao' => 27.80,
            ':ano_fab' => 2010
        ]);

        $REGISTROS = $db->query("SELECT * FROM produto;");

        $listagem = "<h1>Produtos</h1>";
        
        foreach ($REGISTROS as $registro){ 
            $listagem .= '<h4>' . $registro['nome'] . '</h4>';
            $listagem .= "XP: ".$registro['id']."<br>"; 
            $listagem .= $registro['descricao']."<br>";  
            $listagem .= "R$ ".$registro['preco']."<br>";
            $listagem .= $registro['graduacao']."%"."<br>";
            if ($registro['imagem1']){
                $listagem .= "<img src='imagens/produtos/".$registro['imagem1']."' height='384'> <br>";
            }
            $listagem .= '<hr>';
        }

        $db = null;
        echo Monta_Doc_HTML("", $listagem);
        
    } catch (PDOException $ex){
        echo $ex->getMessage();
        die("X- FIM -X");
    }
?>