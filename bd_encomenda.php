<?php

    include_once "controle_bd.php";

    function Inserir($Conexao, $DADOS = []){
        $sql = "INSERT INTO encomenda (id_cliente, id_produto, id_encomenda, data_encomenda)";
        $sql .= "VALUES (:cliente, :produto, :encomenda, :data);";

        $stmt = $Conexao->prepare($sql);
        $id_cliente = $_SESSION['SES_Login'];
        $codigo  = random_int(1000, 9999);
        $data = date('Y-m-d');

        $stmt->bindValue(':cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->bindValue(':produto', $DADOS["id"], PDO::PARAM_INT);
        $stmt->bindValue(':encomenda', $codigo, PDO::PARAM_INT);
        $stmt->bindValue(':data', $data, PDO::PARAM_STR);

        $stmt->execute();
    }

    function Consultar($Conexao){
        $sql = "SELECT * FROM encomenda";
        $REGISTROS = $Conexao->query($sql);

        $listagem = "<h1>Encomendas</h1>";

        foreach ($REGISTROS as $registro){
            $listagem .= '<pre>'.print_r($registro, true).'</pre><hr>';
        }

        return $listagem;
    }

?>