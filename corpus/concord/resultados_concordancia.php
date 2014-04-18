<?php 
   header('Content-Type: application/vnd.ms-excel');
   header('Content-Disposition: attachment; filename="ResultadosConcordancia.xls"');
   
   session_start();header('Content-Type: text/html; charset=latin1');ini_set("session.cookie_httponly", 1);
   $cabecera = $_POST['cabecera'];

    if(isset($_GET['lg']))
	{
		$lg = $_GET['lg'];
		$_SESSION['lg'] = $lg;
		include ("../../idioma/".$lg.".php");
	}
	else if(isset($_SESSION['lg']))
	{
		$lg = $_SESSION['lg'];
		include ("../../idioma/".$lg.".php");
	}

   
/*-------------------------------------------------------------------------------------------------
Funcion que indica si una cadena empieza por un texto.

ENT: $hsystack   - Cadena de texto en la que se busca
     $needle     - Inicio del texto
SAL: Booleano indicando si es cierto o no.
-------------------------------------------------------------------------------------------------*/

function startswith($haystack, $needle) {
    if (is_array($haystack)) {
        foreach($haystack as $hay) {
            if(substr($hay, 0, strlen($needle)) == $needle) {
                return true;
            }
        }
        //return in_array($needle, $haystack);
        return false;
    } else {
        return (substr($haystack, 0, strlen($needle)) == $needle);
    }
}
    
?>
<table border="0" width="90%" align="left">
<?php 
	$cabecera_texto = "";
	echo "<tr><td>$cabecera</td></tr><tr><td align='center'><table border='0' cellpadding='0' cellspacing='0'>";

	$habia_texto = false;
	while($elemento = each($_POST))
	{
		if ($elemento[0] != 'cabecera')	{
			
			if (startswith($elemento[0],"texto")) {
				if ($habia_texto)
				{
					$cabecera_texto = "</table></td></tr><tr><td>&nbsp;</td></tr>";
				}
				
				$cabecera_texto .= $elemento[1]."<tr><td style='background-color:#D8D7A3;border: dashed;border-width: 1px;border-color: #808080;margin-left: 1%;margin-right: 1%;'><table width='100%' border='0' cellpadding='0' cellspacing='4'>";
				$habia_texto = true;
			} else {
				echo $cabecera_texto;
				$elementos_fila = preg_split("/#/",$elemento[1]);
				
				// La estructura de $elementos fila es: id_texto#pre#concordancia#post#indice;
				$id_texto = $elementos_fila[0];
				$pre = $elementos_fila[1];
				$post = $elementos_fila[count($elementos_fila)-2];
				$indice = $elementos_fila[count($elementos_fila)-1];
				echo "<tr>\n";
				echo "<td align='center'>$pre</td>"; // Inicio de fila
				$es_coincidencia = true;
				for ($i=2; $i<count($elementos_fila)-2; $i++)
				{
					if ($es_coincidencia)
						//echo "<td bgcolor='#CCCC00' align='center'><a href='".$_SESSION['application_url']."/corpus/concord/visualizar_texto.php?texto=$id_texto&palabra=".$elementos_fila[$i]."#$indice' target='_blank'>".$elementos_fila[$i]."</a></td>";
						echo "<td bgcolor='#CCCC00' align='center'><a href='#' target='_blank'>".$elementos_fila[$i]."</a></td>";
					else
						echo "<td align='center'>".$elementos_fila[$i]."</td>";
					
					$es_coincidencia = !$es_coincidencia;
				}
				echo "<td align='center'>$post</td>"; // Cierre de fila
				echo "</tr>\n";
				$cabecera_texto = "";
			}
		}
	}
	if ($habia_texto)
	{
		echo "</table></td></tr>";
	}
?>
</table></td></tr>
</table>
</body>
</html>