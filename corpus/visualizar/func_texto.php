<?php 
//-- func_texto.php -------------------------------------------------------------------------------

//     Funciones para la visualizacion de un texto por pantalla.

//----------------------------------------------------------------------------------------------- -->

//=================================================================================================

function visualizar_texto($id_texto, $arg_op)

/*-------------------------------------------------------------------------------------------------
  Visualizacion por pantalla de un texto del corpus.

  ENT: $id_texto     - identificador del texto que se quiere visualizar
       $edition_stmt - formato del fichero que contiene el texto
       $arg_op - indica si es para visualizar o para descargar.
  SAL: visualizacion del texto por pantalla    
-------------------------------------------------------------------------------------------------*/

{
   // Conexion con la base de datos 
   include ("../../comun/conexion.php");
	  	 
   // Cargar datos del formulario
   $id_texto = $_GET['id_texto'];

   $consulta = "SELECT id_texto,edition_stmt, h_title, body FROM texto WHERE texto.id_texto = '$id_texto'";
   $res = mysql_query($consulta);
   $obj = mysql_fetch_object($res);
		 
   if ($obj->edition_stmt == "texto")
      $extension = ".txt";
   else
      $extension = ".html";

   if ($arg_op == "descargar")
   {
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="'.$obj->h_title.$extension.'"');
   } 
   
   
   if ($arg_op == "descargar" || $obj->edition_stmt != "texto")
   {
      echo $obj->body;
   }
   else
   {
      echo str_replace(array("\r\n", "\n", "\r"),'<br />',$obj->body) ;
   }

   mysql_close($enlace);
}
?>