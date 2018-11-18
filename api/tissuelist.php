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
	$db -> query('SELECT * FROM element_cancer_interpretation');
	$tissue_cancer =array();
	while($t = $db->fetch_array()){
		$cancers = explode(';',$t['element_cancer_oncotree_type']);
		foreach($cancers as $cancer){
			if(!empty($cancer)){
			  $tissue_cancer[$tissues[$cancer]['site']][$cancer]['level'] = "";
			  $tissue_cancer[$tissues[$cancer]['site']][$cancer]['nci'] = "";
			  $tissue_cancer[$tissues[$cancer]['site']][$cancer]['umis'] = "";
			  if(isset($tissues[$cancer]['level'])){
				  $tissue_cancer[$tissues[$cancer]['site']][$cancer]['level'] = $tissues[$cancer]['level'];
			  }
			  if(isset($tissues[$cancer]['nci'])){
				  $tissue_cancer[$tissues[$cancer]['site']][$cancer]['nci'] = $tissues[$cancer]['nci'];
			  }
			  if(isset($tissues[$cancer]['umis'])){
				  $tissue_cancer[$tissues[$cancer]['site']][$cancer]['umis'] = $tissues[$cancer]['umis'];
			  }
			}
		}
	}
	$tcg =array();
	foreach($tissue_cancer as $tissue=>$cancers){
		foreach($cancers as $cancer => $type){
			array_push($tcg,array("tissue"=>$tissue,"cancer"=>$cancer,"umis"=>$tissue_cancer[$tissue][$cancer]['umis'],"nci"=>$tissue_cancer[$tissue][$cancer]['nci'],"level"=>$tissue_cancer[$tissue][$cancer]['level']));	
		}	
	}
	ksort($tcg);
	echo json_encode($tcg,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
?>