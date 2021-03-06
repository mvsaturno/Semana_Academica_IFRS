<html>
<head>
	<title>Formulário cadastro de usuários e Eventos</title>
	<meta charset="utf8">
	<link rel="stylesheet" type="text/css" href="css/norm_grid.min.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<script type="text/javascript">

	//*Opção de colocar os palestrantes em uma tabela separada e fazer o 'confere' quando o usuário começar a digitar, se já tem esse palestrante no banco

	//proibir o ctrl+c nos campos email2 e senha2
	function disableCtrlKeyCombination(e)
	{
		//list all CTRL + key combinations you want to disable
		var forbiddenKeys = new Array('v');
		var key;
		var isCtrl;

		if(window.event)
		{
			key = window.event.keyCode;     //IE
			if(window.event.ctrlKey)
	                        isCtrl = true;
	                else
	                        isCtrl = false;
		}
		else
		{
			key = e.which;     //firefox
			if(e.ctrlKey)
				isCtrl = true;
			else
				isCtrl = false;
		}

		//if ctrl is pressed check if other key is in forbidenKeys array
		if(isCtrl)
		{
			for(i=0; i<forbiddenkeys .length; i++)
			{
				//case-insensitive comparation
				if(forbiddenKeys[i].toLowerCase() == String.fromCharCode(key).toLowerCase())
				{
					alert('Key combination CTRL + '
						+String.fromCharCode(key)
						+' has been disabled.');
					return false;
				}
			}
		}
		return true;
	}

	//fazer o preventDefault() dos inputs

	//Validar os forms
		//Comparar se o email e a senha bate nos dois campos
		//ver se tem data e hora

	//criptpgrafar a senha antes de mandar (md5 e salt):
	function submitLogin(md5KeyValue)
	{       
	    var password= document.quickLookForm.password.value;
	    var md5keystring = md5KeyValue;//document.quickLookForm.md5key.value ;
	    var encSaltPass=encryptLoginPassword(md5keystring,password);
	    document.quickLookForm.password.value=encSaltPass;
	    document.quickLookForm.submit();
	}

	function encryptLoginPassword(key,strpwd){
	    var password=strpwd;
	    var enc =MD5(password)+"#"+key);
	    return enc;
	} 
	////Exemplo de input pra essas funções;
	////<input  type="submit" class="button_class" value="Login" title="Login" id="Button2" tabindex="3" onClick = "return submitLogin('abcde');">

	//enviar o por post pro script cadastros.php

	</script>
</head>
<body class="grid">

<form id="aluno" name="cadastra_aluno" class="col-1-1" method="POST" action="cadastros.php">

	<label for="nome">Nome Completo(sem abreviações):</label>
	<input required type="text" id="nome_aluno" name="nome" placeholder="Seu Nome" />

	<label for="matricula">Número de Matrícula (apenas numeros):</label>
	<input required type="text" id="matricula" name="matricula" placeholder="0000000" />

	<label for="email">Email:</label>
	<input required type="email" id="email" name="email" placeholder="email@provedor.com" />

	<label for="email2">Repita seu email:</label>
	<input required type="email2" id="email2" name="email2" placeholder="" />

	<label for="senha">Senha:</label>
	<input required type="password" id="senha" name="senha" placeholder="Senha" />

	<label for="senha2">Repita sua senha:</label>
	<input required type="password" id="senha2" name="senha2" placeholder="" />

	<input type="hidden" name="tipo" value="aluno" />

	<input type="submit" value="Cadastrar" />

</form>

</body>
</html>