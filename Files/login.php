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
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="css/norm_grid.min.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>

<h1>Faça seu login pra continuar:</h1>

<div id="form">

<form id="login" name="valida_login" class="col-1-1" method="POST" action="valida_login.php">

	<label for="matricula">Informe sua Matricula (apenas números): </label>
	<input required type="matricula" id="matricula" name="matricula" placeholder="0000000" />

	<label for="senha">Senha:</label>
	<input required type="password" id="senha" name="senha" placeholder="Senha" /> 
	
	<input type="hidden" name="tipo" value="login" /> 
	<input type="submit" value="Entrar" /> 

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