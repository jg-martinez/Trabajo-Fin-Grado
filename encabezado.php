<?php 
   session_start();header('Content-Type: text/html; charset=utf-8');ini_set("session.cookie_httponly", 1);
   
   include ("comun/permisos.php");
   
   if(isset($_GET['lg']))
	{
		$lg = $_GET['lg'];
		$_SESSION['lg'] = $lg;
		include ("idioma/".$lg.".php");
	}
	else if(isset($_SESSION['lg']))
	{
		$lg = $_SESSION['lg'];
		include ("idioma/".$lg.".php");
	}
?>
<!-- 
   encabezado.htm
   ------------------------------------------------------------------------------------------------
   Encabezado de la pagina principal para un usuario tipo ADMINISTRADOR. Incluye los menus de 
   acceso a los distintos apartados.
-->
<!DOCTYPE html>
<html>
<head>
	<title>Calíope</title>
	<!--<link rel="stylesheet" type="text/css" href="comun/estilo.css">-->
	<link rel="stylesheet" type="text/css" href="CSS/principal.css">
	<meta http-equiv="Content-Language" content="es">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
	<meta name="ProgId" content="FrontPage.Editor.Document">
    <link href='http://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
	<title>$titulo_ppal</title>
	<script src="comun/scripts.js"></script>
	<script type="text/javascript" src="comun/jquery.js"></script>
	<script type="text/javascript"> 
		$(document).ready(function() { 
			$('.menuOpciones > li').bind('mouseover', openSubMenu);
			$('.menuOpciones > li').bind('mouseout', closeSubMenu);
			function openSubMenu() { $(this).find('ul').css('visibility', 'visible'); };	
			function closeSubMenu() { $(this).find('ul').css('visibility', 'hidden'); };		
		}); 
	</script>
<!-- <base target="principal"> -->
</head>
<body>
	<header>
		<div id="titulo">
			<h1>Calíope</h1>
		</div>
		<div id="Cerrar_Sesion">
			<form action="cerrar_sesion.php">
				<input type="submit" value="<?php echo $salir ?>">
			</form>
		</div>
    </header>

<?php 
    // se comprueba si tiene permisos para mostrar el menu principal
   if(tienePermisos("encabezado"))
   {
   
    $noactivo = 0;    
?>

 <nav>
	<ul class="list-nav">
<?php 
      // se comprueba si tiene permisos de usuario o administrador para mostrar un menú u otro
      if(tienePermisos("menuadminusuarios"))
      {
?>
		<li> <?php echo $administrar_sistema ?>
			<ul>
				<li><a href="./usuario/menu_admin_usuario.php" target="principal"><?php echo $administrar_usuarios ?></a></li>
				<li><a href="historico/vista_listado.php" target="principal"><?php echo $administrar_historico ?></a></li>

			</ul>
		</li>
<?php 
      }
      else
      {
             $noactivo++;
      }

	  if(tienePermisos("historico"))
      {

      }
      else
      {
             $noactivo++;
      }  
    
      if(tienePermisos("menuadmintextos"))
      {
?>
		<li> Administrar Textos
			<ul>
				<li><a href="./texto/textos/menu_admin_textos.php" target="principal"><?php echo $textos ?></a></li>
				<li><a href="./texto/tipo/menu_admin_tipo.php" target="principal"><?php echo $tipo_de_texto ?></a></li>
				<li><a href="./texto/campo/menu_admin_campo.php" target="principal"><?php echo $campo_texto ?></a></li>
				<li><a href="./texto/fuente/menu_admin_fuente.php" target="principal"><?php echo $fuente_texto ?></a></li>

			</ul>
		</li>
<?php 
      }
      else
      {
             $noactivo++;
      }
      
      if(tienePermisos("menuconsultacorpus"))
      {
?>
		<li> Corpus
			<ul>
				<li><a href="./corpus/lista/menu_lista.php" target="principal"><?php echo $lista_palabras ?></a></li>
				<li><a href="./corpus/concord/menu_concordancias.php?concord=Buscar Concordancias" target="principal"><?php echo $concordancias ?></a></li>
				<li><a href="./corpus/concord/menu_colocaciones.php?concord=Buscar Colocaciones" target="principal"><?php echo $coocurrencias ?></a></li>
				<li><a href="./corpus/visualizar/menu_visualizar.php?idioma=todos" target="principal"><?php echo $ver_textos ?></a></li>
			</ul>
		</li>
	<!--</ul>-->
	  
    <!--</td>-->
<?php 
      }
      else
      {
             $noactivo++;
      }
      
      if(tienePermisos("administrarglosario"))
      {
?>
		<li> Administrar Glosario
			<ul>
				<li><a href="./glosario/resultado.php?inicial=admin_termino" target="principal"><?php echo $admin_terminos ?></a></li>
				<li><a href="./glosario/resultado.php?inicial=admin_relacion" target="principal"><?php echo $admin_relaciones ?></a></li>
				<li><a href="./glosario/resultado.php?inicial=admin_tipo_relacion" target="principal"><?php echo $admin_coocurrencias ?></a></li>
			</ul>
		</li> 

<?php 
      }
      else
      {
             $noactivo++;
      }

      if ($noactivo > 0)
      {
         $tamano = 20*$noactivo;
?>
<?php 
      }
?>
      <li> <a href="./glosario/menu.php" target="principal">&nbsp;<?php echo $consultar_glosario ?></a></li>
		<li> <?php echo $opciones ?>
			<ul>
				<!--<li><a href="cerrar_sesion.php" target="_top"><img border="0" src="imagenes/bullet2.gif"><?php echo $salir ?></a></li>-->
				<li><a href="./usuario/menu_admin_usuario.php" target="principal"><?php echo $config_cuenta ?></a></li>
				<!-- Ambas banderas en la misma línea-->
				<li><a href="JavaScript:refrescarFrames('esp', '<?php  echo $_SESSION['application_url']; ?>')"><img src="imagenes/bandera_esp.gif" border="0" title=<?php echo $espanol ?>></a></li>
				<li><a href="JavaScript:refrescarFrames('ing', '<?php  echo $_SESSION['application_url']; ?>')"><img src="imagenes/bandera_ing.gif" border="0" title=<?php echo $ingles ?> ></a></li>
			</ul>
		</li>
  </ul>
</nav>

<?php 
   }
   else
   {
      echo "<p class=\"Alerta\"><img border=\"0\" src=\"imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   }  
?>
<?php 
if (isset($_GET['refrescar_frame'])){
	if ($_GET['refrescar_frame'] == "S") {
?>
	<script>
		parent.principal.document.location.reload();
	</script>
<?php
	}
}	//del isset
?>
</body>
</html>
