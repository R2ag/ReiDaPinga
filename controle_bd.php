<?php

	function BD_Conectar(){
		$db = new PDO('sqlite:banco/database.sqlite');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if ( ! $db ){
			die("X- Banco de Dados não está funcionando ?!? -X");
		}

		return $db;
	}

	function BD_Desconectar( $p_Conexao ){
		$p_Conexao = null;
	}

?>