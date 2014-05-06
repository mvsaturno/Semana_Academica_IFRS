<?php 

//Remover acentos dos nomes:
function trataStr($str){
    
    $chars = array(
                   '/(ÁÀÂÃ)/',
                   '/(á|à|â|ã|ª)/',
                   '/(É|È|Ê)/',
                   '/(é|è|ê)/',
                   '/(Í|Ì|Î)/',
                   '/(í|ì|î)/',
                   '/(Ó|Ò|Ô|Õ)/',
                   '/(ó|ò|ô|õ|º)/',
                   '/(Ú|Ù|Û|Ü)/',
                   '/(ú|ù|û|ü)/',
                   '/ç/',
                   '/Ç/',
                   '/ñ/',
                   '/Ñ/'
                  );
    
    $chars2 = array('A', 'a', 'E', 'e', 'I',
                    'i', 'O', 'o', 'U', 'u',
                    'c', 'C', 'n', 'N'
                   );
    
    $str = preg_replace($chars, $chars2, $str);
    
    return $str;
}

	//Formulario para registro de eventos e usuários:
	require_once("config.inc.php");

	if (!empty($_POST)) {
		
		$tipo = $_POST['tipo'];

		if ($tipo == 'aluno') {
			
				if (empty($_POST['nome']) || empty($_POST['senha']) || empty($_POST['email']) || empty($_POST['matricula']) ) {  
			        //Se algum dos parametros veio vazio, tem q dar erro e retornar a seguinte mensagem:
			        // $response["success"] = 0;
			        $response["message"] = "Nome, Senha, Matricula ou Email não informado!";
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
			        $response["success"] = 0;
			        $response["message"] = "Erro ao verificar no Banco! Verifique a conexão";
			        echo '<p align="center">ERRO!<BR/>' . $response["message"] . '<BR><a href="javascript:history.back(1);">Voltar</a></p>';
					die();
		    	}
		    	//Aqui verificamos se o nome bate com a matrícula informada. Se não bater, dá erro:
		    	$row = $stmt->fetch(PDO::FETCH_ASSOC);

		    	$resultado = strcasecmp($row['NomeAluno'], trataStr($_POST['nome']));

		    	echo "Resultado: ";
		    	echo $resultado;

			    if ( $resultado !== 0 ) {
			        $response["success"] = 0;
			        $response["message"] = "Nome Incorreto. Verifique o nome informado. Em caso de problemas mande um email para a organização do evento!";
			        echo '<p align="center">ERRO!<BR/>' . $response["message"] . '<BR><a href="javascript:history.back(1);">Voltar</a></p>';
					die();
			    }
			    //Aqui finalmente verificamos se o aluno já possui email ou senha, o que significa que ele já foi cadastrado no sistema:
			    $email_verif = empty($row['EmailAluno']);
			    $senha_verif = empty($row['SenhaAluno']);

			    if (!$email_verif || !$senha_verif) {
					$response["success"] = 0;
			        $response["message"] = "Usuário já cadastrado!";
			        echo '<p align="center">ERRO!<BR/>' . $response["message"] . '<BR><a href="javascript:history.back(1);">Voltar</a></p>';
					die();

			    }
			    //Caso passe em todos os testes, alteramos a tabela e inserimos os dados:

			    //Mas primeiro, fazemos a encriptação da senha (e o SALT):

			    $passwd = $_POST['senha'];

			    $pre_salt = md5(uniqid(rand(),true));
			    $salt = substr($pre_salt, 0, 6);
			    $encrypted = hash("sha256", $passwd . $salt);
			    
			    $query = "UPDATE Aluno SET EmailAluno=:email, SenhaAluno=:senha, Salt=:salt WHERE MatriculaAluno=:matricula ";

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
			        echo '<p align="center">ERRO!<BR/>' . $response["message"] . '<BR><a href="javascript:history.back(1);">Voltar</a></p>';
					die();
			    }

			    $response["success"] = 1;
	    		$response["message"] = "Aluno cadastrado com sucesso!";
	    		echo '<p align="center">' . $response["message"] . '<BR><a href="login.php">Voltar</a></p>';

		} else if ($tipo == 'evento') {


				if (empty($_POST['nome']) || empty($_POST['palestrante']) ) {  
			        //Se algum dos parametros veio vazio, tem q dar erro e retornar a seguinte mensagem:
			        $response["success"] = 0;
			        $response["message"] = "Nome do palestrante ou Nome do Evento não informados!";
			        echo '<p align="center">ERRO!<BR/>' . $response["message"] . '<BR><a href="javascript:history.back(1);">Voltar</a></p>';
					die(); //Se der erro, o script morre aqui
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
			        echo '<p align="center">ERRO!<BR/>' . $response["message"] . '<BR><a href="javascript:history.back(1);">Voltar</a></p>';
					die();
				}

				$row = $stmt->fetch();
			    if ($row) {
			        $response["success"] = 0;
			        $response["message"] = "Evento já cadastrado!";
			        echo '<p align="center">ERRO!<BR/>' . $response["message"] . '<BR><a href="javascript:history.back(1);">Voltar</a></p>';
					die();
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
			        echo '<p align="center">ERRO!<BR/>' . $response["message"] . '<BR><a href="javascript:history.back(1);">Voltar</a></p>';
					die();
			    }

			    $response["success"] = 1;
	    		$response["message"] = "Evento cadastrado com sucesso!";
	    		echo json_encode($response);
		}
	}

?>