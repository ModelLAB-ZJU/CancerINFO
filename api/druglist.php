<?php
  ini_set('memory_limit', '128000M');
  require_once("../lib/mysql.php");
  $drug = drug();
  echo json_encode($drug,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
  function drug(){
	  $db = new mysql("CGF","conn","utf8");
	  $db -> query('select * from Drug');
	  $drugs = array();
	  while($t = $db->fetch_assoc()){
		  array_push($drugs,$t);	
	  }
	  return $drugs;
  }
?>