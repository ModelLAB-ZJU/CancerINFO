<?php
	if(isset($_GET['gene_symbol'])){
	  $gene = $_GET['gene_symbol'];
	  require_once("../lib/mysql.php");
	  $cgf['gene in cancer'] = GIC($gene);	
	  $cgf['gene mutation in cancer'] = GAIC($gene);
	  $cgf['gene mutation in cancer trreatment'] = GAICT($gene);
	  echo json_encode($cgf,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	}
	function GIC($gene){
		$db = new mysql("CGF","conn","utf8");
		$db -> query('SELECT * FROM element_cancer_interpretation WHERE element_symbol ="'.$gene.'"');
		$t = $db->fetch_array();
		$s['role in cancer'] = $t['element_role_in_cancer'];
		$s['gene-related cancer'] = explode(';',$t['element_cancer_oncotree_type']);
		$s['gene in cancer and therapy evidence'] = $t['element_cancer_interpretation'];
		return $s;
	}
	function GAIC($gene){
		$db = new mysql("CGF","conn","utf8");
		$db -> query("SELECT a.* FROM element_alteration_cancer_interpretation a INNER JOIN ((SELECT phos_detail FROM feature_phos WHERE phos_symbol = '".$gene."') UNION (SELECT snv_detail FROM feature_snv  WHERE snv_symbol = '".$gene."') UNION (SELECT sv_detail FROM feature_sv  WHERE sv_symbol = '".$gene."') UNION (SELECT cnv_detail FROM feature_cnv  WHERE cnv_symbol = '".$gene."') UNION (SELECT ex_detail FROM feature_expression  WHERE ex_symbol = '".$gene."') UNION (SELECT met_detail FROM feature_methylation  WHERE met_symbol = '".$gene."')) b ON a.element_alteration_detail = b.phos_detail");
		$gaic = array();
		$level = level_status();
		while($t = $db->fetch_array()){
			$s = array();
			$s['mutation'] = $t['element_alteration_detail'];
			$s['clinical_significance'] = $t['element_alteration_cancer_evidence_clinical_significance'];
			$s['reference'] = $t['element_alteration_cancer_evidence_support'];
			$s['evidence_type'] = $t['element_alteration_cancer_evidence_type'];
			$s['evidence_level'] = $level['eadci'][strtoupper($t['element_alteration_cancer_evidence_level'])];
			array_push($gaic,$s);	
		}
		return $gaic;
	}
	function GAICT($gene){
		$db = new mysql("CGF","conn","utf8");
		$db -> query("SELECT a.* FROM element_alteration_drug_cancer_interpretation a INNER JOIN ((SELECT phos_detail FROM feature_phos WHERE phos_symbol = '".$gene."') UNION (SELECT snv_detail FROM feature_snv  WHERE snv_symbol = '".$gene."') UNION (SELECT sv_detail FROM feature_sv  WHERE sv_symbol = '".$gene."') UNION (SELECT cnv_detail FROM feature_cnv  WHERE cnv_symbol = '".$gene."') UNION (SELECT ex_detail FROM feature_expression  WHERE ex_symbol = '".$gene."') UNION (SELECT met_detail FROM feature_methylation  WHERE met_symbol = '".$gene."')) b ON a.element_alteration_detail = b.phos_detail");
		$gaict = array();
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
			array_push($gaict,$s);	
		}
		return $gaict;
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