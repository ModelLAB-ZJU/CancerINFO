<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Tissuegenesis</title>
<style type="text/css">
	.td{
		border:1px solid #ccc;
		background:#fff;
		line-height:37px;
		color:#999;
		font-size:14px;
		text-align:center;
		font-size:16px;
	}
</style>
</head>

<body>
<div>		
      <?php
	  	$detail = $_GET["detail"];;
	  	include_once('lib/config.php');
		require_once("lib/mysql.php");
		$db = new mysql("CGF","conn","utf8");
		$svg='<foreignObject x="260px" y="50px" width="800" height="500"><body xmlns="http://www.w3.org/1999/xhtml"><table  border="0" cellpadding="0" cellspacing="0" ><tr><td width="70px"  scope="col" class="td"><strong>Tag</strong></td><td width="100px"  scope="col" class="td"><strong>Tissue Type</strong></td><td width="100px"  scope="col" class="td"><strong><i>P</i> value</strong></td></tr>';
		$img='<svg xmlns="http://www.w3.org/2000/svg" height="350px" width="600px"><image xlink:href="img/BODY.png" x="0" y="0" height="350px" width="260px"></image>';
		$db -> query('SELECT * FROM fs_pvalue WHERE detail = "'.$detail.'"');
		$cc = array("BOWEL"=>'<image xlink:href="img/BOWEL.png" x="110px" y="250px" height="20px" width="20px"></image>',"PANCREAS"=>'<image xlink:href="img/PANCREAS.png" x="170" y="220" height="20px" width="20px"></image>',"THYROID"=>'<image xlink:href="img/THYROID.png" x="110" y="100" height="20px" width="20px"></image>',"SKIN"=>'<image xlink:href="img/SKIN.png" x="25" y="300" height="20px" width="20px"></image>',"LUNG"=>'<image xlink:href="img/LUNG.png" x="130" y="155" height="20px" width="20px"></image>',"BLOOD"=>'<image xlink:href="img/BLOOD.png" x="70" y="165" height="20px" width="20px"></image>',"UTERUS"=>'<image xlink:href="img/UTERUS.png" x="175" y="280" height="20px" width="20px"></image>',"BRAIN"=>'<image xlink:href="img/BRAIN.png" x="145" y="30" height="20px" width="20px"></image>',"OVARY"=>'<image xlink:href="img/OVARY.png" x="130px" y="280px" height="20px" width="20px"></image>',"SOFT_TISSUE"=>'<image xlink:href="img/SOFT_TISSUE.png" x="115" y="320" height="20px" width="20px"></image>',"BILIARY_TRACT"=>'<image xlink:href="img/BILIARY_TRACT.png" x="115" y="220" height="20px" width="20px"></image>',"BREAST"=>'<image xlink:href="img/BREAST.png" x="120" y="140" height="20px" width="20px"></image>',"STOMACH"=>'<image xlink:href="img/STOMACH.png" x="150" y="210" height="20px" width="20px"></image>',"BONE"=>'<image xlink:href="img/BONE.png" x="180px" y="300px" height="20px" width="20px"></image>',"LIVER"=>'<image xlink:href="img/LIVER.png" x="110" y="205" height="20px" width="20px"></image>',"ADRENAL_GLAND"=>'<image xlink:href="img/ADRENAL_GLAND.png" x="118" y="215" height="20px" width="20px"></image>',"OTHER"=>'<image xlink:href="img/OTHER.png" x="0" y="0" height="20px" width="20px"></image>',"BLADDER"=>'<image xlink:href="img/BLADDER.png" x="110" y="280" height="20px" width="20px"></image>',"CERVIX"=>'<image xlink:href="img/CERVIX.png" x="170" y="270" height="20px" width="20px"></image>',"PROSTATE"=>'<image xlink:href="img/PROSTATE.png" x="143" y="310" height="20px" width="20px"></image>',"EYE"=>'<image xlink:href="img/EYE.png" x="115" y="38" height="20px" width="20px"></image>',"TESTIS"=>'<image xlink:href="img/TESTIS.png" x="140px" y="300px" height="20px" width="20px"></image>',"KIDNEY"=>'<image xlink:href="img/KIDNEY.png" x="118" y="220" height="20px" width="20px"></image>',"PLEURA"=>'<image xlink:href="img/PLEURA.png" x="125" y="145" height="20px" width="20px"></image>',"HEAD_NECK"=>'<image xlink:href="img/HEAD_NECK.png" x="115" y="65" height="20px" width="20px"></image>',"VULVA"=>'<image xlink:href="img/VULVA.png" x="140" y="305" height="20px" width="20px"></image>',"PERITONEUM"=>'<image xlink:href="img/PERITONEUM.png" x="100" y="230" height="20px" width="20px"></image>',"THYMUS"=>'<image xlink:href="img/THYMUS.png" x="105" y="105" height="20px" width="20px"></image>',"PENIS"=>'<image xlink:href="img/PENIS.png" x="145" y="325" height="20px" width="20px"></image>');
		$cc1 = array("BOWEL"=>'<img src="img/BOWEL.png" width="20px" />',"PANCREAS"=>'<img src="img/PANCREAS.png" width="20px" />',"THYROID"=>'<img src="img/THYROID.png" width="20px" />',"SKIN"=>'<img src="img/SKIN.png" width="20px" />',"LUNG"=>'<img src="img/LUNG.png" width="20px" />',"BLOOD"=>'<img src="img/BLOOD.png" width="20px" />',"UTERUS"=>'<img src="img/UTERUS.png" width="20px" />',"BRAIN"=>'<img src="img/BRAIN.png" width="20px" />',"OVARY"=>'<img src="img/OVARY.png" width="20px" />',"SOFT_TISSUE"=>'<img src="img/SOFT_TISSUE.png" width="20px" />',"BILIARY_TRACT"=>'<img src="img/BILIARY_TRACT.png" width="20px" />',"BREAST"=>'<img src="img/BREAST.png" width="20px" />',"STOMACH"=>'<img src="img/STOMACH.png" width="20px" />',"BONE"=>'<img src="img/BONE.png" width="20px" />',"LIVER"=>'<img src="img/LIVER.png" width="20px" />',"ADRENAL_GLAND"=>'<img src="img/ADRENAL_GLAND.png" width="20px" />',"OTHER"=>'<img src="img/OTHER.png" width="20px" />',"BLADDER"=>'<img src="img/BLADDER.png" width="20px" />',"CERVIX"=>'<img src="img/CERVIX.png" width="20px" />',"PROSTATE"=>'<img src="img/PROSTATE.png" width="20px" />',"EYE"=>'<img src="img/EYE.png" width="20px" />',"TESTIS"=>'<img src="img/TESTIS.png" width="20px" />',"KIDNEY"=>'<img src="img/KIDNEY.png" width="20px" />',"PLEURA"=>'<img src="img/PLEURA.png" width="20px" />',"HEAD_NECK"=>'<img src="img/HEAD_NECK.png" width="20px" />',"VULVA"=>'<img src="img/VULVA.png" width="20px" />',"PERITONEUM"=>'<img src="img/PERITONEUM.png" width="20px" />',"THYMUS"=>'<img src="img/THYMUS.png" width="20px" />',"PENIS"=>'<img src="img/PENIS.png" width="20px" />');
		$tissues = array_keys($cc);
		$n=0;		
		while($t = $db->fetch_array()){
			foreach($tissues as $c){
				if($t[$c]>0){
					$img .= $cc[$c];
					$svg .= '<tr><td width="70px"  scope="col" class="td">'.$cc1[$c].'</td><td width="100px"  scope="col" class="td">'.$c.'</td><td width="100px"  scope="col" class="td">'.$t[$c].'</td></tr>';	
					$n++;
				}
			}
		}
		$svg .= '</table></body></foreignObject>';
		if($n==0)
		{
			echo " \tNO AVAILABLE FOR THIS MUTATION!";
		}else{
			echo $img.$svg.'</svg>';
		}
	?>
	
</div>

</body>
</html>