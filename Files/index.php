<?php 

	session_start('1');
if ($_SESSION['lgn'] == 'abrolhos!') {
	header('Location: main.php');
} else {
	header('Location: login.php');
}

 ?>