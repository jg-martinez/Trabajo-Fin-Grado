<?php 
   session_start();header('Content-Type: text/html; charset=utf-8');ini_set("session.cookie_httponly", 1);

   include ("../comun/permisos.php");
   
    if(isset($_GET['lg']))
	{
		$lg = $_GET['lg'];
		$_SESSION['lg'] = $lg;
		include ("../idioma/".$lg.".php");
	}
	else if(isset($_SESSION['lg']))
	{
		$lg = $_SESSION['lg'];
		include ("../idioma/".$lg.".php");
	}
?>
<!-- 
    Pagina que muestra el menu izquiero de operaciones sobre el glosario
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Calíope</title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="../CSS/menu_glosario.css">
	<meta http-equiv=Content-Type content="text/html; charset=windows-utf-8">
	<meta content="MSHTML 6.00.2800.1498" name=GENERATOR>

   <script type="text/JavaScript">

	function check_datos(data)
	{    
	
	   // Comprobar TERMINO (no vacio)
	
	   if(data.palabra.value == "" ) 
	   {
	      alert("<?php echo $term_vacio ?>");
		  return false;
	   }
	
	   // Si se han pasado todas las comprobaciones el formulario es valido
	
	   return true;
	}
	
	function check_datos2(data)
	{    
	
	   // Comprobar TERMINO (no vacio)
	
	   if(data.palabra.value == "" ) 
	   {
		  alert("<?php echo $mal_categoria_gramatical ?> \n- <?php echo $sustantivo ?> \n- <?php echo $verbo ?>  \n- <?php echo $determinante ?> \n- <?php echo $adjetivo ?> \n- <?php echo $pronombre ?> \n- <?php echo $preposicion ?> \n- <?php echo $conjuncion ?> \n- <?php echo $adverbio ?> \n- <?php echo $interjeccion ?>".toLowerCase());
	      //alert("<?php echo $term_vacio ?>");
		  return false;
	   }
	   else if (data.palabra.value == "sustantivo" || data.palabra.value == "sustantivo/verbo" || data.palabra.value == "sustantivo/adjetivo" ||
				data.palabra.value == "verbo" || data.palabra.value == "determinante" || data.palabra.value == "adjetivo" || data.palabra.value == "pronombre" ||
				data.palabra.value == "preposici\xF3n" || data.palabra.value == "conjunci\xF3n" || data.palabra.value == "adverbio" || data.palabra.value == "interjecci\xF3n")
		{
			return true;
		}
		else
		{
			alert("<?php echo $mal_categoria_gramatical ?> \n- <?php echo $sustantivo ?> \n- <?php echo $verbo ?>  \n- <?php echo $determinante ?> \n- <?php echo $adjetivo ?> \n- <?php echo $pronombre ?> \n- <?php echo $preposicion ?> \n- <?php echo $conjuncion ?> \n- <?php echo $adverbio ?> \n- <?php echo $interjeccion ?>".toLowerCase());
			return false;
		}
		
		
	   // Si se han pasado todas las comprobaciones el formulario es valido
	
	   return true;
	}

   </script>
</head>
<body>

<?php 
   if(tienePermisos("glosariomenu"))
   {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<!--
   <tr>
      <td align="left" bgcolor="#FFFF99"><img border="0" src="../imagenes/esq_sup_i.gif" height="22" width="22"></td>
      <td align="right" bgcolor="#FFFF99"><img border="0" src="../imagenes/esq_sup_d.gif" height="22" width="22"></td>
   </tr>
 -->
   <tr><td colspan="2" bgcolor="#FFFFFF">&nbsp;</td></tr>
   <tr>
      <td colspan="2" align="center" valign="top" bgcolor="#FFFFFF">
        <p id="titulo"><?php echo $busqueda_inicial ?></p>
        <table border="0">
            <!-- listado de las letras del abecedario -->
		  <tr>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=a" target="resultado">A</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=b" target="resultado">B</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=c" target="resultado">C</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=d" target="resultado">D</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=e" target="resultado">E</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=f" target="resultado">F</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=g" target="resultado">G</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=h" target="resultado">H</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=i" target="resultado">I</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=j" target="resultado">J</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=k" target="resultado">K</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=l" target="resultado">L</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=m" target="resultado">M</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=n" target="resultado">N</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=%F1" target="resultado">&Ntilde;</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=o" target="resultado">O</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=p" target="resultado">P</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=q" target="resultado">Q</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=r" target="resultado">R</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=s" target="resultado">S</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=t" target="resultado">T</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=u" target="resultado">U</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=v" target="resultado">V</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=w" target="resultado">W</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=x" target="resultado">X</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=y" target="resultado">Y</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=z" target="resultado">Z</a></font></td>
		    <td align="center" colspan="3" bgcolor="#FFFFFF"><a href="resultado.php?inicial=todas" target="resultado">(<?php echo $todos ?>)</a></td>
		  </tr>
        </table>
      </td>
   </tr>
   <tr>
      <td colspan="2" bgcolor="#FFFFFF" align="center">
	    <form action="resultado.php" target="resultado" method="post" name="formulario" onSubmit='return check_datos(formulario);'>
		  <p id="titulo"><?php echo $busqueda_termino ?></p>
		  <table width="100%" border="0" bgcolor="#FFFFFF">
		     <tr>
		        <td align="center">
		        	<input name="palabra" size="26" title="<?php echo $termino_buscar ?>">&nbsp;&nbsp;
		        	<input type="submit" value="Buscar">
		        </td>
		     </tr>
	      </table>
	    </form>
	 </td>
   </tr>
   <tr>
		<td colspan="2" bgcolor="#FFFFFF" align="center">
			<form action="resultado.php" target="resultado" method="post" name="form" onSubmit='return check_datos2(form);'>
				<input type="hidden" name="inicial" value="cat_gram">
				<p id="titulo"><?php echo $busqueda_categoria_gramatical ?></p>
				<table width="100%" border="0" bgcolor="#FFFFFF">
					<tr>
						<td align="center">
							<select name="palabra" size="1" title="<?php echo $categoria_gramatical_buscar ?>">
								<option value="sustantivo"><?php echo $sustantivo_select ?></option>
								<option value="verbo"><?php echo $verbo_select ?></option>
								<option value="determinante"><?php echo $determinante_select ?></option>
								<option value="adjetivo"><?php echo $adjetivo_select ?></option>
								<option value="pronombre"><?php echo $pronombre_select ?></option>
								<option value="preposicion"><?php echo $preposicion_select ?></option>
								<option value="conjuncion"><?php echo $conjuncion_select ?></option>
								<option value="adverbio"><?php echo $adverbio_select ?></option>
								<option value="interjeccion"><?php echo $interjeccion_select ?></option>
							</select>
							<!-- <input name="palabra" size="26" title="<?php echo $categoria_gramatical_buscar ?>"> -->&nbsp;&nbsp;
							<input type="submit" value="Buscar">
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
	<tr>	
<?php 
	  if(tienePermisos("glosariomenuadmin"))
	  {
?>
		<td colspan="2" align="center">
			<input id="admin_termin" type="button" value=" <?php echo $administrar_terminos ?> " onclick="parent.frames['resultado'].document.location='resultado.php?inicial=admin_termino';"/>
			<input type="button" value=" <?php echo $administrar_relaciones ?> " onclick="parent.frames['resultado'].document.location='resultado.php?inicial=admin_relacion';"/>
			<input type="button" value=" <?php echo $administrar_tipo_relaciones ?> " onclick="parent.frames['resultado'].document.location='resultado.php?inicial=admin_tipo_relacion';"/>
		</td>
<?php 
      }
?>
	</tr>
   <tr><td colspan="2" bgcolor="#FFFFFF">&nbsp;</td></tr>
<!--    <tr>
      <td align="left" bgcolor="#FFFF99"><img border="0" src="../imagenes/esq_inf_i.gif" height="22" width="22"></td>
      <td align="right" bgcolor="#FFFF99"><img border="0" src="../imagenes/esq_inf_d.gif" height="22" width="22"></td>
   </tr>
 -->
</table>
<?php 
   }
   else
   {
	   echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   }
?>
</body>
</html>
