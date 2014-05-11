<?php 

	//Formulario para registro de eventos e usuários:
	require_once("config.inc.php");

	if (!empty($_POST)) {

		$tipo = $_POST['tipo'];

		if ( strcasecmp($tipo, 'login') === 0 ) {
	
				if (empty($_POST['senha']) || empty($_POST['matricula']) ) {  
			        //Se algum dos parametros veio vazio, tem q dar erro e retornar a seguinte mensagem:
			        // $response["success"] = 0;
			        $response["message"] = "Senha ou Matricula não informado!";
			        echo '<p align="center">ERRO!<BR/>' . $response["message"] . '<BR><a href="javascript:history.back(1);">Voltar</a></p>';
			        die(); //Se der erro, o script morre aqui
		    	} 

		    	//Checa se o usuário já foi cadastrado antes.
		    	//Primeiro fazemos uma query pelo número da matrícula pra verificar se o usuario não inventou um numero qualquer:
				$query = "SELECT * FROM Aluno WHERE MatriculaAluno = :matricula";

				$query_params = array(
						':matricula' => $_POST['matricula']
					);

				try {
					$stmt = $db->prepare($query);
					$result = $stmt->execute($query_params);
				} catch (PDOException $ex) {
			        // $response["success"] = 0;
			        $response["message"] = "Erro ao verificar no Banco! Verifique a conexão";
			        echo '<p align="center">ERRO!<BR/>' . $response["message"] . '<BR><a href="javascript:history.back(1);">Voltar</a></p>';
			        die();
		    	}
		    	$row = $stmt->fetch(PDO::FETCH_ASSOC);

		    	if (!empty($row['SenhaAluno']) || !empty($row['EmailAluno'])) {
		    		$salt = $row['Salt'];
		    		$encrypted = hash("sha256", $_POST['senha'] . $salt);

		    	if ( strcasecmp($row['SenhaAluno'], $encrypted) === 0) {
			        session_start('1');
			        $_SESSION['lgn'] = 'abrolhos!';
			        $_SESSION['Nome'] = $row['NomeAluno'];
			        $_SESSION['Matricula'] = $row['MatriculaAluno'];
			        $_SESSION['Email'] = $row['EmailAluno'];
			        header('Location: index.php');
		    	} else {
			        $response["message"] = "Senha Incorreta! Verifique a sua senha e tente novamente...";
			        echo '<p align="center">ERRO!<BR/>' . $response["message"] . '<BR><a href="javascript:history.back(1);">Voltar</a></p>';
			        die();	
		    		}
		    	} else {
			        $response["message"] = 'O Usuário informado ainda não fez seu cadastro!';
			        echo '<p align="center">ERRO!<BR/>' . $response["message"] . '<BR><a href="javascript:history.back(1);">Voltar</a> <a href="form_cadastro.php">Cadastrar</a> </p>';
		    		die();
		    	}
		} elseif (strcasecmp($tipo, 'rec_senha') === 0 ) {
			if (empty($_POST['email']) || empty($_POST['matricula']) || empty($_POST['nome'])) {  
				$response["message"] = "Email, Nome ou Matricula não informado!";
			        echo '<p align="center">ERRO!<BR/>' . $response["message"] . '<BR><a href="javascript:history.back(1);">Voltar</a></p>';
			        die();
			    }

			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$posicao = strpos($url, 'valida_login.php');
			$final = substr($url, 0, $posicao);

			$query = "SELECT Salt FROM Aluno WHERE MatriculaAluno = :matricula";

				$query_params = array(
						':matricula' => $_POST['matricula']
					);

				try {
					$stmt = $db->prepare($query);
					$result = $stmt->execute($query_params);
				} catch (PDOException $ex) {
			        // $response["success"] = 0;
			        $response["message"] = "Erro ao verificar no Banco! Verifique a conexão";
			        echo '<p align="center">ERRO!<BR/>' . $response["message"] . '<BR><a href="javascript:history.back(1);">Voltar</a></p>';
			        die();
		    	}
		    	$row = $stmt->fetch(PDO::FETCH_ASSOC);
		    
		    $matricula = $row['MatriculaAluno'];
			$salt = $row['Salt'];
			$id_aluno = $row['idAluno'];
			$message = '<html><body>';
			$message .= "</body></html>";
			$message .= "<h1> Recuperação de senha </h1>";
			$message .= "<p>Olá! Você requisitou a possibilidade de cadastrar uma nova senha no sistema de cadastro da Semana Acadêmica do curso de Sistemas para Internet do IFRS - Câmpus Porto Alegre.</p>";
  			$message .= "<p>Caso vc não tenha feito essa solicitação ignore esta mensagem. Caso contrário clique no botão abaixo para continuar:</p>";
  			$message .= "<form action='".$final."/new_pswd.php' method='POST' >";
  			$message .= '<input type="hidden" name="tipo" value="rec_login"/> ';
  			$message .= '<input type="hidden" name="u" value="'.$matricula.'"/> ';
  			$message .= '<input type="hidden" name="k" value="'.$salt.'"/>';
  			$message .= '<input type="hidden" name="i" value="'.$id_aluno.'"/>';
  			$message .= '<input type="submit" value="Cadastrar nova senha"/>';
  			$message .= '</form>';
  			$message .= "</body></html>";
  			$headers = "From: $from\r\n";
            $headers .= "Content-type: text/html\r\n";
			if (mail($to,$subject,$message,$headers)) {
            	// echo 'Mail sent!';
                $json_answer = array('result' => 'success');
              } else{
                $json_answer = array('result' => 'failure on sending mail!');
             }
		} 
	}

?>