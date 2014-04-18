<?php 
/*

<!-- listar_palabras.php -------------------------------------------------------------------------------
     listar_xml.php - deprecated, se ha cambiado de nombre

     Funciones para la carga y presentacion del listado de palabra

----------------------------------------------------------------------------------------------- -->
*/

//=================================================================================================

function cargarListado($texto, $columnas, $orden, $sentido, $frecuencia_desde, $frecuencia_hasta)

/*-------------------------------------------------------------------------------------------------
  Funcion de carga de un fichero XML y presentacion de los datos por pantalla.

  ENT: $texto    - identificadores de los texto al que pertenece el listado de palabras
       $columnas - numero de columnas de la presentacion de los resultados
       $orden    - Indica por que se ordena, si nombre o frecuencia
       $sentido  - Indica si el sentido es ascendente o descendente
       $frecuencia_desde - Indica la frecuencia de corte minima
       $frecuencia_hasta - Indica la frecuencia de corte maxima
  SAL: presentacion de los listados de palabras
-------------------------------------------------------------------------------------------------*/
{
   // Conexion con la base de datos
   include ("../../comun/conexion.php");
   //---------- CONSTRUCCION DE LA CONSULTA ----------
   $textos = "a.id_texto=".$texto[0];
   for ($i=1;$i<count($texto); $i++)
   {
   	 $textos .= " or a.id_texto=".$texto[$i];
   }
   
   if ($frecuencia_desde != "")
   	$textos.= " and a.frecuencia >= $frecuencia_desde";

   if ($frecuencia_hasta != "")
   	$textos.= " and a.frecuencia <= $frecuencia_hasta";

   // Construimos el orden para que se cumpla que dentro de una frecuencia se siga el orden y viceversa.
   if ($orden == "a.id_termino")
      $orden .= " $sentido, a.id_texto, a.frecuencia";
   else
      $orden .= " $sentido, a.id_termino, a.id_texto";
   
   $consulta = "SELECT a.id_texto, a.id_termino, a.frecuencia, b.id_glosario terminob, c.lang_usage from lista_terminos a join texto c on a.id_texto=c.id_texto left join glosario b on a.id_termino=b.termino where $textos order by $orden";
   $res = mysql_query($consulta) or die("$consulta Lectura de texto incorrecta.");
   
   $ultimo_indice = -1; // Indica el indice del ultimo texto seleccionado 
   $ultimo_termino;
   
   $script_text = "";
   while ($obj = mysql_fetch_object($res))
   {
	  $idiom = $obj->lang_usage;
      // calculamos el nuevo indice
      $nuevo_indice = -1;
      $salir = false;
      while (!$salir && $nuevo_indice < count($texto))
      {
         $nuevo_indice++;
         $salir = ($obj->id_texto == $texto[$nuevo_indice])?true:false;
      }
	  $p = strtolower($obj->id_termino);
	  if ($obj->lang_usage == "esp")
	  {
		  $consulta = "SELECT pos FROM eswn_variant WHERE word='$p'";
		  $res2 = mysql_query($consulta) or die (mysql_error());
	  }
	  else
	  {
		  $consulta = "SELECT offset FROM synsetword WHERE word='$p'";
		  $res2 = mysql_query($consulta) or die (mysql_error());
	  }
     	
      // Siguiente ocurrencia, texto distinto del anterior, posterior en el array.
      if ($nuevo_indice > $ultimo_indice)
      {
        if ($ultimo_indice == -1)
           echo "<tr>";
      	
        for ($i=$ultimo_indice+1; $i<$nuevo_indice;$i++)
           echo "<td>&nbsp;</td><td>&nbsp;</td>";
        
        // Si el termino no coincice, eso indica que va en la linea siguiente.
        if ($ultimo_indice == -1 || $obj->id_termino == $ultimo_termino)
        {
          //if ($_POST['esExcel'] == "")
		  if(!isset($_POST['esExcel']))
          {
			$p = strtolower($obj->id_termino);
			
			/*if ($obj->lang_usage == "esp")
			{
				$consulta = "SELECT * FROM eswn_variant WHERE word='$p'";
				$res2 = mysql_query($consulta) or die (mysql_error());
			}
			else
			{
				$consulta = "SELECT offset FROM synsetword WHERE word='$p'";
				$res2 = mysql_query($consulta) or die (mysql_error());
			}*/
			if (mysql_num_rows($res2) != 0)
			{
				$script_text.= "palabras['".$obj->id_texto."_".$obj->id_termino."'] = {frecuencia:'$obj->frecuencia',lang:'$obj->lang_usage',existeGlosario:'".($obj->terminob == "")."',id_glosario:'".$obj->terminob."',existeEurowordnet:true};";
			}
			else
			{
				$script_text.= "palabras['".$obj->id_texto."_".$obj->id_termino."'] = {frecuencia:'$obj->frecuencia',lang:'$obj->lang_usage',existeGlosario:'".($obj->terminob == "")."',id_glosario:'".$obj->terminob."',existeEurowordnet:false};";
			}
            
        	echo "<td>";
            echo "<a href='#' onContextMenu=\"return botonDerecho ('".$obj->id_texto."_".$obj->id_termino."', event)\"";


			if ($obj->terminob != "")
            	echo " class='EnGlosario'";
            echo ">$obj->id_termino</a>";
          }
          else
          {
             if ($obj->terminob == "")
                echo "<td><b>$obj->id_termino</b>";
             else
                echo "<td style='color:#3366CC'><b>$obj->id_termino</b>";
          }
          
          echo "</td><td><b>$obj->frecuencia</b></td>";
        }
        else if ($ultimo_indice != -1)
        {
      	   for ($i=$nuevo_indice; $i<count($texto);$i++)
              echo "<td>&nbsp;</td><td>&nbsp;</td>";
     		
           echo "</tr><tr>";
     		
           for ($i=0; $i<$nuevo_indice;$i++)
              echo "<td>&nbsp;</td><td>&nbsp;</td>";
            
          //if ($_POST['esExcel'] == "")
		  if(!isset($_POST['esExcel']))
          {
			/*$p = strtolower($obj->id_termino);
          	if ($obj->lang_usage == "esp")
			{
				$consulta = "SELECT * FROM eswn_variant WHERE word='$p'";
				$res2 = mysql_query($consulta) or die (mysql_error());
			}
			else
			{
				$consulta = "SELECT offset FROM synsetword WHERE word='$p'";
				$res2 = mysql_query($consulta) or die (mysql_error());
			}*/

			if (mysql_num_rows($res2) != 0)
			{
				$script_text.= "palabras['".$obj->id_texto."_".$obj->id_termino."'] = {frecuencia:'$obj->frecuencia',lang:'$obj->lang_usage',existeGlosario:'".($obj->terminob == "")."',id_glosario:'".$obj->terminob."',existeEurowordnet:'true'};";
			}
			else
			{
				$script_text.= "palabras['".$obj->id_texto."_".$obj->id_termino."'] = {frecuencia:'$obj->frecuencia',lang:'$obj->lang_usage',existeGlosario:'".($obj->terminob == "")."',id_glosario:'".$obj->terminob."',existeEurowordnet:'false'};";
			}
            echo "<td>";
			echo "<a href='#' onContextMenu=\"return botonDerecho ('".$obj->id_texto."_".$obj->id_termino."', event)\"";
			if ($obj->terminob != "")
            	echo " class='EnGlosario'";
            echo ">$obj->id_termino</a>";
          }
          else
          {
             if ($obj->terminob == "")
                echo "<td><b>$obj->id_termino</b>";
             else
                echo "<td style='color:#3366CC'><b>$obj->id_termino</b>";
          }
                    
          echo "</td><td><b>$obj->frecuencia</b></td>";
        }
      }
      // Siguiente ocurrencia, mismo texto
      else if ($nuevo_indice == $ultimo_indice)
      {
      	for ($i=$ultimo_indice+1; $i<count($texto);$i++)
            echo "<td>&nbsp;</td><td>&nbsp;</td>";
     		
         echo "</tr><tr>";
     		
         for ($i=0; $i<$ultimo_indice;$i++)
            echo "<td>&nbsp;</td><td>&nbsp;</td>";
            
          //if ($_POST['esExcel'] == "")
		  if(!isset($_POST['esExcel']))
          {
			/*$p = strtolower($obj->id_termino);
          	if ($obj->lang_usage == "esp")
			{
				$consulta = "SELECT * FROM eswn_variant WHERE word='$p'";
				$res2 = mysql_query($consulta) or die (mysql_error());
			}
			else
			{
				$consulta = "SELECT * FROM synsetword WHERE word='$p'";
				$res2 = mysql_query($consulta) or die (mysql_error());
			}*/

			if (mysql_num_rows($res2) != 0)
			{
				$script_text.= "palabras['".$obj->id_texto."_".$obj->id_termino."'] = {frecuencia:'$obj->frecuencia',lang:'$obj->lang_usage',existeGlosario:'".($obj->terminob == "")."',id_glosario:'".$obj->terminob."',existeEurowordnet:'true'};";
			}
			else
			{
				$script_text.= "palabras['".$obj->id_texto."_".$obj->id_termino."'] = {frecuencia:'$obj->frecuencia',lang:'$obj->lang_usage',existeGlosario:'".($obj->terminob == "")."',id_glosario:'".$obj->terminob."',existeEurowordnet:'false'};";
			}
            echo "<td>";
			echo "<a href='#' onContextMenu=\"return botonDerecho ('".$obj->id_texto."_".$obj->id_termino."', event)\"";
			if ($obj->terminob != "")
            	echo " class='EnGlosario'";
            echo ">$obj->id_termino</a>";
          }
          else
          {
             if ($obj->terminob == "")
                echo "<td><b>$obj->id_termino</b>";
             else
                echo "<td style='color:#3366CC'><b>$obj->id_termino</b>";
          }
                      
         echo "</td><td><b>$obj->frecuencia</b></td>";
      }
      // Siguiente ocurrencia, texto anterior en el array => fila siguiente.
      else if ($nuevo_indice < $ultimo_indice)
      {
         for ($i=$ultimo_indice+1; $i<count($texto);$i++)
            echo "<td>&nbsp;</td><td>&nbsp;</td>";
         
         echo "</tr><tr>";
         
         for ($i=0; $i<$nuevo_indice;$i++)
            echo "<td>&nbsp;</td><td>&nbsp;</td>";

          //if ($_POST['esExcel'] == "")
		  if(!isset($_POST['esExcel']))
          {
			/*$p = strtolower($obj->id_termino);
          	if ($obj->lang_usage == "esp")
			{
				$consulta = "SELECT * FROM eswn_variant WHERE word='$p'";
				$res2 = mysql_query($consulta) or die (mysql_error());
			}
			else
			{
				$consulta = "SELECT offset FROM synsetword WHERE word='$p'";
				$res2 = mysql_query($consulta) or die (mysql_error());
			}*/

			if (mysql_num_rows($res2) != 0 || mysql_num_rows($res3) != 0)
			{
				$script_text.= "palabras['".$obj->id_texto."_".$obj->id_termino."'] = {frecuencia:'$obj->frecuencia',lang:'$obj->lang_usage',existeGlosario:'".($obj->terminob == "")."',id_glosario:'".$obj->terminob."',existeEurowordnet:'true'};";
			}
			else
			{
				$script_text.= "palabras['".$obj->id_texto."_".$obj->id_termino."'] = {frecuencia:'$obj->frecuencia',lang:'$obj->lang_usage',existeGlosario:'".($obj->terminob == "")."',id_glosario:'".$obj->terminob."',existeEurowordnet:'false'};";
			}
			echo "<td>";
			echo "<a href='#' onContextMenu=\"return botonDerecho ('".$obj->id_texto."_".$obj->id_termino."', event)\"";
            if ($obj->terminob != "")
            	echo " class='EnGlosario'";
            echo ">$obj->id_termino</a>";
          }
          else
          {
             if ($obj->terminob == "")
                echo "<td><b>$obj->id_termino</b>";
             else
                echo "<td style='color:#3366CC'><b>$obj->id_termino</b>";
          }
                      
          echo "</td><td><b>$obj->frecuencia</b></td>";
      }
      
      $ultimo_indice = $nuevo_indice;
      $ultimo_termino = $obj->id_termino;
   }
   
   // Si el ultimo termino no es del ultimo texto, rellenamos la tabla.
   for ($i=$ultimo_indice+1; $i<count($texto); $i++)
   {
      echo "<td>&nbsp;</td><td>&nbsp;</td>";
   }

   echo "</tr>";
   if ($script_text != "")
      echo "<script language='javascript' type='text/javascript'>".$script_text."</script>";
	  
	mysql_close($enlace);
}

?>