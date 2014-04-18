<?php 
   session_start();

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
<!--------------------------------------------------------------

---------------------------------------------------------------->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head><title></title>
   <link rel="stylesheet" type="text/css" href="../comun/estilo.css">
   <meta http-equiv=Content-Type content="text/html; charset=windows-1252">
   <meta content="MSHTML 6.00.2800.1498" name=GENERATOR>
</head>
<body>

<?php 
   if(tienePermisos("glosariomenu"))
   {
?>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="conborde">
<!--
   <tr>
      <td align="left" bgcolor="#FFFF99"><img border="0" src="../imagenes/esq_sup_i.gif" height="22" width="22"></td>
      <td align="right" bgcolor="#FFFF99"><img border="0" src="../imagenes/esq_sup_d.gif" height="22" width="22"></td>
   </tr>
 -->
   <tr><td colspan="2" bgcolor="#FFFF99">&nbsp;</td></tr>
   <tr>
      <td colspan="2" align="center" valign="top" bgcolor="#FFFF99">
        <span class="titulo titulo_gris"><?php echo $busqueda_inicial ?></span><br>
        <table border="0" style="border: 1 dotted #800000">
		  <tr>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=a" target="resultado">A</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=b" target="resultado">B</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=c" target="resultado">C</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=d" target="resultado">D</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=e" target="resultado">E</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=f" target="resultado">F</a></font></td>
		  </tr>
		  <tr>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=g" target="resultado">G</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=h" target="resultado">H</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=i" target="resultado">I</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=j" target="resultado">J</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=k" target="resultado">K</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=l" target="resultado">L</a></font></td>
		  </tr>
		  <tr>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=m" target="resultado">M</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=n" target="resultado">N</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=%F1" target="resultado">&Ntilde;</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=o" target="resultado">O</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=p" target="resultado">P</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=q" target="resultado">Q</a></font></td>
		  </tr>
		  <tr>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=r" target="resultado">R</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=s" target="resultado">S</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=t" target="resultado">T</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=u" target="resultado">U</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=v" target="resultado">V</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=w" target="resultado">W</a></font></td>
		  </tr>
		  <tr>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=x" target="resultado">X</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=y" target="resultado">Y</a></font></td>
		    <td align="center"><font size="5"><a href="resultado.php?inicial=z" target="resultado">Z</a></font></td>
		    <td align="center" colspan="3" bgcolor="#FFFF55"><a href="resultado.php?inicial=todas" target="resultado">(<?php echo $todos ?>)</a></td>
		  </tr>
        </table>
      </td>
   </tr>
   <tr>
      <td colspan="2" bgcolor="#FFFF99" align="center">
      <br>
	    <form action="resultado.php" target="resultado" method=post name="formulario" onSubmit='return check_datos(formulario);'>
		  <span class="titulo titulo_gris"><?php echo $busqueda_termino ?></span><br>
		  <table width="100%" border="0" bgcolor="#CC0000">
		     <tr>
		        <td align="center">
		        	<input name="palabra" size="26" title="<?php echo $termino_buscar ?>">&nbsp;&nbsp;
		        	<input type="image" align="absbottom" src="../imagenes/bot_buscar.gif" width="30px" height="24px">
		        </td>
		     </tr>
	      </table>
	    </form>
	 </td>
   </tr>
<?php 
	  if(tienePermisos("glosariomenuadmin"))
	  {
?>
   <tr>
      <td colspan="2" bgcolor="#FFFF99" align="center">
		<span class="titulo titulo_gris"><?php echo $administrar_glosario ?></span><br><br>
			<input type="button" class="boton" value=" <?php echo $administrar_terminos ?> " onclick="parent.frames['resultado'].document.location='resultado.php?inicial=admin_termino';"/><br><br>
		<input type="button" class="boton" value=" <?php echo $administrar_relaciones ?> " onclick="parent.frames['resultado'].document.location='resultado.php?inicial=admin_relacion';"/><br><br>
		<input type="button" class="boton" value=" <?php echo $administrar_tipo_relaciones ?> " onclick="parent.frames['resultado'].document.location='resultado.php?inicial=admin_tipo_relacion';"/><br><br>
      </td>
   </tr>

<?php 
      }
?>
   <tr><td colspan="2" bgcolor="#FFFF99">&nbsp;</td></tr>
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