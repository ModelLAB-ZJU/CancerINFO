<?php
require_once("mysql.php");
$db = new mysql("CGF","conn","utf8");
$db -> query("SELECT * FROM cancers");
$tissues=array();
$tissue_type=array();
while($t = $db->fetch_array()){
	$tissues[$t['primary']]['meta main tumor type'][$t['mainType']] = 1;
	if($t['secondary']!=""){
		$tissues[$t['primary']]['secondary tumor type'][$t['secondary']] = 1;
	}
	if($t['tertiary']!=""){
		$tissues[$t['primary']]['tertiary tumor type'][$t['tertiary']] = 1;
	}
	if($t['quaternary']!=""){
		$tissues[$t['primary']]['quaternary tumor type'][$t['quaternary']] = 1;
	}
	if($t['quinternary']!=""){
		$tissues[$t['primary']]['quinternary tumor type'][$t['quinternary']] = 1;
	}
	$tissue_type{$t['primary']}=$t['primary'];
}
$type = $_GET['type'];

switch($type){
	case "tissue_type":
		$tissue_type = array_unique($tissue_type);
		ksort($tissue_type);
		$json = json_encode($tissue_type);
		echo $json;
		break;
	case "class_type":
		$tissue_type = $_GET['tissue'];
		$class_types = array_keys($tissues[$tissue_type]);
		foreach($class_types as $type){
			$jsi[$type] = $type;	
		}
		$jsoni = json_encode($jsi);
		echo $jsoni;
		break;
	case "tumor_type":
		$tissue_type = $_GET['tissue'];
		$class_type = $_GET['class'];
		$tumor_types = array_keys($tissues[$tissue_type][$class_type]);
		foreach($tumor_types as $type){
			$jsa[$type] = $type;	
		}
		$sjsa = array_unique($jsa);
		ksort($sjsa);
		$jsona = json_encode($sjsa);
		echo $jsona;
		break;
}

?>