<?php 
    include_once('lib/config.php');
	require_once("lib/mysql.php");
	$smarty -> assign("title","DOCUMENTATION");
	$smarty -> assign("st",$_GET);
	$smarty -> assign("genelist",pct_genelist());
	if(isset($_GET['gene'])){
		$smarty -> assign("gene",$_GET['gene']);
		$smarty -> assign("pct_drug",pct_drug($_GET['gene']));
		$smarty -> assign("pct_gene",pct_gene($_GET['gene']));
	}
	$smarty -> assign("drugs",drug());
	$smarty -> assign("tissuelist",pathway(''));
	if(isset($_GET['pathway'])){
		$smarty -> assign("pathway",pathway($_GET['pathway']));
	}
	$smarty -> assign("cancerlist",ccl_cancerlist());
	if(isset($_GET['CCL'])){
		$smarty -> assign("ccl_cancer",$_GET['CCL']);
		$smarty -> assign("ccl",CCL($_GET['CCL']));
	}
	$smarty -> display("doc.html");
	
	
	function drug(){
		$db = new mysql("CGF","conn","utf8");
		$db -> query('select * from Drug');
		$drugs = array();
		while($t = $db->fetch_array()){
			array_push($drugs,$t);	
		}
		return $drugs;
	}
	
	function pct_drug($gene){
		$db = new mysql("CGF","conn","utf8");
		$db -> query('select * from pct_therapy_drug where gene="'.$gene.'"');
		$pct_drug = array();
		while($t = $db->fetch_array()){
			array_push($pct_drug,$t);	
		}
		return $pct_drug;
	}
	function pct_gene($gene){
		$db = new mysql("CGF","conn","utf8");
		$db -> query('select * from pct_therapy where gene="'.$gene.'"');
		$pct_gene = array();
		$pct_gene = $db->fetch_array();
		return $pct_gene;
	}
	
	function pct_genelist(){
		$db = new mysql("CGF","conn","utf8");
		$db -> query('select * from pct_therapy_drug');
		$genelist = array();
		while($t = $db->fetch_array()){
			$genelist[$t['gene']] = 1;	
		}
		return $genelist;
	}
	function pathway($type){
		$db = new mysql("CGF","conn","utf8");
		$pathway = array();
		if($type == ""){
			$db -> query('select * from tissue_pathway');		
			while($t = $db->fetch_array()){
				$pathway[$t['type1']]=1;	
			}	
		}
		else{
			$db -> query('select * from tissue_pathway where type1 = "'.$type.'"');
			while($t = $db->fetch_array()){
				array_push($pathway,$t);	
			}
		}
		return $pathway;
	}
	
	function ccl($cancer){
		$db = new mysql("CGF","conn","utf8");
		$db -> query('select * from GDSC where ONCOTREE = "'.$cancer.'"');
		$ccl = array();
		while($t = $db->fetch_array()){
			array_push($ccl,$t);	
		}
		return $ccl;	
	}
	function ccl_cancerlist(){
		$db = new mysql("CGF","conn","utf8");
		$db -> query('select * from gdsc_cancername');
		$cancers = array();
		while($t = $db->fetch_array()){
			$cancers[$t['oncotree_name']] = 1;
		}
		ksort($cancers);
		return $cancers;	
	}
?>