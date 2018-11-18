<?php
if(isset($_GET['gene_symbol'])){
		$gene = $_GET['gene_symbol'];
		ini_set('memory_limit', '128000M');
		require_once("../lib/mysql.php");
		$db = new mysql("CGF","conn","utf8"); 
		$db -> query("SELECT * FROM gene_info WHERE gene_symbol = '".$gene."'");
		$geneinfo = array();
		$gobp = array();
		while($t = $db->fetch_array()){
			$item = array("GO BP", "GO CC", "GO MF", "pharmgkb","wikipathways","biocarta","kegg","pid","reactome","netpath","smpdb","interpro");
					if(in_array($t['type'],$item)){
						$s = explode(';',$t['des']);
						$info =array();
						$info['id']	= $s[1];
						$info['des'] = $s[0];
						$info['class'] = $s[2];
						$geneinfo[$t['type']][] = $info;
					}
					else{
						$geneinfo[$t['type']] = $t['des'];
					}
				}
				echo json_encode($geneinfo,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			}
?>