<?php

// ********************************************************************
function SES_Fez_Login( $p_Usuario )
{
    $_SESSION['SES_Fez_Login'] = time();
    $_SESSION['SES_Login'] = $p_Usuario;
}

// ********************************************************************
function SES_Sessao_Ativa() 
{
    $_SESSION['SES_Sessao_Ativa'] = time();
    SES_Tempo_Total();
}

// ********************************************************************
function SES_Tempo_Total()
{
    $_SESSION['SES_Tempo_Total'] = $_SESSION['SES_Sessao_Ativa'] - $_SESSION['SES_Fez_Login'];
}

// ********************************************************************
function SES_Expirou() 
{
    $expirou = true;
    $intervalo = time() - $_SESSION['SES_Sessao_Ativa'];
    
    if ( $intervalo < 1800 ) 
    {
        $expirou = false; 
    }
    
    return $expirou;
}
  
?>
