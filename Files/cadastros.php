<?php 

	//Formulario para registro de eventos e usuários:
	require("config.inc.php");

	if (!empty($_POST)) {

		$tipo = $_POST['tipo'];

		if ($tipo == 'aluno') {
			
				if (empty($_POST['nome']) || empty($_POST['senha']) || empty($_POST['email']) || empty($_POST['matricula']) ) {  
			        //Se algum dos parametros veio vazio, tem q dar erro e retornar a seguinte mensagem:
			        $response["success"] = 0;
			        $response["message"] = "Nome, Senha, Matricula ou Email não informado!";
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
		    	//Aqui verificamos se o nome bate com a matrícula informada. Se não bater, dá erro:
		    	$row = $stmt->fetch(PDO::FETCH_ASSOC);
			    if ($row['NomeAluno'] == $_POST['nome']) {
			        $response["success"] = 0;
			        $response["message"] = "Nome Incorreto. Verifique o nome informado. Em caso de problemas mande um email para a organização do evento!";
			        die(json_encode($response));
			    }
			    //Aqui finalmente verificamos se o aluno já possui email ou senha, o que significa que ele já foi cadastrado no sistema:
			    $email_verif = empty($row['EmailAluno']);
			    $senha_verif = empty($row['SenhaAluno']);

			    if (!$email_verif || !$senha_verif) {
					$response["success"] = 0;
			        $response["message"] = "Usuário já cadastrado!";
			        die(json_encode($response));

			    }
			    //Caso passe em todos os testes, alteramos a tabela e inserimos os dados:

			    //Mas primeiro, fazemos a encriptação da senha (e o SALT):

			    $passwd = $_POST['senha'];

			    $pre_salt = md5(uniqid(rand(),true));
			    $salt = substr($pre_salt, 0, 6);
			    $encrypted = hash("sha256", $passwd . $salt);
			    
			    $query = "UPDATE aluno SET EmailAluno=:email, SenhaAluno=:senha, Salt=:salt WHERE MatriculaAluno=:matricula ";

			    $query_params = array(
			    	':email' => $_POST['email'],
			    	':senha' => $encrypted,
			    	':matricula' => $_POST['matricula'],
			    	':salt' => $salt
			    	);

			    try {
			    	$stmt = $db->prepare($query);
			    	$result = $stmt->execute($query_params);
			    } catch (PDOException $ex) {
			    	$response["success"] = 0;
			        $response["message"] = "Erro Ao inserir! Verifique a conexão";
			        die(json_encode($response));
			    }

			    $response["success"] = 1;
	    		$response["message"] = "Aluno cadastrado com sucesso!";
	    		echo json_encode($response);

		} else if ($tipo == 'evento') {


				if (empty($_POST['nome']) || empty($_POST['palestrante']) ) {  
			        //Se algum dos parametros veio vazio, tem q dar erro e retornar a seguinte mensagem:
			        $response["success"] = 0;
			        $response["message"] = "Nome do palestrante ou Nome do Evento não informados!";
			        die(json_encode($response)); //Se der erro, o script morre aqui
		    	}

		    	$query = "SELECT 1 FROM Evento WHERE NomeEvento = :nome_evento";

				$query_params = array(
						':nome_evento' => $_POST['nome']
					);

				try {
					$stmt = $db->prepare($query);
			    	$result = $stmt->execute($query_params);
				} catch (Exception $e) {
					$response["success"] = 0;
			        $response["message"] = "Erro Ao Validar no banco! Verifique a conexão";
			        die(json_encode($response));
				}

				$row = $stmt->fetch();
			    if ($row) {
			        $response["success"] = 0;
			        $response["message"] = "Evento já cadastrado!";
			        die(json_encode($response));
			    }

			    $query = "INSERT INTO Evento ( NomeEvento, PalestranteEvento, DataEvento ) values( :nome, :palestrante, :data ) ";

			    $query_params = array(
			    	':nome' => $_POST['nome'],
			    	':palestrante' => $_POST['palestrante'],
			    	':data' => $_POST['data']
			    	);

			    try {
			    	$stmt = $db->prepare($query);
			    	$result = $stmt->execute($query_params);
			    } catch (PDOException $ex) {
			    	$response["success"] = 0;
			        $response["message"] = "Erro Ao inserir Evento! Verifique a conexão";
			        die(json_encode($response));
			    }

			    $response["success"] = 1;
	    		$response["message"] = "Evento cadastrado com sucesso!";
	    		echo json_encode($response);
		}

	}

?>