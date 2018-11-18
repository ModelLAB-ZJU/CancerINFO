<?php 
	require_once("../lib/mysql.php");
	if(isset($_GET['cancer_name'])){
		$cancer = $_GET['cancer_name'];
		$cgf['gene in cancer'] = cg($cancer);
		$cgf['gene mutation in cancer'] = cga($cancer);
		$cgf['Gene mutation in cancer therapy'] = ctga($cancer);
		echo json_encode($cgf,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
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
	function cga($cancer){
		$db = new mysql("CGF","conn","utf8");
		$db -> query('SELECT * FROM element_alteration_cancer_interpretation WHERE (element_alteration_oncotree_type LIKE "%;'.$cancer.'" or element_alteration_oncotree_type LIKE "'.$cancer.';%" or element_alteration_oncotree_type LIKE "'.$cancer.'" or element_alteration_oncotree_type LIKE "%;'.$cancer.';%")');
		$cga = array();
		$level = level_status();
		while($t = $db->fetch_array()){
			$s = array();
			$s['mutation'] = $t['element_alteration_detail'];
			$s['clinical_significance'] = $t['element_alteration_cancer_evidence_clinical_significance'];
			$s['reference'] = $t['element_alteration_cancer_evidence_support'];
			$s['evidence_type'] = $t['element_alteration_cancer_evidence_type'];
			$s['evidence_level'] = $level['eadci'][strtoupper($t['element_alteration_cancer_evidence_level'])];
			array_push($cga,$s);	
		}
		return $cga;
	}
	function ctga($cancer){
		$db = new mysql("CGF","conn","utf8");
		$db -> query('SELECT * FROM element_alteration_drug_cancer_interpretation WHERE (element_alteration_cancer_oncotree_type LIKE "%;'.$cancer.'" or element_alteration_cancer_oncotree_type LIKE "'.$cancer.';%" or element_alteration_cancer_oncotree_type LIKE "'.$cancer.'" or element_alteration_cancer_oncotree_type LIKE "%;'.$cancer.';%")');
		$ctga = array();
		$level = level_status();
		while($t = $db->fetch_array()){
			$s = array();
			$s['mutation'] = $t['element_alteration_detail'];
			$s['drugs'] = $t['element_alteration_cancer_drugs'];
			$s['drug_family'] = $t['element_alteration_cancer_drug_family'];
			$s['evidence_type'] = $t['element_alteration_cancer_drug_evidence_type'];
			$s['clinical_significance'] = $t['element_alteration_cancer_drug_evidence_clinical_significance'];
			$s['evidence_level'] = $level['eaci'][strtoupper($t['element_alteration_cancer_drug_evidence_level'])];
			$s['reference'] = $t['element_alteration_cancer_drug_evidence_support'];
			array_push($ctga,$s);
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