<?php
require_once("mysql.php");
$db = new mysql("CGF","conn","utf8");
$db -> query("SELECT element_symbol FROM element_cancer_interpretation");		
while($t = $db->fetch_array()){
	$js[$t[0]] = $t[0];
}
$sjs = array_unique($js);
ksort($sjs);
$json = json_encode($sjs);
echo $json;

?>