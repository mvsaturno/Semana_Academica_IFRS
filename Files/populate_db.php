<?php 

require("config.inc.php");

$arq = "matricula_aluno.json";

$json = json_decode(file_get_contents($arq), TRUE);

echo "Saída do Json:<br /> ";
// var_dump($json["Alunos"]);

 print_r($json["Alunos"]);

foreach ($json['Alunos'] as $aluno) {
	// print_r($aluno["Matricula"]);
	// print_r($aluno["Aluno"]);

	$query = "INSERT INTO Aluno ( NomeAluno, MatriculaAluno ) values( :nome, :matricula ) ";
				$nome =  $aluno['Aluno'];
				$matr = $aluno['Matricula'];
			    $query_params = array(
			    	':nome' => $nome,
			    	':matricula' => $matr
			    	);

			    try {
			    	$stmt = $db->prepare($query);
			    	$result = $stmt->execute($query_params);
			    } catch (PDOException $ex) {
			    	$response["success"] = 0;
			        $response["message"] = "Erro Ao inserir! Verifique a conexão";
			        $response["aluno"] = $query_params;
			        $response["error"] = $ex->getMessage();
			        die(json_encode($response));
			    }

			    $response["success"] = 1;
	    		$response["message"] = "Aluno cadastrado com sucesso!";
	    		echo json_encode($response);
}

 ?>