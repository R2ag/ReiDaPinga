<?php

    include_once "controle_bd.php";

    function Inserir($Conexao, $DADOS = []) {
        $sql = "INSERT INTO encomenda (id_cliente, id_produto, id_encomenda, data_encomenda, pagamento) ";
        $sql .= "VALUES (:cliente, :produto, :encomenda, :data, :pagamento);";

        $id_cliente = $_SESSION['SES_Login'];
        $codigo  = random_int(1000, 9999);
        $data = date('Y-m-d');

        $stmt = $Conexao->prepare($sql);
        $stmt->execute([
            ':cliente' => $id_cliente,
            ':produto' => $DADOS["produto"],
            ':encomenda' => $codigo,
            ':data' => $data,
            ':pagamento' => $DADOS["pagamento"]
        ]);
    }

    function Consultar($Conexao) {
        $sql = "SELECT * FROM encomenda ";
        $sql .= "LEFT JOIN produto ON encomenda.id_produto = produto.id ";
        $sql .= "LEFT JOIN cliente ON encomenda.id_cliente = cliente.id ";
        $sql .= "ORDER BY produto.nome; ";

        $REGISTROS = $Conexao->query($sql);

        $listagem = "<h1>Encomendas</h1>";

        foreach ($REGISTROS as $registro) {
            $listagem .= "Produto: " . $registro["nome"] . "<br>";
            $listagem .= "R$: " . $registro["preco"] . "<br>";

            $listagem .= sprintf('<form action="conf_encomenda.php" method="post">
                <input type="hidden" value="%s">
                Forma de Pagamento: <select name="pagamento">
                <option>Visa</option>
                <option>MasterCard</option>
                <option>Pix</option>
                <option>Boleto</option>
                <option>outro</option>
                </select>
                <input type="submit" value="Confirmar encomenda">
                </form>', $registro["id"]);
        }

        return $listagem;
    }

    function Confirmar($Conexao, $DADOS = []) {
        $confirmado = "<pre>" . print_r($DADOS, true) . "</pre>";
        return $confirmado;
    }

    function Qtd_Encomendas() {
        $bd = BD_Conectar();
        $sql = "SELECT COUNT(id) AS qtd_encomendas FROM encomenda WHERE id_cliente = :cliente;";
    
        $stmt = $bd->prepare($sql);
        $stmt->execute([
            ':cliente' => $_SESSION["Login"]
        ]);
    
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);
    
        $bd = null;
    
        return $registro["qtd_encomendas"];
    }


?>