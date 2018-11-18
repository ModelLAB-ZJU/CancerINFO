<?php 
    include_once('lib/config.php');
	require_once("lib/mysql.php");
	$smarty -> assign("title","CANCER BROWSER");
	$smarty -> assign("update",$update);
	$db = new mysql("CGF","conn","utf8");
	$db -> query("SELECT * FROM cancers");
	$tissues = array();
	$tissue_cancers=array();
	
	while($t = $db->fetch_array()){
		$tissues[$t['primary']][$t['mainType']][$t['secondary']] = 1;
		$tissues[$t['primary']][$t['mainType']][$t['tertiary']] = 1;
		$tissues[$t['primary']][$t['mainType']][$t['quaternary']] = 1;
		$tissues[$t['primary']][$t['mainType']][$t['quinternary']] = 1;
		
		$tissues[$t['primary']][$t['secondary']][$t['tertiary']] = 1;
		$tissues[$t['primary']][$t['secondary']][$t['quaternary']] = 1;
		$tissues[$t['primary']][$t['secondary']][$t['quinternary']] = 1;
		
		$tissues[$t['primary']][$t['tertiary']][$t['quaternary']] = 1;
		$tissues[$t['primary']][$t['tertiary']][$t['quinternary']] = 1;
		
		$tissues[$t['primary']][$t['quaternary']][$t['quinternary']] = 1;
		
		$tissues[$t['primary']][$t['quinternary']][''] = 1;
		if($t['secondary']!=""){
			$tissue_cancers[$t['primary']][$t['secondary']]=1;
		}
		if($t['tertiary']!=""){
			$tissue_cancers[$t['primary']][$t['tertiary']]=1;
		}
		if($t['quaternary']!=""){
			$tissue_cancers[$t['primary']][$t['quaternary']]=1;
		}
		if($t['quinternary']!=""){
			$tissue_cancers[$t['primary']][$t['quinternary']]=1;
		}
	}
	if(isset($_GET['tissue'])){
		$tissue = $_GET['tissue'];
		$cancers = array();
		$cancer_gene=array();
		$cancer_ga=array();
		$cancer_gat=array();
		$cancerdrug=array();
		$id=$tissue;
		if(isset($_GET['cancer'])){
			$can = $_GET['cancer'];
			$id=$can;
			unset($tissues[$tissue][$can][""]);
			$cancers=array_keys($tissues[$tissue][$can]);
			array_push($cancers,$can);
			$cancer_gene=cg($cancers);
			$cancer_ga=cga($cancers);
			$cancer_gat=ctga($cancers);
			$cancerdrug=cancerdrug($cancers);
			//echo $cancer_gene[0][0];
		}
		else{
			unset($tissues[$tissue][""]);
			$cancers=array_keys($tissues[$tissue]);
			$cancer_gene=cg($cancers);
			$cancer_ga=cga($cancers);
			$cancer_gat=ctga($cancers);
			$cancerdrug=cancerdrug($cancers);
		}
		
		$smarty -> assign("can",$id);
		$smarty -> assign("cancers",array_keys($tissue_cancers[$tissue]));
		$smarty -> assign("tissue",$tissue);
		$smarty -> assign("tissue_cancers",$tissue_cancers);
		
		$smarty -> assign("cg",$cancer_gene);
		$smarty -> assign("cga",$cancer_ga);
		$smarty -> assign("ctga",$cancer_gat);
		$smarty -> assign("cancerdrug",$cancerdrug);
		
		$smarty -> assign("level_status",level_status());
		$smarty -> display("cancer_browser-2.html");
	}
	else{
		$smarty -> display("cancer_browser-1.html");
	}
	function cg($cancers){
		$db = new mysql("CGF","conn","utf8");
		$gene = array();
		foreach($cancers as $cancer){
		$db -> query('SELECT element_symbol,element_role_in_cancer FROM element_cancer_interpretation WHERE (element_cancer_oncotree_type LIKE "%;'.$cancer.'" or element_cancer_oncotree_type LIKE "'.$cancer.';%" or element_cancer_oncotree_type LIKE "'.$cancer.'" or element_cancer_oncotree_type LIKE "%;'.$cancer.';%")');
		while($t=$db->fetch_array()){
			$geneinfo = geneinfo($t['element_symbol']);	
			$name=array("");$entrezgene_id=array("");$alias=array("");$ensembl_id=array("");
			if(isset($geneinfo[$t['element_symbol']]["name"])){
				$name = array_keys($geneinfo[$t['element_symbol']]["name"]);
			}
			if(isset($geneinfo[$t['element_symbol']]["entrezgene_id"])){
				$entrezgene_id = array_keys($geneinfo[$t['element_symbol']]["entrezgene_id"]);
			}
			if(isset($geneinfo[$t['element_symbol']]["alias"])){
				$alias = array_keys($geneinfo[$t['element_symbol']]["alias"]);
			}
			if(isset($geneinfo[$t['element_symbol']]["ensembl_id"])){
				$ensembl_id = array_keys($geneinfo[$t['element_symbol']]["ensembl_id"]);
			}
			array_push($gene,array("name"=>$name[0],"alias"=>$alias[0],"entrezgene_id"=>$entrezgene_id[0],"ensembl_id"=>$ensembl_id[0],"symbol"=>$t['element_symbol'],"role"=>$t['element_role_in_cancer']));
		}
		}
		$gene=array_unique($gene,SORT_REGULAR);
		return $gene;
	}
	function cga($cancers){
		$db = new mysql("CGF","conn","utf8");
		$cga = array();
		foreach($cancers as $cancer){
		$db -> query('SELECT * FROM element_alteration_cancer_interpretation WHERE (element_alteration_oncotree_type LIKE "%;'.$cancer.'" or element_alteration_oncotree_type LIKE "'.$cancer.';%" or element_alteration_oncotree_type LIKE "'.$cancer.'" or element_alteration_oncotree_type LIKE "%;'.$cancer.';%")');
		while($t = $db->fetch_array()){
			array_push($cga,$t);	
		}
		}
		$cga=array_unique($cga,SORT_REGULAR);
		return $cga;
	}
	function ctga($cancers){
		$db = new mysql("CGF","conn","utf8");
		$ctga = array();
		foreach($cancers as $cancer){
		$db -> query('SELECT * FROM element_alteration_drug_cancer_interpretation WHERE (element_alteration_cancer_oncotree_type LIKE "%;'.$cancer.'" or element_alteration_cancer_oncotree_type LIKE "'.$cancer.';%" or element_alteration_cancer_oncotree_type LIKE "'.$cancer.'" or element_alteration_cancer_oncotree_type LIKE "%;'.$cancer.';%")');
		while($t = $db->fetch_array()){
			array_push($ctga,$t);	
		}
		}
		$ctga=array_unique($ctga,SORT_REGULAR);
		return $ctga;
	}
	function geneinfo($gene){
		$db = new mysql("CGF","conn","utf8");
		$db -> query("SELECT * FROM gene_info WHERE gene_symbol = '".$gene."'");
		$geneinfo = array();
		while($t = $db->fetch_array()){
			$geneinfo[$t['gene_symbol']][$t['type']][$t['des']]=1;	
		}
		return $geneinfo;
	}
	function cancerdrug($cancers){
		$db = new mysql("CGF","conn","utf8");
		$cancerdrug = array();
		foreach($cancers as $cancer){
		$db -> query("SELECT * FROM cancer_drug WHERE Conditions_oncotree = '".$cancer."'");
		
		while($t = $db->fetch_array()){
			array_push($cancerdrug,$t);	
		}
		}
		$cancerdrug=array_unique($cancerdrug,SORT_REGULAR);
		return $cancerdrug;
	}
	function level_status(){
		$db = new mysql("CGF","conn","utf8");
		$db -> query('SELECT * FROM level_status');
		$level=array();
		while($t=$db->fetch_array()){
			$level[$t['db']][strtoupper($t['original_level'])] = $t['level'];
		}
		return $level;
	}
?>          