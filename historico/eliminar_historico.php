<?php
   session_start();header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);
   
   include ("../comun/permisos.php");
   
   if(tienePermisos("historico"))
   {
   	 $lista_historico = $_POST['historico'];
   	 
   	 $consulta = "DELETE FROM historico WHERE id in (";
   	
	 $esprimero = true;
   	 while($elemento = each($lista_historico))
	 {
	     if (!$esprimero)
	     	$consulta.=",";
	     else
	     	$esprimero = false;
	     	
	     $consulta.= $elemento[1];
	 }
	 $consulta .= ")";
	 
   	 include ("../comun/conexion.php");
	 
	 mysql_query($consulta) or die("<html><head><link rel=\"stylesheet\" type=\"text/css\" href=\"../comun/estilo.css\"></head><body><p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>".
	 "No se pudo eliminar del contexto.</p><p align=\"center\"><input type='button' class='boton' onclick=\"document.location='vista_listado.php'\" value='Volver' /></p></body></html>");
	 
	 mysql_close($enlace);
   	 
	 echo "<script>document.location ='vista_listado.php';</script>";
   }
   else
   {
      echo "<html><head><link rel=\"stylesheet\" type=\"text/css\" href=\"../comun/estilo.css\"></head><body><p class=\"Alerta\"><img border=\"0\" src=\"../imagenes/alerta2.gif\"><br>ACCESO INV&Aacute;LIDO a la p&aacute;gina.</p></body></html>";
   }  
?>