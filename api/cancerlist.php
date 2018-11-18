<?php 
	require_once("../lib/mysql.php");
	$db = new mysql("CGF","conn","utf8");
	
	$db -> query("SELECT * FROM cancer");
	$tissues = array();		
	while($t = $db->fetch_array()){
		$tissues[$t['cancer_oncotree_name']]['site'] = $t['primary_site'];
		$tissues[$t['cancer_oncotree_name']]['level'] = $t['level'];
		$tissues[$t['cancer_oncotree_name']]['nci'] = $t['nci_id'];
		$tissues[$t['cancer_oncotree_name']]['umis'] = $t['umis_id'];
	}
	ksort($tissues);
	echo json_encode($tissues,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
?>          