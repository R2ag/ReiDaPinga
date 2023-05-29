<?php

    include_once "controle_bd.php";

    function Inserir($Conexao, $DADOS = []){
        $sql = "INSERT INTO encomenda (id_cliente, id_produto, id_encomenda, data_encomenda, pagamento)";
        $sql .= "VALUES (:cliente, :produto, :encomenda, :data, :pagamento);";

        $stmt = $Conexao->prepare($sql);
        $id_cliente = $_SESSION['SES_Login'];
        $codigo  = random_int(1000, 9999);
        $data = date('Y-m-d');

        $stmt->bindValue(':cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->bindValue(':produto', $DADOS["produto"], PDO::PARAM_INT);
        $stmt->bindValue(':encomenda', $codigo, PDO::PARAM_INT);
        $stmt->bindValue(':data', $data, PDO::PARAM_STR);
        $stmt->bindValue(':pagamento', $DADOS["pagamento"], PDO::PARAM_STR);

        $stmt->execute();
    }

    function Consultar($Conexao){
        $sql = "";
        $sql .= "SELECT * FROM encomenda ";
        $sql .= "LEFT JOIN produto ON encomenda.id_produto = produto.id ";
        $sql .= "LEFT JOIN cliente ON encomenda.id_cliente = cliente.id ";
        $sql .= "ORDER BY produto.nome; ";

        $REGISTROS = $Conexao->query($sql);

        $listagem = "<h1>Encomendas</h1>";

        foreach ($REGISTROS as $registro){
            $listagem .= "Produto: ". $registro["nome"]."<br>";
            $listagem .= "R$: ".$registro["preco"]."<br>";

            $listagem .= "<form action='conf_encomenda.php' method='post'>";
            $listagem .= "<input type='hiden' value=".$registro["id"].">";
            $listagem .= "Forma de Pagamento: <select name='pagamento'>";
            $listagem .= "<option>Visa</option>";
            $listagem .= "<option>MasterCard</option>";
            $listagem .= "<option>Pix</option>";
            $listagem .= "<option>Boleto</option>";
            $listagem .= "<option>outro</option>";
            $listagem .= "</select>";

            $listagem .= "<input type='submit' value='Confirmar encomenda'>";
            $listagem .= "</form>";
        }

        return $listagem;
    }

    function Confirmar($Conexao, $DADOS = []){
        $confirmado = "";
        $confirmado .= "<pre>".print_r($DADOS, true)."</pre>";

        return $confirmado;
    }

    function Qtd_Encomendas(){
        $bd = BD_Conectar();
        $sql = "SELECT COUNT(id) AS qtd_encomendas FROM encomendas WHERE  id_cliente  = :cliente ;";

        $stmt = $bd->prepare($sql);
        $stmt->bindValue(":cliente", $_SESSION["Login"], PDO::PARAM_INT);
        $stmt->execute();

        $REGISTROS = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $registro = $REGISTROS[0];

        $bd = null;

        return $registro["qtd_encomendas"];

    }

?>