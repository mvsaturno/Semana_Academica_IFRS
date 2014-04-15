<?php 

	//Formulario para registro de eventos e usuários:
	require("config.inc.php");

	if (!empty($_POST)) {

		var tipo = $_POST['tipo'];

		if (tipo == 'aluno') {
			
				if (empty($_POST['nome']) || empty($_POST['senha']) || empty($_POST['email']) || empty($_POST['matricula']) ) {  
			        //Se algum dos parametros veio vazio, tem q dar erro e retornar a seguinte mensagem:
			        $response["success"] = 0;
			        $response["message"] = "Nome, Senha, Matricula ou Email não informado!";
			        die(json_encode($response)); //Se der erro, o script morre aqui
		    	} 

				$query = "SELECT 1 FROM Aluno WHERE MatriculaAluno = :matricula";

				$query_params = array(
						':matricula' => $_POST['matricula'];
					);


				try {
					$stmt = $db->prepare($query);
					$result = $stmt->execute($query_params);
				} catch (PDOException $ex) {
			        $response["success"] = 0;
			        $response["message"] = "Erro ao verificar no Banco! Verifique a conexão";
			        die(json_encode($response));
		    	}

		    	$row = $stmt->fetch();
			    if ($row) {
			        $response["success"] = 0;
			        $response["message"] = "Aluno já cadastrado!";
			        die(json_encode($response));
			    }

			    $query = "INSERT INTO Aluno ( NomeAluno, MatriculaAluno, SenhaAluno, EmailAluno ) values( :nome, :matricula, :senha, :email ) ";

			    $query_params = array(
			    	':nome' => $_POST['nome'];
			    	':matricula' => $_POST['matricula'];
			    	':senha' => $_POST['senha'];
			    	':email' => $_POST['email'];
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


		} else if (tipo == 'evento') {


				if (empty($_POST['nome']) || empty($_POST['palestrante']) ) {  
			        //Se algum dos parametros veio vazio, tem q dar erro e retornar a seguinte mensagem:
			        $response["success"] = 0;
			        $response["message"] = "Nome do palestrante ou Nome do Evento não informados!";
			        die(json_encode($response)); //Se der erro, o script morre aqui
		    	}

		    	$query = "SELECT 1 FROM Evento WHERE NomeEvento = :nome_evento";

				$query_params = array(
						':nome_evento' => $_POST['nome'];
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

			    $query = "INSERT INTO Evento ( NomeEvento, PalestranteEvento, DataEvento, HoraEvento ) values( :nome, :palestrante, :data, :hora ) ";

			    $query_params = array(
			    	':nome' => $_POST['nome'];
			    	':palestrante' => $_POST['palestrante'];
			    	':data' => $_POST['data'];
			    	':hora' => $_POST['hora'];
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