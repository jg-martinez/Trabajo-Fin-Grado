<?php
$m = new Mongo();
$d = $m ->selectDB("textos");
$t = $d->selectCollection("t");


mysql_connect("localhost","tfc","caliope");
mysql_select_db("caliope");
$sql=mysql_query("select * from texto");
while($row=mysql_fetch_assoc($sql)){

//$t->insert(utf8_encode(json_encode($row)));
//$t->insert(json_encode(utf8_encode($row)));
$output[]=$row;
}
var_dump($output[1]);
foreach ($output as $txt){
//var_dump(utf8_encode(implode($txt)));
//$t->insert(explode(utf8_encode(implode($txt))));
$txt[body]=utf8_encode($txt[body]);
$txt[h_title]=utf8_encode($txt[h_title]);
$txt[id_fuente]=utf8_encode($txt[id_fuente]);
$t->insert($txt);
}
//print(json_encode($output));
//mysql_close();
?>