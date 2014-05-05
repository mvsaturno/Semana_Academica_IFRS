<?php 

	//Formulario para registro de eventos e usuários:
	require("config.inc.php");

	if (!empty($_POST)) {

		$tipo = $_POST['tipo'];

		if ($tipo == 'login') {
			
				if (empty($_POST['senha']) || empty($_POST['matricula']) ) {  
			        //Se algum dos parametros veio vazio, tem q dar erro e retornar a seguinte mensagem:
			        $response["success"] = 0;
			        $response["message"] = "Senha ou Matricula não informado!";
			        die(json_encode($response)); //Se der erro, o script morre aqui
		    	} 

		    	//Checa se o usuário já foi cadastrado antes.
		    	//Primeiro fazemos uma query pelo número da matrícula pra verificar se o usuario não inventou um numero qualquer:
				$query = "SELECT * FROM aluno WHERE MatriculaAluno = :matricula";

				$query_params = array(
						':matricula' => $_POST['matricula']
					);

				try {
					$stmt = $db->prepare($query);
					$result = $stmt->execute($query_params);
				} catch (PDOException $ex) {
			        $response["success"] = 0;
			        $response["message"] = "Erro ao verificar no Banco! Verifique a conexão";
			        die(json_encode($response));
		    	}
		    	$row = $stmt->fetch(PDO::FETCH_ASSOC);

		    	if (!empty($row['SenhaAluno']) || !empty($row['EmailAluno'])) {
		    		$salt = $row['Salt'];
		    	$encrypted = hash("sha256", $_POST['senha'] . $salt);

		    	if ($row['SenhaAluno'] == $encrypted) {
		    		$response["success"] = 1;
			        $response["message"] = "Sucesso!";
			        session_start('1');
			        $_SESSION['lgn'] = 'abrolhos!';
			        header('Location: index.php');
		    	} else {
		    		$response["success"] = 0;
			        $response["message"] = "Senha Incorreta! Verifique a sua senha e tente novamente...";
			        die(json_encode($response));	
		    		}
		    	} else {
		    		$response["success"] = 0;
			        $response["message"] = "O Usuário informado ainda não fez seu cadastro!";
		    		die(json_encode($response));
		    	}
		} 
	}

?>