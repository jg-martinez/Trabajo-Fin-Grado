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
     Pagina que muestra los resultados de las operaciones sobre el glosario de terminos
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>

<head>
	<title>Calíope</title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="../CSS/resultado.css">
	<!--<link rel="stylesheet" type="text/css" href="../comun/estilo.css">--> 
	<meta http-equiv=Content-Type content="text/html; charset=utf-8">
	<meta content="MSHTML 6.00.2800.1498" name=GENERATOR>

<script type="text/JavaScript">

function check_datos_term(data)
{    

   // Comprobar TERMINO (no vacio)

   if(data.termino.value == "" ) 
   {
      alert("<?php echo $term_vacio ?>");
	  return false;
   }

   // Si se han pasado todas las comprobaciones el formulario es valido

   return true;
}

function check_datos_rel(data)
{    

   // Comprobar TERMINO(no vacio)

    if(data.termino1.value == "" || data.termino2.value == "") 
   {
      alert("<?php echo $aviso ?>");
	  return false;
   }

   //if(data.particula.value == "" ) 
   //{
     // alert("<?php echo $aviso1 ?>");
	  //return false;
   //}

   // Comprobar que se ha seleccionado una relacion

   if(data.tipo.options[data.tipo.selectedIndex].value == "")
   {
      alert("<?php echo $aviso7 ?>");
	  return false;
   }

   // Si se han pasado todas las comprobaciones el formulario es valido

   return true;
}

</script>

</head>

<body>

<?php 
   if(tienePermisos("glosarioresultado"))
   {
      include("func_glosario.php");
	
      if (isset($_GET['inicial']))
	  {
		$inicial = $_GET['inicial'];
	  }
	  else
	  {
		if (isset($_POST['inicial']))
		{
			$inicial = $_POST['inicial'];
		}
		else
		{
			$inicial = '';
		}
	  }

      if($inicial != '')
	  {
		if($inicial == 'admin_termino') //-- ADMINISTRACION DE TERMINOS DE GLOSARIO ------------------------
		 {
			if(isset($_GET['termino']))
			{
				$termino_nuevo = $_GET['termino'];
			}
			else
			{
				$termino_nuevo = "";
			}
?>

<p align="center"><span class="titulo titulo_rojo"><?php echo $administracion_terms_glosario ?></span><br></br>
<img border="0" src="../imagenes/linea_horiz.gif"></p>


<form action="operacion_glosario.php" method="post" name="form_term" onSubmit='return check_datos_term(form_term);'>
	<input name="arg_op" type="hidden" value="nuevo">
<table border="0" cellspacing="10" cellpadding="5" align="center">
  <tr>
    <td bgcolor="#000066" width="100" align="center">
    	<span style="font-family:Book Antigua;font-size:28pt;font-weight:bold;color:white;">1</span><br><span style="font-family:Book Antigua;font-size:16pt;color:white;"><?php echo $new ?><br><?php echo $term ?></span>
    </td>
    <td style="border: 1 solid #CCCCCC" align="center"><?php echo $escriba_nomb_new_term ?>:<br><br>
<?php
	if ($termino_nuevo == "")
	{
?>
       <input type="text" name="termino" size="26" class="obligatorio" title="<?php echo $term ?>"><br><br>
<?php
	}
	else
	{
?>
		<input type="text" name="termino" size="26" value="<?php echo $termino_nuevo ?>" class="obligatorio" title="<?php echo $term ?>"><br><br>
<?php
	}
?>
       <?php echo $idioma ?>:&nbsp;&nbsp;
       <select name="idioma" title="Idioma">
	     <option value="esp"><?php echo $espanol ?></option>
	     <option value="ing"><?php echo $ingles ?></option>
       </select><br><br>
       <input type="submit" class="boton long_93 boton_aceptar" value="      <?php echo $boton_aceptar ?> " />
    </td>
  </tr>
  <tr>
   <td bgcolor="#000066" width="100" align="center">
	<span style="font-family:Book Antigua;font-size:28pt;font-weight:bold;color:white;">2</span><br><span style="font-family:Book Antigua;font-size:16pt;color:white;"><?php echo $modif ?>/<br><?php echo $elim ?><br><?php echo $term ?></span>
   </td>
   <td style="border: 1 solid #CCCCCC" align="center">
     <?php echo $aviso3 ?>:
     <p align="center">
		<table style="border: 1 dotted #800000" bgcolor="#FFFF99">
		  <tr>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=a">A</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=b">B</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=c">C</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=d">D</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=e">E</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=f">F</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=g">G</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=h">H</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=i">I</a></font></td>
			</tr>
		  <tr>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=j">J</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=k">K</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=l">L</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=m">M</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=n">N</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=%F1">&Ntilde;</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=o">O</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=p">P</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=q">Q</a></font></td>
			</tr>
		  <tr>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=r">R</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=s">S</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=t">T</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=u">U</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=v">V</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=w">W</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=x">X</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=y">Y</a></font></td>
		    <td align="center"><font size="5"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=z">Z</a></font></td>
		  </tr>
		  </tr>
		    <td colspan="9" align="center" bgcolor="#FFFF55"><a href="operacion_glosario.php?arg_op=modif_elim&inicial=todas"><?php echo $todos ?></a></td>
		  </tr>
		</table>
     </p>
   </td>
  </tr>
</table>
</form>


<!--<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="resultado.php"><?php echo $glosario ?></a> > <u><?php echo $administrar_terminos ?></u></td>
   </tr>
</table>-->

<?php 
		 }
		 else if ($inicial == 'cat_gram') //-- BUSQUEDA POR CATEGORIA GRAMATICAL ----------
		 {
			$termino = $_POST['palabra'];
			buscar_cat_gramatical($termino);
		 }
		 else
		 {
			if($inicial == 'admin_relacion') //-- ADMINISTRACION RELACION ENTRE TERMINOS ----------
			{
			   echo "<p align=\"center\"><span class=\"titulo titulo_rojo\">".$administracion_rels_terms."</span><br>";
               echo "<img border=\"0\" src=\"../imagenes/linea_horiz.gif\" ></p>";
?>
<form action="operacion_glosario.php" method="post" name="form_rel" onSubmit='return check_datos_rel(form_rel);'>
   <input name="arg_op" type="hidden" value="relacion">

<table align="center" border="0" width="80%">
    <tr>
       <td align="center"><?php echo $term ?> 1 (<?php echo $menu_principal ?>):<br><input name="termino1" size="26" title="<?php echo $term ?> 1"></td>
       <td align="center"><?php echo $particula ?>:<br><input name="particula" size="26" title="<?php echo $particula ?>"></td>
	   <td align="center"><?php echo $term ?> 2:<br><input name="termino2" size="26" title="<?php echo $term ?> 2"></td>
    </tr>
	<tr>
		<td align="center"><?php echo $rel ?>:<br>
			<select name="tipo" title="<?php echo $tipo_rel ?>">
				<option value="" selected="selected">- Selecciona una Relaci&oacute;n -</option>
				<optgroup label="Relaciones Jer&aacute;rquicas"></optgroup>
				<optgroup label="-> Hiponimia">
<?php
				/* Consulta a la base de datos */
				include ("../comun/conexion.php");
				$consulta = "SELECT nombre_tipo FROM tipo_relacion WHERE tipo_rel = 'hiponimia'";
				$res = mysql_query($consulta);
				while($obj = mysql_fetch_object($res))
				{
					echo "<option value=".$obj->nombre_tipo.">".$obj->nombre_tipo."</option>";
				}
?>
				</optgroup>
				<optgroup label="-> Meronimia">
<?php
				/* Consulta a la base de datos */
				include ("../comun/conexion.php");
				$consulta = "SELECT nombre_tipo FROM tipo_relacion WHERE tipo_rel = 'meronimia'";
				$res = mysql_query($consulta);
				while($obj = mysql_fetch_object($res))
				{
					echo "<option value=".$obj->nombre_tipo.">".$obj->nombre_tipo."</option>";
				}
?>
				</optgroup>
				<optgroup label="Relaciones No Jer&aacute;rquicas"></optgroup>
				<optgroup label="-> De Colocaci&oacute;n (Ad Hoc)">
<?php 
				/* Consulta a la base de datos */
				include ("../comun/conexion.php");
				$consulta = "SELECT nombre_tipo FROM tipo_relacion WHERE tipo_rel = 'colocacion'";
				$res = mysql_query($consulta);
				while($obj = mysql_fetch_object($res))
				{
					echo "<option value=".$obj->nombre_tipo.">".$obj->nombre_tipo."</option>";
				}
?>
				</optgroup>
			</select>
		</td>
		<td align="center" colspan="2"><?php echo $nota ?>:<br><input name="nota" size="70" title="<?php echo $nota ?> 2"></td>
	</tr>
	<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
	<tr>
		<td align="right"><input type="submit" class="boton long_93 boton_aceptar" value="<?php echo $anadir ?>"></td>
		<td align="center" colspan="2"><input type="button" class="boton" value="Modificar/Eliminar" onclick="parent.frames['resultado'].document.form_rel.action='operacion_glosario.php?accion=modificar&arg_op=relacion'; document.form_rel.submit();"></td>
		<!-- <td align="left"><input type="button" class="boton" value="Eliminar" onclick="parent.frames['resultado'].document.location='operacion_glosario.php?accion=eliminar';"></td> -->
	</tr>
</form>

<br>

<!--<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
   <br>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="resultado.php"><?php echo $glosario ?></a> > <u><?php echo $administrar_relaciones ?></u></td>
   </tr>
</table>-->
<?php 
			}
			else 
			{
               if($inicial == 'admin_tipo_relacion')  //-- ADMINISTRACION TIPO DE RELACION -----------
			   {
?>

<p align="center"><span class="titulo titulo_rojo"><?php echo $administracion_tipo_rels ?></span><br></br>
<img border="0" src="../imagenes/linea_horiz.gif"><br><br>
</p>
<?php 
                  listar_tipos_relacion();
?>
<br>
<p align="center">
<input type="button" class="boton boton_nuevo" value="        <?php echo $new_tipo_rel ?> " onclick="document.location='operacion_glosario.php?arg_op=nuevo_tipo_relac';"/><br><br>
</p>

<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="resultado.php"><?php echo $glosario ?></a> > <u><?php echo $administracion_tipo_rels ?></u></td>
   </tr>
</table>

<?php 
			   }
			   else  //-- BUSQUEDA POR INICIAL ---------------------------------------------------- 
			   {
	              buscar_inicial($inicial);
?>
<br>

<!--<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <u><?php echo $glosario ?></u></td>
   </tr>
</table>-->
<script language="text/javascript">
	function trigger_contexto(id) {
		var el = document.getElementById(id);
		alert (className);
		el.className = (el.className == "contexto_out")?"contexto_in":"contexto_out";
		
		alert (className);
	}
</script>
<?php 
               }
            }
		 }
      }
	  else  //-- BUSQUEDA POR TERMINO -------------------------------------------------------------
	  {
	      if (isset($_POST['palabra']))
		  {
			$termino = $_POST['palabra'];
		  }
		  else
		  {
			$termino = '';
		  }
		  
		 if($termino != '')
		 {
		    buscar_termino($termino);
?>

<br>

<!--<table border="0" width="100%" style="border-top: 1 solid #FF0000">
   <tr>
      <td width="190"><img border="0" src="../imagenes/tit_principal_pie.gif"></td>
	  <td class="Pie"><a href="../principal.php"><?php echo $menu_principal ?></a> > <a href="resultado.php"><?php echo $glosario ?></A> > <u><?php echo $mostrar_termino ?></u></td>
   </tr>
</table>-->

<?php 
		 }
		 else
		 {
		    echo "<p align=center><font size=5 color=#000088>".$bienvenido_glosario."<br>";
			echo "<i>Cal&iacute;ope</i><br><br>".$aviso6."</font><br><br>";

			if(tienePermisos("glosarioresultadoadmin"))
			{
			   echo "<a href=\"../ayuda/ayuda_glosario_admin.htm\" target=\"_blank\"><span class=\"subtitulo titulo_rojo\"><img border=\"0\" src=\"../imagenes/ayuda.png\" width=\"43\" height=\"24\" /><br>".$ayuda."</span></a>";
			}
			if(tienePermisos("glosarioresultadousuario"))
			{
			   echo "<a href=\"../ayuda/ayuda_glosario.htm\" target=\"_blank\"><span class=\"subtitulo titulo_rojo\"><img border=\"0\" src=\"../imagenes/ayuda.png\" width=\"43\" height=\"24\" /><br>".$ayuda."</span></a>";
			}

			echo "</p>";
		 }
	  }
   }
   else
   {
	   echo "<p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".$acceso_invalido_pagina."</p>";
   }
?>

</body>
</html>
