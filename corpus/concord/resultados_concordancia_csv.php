<?php 
   header('Content-Type: application/vnd.ms-excel');
   header('Content-Disposition: attachment; filename="Resultados.csv"');
   
   session_start();header('Content-Type: text/html; charset=utf-8');ini_set("session.cookie_httponly", 1);
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
<?php 
	$cabecera_texto = "";

	$habia_texto = false;
	while($elemento = each($_POST))
	{
		if ($elemento[0] != 'cabecera')	{
			
			if (startswith($elemento[0],"texto")) {
				if ($habia_texto)
				{
				}
				$habia_texto = true;
			} else {
				echo $cabecera_texto;
				$elementos_fila = preg_split("/#/",$elemento[1]);
				
				// La estructura de $elementos fila es: id_texto#pre#concordancia#post#indice;
				$id_texto = $elementos_fila[0];
				$pre = $elementos_fila[1];
				$post = $elementos_fila[count($elementos_fila)-2];
				$indice = $elementos_fila[count($elementos_fila)-1];
				echo '"'.$pre.'"'.','; // Inicio de fila
				$es_coincidencia = true;
				for ($i=2; $i<count($elementos_fila)-2; $i++)
				{
					if ($es_coincidencia)
						//echo "<td bgcolor='#CCCC00' align='center'><a href='".$_SESSION['application_url']."/corpus/concord/visualizar_texto.php?texto=$id_texto&palabra=".$elementos_fila[$i]."#$indice' target='_blank'>".$elementos_fila[$i]."</a></td>";
						echo '"'.$elementos_fila[$i].'"'.',';
					else
						echo '"'.$elementos_fila[$i].'"'.',';
					
					$es_coincidencia = !$es_coincidencia;
				}
				echo '"'.$post.'"'; // Cierre de fila
				echo "\n";
				$cabecera_texto = "";
			}
		}
	}
	if ($habia_texto)
	{
	}
?>