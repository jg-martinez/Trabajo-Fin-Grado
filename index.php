<?php
session_start();
$lang=substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);
switch ($lang){
	case "es":
		$_SESSION['lg'] = $lang;
		include ("idioma/".$lang.".php");
		break;	
	default:
		$lang = "en";
		$_SESSION['lg'] = $lang;
		include ("idioma/".$lang.".php");
		
}?>
<!-- 
   index.htm
   ------------------------------------------------------------------------------------------------
   Pagina de inicio. Se realiza la autenticacion.

-->
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $titulo_ppal ?></title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="CSS/portada.css">
		<link href='http://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<div id="pagina_acceso">
			<div id="cabecera_principal">
				<hgroup>
				<h1><?php echo $titulo_ppal ?></h1>
				<h2><?php echo $titulo_secund ?></h2>
			</hgroup>
			</div>
			<div id="formulario_acceso">
				<form action="index2.php" method="post">
					<input type="text" name="nombre" placeholder=<?php echo $titulo_user ?>><br />
					<input type="password" name="passwd" placeholder=<?php echo $titulo_pwd ?>><br />
					<input type="submit" value="<?php echo $boton_iniciar_sesion ?>">
				</form>
			</div>
		</div>
	</body>
</html>