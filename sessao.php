<?php
  session_start();
  include_once "ses_funcoes.php";

  $_SESSION["SES_Nome"] = "lojaRMS";
  $_SESSION["SES_ID"]   = session_id();
  $_SESSION["SES_Cli_Navegador"] = $_SERVER["HTTP_USER_AGENT"];
  $_SESSION["SES_Cli_DataHora"]  = $_SERVER["REQUEST_TIME"];
  $_SESSION["SES_Cli_IP"]        = $_SERVER["REMOTE_ADDR"];

  if(!isset($_SESSION['SES_Login'])){
    header("Location: ses_login.php?SES=".basename(__FILE__));
    exit;
  }

  SES_Sessao_Ativa();

  if( SES_Expirou() ){
    header("Location: ses_logout.php?SES=".basename(__FILE__));
    exit;
  }

?>