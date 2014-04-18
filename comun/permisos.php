<?php
	$permisos=array();
	$permisosDescripcion=array();
	
	// Permisos para administracion de usuarios.
	$permisos["encabezado"]="#admin#usuarioavanzado#usuario#";
	// Descripciones de permisos.
	$permisosDescripcionEsp[]="Administrador";
	$permisosDescripcionEsp[]="Usuario Avanzado";
	$permisosDescripcionEsp[]="Usuario";
	
	// Descripciones de permisos.
	$permisosDescripcionIng[]="Administrator";
	$permisosDescripcionIng[]="Advanced User";
	$permisosDescripcionIng[]="User";

	// Permisos para administracion de textos.
	$permisos["menuadmintextos"]="#usuarioavanzado#";

	// corpus
	$permisos["corpusmenu"]="#admin#usuarioavanzado#usuario#";
	$permisos["menuconsultacorpus"]="#admin#usuarioavanzado#usuario#";
	
	// corpus -> concordancias
	$permisos["corpusconcordanciasmenu"]="#admin#usuarioavanzado#usuario#";
	$permisos["corpusconcordanciasvisualizartexto"]="#admin#usuarioavanzado#usuario#";
	$permisos["corpusconcordanciaslista"]="#admin#usuarioavanzado#usuario#";

	// corpus -> lista
	$permisos["corpusvertextoentero"]="#admin#";
	$permisos["corpuslistavisualizartexto"]="#admin#usuarioavanzado#usuario#";
	$permisos["corpuslistaoperacion"]="#admin#usuarioavanzado#usuario";
	$permisos["corpuslistamenu"]="#admin#usuarioavanzado#usuario#";
	
	// corpus -> visualizar
	$permisos["corpusvisualizarmenu"]="#admin#usuarioavanzado#usuario#";
	$permisos["corpusvisualizaroperacion"]="#admin#usuarioavanzado#usuario#";

	// glosario
	$permisos["glosariomenu"]="#admin#usuarioavanzado#usuario#";
	$permisos["glosariomenuadmin"]="#admin#usuarioavanzado#";
	$permisos["glosariomenuacceso"]="#admin#usuarioavanzado#usuario#";
	$permisos["glosariooperacion"]="#admin#usuarioavanzado#usuario#";
	$permisos["glosariooperacionnuevo"]="#admin#usuarioavanzado#";
	$permisos["glosariooperacionmodificar"]="#admin#usuarioavanzado#";
	$permisos["glosariooperacioneliminar"]="#admin#usuarioavanzado#";
	$permisos["glosariooperacion2"]="#admin#usuarioavanzado#";
	$permisos["glosarioresultado"]="#admin#usuarioavanzado#usuario#";
	$permisos["glosarioresultadoadmin"]="#admin#usuarioavanzado#";
	$permisos["glosarioresultadousuario"]="#usuario#";
	
	// texto
	$permisos["textomenuadmin"]="#usuarioavanzado#";
	
	// texto -> campo
	$permisos["textocampomenuadmin"]="#admin#usuarioavanzado#";
	$permisos["textocampooperacion"]="#admin#usuarioavanzado#";
	$permisos["textocampooperacion2"]="#admin#usuarioavanzado#";
	
	// texto -> fuente
	$permisos["textofuentemenuadmin"]="#admin#usuarioavanzado#";
	$permisos["textofuenteoperacion"]="#admin#usuarioavanzado#";
	$permisos["textofuenteoperacion2"]="#admin#usuarioavanzado#";
	
	// texto -> textos
	$permisos["textotextosmenuadmin"]="#admin#usuarioavanzado#";
	$permisos["textotextosoperacion"]="#admin#usuarioavanzado#";
	$permisos["textotextosoperacion2"]="#admin#usuarioavanzado#";
	
	// texto -> tipo
	$permisos["textotipomenuadmin"]="#admin#usuarioavanzado#";
	$permisos["textotipooperacion"]="#admin#usuarioavanzado#";
	$permisos["textotipooperacion2"]="#admin#usuarioavanzado#";
	
	// usuario
	$permisos["usuariomenuadmin"]="#admin#usuarioavanzado#usuario#";
	$permisos["menuadminusuarios"]="#admin#";
	$permisos["usuariooperacionnuevo"]="#admin#";
	$permisos["usuariooperacionmodificar"]="#admin#";
	$permisos["usuariolistar"]="#admin#";
	$permisos["usuariooperacioncambiarcontrasena"]="#admin#usuarioavanzado#usuario#";
	$permisos["nuevousuario"]="#admin#";
	
	// Permisos sobre glosario
	$permisos["menuconsultaglosario"]="#admin#usuarioavanzado#usuario#";
	$permisos["administrarglosario"]="#usuarioavanzado#";
	
	// Permisos para el buscador de textos y sus columnas especiales
	$permisos["buscadorespecial"]="#admin#";
	
	$permisos["historico"] = "#admin#"; // Permisos para las operaciones con historicos (listado y borrado)
	$_SESSION["permisos"] = $permisos;

	function tienePermisos($seccion)
/*-------------------------------------------------------------------------------------------------
  Funcion de comprobacion de permisos.

  ENT:  - $perm_array: Array de permisos.
		- $seccion: Seccion para la que se comprueban los permisos
  SAL: true/false, segun tenga o no permisos.
-------------------------------------------------------------------------------------------------*/
	{
		return (($_SESSION['estado']!="") && (preg_match("/#" . $_SESSION['estado'] . "#/",$_SESSION["permisos"][$seccion])));
	}
?>