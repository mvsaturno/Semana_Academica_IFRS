<?php 

session_start('1');
unset($_SESSION['lgn']);
unset($_SESSION['Nome']);
unset($_SESSION['Matricula']);
unset($_SESSION['Email']);

header('Location: index.php');

 ?>