 <FORM action="./free.php" method="POST">
    <P>
    <LABEL for="taa">Título: </LABEL>
              <INPUT type="text" name="titulo"/><BR>
    <INPUT type="submit" value="Buscar" name="submit"/> 
    </P>
 </FORM>
 
 <?php
echo '<?xml version="1.0" encoding="utf-8"?>';
include 'freelingQuery.php';
set_time_limit(0); //las querys a freeling tardan bastante
 if (isset($_POST['titulo'])){
 echo "Resultados de " . htmlspecialchars($_POST['titulo'])." : <br>";
 $variable = $_POST['titulo'];
 
$m = new Mongo();
$d = $m ->selectDB("textos");
$t = $d->selectCollection("t");

$caca= $t->find(array('h_title'=>$variable));
$doc = $caca->hasNext() ? $caca->next() : null;

$freeling = new freelingQuery();
if (!$doc){
	echo "Donc";
	foreach ($caca as $c){

		$anotado = $freeling->Query($c);
		
		echo $anotado;

	}
var_dump($anotado);
}


}

?>