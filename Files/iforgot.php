<?php 

session_start('1');
if (isset($_SESSION['lgn'])) {
	if ($_SESSION['lgn'] == 'abrolhos!') {
		header('Location: main.php');
	} 
} else {
?>
<html>
<head>
	<meta charset="utf8">
	<title>Recuperar Senha</title>
	<link rel="stylesheet" type="text/css" href="css/norm_grid.min.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>

<h1>Informe seus dados:</h1>

<div id="form">

<form id="login" name="recupera_senha" class="col-1-1" method="POST" action="valida_login.php">

	<label for="nome">Nome Completo(sem abreviações):</label>
	<input required type="text" id="nome_aluno" name="nome" placeholder="Seu Nome" />

	<label for="matricula">Número de Matrícula (apenas numeros):</label>
	<input required type="text" id="matricula" name="matricula" placeholder="0000000" />

	<label for="email">Email:</label>
	<input required type="email" id="email" name="email" placeholder="email@provedor.com" />

	<input type="hidden" name="tipo" value="rec_senha" /> 
	<input type="submit" value="Recuperar" /> 

</form>

</div>

<div id="cadastro">
<label>Não fez seu login?</label>
<a href="form_cadastro.php"><button>Cadastro</button></a>
</div>

</body>
</html>
<?php 
	}
 ?>