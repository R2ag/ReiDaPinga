<?php
	error_reporting(0);
	error_reporting(E_ALL);
	function LOG_Erro($errno, $errstr, $errfile, $errline){ 
		die("<hr><hr>#: ".$errno."<br>Msg:: ".$errstr."<br>Arq: ".$errfile."<br>Lin: ".$errline); 
	}
	set_error_handler("LOG_Erro");

	function LOG_Excessao(Throwable $exception){ 
		echo "<hr><hr>PHP Exception: " , $exception->getMessage(), "<hr>"; 
	}
	set_exception_handler('LOG_Excessao');
?>