<?php
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}

switch($_GET['type']){
 case "changeName":
  $id=$_GET['id'];
  
  $code_deptbasic=input_find_by_deptcode($id);
  foreach ($code_deptbasic as $dept){
             echo "<option value=\"".$dept['ref_code']."\">".$dept['ref_code']."</option>";
        };
  break;
 case "changeUnit":
  $id=$_GET['id'];
  $ref_deptbasic=input_find_by_REF($id);
  foreach ($ref_deptbasic as $dept){
             echo "<option value=\"".$dept['in_unit']."\">".$dept['in_unit']."</option>";
        };
  break;
}

 ?>
