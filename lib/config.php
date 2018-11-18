<?php 
include_once("./smarty/Smarty.class.php");
$smarty = new Smarty();
$smarty->template_dir = "./templates/";
$smarty->compile_dir = "./templates_c/";
$smarty->left_delimiter = '{';
$smarty->right_delimiter = '}';
//
//require_once("mysql.php");
//$db = new mysql("CGF","conn","utf8");
global $update;
$update='<p>&nbsp;</p><p class="authors"><strong>Contributors: </strong>SynergyLab</p><p><strong>Last Updated: </strong>Oct 26, 2018</p><p class="fine-print"> <strong>Disclaimer</strong>: The information presented at CancerINFO is compiled from sources believed to be reliable. Extensive efforts have been made to make this information as accurate and as up-to-date as possible. However, the accuracy and completeness of this information cannot be guaranteed. Despite our best efforts, this information may contain typographical errors and omissions. The contents are to be used only as a guide, and health care providers should employ sound clinical judgment in interpreting this information for individual patient care. </p>';
?>