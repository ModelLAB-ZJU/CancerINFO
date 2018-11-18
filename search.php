<?php
	ini_set('memory_limit', '128000M');
	set_time_limit(0);
	require_once("lib/mysql.php");
	$db = new mysql("CGF","conn","utf8"); 
    include_once('lib/config.php');
	$smarty -> assign("title","SEARCH");
	$smarty -> assign("update",$update);
	if(isset($_GET['searchtype'])){
		$searchType = $_GET['searchtype'];
		$smarty -> assign("searchType",$searchType);
		switch ($searchType){
			case "tissue":
				if(isset($_GET['tissue'])&!isset($_GET['tumor'])){
					$tissue = $_GET['tissue'];
					$smarty -> assign("tissue",$tissue);
					$db -> query("SELECT * FROM cancer WHERE primary_site = '".$tissue."'");
					$tissues = array();		
					while($t = $db->fetch_array()){
						$tissues[$t['cancer_oncotree_name']]['level'] = $t['level'];
						$tissues[$t['cancer_oncotree_name']]['nci'] = $t['nci_id'];
						$tissues[$t['cancer_oncotree_name']]['umis'] = $t['umis_id'];
					}
					$cgs = array();
					$cgas = array();
					$ctgas = array();
					$sat = array();
					foreach($tissues as $cancer => $type){
						$cg = cg($cancer);
						$gene_num = count($cg);
						array_push($sat,array("cancer"=>$cancer,"level"=>$tissues[$cancer]["level"],"nci"=>$tissues[$cancer]["nci"],"umis"=>$tissues[$cancer]["umis"],"num"=>$gene_num));	
						$cgs = array_merge($cgs,$cg);
						$cgas = array_merge($cgas,cga($cancer));
						$ctgas = array_merge($ctgas,ctga($cancer));	
					}
					$smarty -> assign("type","tissue");
					$smarty -> assign("sat",$sat);
					$smarty -> assign("cg",$cgs);
					$smarty -> assign("cga",$cgas);
					$smarty -> assign("ctga",$ctgas);
				}
				
				if(isset($_GET['tumor'])){
					$tissues=tissue();
					$tissue = $_GET['tissue'];
					$cancer = $_GET['tumor'];
					unset($tissues[$tissue][$cancer][""]);
					$cancers=array_keys($tissues[$tissue][$cancer]);
					array_push($cancers,$cancer);
					$cgs = array();
					$cgas = array();
					$ctgas = array();
					foreach($cancers as $can){
						$cgs = array_merge($cgs,cg($can));
						$cgas = array_merge($cgas,cga($can));
						$ctgas = array_merge($ctgas,ctga($can));	
					}
					
					$smarty -> assign("type","cancer");
					$smarty -> assign("cancer",$cancer);
					$smarty -> assign("tissue",$tissue);
					$smarty -> assign("cg",$cgs);
					$smarty -> assign("cga",$cgas);
					$smarty -> assign("ctga",$ctgas);
					
				}
				$smarty -> assign("level_status",level_status());
				$smarty -> display("search-result.html");
				break;
				
			case "gene":
				$smarty -> display("search-result.html");
				break;	
		}
		
	}
	else{
		$smarty -> display("search.html");
	}
	
	
	function tissue(){
	  $db = new mysql("CGF","conn","utf8");
	  $db -> query("SELECT * FROM cancers");
	  $tissues = array();
	  
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
	  }
	  return $tissues;
	}
	
	function cg($cancer){
		$db = new mysql("CGF","conn","utf8");
		$db -> query('SELECT element_symbol,element_role_in_cancer FROM element_cancer_interpretation WHERE (element_cancer_oncotree_type LIKE "%;'.$cancer.'" or element_cancer_oncotree_type LIKE "'.$cancer.';%" or element_cancer_oncotree_type LIKE "'.$cancer.'" or element_cancer_oncotree_type LIKE "%;'.$cancer.';%")');
		$gene = array();
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
		return $gene;
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
	
	function cga($cancer){
		$db = new mysql("CGF","conn","utf8");
		$db -> query('SELECT * FROM element_alteration_cancer_interpretation WHERE (element_alteration_oncotree_type LIKE "%;'.$cancer.'" or element_alteration_oncotree_type LIKE "'.$cancer.';%" or element_alteration_oncotree_type LIKE "'.$cancer.'" or element_alteration_oncotree_type LIKE "%;'.$cancer.';%")');
		$cga = array();
		while($t = $db->fetch_array()){
			array_push($cga,$t);	
		}
		return $cga;
	}
	
	function ctga($cancer){
		$db = new mysql("CGF","conn","utf8");
		$db -> query('SELECT * FROM element_alteration_drug_cancer_interpretation WHERE (element_alteration_cancer_oncotree_type LIKE "%;'.$cancer.'" or element_alteration_cancer_oncotree_type LIKE "'.$cancer.';%" or element_alteration_cancer_oncotree_type LIKE "'.$cancer.'" or element_alteration_cancer_oncotree_type LIKE "%;'.$cancer.';%")');
		$ctga = array();
		while($t = $db->fetch_array()){
			array_push($ctga,$t);	
		}
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
?>          