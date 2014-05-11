<?php 

	session_start('1');
	
	if ($_SESSION['lgn'] != 'abrolhos!') {

		header('Location: login.php');

	} else {

?>

<html>
<head>
	<title>Formulário cadastro de usuários e Eventos</title>
	<meta charset="utf8">
	<link rel="stylesheet" type="text/css" href="css/norm_grid.min.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	</head>
<body class="grid">

<h1> Bem Vindo(a) <?php echo $_SESSION['Nome']; ?>! </h1>

<a href="logout.php" onclick="">Log Out</a>

<?php 

if ($_SESSION['Matricula'] == '0871117' || $_SESSION['Matricula'] == '0724114' || $_SESSION['Matricula'] == '0724114' || $_SESSION['Matricula'] == '0875112') {
	?>

	<a href="form_evento.php"><button>Cadastrar Evento!</button></a>

<?php
}

 ?>

</body>
</html>

<?php
}
?>