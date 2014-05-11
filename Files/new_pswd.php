<?php 
require_once("config.inc.php");

	if (!empty($_POST)) {

		$tipo = $_POST['tipo'];

		if ( strcasecmp($tipo, 'rec_login') === 0 ) {
			//u = matricula, k = salt, i = id;
			if (empty($_POST['u']) || empty($_POST['k']) || empty($_POST['i']) ) {  
				$response["message"] = "Dados importantes não informados!";
			        echo '<p align="center">ERRO!<BR/>' . $response["message"] . '<BR><a href="javascript:history.back(1);">Voltar</a></p>';
			        die(); //Se der erro, o script morre aqui
			}

			
		}
	}
 ?>